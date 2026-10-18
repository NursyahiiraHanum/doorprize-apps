<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Rap2hpoutre\FastExcel\FastExcel;

class ParticipantController extends Controller
{
    // FUNGSI INDEX DATA
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $participants = Participant::when($search, function($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('npk', 'LIKE', "%{$search}%");
        })->paginate(10);

        return view('participants', compact('participants'));
    }

    // FUNGSI CREATE DATA
    public function create()
    {
        return view('participants.create');
    }

    // FUNGSI STORE DATA
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'npk'                   => 'required|unique:participants,npk',
            'name'                  => 'required|string|max:255',
            'birthdate'             => 'nullable|date',
            'join_date'             => 'nullable|date', 
            'tanggungan'            => 'required|integer|min:0',
            'total_tiket'           => 'required|integer|min:1',
            'plant'                 => 'required|string|max:255',
            'status_karyawan'       => 'required|string',
            'kendaraan'             => 'required|string',
            'tahun_terakhir_menang' => 'nullable|integer',
            'qr_code'               => 'required|unique:participants,qr_code',
        ]);

        Participant::create($validatedData);

        return redirect()->route('participants.index')->with('success', 'Data peserta berhasil ditambahkan.');
    }

    // FUNGSI SHOW DATA
    public function show(Participant $participant)
    {
        return view('participants.show', compact('participant'));
    }

    public function edit(Participant $participant)
    {
        return view('participants.edit', compact('participant'));
    }

    // FUNGSI UPDATE DATA
    public function update(Request $request, Participant $participant)
    {
        $validatedData = $request->validate([
            'npk'                   => 'required|unique:participants,npk,' . $participant->id,
            'name'                  => 'required|string|max:255',
            'birthdate'             => 'nullable|date',
            'join_date'             => 'nullable|date', 
            'tanggungan'            => 'required|integer|min:0',
            'total_tiket'           => 'required|integer|min:1',
            'status_karyawan'       => 'required|string',
            'plant'                 => 'required|string|max:255',
            'kendaraan'             => 'nullable|string',
            'tahun_terakhir_menang' => 'nullable|integer',
            'qr_code'               => 'nullable|unique:participants,qr_code,' . $participant->id,
        ]);

        $participant->update($validatedData);

        return redirect()->route('participants.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    // FUNGSI DELETE DATA
    public function destroy(Participant $participant)
    {
        $participant->delete();
        return redirect()->route('participants.index')->with('success', 'Data peserta berhasil dihapus.');
    }

    // FUNGSI IMPORT DATA
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        // 1. Reset QR Code lama sebelum import
        Participant::query()->update(['qr_code' => null]);

        // 2. Baca file excel
        $file = $request->file('file');
        $collection = (new FastExcel)->import($file);

        if ($collection->isNotEmpty()) {
            \Illuminate\Support\Facades\Log::info('Excel Upload Headers: ', array_keys((array)$collection->first()));
        }

        foreach ($collection as $row) {
            $normalizeHeader = [];
            foreach ($row as $key => $val) {
                $cleanKey = strtolower(trim((string)$key));
                $normalizeHeader[$cleanKey] = $val;
                
                // Normalisasi nama kolom tanpa spasi dan karakter non-alphanumeric
                $stripped = preg_replace('/[^a-z0-9]/', '', $cleanKey);
                $normalizeHeader[$stripped] = $val;
            }

            if (empty($normalizeHeader['npk'])) continue;

            $npk = trim((string) $normalizeHeader['npk']);

            // 1. Tanggal Lahir (Birthdate)
            $rawBirthdate = $normalizeHeader['birthdate'] 
                ?? $normalizeHeader['birtdate'] 
                ?? $normalizeHeader['tgllahir'] 
                ?? $normalizeHeader['tanggallahir'] 
                ?? $normalizeHeader['tgl_lahir'] 
                ?? $normalizeHeader['tanggal_lahir'] 
                ?? $normalizeHeader['birthday'] 
                ?? $normalizeHeader['dob'] 
                ?? null;

            $birthdate = null;
            if ($rawBirthdate instanceof \DateTimeInterface) {
                $birthdate = $rawBirthdate->format('Y-m-d');
            } elseif (!empty($rawBirthdate)) {
                try {
                    $birthdate = \Carbon\Carbon::parse($rawBirthdate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $birthdate = null;
                }
            }

            // 📅 2. Tanggal Gabung/Masuk Kerja (Join Date)
            $rawJoinDate = $normalizeHeader['joindate']
                ?? $normalizeHeader['join_date']
                ?? $normalizeHeader['tglmasuk']
                ?? $normalizeHeader['tanggalmasuk']
                ?? $normalizeHeader['tgl_masuk']
                ?? $normalizeHeader['tanggal_masuk']
                ?? $normalizeHeader['hiredate']
                ?? $normalizeHeader['hire_date']
                ?? null;

            $joinDate = null;
            if ($rawJoinDate instanceof \DateTimeInterface) {
                $joinDate = $rawJoinDate->format('Y-m-d');
            } elseif (!empty($rawJoinDate)) {
                try {
                    $joinDate = \Carbon\Carbon::parse($rawJoinDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $joinDate = null;
                }
            }

            // 3. Plant (LOCATION)
            $plantRaw = $normalizeHeader['location']
                ?? $normalizeHeader['plant'] 
                ?? $normalizeHeader['lokasi'] 
                ?? $normalizeHeader['site'] 
                ?? $normalizeHeader['cabang'] 
                ?? $normalizeHeader['factory'] 
                ?? null;

            $plant = 'Cibitung';
            if (!empty($plantRaw)) {
                $cleanPlant = strtoupper(trim((string)$plantRaw));
                if (str_contains($cleanPlant, 'KARAWANG')) {
                    $plant = 'Karawang';
                } elseif (str_contains($cleanPlant, 'CIBITUNG')) {
                    $plant = 'Cibitung';
                } else {
                    $plant = ucwords(strtolower(trim(str_ireplace('PLANT', '', (string)$plantRaw))));
                }
            }

            // 4. Kendaraan (BUS/MOBIL)
            $kendaraanRaw = $normalizeHeader['bus/mobil']
                ?? $normalizeHeader['busmobil']
                ?? $normalizeHeader['bus_mobil']
                ?? $normalizeHeader['kendaraan'] 
                ?? $normalizeHeader['jeniskendaraan'] 
                ?? $normalizeHeader['bus'] 
                ?? $normalizeHeader['bis'] 
                ?? $normalizeHeader['transport'] 
                ?? $normalizeHeader['transportasi'] 
                ?? $normalizeHeader['armada'] 
                ?? $normalizeHeader['mobil'] 
                ?? null;

            $kendaraan = null;
            if (!empty($kendaraanRaw)) {
                $cleanKendaraan = strtoupper(trim((string)$kendaraanRaw));
                // --- PERUBAHAN LOGIKA DI SINI ---
                if (str_contains($cleanKendaraan, 'BUS DEPOK')) {
                    $kendaraan = 'BUS DEPOK';
                } elseif (str_contains($cleanKendaraan, 'BUS')) {
                    $kendaraan = 'BUS';
                } elseif (str_contains($cleanKendaraan, 'MOBIL')) {
                    $kendaraan = 'MOBIL PRIBADI';
                } else {
                    $kendaraan = strtoupper(trim(preg_replace('/[^\x20-\x7E]/u', '', $cleanKendaraan)));
                }
            }

            // 5. Status Karyawan
            $statusRaw = $normalizeHeader['status'] 
                ?? $normalizeHeader['statuskaryawan'] 
                ?? $normalizeHeader['status_karyawan'] 
                ?? 'KONTRAK';
            $status_karyawan = strtoupper(trim((string)$statusRaw));

            // 6. Total Tiket
            $totalTiketRaw = $normalizeHeader['totaltiket'] 
                ?? $normalizeHeader['total_tiket'] 
                ?? $normalizeHeader['total'] 
                ?? $normalizeHeader['tiket'] 
                ?? 1;

            // 7. Tanggungan
            $tanggunganRaw = $normalizeHeader['tanggungan'] ?? 0;

            // 8. Tahun Terakhir Menang
            $tahunMenangRaw = $normalizeHeader['tahun_menang'] 
                ?? $normalizeHeader['tahunmenang'] 
                ?? $normalizeHeader['tahunterakhirmenang'] 
                ?? null;

            // 9. Insert / Update Data Peserta
            Participant::updateOrCreate(
                ['npk' => $npk],
                [
                    'name'                  => $normalizeHeader['name'] ?? $normalizeHeader['nama'] ?? $npk,
                    'birthdate'             => $birthdate,
                    'join_date'             => $joinDate, 
                    'tanggungan'            => (int) $tanggunganRaw,
                    'plant'                 => $plant,
                    'total_tiket'           => (int) $totalTiketRaw,
                    'status_karyawan'       => $status_karyawan,
                    'kendaraan'             => $kendaraan,
                    'tahun_terakhir_menang' => !empty($tahunMenangRaw) ? (int)$tahunMenangRaw : null,
                    'qr_code'               => null,
                ]
            );
        }

        return redirect()->route('participants.index')->with('success', 'Data berhasil diimport. Silakan klik tombol "Generate QR".');
    }

    // FUNGSI GENERATE QR CODE
    public function generateQr()
    {
        $participants = Participant::whereNull('qr_code')->orWhere('qr_code', '')->get();

        if ($participants->isEmpty()) {
            return redirect()->back()->with('success', 'Semua peserta sudah memiliki QR Code.');
        }

        foreach ($participants as $participant) {
            $participant->update([
                'qr_code' => $participant->npk
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil me-generate ' . $participants->count() . ' QR Code baru!');
    }

    // FUNGSI CETAK QR CODE SATU PESERTA (PER BARIS)
    public function printSingleQr(Participant $participant)
    {
        if (empty($participant->qr_code)) {
            $participant->update(['qr_code' => $participant->npk]);
        }

        $participants = collect([$participant]);
        
        $chuhatsuLogoPath = public_path('theme/assets/images/logo-chuhatsu.png');
        $chuhatsuLogoB64  = file_exists($chuhatsuLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($chuhatsuLogoPath)) : '';

        $voyagesLogoPath = public_path('theme/assets/images/logo/logo-voyages.png');
        $voyagesLogoB64  = file_exists($voyagesLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($voyagesLogoPath)) : '';

        return view('participants-qr', compact('participants', 'chuhatsuLogoB64', 'voyagesLogoB64'));
    }

    // FUNGSI EXPORT SEMUA QR KE 1 PDF INSTAN 
    public function exportQr(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(180);

        Participant::whereNull('qr_code')->orWhere('qr_code', '')->update([
            'qr_code' => DB::raw('npk')
        ]);

        // $participants = Participant::orderBy('npk', 'asc')->get();
        $participants = Participant::orderByRaw('CAST(npk AS UNSIGNED) ASC')->get();


        if ($participants->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data peserta untuk di-export.');
        }

        $qrCodes = [];
        foreach ($participants as $p) {
            $qrData = $p->qr_code ?? $p->npk;
            
            $svgString = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)
                ->format('svg')
                ->margin(1) 
                ->generate($qrData);
                
            $qrCodes[$p->id] = 'data:image/svg+xml;base64,' . base64_encode($svgString);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('participants-print-all', [
            'participants' => $participants,
            'qrCodes'      => $qrCodes,
        ]);

        $pdf->setPaper([0, 0, 450, 220]); 
        $pdf->setOption('isRemoteEnabled', true); 

        $filename = 'Tiket_Gathering_Chuhatsu_Lengkap_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    // FUNGSI EXPORT ZIP
    // public function exportQrZip()
    // {
    //     set_time_limit(180);
    //     ini_set('memory_limit', '512M');

    //     Participant::whereNull('qr_code')->orWhere('qr_code', '')->update([
    //         'qr_code' => DB::raw('npk')
    //     ]);

    //     $totalParticipants = Participant::count();
    //     if ($totalParticipants === 0) {
    //         return redirect()->back()->with('error', 'Tidak ada data peserta.');
    //     }

    //     $perPage = 100;
    //     $totalBatches = (int) ceil($totalParticipants / $perPage);
    //     $zipFileName = 'QR_Peserta_Per100_' . date('Ymd_His') . '.zip';
    //     $zipPath = storage_path('app/public/' . $zipFileName);

    //     if (!file_exists(storage_path('app/public'))) {
    //         mkdir(storage_path('app/public'), 0755, true);
    //     }

    //     $zip = new \ZipArchive();
    //     if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
    //         return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
    //     }

    //     $chuhatsuLogoUrl = asset('theme/assets/images/logo-chuhatsu.png');
    //     $voyagesLogoUrl  = asset('theme/assets/images/logo/logo-voyages.png');

    //     for ($i = 1; $i <= $totalBatches; $i++) {
    //         $participants = Participant::orderBy('npk', 'asc')
    //             ->skip(($i - 1) * $perPage)
    //             ->take($perPage)
    //             ->get();

    //         $batchHtml = view('participants-print-all', compact('participants', 'chuhatsuLogoUrl', 'voyagesLogoUrl'))->render();
            
    //         $startNo = (($i - 1) * $perPage) + 1;
    //         $endNo   = min($i * $perPage, $totalParticipants);
            
    //         $zip->addFromString("Batch_{$i}_Peserta_{$startNo}-{$endNo}.html", $batchHtml);
    //     }

    //     $zip->close();

    //     return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    // }
}
