<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Tampilkan halaman scan + data dari tabel attendances
    // public function index()
    // {
    //     $attendances = Attendance::with('participant')
    //         ->orderBy('scanned_at', 'desc')
    //         ->get();

    //     return view('attendance', compact('attendances'));
    // }

    // DEMO INI
    public function index()
    {
        $attendances = Attendance::with('participant')->latest()->get();
        $totalParticipants = Participant::count();
        
        // Cek apakah semua peserta sudah hadir
        $allAttended = $totalParticipants > 0 && ($attendances->count() >= $totalParticipants);

        return view('attendance', compact('attendances', 'allAttended'));
    }

    // Proses Scan QR Code
    public function store(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // 1. Cari peserta di tabel participants
        $participant = Participant::where('qr_code', $request->qr_code)
            ->orWhere('npk', $request->qr_code)
            ->first();

        if (!$participant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data peserta tidak ditemukan!'
            ], 404);
        }

        // 2. Cek apakah peserta sudah hadir
        $existingAttendance = Attendance::where('participant_id', $participant->id)->first();

        if ($existingAttendance) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Peserta ' . $participant->name . ' SUDAH registrasi pada ' . Carbon::parse($existingAttendance->scanned_at)->format('H:i:s d-m-Y'),
                'data'    => $participant
            ], 400);
        }

        // 3. Simpan kehadiran
        $attendance = Attendance::create([
            'participant_id' => $participant->id,
            'scanned_at'     => Carbon::now(),
        ]);

        return response()->json([
            'status'        => 'success',
            'message'       => 'Registrasi Berhasil! Waktu hadir tersimpan di sistem.',
            'data'          => $participant,
            'attendance_id' => $attendance->id,
        ], 200);
    }

    // Hapus single kehadiran
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kehadiran peserta berhasil dibatalkan.'
        ]);
    }

    // Reset Kehadiran Terpilih (Bulk Delete via Checkbox)
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:attendances,id'
        ]);

        Attendance::whereIn('id', $request->ids)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kehadiran peserta terpilih berhasil direset.'
        ]);
    }

    //DEMO 

    public function demoAttendAll()
    {
        // Ambil ID peserta yang BELUM HADIR saja
        $attendedParticipantIds = Attendance::pluck('participant_id')->toArray();
        $unattendedIds = Participant::whereNotIn('id', $attendedParticipantIds)->pluck('id');

        // Jika sudah tidak ada peserta yang belum hadir
        if ($unattendedIds->isEmpty()) {
            return response()->json([
                'status'  => 'info',
                'message' => 'Semua peserta sudah tercatat hadir!'
            ]);
        }

        $now = now();
        $dataToInsert = $unattendedIds->map(function ($id) use ($now) {
            return [
                'participant_id' => $id,
                'scanned_at'     => $now,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        })->toArray();

        // Masukkan data peserta yang belum hadir
        Attendance::insert($dataToInsert);

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil menghadirkan seluruh peserta untuk demo!'
        ]);
    }
}