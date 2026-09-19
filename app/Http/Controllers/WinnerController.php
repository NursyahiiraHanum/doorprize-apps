<?php

namespace App\Http\Controllers;

use App\Models\DoorprizeWinner;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\WinnersExport; 
use Maatwebsite\Excel\Facades\Excel; 

class WinnerController extends Controller
{
    public function index(Request $request)
    {
        $sesi   = $request->input('sesi');
        $search = trim($request->input('search'));

        $winners = DoorprizeWinner::with(['participant', 'prize'])
            // Filter Sesi (Khusus dicari pada tabel prizes)
            ->when($sesi && $sesi !== 'all', function ($query) use ($sesi) {
                $query->whereHas('prize', function ($q) use ($sesi) {
                    $q->where('sesi', $sesi)
                      ->orWhere('sesi', 'like', "%{$sesi}%");
                });
            })
            // Filter Pencarian (Nama, NPK, Hadiah)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('participant', function ($qp) use ($search) {
                        $qp->where('name', 'like', "%{$search}%")
                           ->orWhere('npk', 'like', "%{$search}%");
                    })
                    ->orWhereHas('prize', function ($qpz) use ($search) {
                        $qpz->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('won_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Hitung Statistik Real-time
        $totalBarang   = Prize::sum('quantity');
        $totalPemenang = DoorprizeWinner::count();
        $sisaBarang    = max(0, $totalBarang - $totalPemenang);

        return view('winners', compact(
            'winners',
            'totalBarang',
            'totalPemenang',
            'sisaBarang',
            'sesi',
            'search'
        ));
    }

    public function reset()
    {
        try {
            DB::beginTransaction();

            $currentYear = (int) date('Y');

            // Hapus semua data dari tabel doorprize_winners
            DoorprizeWinner::query()->delete();

            // Reset tahun_terakhir_menang peserta yang tercatat menang pada tahun ini
            \App\Models\Participant::where('tahun_terakhir_menang', $currentYear)
                ->update(['tahun_terakhir_menang' => null]);

            DB::commit();

            return redirect()->route('winners.index')->with('success', 'Semua riwayat pemenang dan status menang tahun ini berhasil direset!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('winners.index')->with('error', 'Gagal mereset riwayat: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $sesi   = $request->input('sesi');
        $search = trim($request->input('search'));

        $fileName = 'Laporan_Pemenang_Doorprize_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new WinnersExport($sesi, $search), $fileName);
    }
}