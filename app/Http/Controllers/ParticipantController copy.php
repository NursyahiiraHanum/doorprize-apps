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
            'birthdate'             => 'nullable|date',
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

        // 1. DENGAN INI, SEMUA DATA LAMA PASTI DI-RESET QR-NYA
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
                // Versi tanpa karakter non-alphanumeric (cth: "jenis kendaraan" -> "jeniskendaraan")
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

            // 2. Plant (LOCATION / Plant Cibitung / Plant Karawang)
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

            // 3. Kendaraan (BUS/MOBIL -> Bis, Bus, 🚗 Mobil Pribadi, dll)
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
                if (str_contains($cleanKendaraan, 'MOBIL')) {
                    $kendaraan = 'MOBIL PRIBADI';
                } elseif (str_contains($cleanKendaraan, 'BIS') || str_contains($cleanKendaraan, 'BUS')) {
                    $kendaraan = 'BIS';
                } else {
                    $kendaraan = strtoupper(trim(preg_replace('/[^\x20-\x7E]/u', '', $cleanKendaraan)));
                }
            }

            // 4. Status Karyawan
            $statusRaw = $normalizeHeader['status'] 
                ?? $normalizeHeader['statuskaryawan'] 
                ?? $normalizeHeader['status_karyawan'] 
                ?? 'KONTRAK';
            $status_karyawan = strtoupper(trim((string)$statusRaw));

            // 5. Total Tiket
            $totalTiketRaw = $normalizeHeader['totaltiket'] 
                ?? $normalizeHeader['total_tiket'] 
                ?? $normalizeHeader['total'] 
                ?? $normalizeHeader['tiket'] 
                ?? 1;

            // 6. Tanggungan
            $tanggunganRaw = $normalizeHeader['tanggungan'] ?? 0;

            // 7. Tahun Terakhir Menang
            $tahunMenangRaw = $normalizeHeader['tahun_menang'] 
                ?? $normalizeHeader['tahunmenang'] 
                ?? $normalizeHeader['tahunterakhirmenang'] 
                ?? null;

            // 8. Insert / Update Data Peserta
            Participant::updateOrCreate(
                ['npk' => $npk],
                [
                    'name'                  => $normalizeHeader['name'] ?? $normalizeHeader['nama'] ?? $npk,
                    'birthdate'             => $birthdate,
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
        // Generate ZIP berisi file HTML per peserta
        // Pakai cursor() agar record di-load satu-satu (tidak habiskan RAM)
        // HTML di-build sebagai string PHP biasa (tidak pakai view()->render() yang boros memory)
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        // Cek apakah ada peserta
        $count = Participant::count();
        if ($count === 0) {
            return redirect()->back()->with('error', 'Tidak ada data peserta untuk di-export.');
        }

        // Update qr_code yang kosong dulu (bulk update)
        Participant::whereNull('qr_code')->orWhere('qr_code', '')->update(['qr_code' => \Illuminate\Support\Facades\DB::raw('npk')]);

        // Embed logo sekali saja di memori (shared semua file HTML)
        $chuhatsuLogoPath = public_path('theme/assets/images/logo-chuhatsu.png');
        $chuhatsuLogoB64  = file_exists($chuhatsuLogoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($chuhatsuLogoPath))
            : '';

        $voyagesLogoPath = public_path('theme/assets/images/logo/logo-voyages.png');
        $voyagesLogoB64  = file_exists($voyagesLogoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($voyagesLogoPath))
            : '';

        $zipFileName = 'QR_Peserta_Gathering_' . date('Ymd_His') . '.zip';
        $zipPath     = storage_path('app/public/' . $zipFileName);

        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        $totalAdded = 0;

        // cursor() = lazy load satu record per iterasi, tidak load semua ke RAM
        foreach (Participant::cursor() as $p) {
            try {
                $qrSvg    = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate($p->qr_code ?? $p->npk);
                $birthdate = $p->birthdate ? \Carbon\Carbon::parse($p->birthdate)->format('d/m/Y') : '-';
                $plant     = htmlspecialchars(strtoupper($p->plant ?? '-'));
                $status    = htmlspecialchars(strtoupper($p->status_karyawan ?? '-'));
                $kendaraan = htmlspecialchars(strtoupper($p->kendaraan ?? '-'));
                $name      = htmlspecialchars($p->name);
                $npk       = htmlspecialchars($p->npk);

                // Build HTML sebagai string langsung — TIDAK pakai Blade/view->render()
                $logoChImg     = $chuhatsuLogoB64 ? "<img src=\"{$chuhatsuLogoB64}\" class=\"logo-ch\" alt=\"Chuhatsu\">" : '';
                $logoVoyImg    = $voyagesLogoB64  ? "<img src=\"{$voyagesLogoB64}\" class=\"logo-voy\" alt=\"Voyages\">" : '';

                $html = <<<HTML
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
<title>QR - {$npk} - {$name}</title>
<style>
@page{size:14cm 7.2cm landscape;margin:0}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Helvetica Neue',Arial,sans-serif;background:#e2e8f0;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;padding:16px}
.card{width:14cm;height:7.2cm;border-radius:10px;background:#fff;display:flex;overflow:hidden;position:relative;border:1px solid #cbd5e1;box-shadow:0 6px 24px rgba(0,0,0,0.12)}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#10b981,#0d9488,#0f172a);z-index:9}
.notch{position:absolute;width:16px;height:16px;background:#e2e8f0;border-radius:50%;right:3.4cm;z-index:10;border:1px solid #cbd5e1;transform:translateX(50%)}
.notch-t{top:-8px}.notch-b{bottom:-8px}
.left{flex:1;padding:12px 14px 8px 14px;display:flex;flex-direction:column;justify-content:space-between}
.ev-tag{font-size:7px;font-weight:900;text-transform:uppercase;letter-spacing:1.2px;color:#10b981;margin-bottom:1px}
.p-name{font-size:14px;font-weight:900;color:#0f172a;text-transform:uppercase;line-height:1.1;margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ev-sub{font-size:7.5px;font-weight:700;color:#64748b;margin-bottom:7px}
.info{background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:5px 9px;display:grid;grid-template-columns:1fr 1fr;gap:4px 8px;margin-bottom:7px}
.ii{display:flex;flex-direction:column}
.il{font-size:5.5px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px}
.iv{font-size:9.5px;font-weight:700;color:#1e293b}
.full{grid-column:span 2}
.badge{display:inline-block;background:#d1fae5;color:#047857;font-size:8.5px;font-weight:700;padding:1px 6px;border-radius:4px}
.ft{border-top:1px dashed #e2e8f0;padding-top:5px;display:flex;justify-content:space-between;align-items:center}
.npk-l{font-size:5.5px;font-weight:800;color:#94a3b8;text-transform:uppercase}
.npk-v{font-size:11px;font-weight:900;color:#0f172a}
.logo-ch{height:24px;max-width:100px;object-fit:contain}
.right{width:3.4cm;background:#0f172a;border-left:2px dashed #334155;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:8px 4px;text-align:center;flex-shrink:0}
.qr-box{background:#fff;padding:4px;border-radius:5px;margin-bottom:5px;display:inline-block}
.qr-box svg{width:70px!important;height:70px!important;display:block}
.scan{font-size:5px;font-weight:800;letter-spacing:1px;color:#38bdf8;text-transform:uppercase;margin-bottom:5px}
.brand{display:flex;flex-direction:column;align-items:center}
.brand-l{font-size:4.5px;font-weight:700;color:#475569;letter-spacing:.8px;text-transform:uppercase}
.logo-voy{height:11px;width:auto;margin-top:2px;opacity:.85}
.no-print{margin-top:14px;text-align:center}
.btn{background:linear-gradient(135deg,#10b981,#0d9488);color:#fff;border:none;padding:9px 26px;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer}
.hint{margin-top:6px;font-size:10px;color:#64748b}
@media print{body{background:#fff;padding:0;min-height:unset}.card{box-shadow:none;border:1px solid #999;border-radius:0}.no-print{display:none!important}.notch{background:#fff}}
</style></head><body>
<div class="card">
  <div class="notch notch-t"></div><div class="notch notch-b"></div>
  <div class="left">
    <div>
      <div class="ev-tag">&#127807; CHUHATSU FAMILY GATHERING 2026</div>
      <div class="p-name">{$name}</div>
      <div class="ev-sub">LEMBANG PARK &amp; ZOO</div>
      <div class="info">
        <div class="ii"><span class="il">Plant</span><span class="iv">{$plant}</span></div>
        <div class="ii"><span class="il">Status</span><span class="iv">{$status}</span></div>
        <div class="ii"><span class="il">Kendaraan</span><span class="iv">{$kendaraan}</span></div>
        <div class="ii"><span class="il">Tanggungan</span><span class="iv">{$p->tanggungan} Orang</span></div>
        <div class="ii full"><span class="il">Tiket Masuk Zoo</span><span class="badge">{$p->total_tiket} Tiket</span></div>
      </div>
    </div>
    <div class="ft">
      <div><div class="npk-l">Nomor NPK</div><div class="npk-v">{$npk}</div></div>
      {$logoChImg}
    </div>
  </div>
  <div class="right">
    <div class="qr-box">{$qrSvg}</div>
    <div class="scan">&#128247; Scan Absen</div>
    <div class="brand"><span class="brand-l">Event by</span>{$logoVoyImg}</div>
  </div>
</div>
<div class="no-print">
  <button class="btn" onclick="window.print()">&#128438; Cetak / Save PDF</button>
  <div class="hint">Pilih "Save as PDF" di dialog print</div>
</div>
</body></html>
HTML;

                $safeName = \Illuminate\Support\Str::slug($p->name);
                $zip->addFromString("{$p->npk}_{$safeName}.html", $html);
                $totalAdded++;

                // Bebaskan memory tiap iterasi
                unset($html, $qrSvg);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal generate HTML NPK {$p->npk}: " . $e->getMessage());
            }
        }

        $zip->close();

        if ($totalAdded === 0 || !file_exists($zipPath)) {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}

