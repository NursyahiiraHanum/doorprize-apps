<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Attendance;
use App\Models\Prize;
use App\Models\DoorprizeWinner;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $totalParticipants = Participant::count();
        $totalAttendance   = Attendance::count();
        $attendanceRate    = $totalParticipants > 0 ? round(($totalAttendance / $totalParticipants) * 100, 1) : 0;
        
        $totalPrizes     = Prize::sum('quantity');
        $totalWinners    = DoorprizeWinner::count();
        $remainingPrizes = max(0, $totalPrizes - $totalWinners);

        $recentWinners = DoorprizeWinner::with(['participant', 'prize'])
            ->orderBy('won_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalParticipants',
            'totalAttendance',
            'attendanceRate',
            'totalPrizes',
            'totalWinners',
            'remainingPrizes',
            'recentWinners'
        ));
    }
}
