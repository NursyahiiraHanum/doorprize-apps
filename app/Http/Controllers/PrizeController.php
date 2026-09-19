<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    public function index()
    {
        // Mengambil data per sesi langsung tanpa relasi pemenang
        $sesi1Prizes = Prize::where('sesi', 1)->latest()->get();
        $sesi2Prizes = Prize::where('sesi', 2)->latest()->get();
        $sesi3Prizes = Prize::where('sesi', 3)->latest()->get();

        $stats = [
            'sesi1' => $sesi1Prizes->sum('quantity'),
            'sesi2' => $sesi2Prizes->sum('quantity'),
            'sesi3' => $sesi3Prizes->sum('quantity'),
        ];

        return view('prizes', compact('sesi1Prizes', 'sesi2Prizes', 'sesi3Prizes', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'sesi'            => 'required|integer|in:1,2,3',
            'employee_status' => 'required|string|in:ALL,CONTRACT,PERMANENT',
        ]);

        Prize::create($validated);

        return redirect()->back()->with('success', 'Hadiah berhasil ditambahkan!');
    }

    public function update(Request $request, Prize $prize)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'sesi'            => 'required|integer|in:1,2,3',
            'employee_status' => 'required|string|in:ALL,CONTRACT,PERMANENT',
        ]);

        $prize->update($validated);

        return redirect()->back()->with('success', 'Hadiah berhasil diperbarui!');
    }

    public function destroy(Prize $prize)
    {
        $prize->delete();

        return redirect()->back()->with('success', 'Hadiah berhasil dihapus!');
    }
}