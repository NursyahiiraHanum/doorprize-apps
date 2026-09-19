<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Prize;
use App\Models\DoorprizeWinner;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // 1. Peserta yang HADIR (dari tabel attendances)
        $attendedParticipantIds = Attendance::pluck('participant_id')->toArray();

        // 2. Peserta yang SUDAH MENANG pada event tahun ini (agar tidak menang 2x)
        $alreadyWonTodayIds = DoorprizeWinner::whereYear('won_at', $currentYear)
            ->pluck('participant_id')
            ->toArray();

        // Query Peserta Hadir & Belum Menang Hari Ini
        $query = Participant::whereIn('id', $attendedParticipantIds)
            ->whereNotIn('id', $alreadyWonTodayIds);

        // Filter Syarat Sesi
        if ($sesi === 1) {
            // SESI 1: Semua Karyawan (TETAP & KONTRAK) - Belum menang tahun lalu
            $query->where(function ($q) use ($currentYear) {
                $q->whereNull('tahun_terakhir_menang')
                  ->orWhere('tahun_terakhir_menang', '<', $currentYear - 1);
            });
        } elseif ($sesi === 2) {
            // SESI 2: Karyawan TETAP - Belum menang 2 tahun terakhir
            $query->where('status_karyawan', 'PERMANENT')
                  ->where(function ($q) use ($currentYear) {
                      $q->whereNull('tahun_terakhir_menang')
                        ->orWhere('tahun_terakhir_menang', '<', $currentYear - 2);
                  });
        } elseif ($sesi === 3) {
            // SESI 3: Karyawan TETAP - Belum menang tahun lalu
            $query->where('status_karyawan', 'PERMANENT')
                  ->where(function ($q) use ($currentYear) {
                      $q->whereNull('tahun_terakhir_menang')
                        ->orWhere('tahun_terakhir_menang', '<', $currentYear - 1);
                  });
        }

        $candidates = $query->select('id', 'npk', 'name', 'status_karyawan', 'kendaraan')->get();

        // 3. Ambil seluruh Hadiah di Sesi ini & Gandakan sesuai Quantity
        $prizes = Prize::where('sesi', $sesi)->get();

        $prizeList = [];
        foreach ($prizes as $prize) {
            for ($i = 0; $i < $prize->quantity; $i++) {
                $prizeList[] = [
                    'id'   => $prize->id,
                    'name' => $prize->name,
                ];
            }
        }

        return response()->json([
            'success'    => true,
            'candidates' => $candidates,
            'prizes'     => $prizeList, // Jumlah item sesuai total quantity hadiah sesi ini (misal 20/30)
        ]);
    }

    // Simpan Pemenang ke Database
    public function storeWinners(Request $request)
    {
        $request->validate([
            'sesi'                      => 'required|integer|in:1,2,3',
            'winners'                   => 'required|array|min:1',
            'winners.*.participant_id' => 'required|exists:participants,id',
            'winners.*.prize_id'       => 'required|exists:prizes,id',
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