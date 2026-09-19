<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParticipantController extends Controller
{
    // FUNGSI INDEX DATA
    public function index()
    {
        $participants = Participant::paginate(10);
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
            'tanggungan'            => 'required|integer|min:0',
            'total_tiket'           => 'required|integer|min:1',
            'status_karyawan'       => 'required|string',
            'gender'                => 'required|string',
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
            'tanggungan'            => 'required|integer|min:0',
            'total_tiket'           => 'required|integer|min:1',
            'status_karyawan'       => 'required|string',
            'gender'                => 'required|string',
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

        $file = $request->file('file');

        // Menggunakan library rap2hpoutre/fast-excel untuk import
        $collection = (new \Rap2hpoutre\FastExcel\FastExcel)->import($file);

        foreach ($collection as $row) {
            // Normalisasi header (mengubah menjadi lowercase dan menghilangkan spasi ekstra)
            $normalizeHeader = [];
            foreach ($row as $key => $val) {
                $normalizeHeader[strtolower(trim($key))] = $val;
            }

            // Pastikan kolom NPK tersedia dari Excel
            if (empty($normalizeHeader['npk'])) continue;

            $npk = trim($normalizeHeader['npk']);

            // 1. Cek data lama di database
            $existing = Participant::where('npk', $npk)->first();

            // 2. Pertahankan QR Code lama. Jika peserta baru, otomatis buat dari NPK
            $qrCode = ($existing && !empty($existing->qr_code)) ? $existing->qr_code : $npk;

            // 3. Update jika NPK sudah ada, Tambah baru jika NPK belum ada
            Participant::updateOrCreate(
                ['npk' => $npk],
                [
                    'name'                  => $normalizeHeader['name'] ?? $npk,
                    'tanggungan'            => isset($normalizeHeader['tanggungan']) ? (int) $normalizeHeader['tanggungan'] : 0,
                    'total_tiket'           => isset($normalizeHeader['total']) ? (int) $normalizeHeader['total'] : 1,
                    'status_karyawan'       => strtoupper(trim($normalizeHeader['status'] ?? 'KONTRAK')),
                    'gender'                => strtoupper(trim($normalizeHeader['gender'] ?? 'MALE')),
                    'kendaraan'             => $normalizeHeader['kendaraan'] ?? ($existing->kendaraan ?? null),
                    'tahun_terakhir_menang' => $normalizeHeader['tahun_menang'] ?? ($existing->tahun_terakhir_menang ?? null),
                    'qr_code'               => $qrCode, // QR Code dijamin TIDAK HILANG!
                ]
            );
        }

        return redirect()->route('participants.index')->with('success', 'Data peserta berhasil diimport/diperbarui tanpa merusak QR Code.');
    }

    // FUNGSI GENERATE QR CODE MASSAL (UNTUK DATA YANG BELUM PUNYA QR)
    public function generateQr()
    {
        $participants = Participant::whereNull('qr_code')->orWhere('qr_code', '')->get();
        
        if ($participants->isEmpty()) {
            return redirect()->route('participants.index')->with('success', 'Semua peserta sudah memiliki QR Code.');
        }

        $count = 0;
        foreach ($participants as $p) {
            // Isi qr_code dengan NPK (atau format custom seperti EMP-NPK)
            $p->update(['qr_code' => $p->npk]);
            $count++;
        }

        return redirect()->route('participants.index')->with('success', "$count QR Code berhasil digenerate.");
    }

    // FUNGSI TAMPILKAN SEMUA QR CODE UNTUK CETAK MASSAL
    public function exportQr()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $participants = Participant::whereNotNull('qr_code')
            ->where('qr_code', '!=', '')
            ->get();

        if ($participants->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada QR Code.');
        }

        $zipFileName = 'Kartu_Tiket_Peserta_' . date('Ymd_His') . '.zip';
        $tempDir = storage_path('app/public/temp_qr');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . '/' . $zipFileName;
        $zip = new \ZipArchive;

        // Load Logo ke Base64
        $logoBase64 = null;
        $logoPath = public_path('images/logo-voyages.png');
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $folderInZip = 'Kartu_Tiket_Peserta/';

            foreach ($participants as $participant) {
                // Generate QR Code PNG Base64
                try {
                    $qrBinary = QrCode::format('png')->size(130)->margin(0)->generate($participant->qr_code);
                    $qrBase64 = 'data:image/png;base64,' . base64_encode($qrBinary);
                } catch (\Exception $e) {
                    $qrBinary = QrCode::format('svg')->size(130)->margin(0)->generate($participant->qr_code);
                    $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrBinary);
                }

                // Panggil view khusus PDF 'participant-card-pdf'
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('participant-card-pdf', [
                    'participant' => $participant,
                    'qrBase64'    => $qrBase64,
                    'logoBase64'  => $logoBase64,
                ])->setPaper([0, 0, 396.85, 204.09]);

                $cleanName = \Illuminate\Support\Str::slug($participant->name, '_');
                $fileNameInZip = $folderInZip . $participant->npk . '_' . $cleanName . '.pdf';

                $zip->addFromString($fileNameInZip, $pdf->output());
                unset($pdf);
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // FUNGSI CETAK QR CODE SATU PESERTA (PER BARIS)
    public function printSingleQr(Participant $participant)
    {
        if (empty($participant->qr_code)) {
            $participant->update(['qr_code' => $participant->npk]);
        }

        $participants = collect([$participant]);
        return view('participants-qr', compact('participants'));
    }
}
