<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Prize;
use App\Models\DoorprizeWinner;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoorprizeController extends Controller
{
    public function index()
    {
        return view('doorprize');
    }

    // Ambil Data Peserta & Hadiah Berdasarkan Sesi
    public function getSessionData(Request $request)
    {
        $sesi = (int) $request->get('sesi', 1);
        $currentYear = (int) date('Y');
        // Tentukan tanggal batas minimal masa kerja (tepat 1 tahun yang lalu dari hari ini)
        $oneYearAgo = Carbon::now()->subYear()->toDateString();

        // 1. Ambil semua ID peserta yang HADIR (dari tabel attendances)
        $attendedParticipantIds = Attendance::pluck('participant_id')->toArray();

        // 2. Ambil semua ID peserta yang SUDAH MENANG di tabel doorprize_winners
        // Otomatis mengeliminasi pemenang Sesi 1 dari Sesi 2, dan pemenang Sesi 1 & 2 dari Sesi 3
        $alreadyWonIds = DoorprizeWinner::pluck('participant_id')->toArray();

        // ---- QUERY DASAR: Peserta harus HADIR dan BELUM MENANG ----
        $query = Participant::whereIn('id', $attendedParticipantIds)
            ->whereNotIn('id', $alreadyWonIds)
            ->where(function ($q) use ($currentYear) {
                $q->whereNull('tahun_terakhir_menang')
                  ->orWhere('tahun_terakhir_menang', '<', $currentYear);
            });

        // Batas tahun terakhir menang:
        // Sesi 1 & 2: Tidak boleh yang menang 1 tahun terakhir (< currentYear - 1 atau null)
        // Sesi 3    : Tidak boleh yang menang 2 tahun terakhir (< currentYear - 2 atau null)
        $oneYearCutoff = $currentYear - 1;
        $twoYearCutoff = $currentYear - 2;

        // ---- FILTER SPESIFIK BERDASARKAN SESI ----
        if ($sesi === 1) {
            // Sesi 1:
            // - Status: Tetap / Permanent, atau Kontrak dengan masa kerja minimal 1 tahun
            // - Tahun terakhir menang: belum pernah menang (null) atau menang sebelum 1 tahun terakhir (< $oneYearCutoff)
            $query->where(function ($q) use ($oneYearAgo) {
                $q->whereIn('status_karyawan', ['PERMANENT', 'TETAP'])
                  ->orWhere(function ($sub) use ($oneYearAgo) {
                      $sub->whereIn('status_karyawan', ['CONTRACT', 'KONTRAK'])
                          ->whereNotNull('join_date')
                          ->where('join_date', '<=', $oneYearAgo);
                  });
            })
            ->where(function ($q) use ($oneYearCutoff) {
                $q->whereNull('tahun_terakhir_menang')
                  ->orWhere('tahun_terakhir_menang', '<', $oneYearCutoff);
            });

        } elseif ($sesi === 2) {
            // Sesi 2:
            // - Status: Tetap / Permanent
            // - Tahun terakhir menang: belum pernah menang (null) atau menang sebelum 1 tahun terakhir (< $oneYearCutoff)
            // - Otomatis tereliminasi jika sudah menang di Sesi 1 (via $alreadyWonIds)
            $query->whereIn('status_karyawan', ['PERMANENT', 'TETAP'])
                  ->where(function ($q) use ($oneYearCutoff) {
                      $q->whereNull('tahun_terakhir_menang')
                        ->orWhere('tahun_terakhir_menang', '<', $oneYearCutoff);
                  });

        } elseif ($sesi === 3) {
            // Sesi 3:
            // - Status: Tetap / Permanent
            // - Tahun terakhir menang: belum pernah menang (null) atau menang sebelum 2 tahun terakhir (< $twoYearCutoff)
            // - Otomatis tereliminasi jika sudah menang di Sesi 1 & Sesi 2 (via $alreadyWonIds)
            $query->whereIn('status_karyawan', ['PERMANENT', 'TETAP'])
                  ->where(function ($q) use ($twoYearCutoff) {
                      $q->whereNull('tahun_terakhir_menang')
                        ->orWhere('tahun_terakhir_menang', '<', $twoYearCutoff);
                  });
        }

        
        // 1. Ambil kandidat yang lolos seluruh filter sesi
        $candidates = $query->select(
            'id',
            'npk',
            'name',
            'status_karyawan',
            'kendaraan',
            'join_date',
            'tahun_terakhir_menang'
        )->get();

        // 2. Kelompokkan kandidat berdasarkan distribusi NPK
        $candidateGroups = $this->groupCandidatesByNpk($candidates);

        // 3. Ambil hadiah sesi ini
        $prizes = Prize::whereIn('sesi', [
            (string) $sesi,
            'sesi' . $sesi
        ])->get();

        // 4. Gandakan hadiah sesuai kuantitas
        $prizeList = [];

        foreach ($prizes as $prize) {
            for ($i = 0; $i < $prize->quantity; $i++) {
                $prizeList[] = [
                    'id'   => $prize->id,
                    'name' => $prize->name,
                ];
            }
        }

        // 5. Hitung target pemenang untuk setiap kelompok NPK
        $groupTargets = $this->allocateGroupTargets(
            $candidateGroups,
            count($prizeList)
        );

        // 6. Gabungkan kandidat secara bergiliran dari setiap kelompok
        // supaya urutan data tidak didominasi satu kelompok NPK.
        $balancedCandidates = $this->interleaveCandidates(
            $candidateGroups
        );

        return response()->json([
            'success' => true,
            'candidates' => $balancedCandidates,
            'candidate_groups' => $candidateGroups,
            'group_targets' => $groupTargets,
            'prizes' => $prizeList,
        ]);

        
    /**
     * Membagi kandidat yang lolos menjadi tiga kelompok NPK.
     * Pembagian dilakukan berdasarkan peringkat NPK numerik:
     * sepertiga bawah, tengah, dan atas.
     */

}

 private function groupCandidatesByNpk($candidates): array
    {
        $sorted = $candidates->sort(function ($a, $b) {
            $npkA = (float) preg_replace('/\D/', '', (string) $a->npk);
            $npkB = (float) preg_replace('/\D/', '', (string) $b->npk);

            return $npkA <=> $npkB;
        })->values();

        $groups = [
            'kecil' => [],
            'menengah' => [],
            'besar' => [],
        ];

        $total = $sorted->count();

        foreach ($sorted as $index => $candidate) {
            if ($total === 0) {
                break;
            }

            // Membagi berdasarkan posisi dalam distribusi NPK.
            $groupIndex = min(
                2,
                (int) floor($index * 3 / $total)
            );

            $groupNames = ['kecil', 'menengah', 'besar'];
            $groupName = $groupNames[$groupIndex];

            $candidate->setAttribute('kelompok_npk', $groupName);

            $groups[$groupName][] = $candidate;
        }

        // Acak urutan kandidat di dalam setiap kelompok.
        foreach ($groups as $name => $members) {
            shuffle($members);
            $groups[$name] = $members;
        }

        return $groups;
    }

    /**
     * Membagi target hadiah secara bergiliran ke kelompok NPK
     * yang masih memiliki kandidat.
     */
    private function allocateGroupTargets(
        array $groups,
        int $totalPrizes
    ): array {
        $targets = [
            'kecil' => 0,
            'menengah' => 0,
            'besar' => 0,
        ];

        $capacities = [];

        foreach ($groups as $name => $members) {
            $capacities[$name] = count($members);
        }

        $groupNames = ['kecil', 'menengah', 'besar'];
        $allocated = 0;

        while ($allocated < $totalPrizes) {
            $progress = false;

            foreach ($groupNames as $name) {
                if (
                    $targets[$name] < $capacities[$name]
                    && $allocated < $totalPrizes
                ) {
                    $targets[$name]++;
                    $allocated++;
                    $progress = true;
                }
            }

            // Berhenti jika semua kandidat sudah dialokasikan.
            if (!$progress) {
                break;
            }
        }

        return $targets;
    }

    /**
     * Menyebarkan urutan kandidat antarkelompok secara bergiliran.
     */
    private function interleaveCandidates(array $groups): array
    {
        $result = [];
        $maxCount = max(
            array_map('count', $groups) ?: [0]
        );

        for ($i = 0; $i < $maxCount; $i++) {
            foreach (['kecil', 'menengah', 'besar'] as $name) {
                if (isset($groups[$name][$i])) {
                    $result[] = $groups[$name][$i];
                }
            }
        }

        return $result;
    }

    // ... (Sisa fungsi storeWinners, operatorView, displayView tetap sama) ...
    
    // Simpan Pemenang ke Database
    public function storeWinners(Request $request)
    {
        $request->validate([
            'sesi'                      => 'required|integer|in:1,2,3',
            'winners'                   => 'required|array|min:1',
            'winners.*.participant_id'  => 'required|exists:participants,id',
            'winners.*.prize_id'        => 'required|exists:prizes,id',
        ]);

        DB::beginTransaction();
        try {
            $currentYear = (int) date('Y');
            $now = now();

            foreach ($request->winners as $winnerData) {
                // 1. Catat ke tabel doorprize_winners
                DoorprizeWinner::create([
                    'participant_id' => $winnerData['participant_id'],
                    'prize_id'       => $winnerData['prize_id'],
                    'won_at'         => $now,
                ]);

                // 2. Update tahun terakhir menang peserta
                Participant::where('id', $winnerData['participant_id'])->update([
                    'tahun_terakhir_menang' => $currentYear,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyimpan ' . count($request->winners) . ' pemenang!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pemenang: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function operatorView()
    {
        return view('doorprize.operator');
    }

    public function displayView()
    {
        return view('doorprize.display');
    }
}
