<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Rap2hpoutre\FastExcel\FastExcel;
use ZipArchive;
use Spatie\Browsershot\Browsershot;

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

        // 1. DENGAN INI, SEMUA DATA LAMA PASTI DI-RESET QR-NYA
        Participant::query()->update(['qr_code' => null]);

        // 2. Baca file excel
        $file = $request->file('file');
        $collection = (new FastExcel)->import($file);

        foreach ($collection as $row) {
            $normalizeHeader = [];
            foreach ($row as $key => $val) {
                $normalizeHeader[strtolower(trim($key))] = $val;
            }

            if (empty($normalizeHeader['npk'])) continue;

            $npk = trim((string) $normalizeHeader['npk']);

            // 3. Insert / Update Data Peserta (QR tetap NULL)
            Participant::updateOrCreate(
                ['npk' => $npk],
                [
                    'name'                  => $normalizeHeader['name'] ?? $npk,
                    'tanggungan'            => isset($normalizeHeader['tanggungan']) ? (int) $normalizeHeader['tanggungan'] : 0,
                    'total_tiket'           => isset($normalizeHeader['total']) ? (int) $normalizeHeader['total'] : 1,
                    'status_karyawan'       => strtoupper(trim($normalizeHeader['status'] ?? 'KONTRAK')),
                    'gender'                => strtoupper(trim($normalizeHeader['gender'] ?? 'MALE')),
                    'kendaraan'             => $normalizeHeader['kendaraan'] ?? null,
                    'tahun_terakhir_menang' => $normalizeHeader['tahun_menang'] ?? null,
                    'qr_code'               => null, // Dijamin NULL
                ]
            );
        }

        return redirect()->route('participants.index')->with('success', 'Data berhasil diimport. Semua status QR di-reset, silakan klik tombol "Generate QR".');
    }

    // FUNGSI GENERATE QR CODE (Untuk data yang QR Code-nya masih NULL)
    public function generateQr()
    {
        // Ambil semua peserta yang qr_code nya masih kosong
        $participants = Participant::whereNull('qr_code')->orWhere('qr_code', '')->get();

        if ($participants->isEmpty()) {
            return redirect()->back()->with('success', 'Semua peserta sudah memiliki QR Code.');
        }

        foreach ($participants as $participant) {
            // Set data QR Code berdasarkan NPK
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
        return view('participants-qr', compact('participants'));
    }

    public function exportQr()
    {
        // Increase time limit for batch PDF & ZIP generation
        set_time_limit(600);

        $participants = Participant::all();

        if ($participants->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data peserta untuk di-export.');
        }

        // Buat folder temporary jika belum ada
        $tempDir = storage_path('app/public/temp_pdf_cards');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = 'Kartu_QR_Peserta_PDF_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($participants as $participant) {
                if (empty($participant->qr_code)) {
                    $participant->update(['qr_code' => $participant->npk]);
                }

                try {
                    // Render PDF per peserta dengan desain presisi 14cm x 7.2cm dari participants-qr.blade.php
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cards.qr-card-pdf', [
                        'p' => $participant,
                    ])->setPaper([0, 0, 396.85, 204.09], 'landscape'); // 14cm x 7.2cm (landscape)

                    $safeName = \Illuminate\Support\Str::slug($participant->name);
                    $pdfFileName = $participant->npk . '_' . $safeName . '.pdf';
                    $pdfPath = $tempDir . '/' . $pdfFileName;

                    // Simpan output PDF sementara
                    file_put_contents($pdfPath, $pdf->output());

                    if (file_exists($pdfPath)) {
                        $zip->addFile($pdfPath, $pdfFileName);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            $zip->close();
        }

        // Hapus file PDF sementara setelah file ZIP selesai
        array_map('unlink', glob("$tempDir/*.pdf"));

        // Download ZIP lalu hapus file ZIP dari server
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}

