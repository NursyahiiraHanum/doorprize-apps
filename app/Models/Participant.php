<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $fillable = [
        'npk',
        'name',
        'tanggungan',
        'total_tiket',
        'status_karyawan',
        'gender',
        'kendaraan',
        'tahun_terakhir_menang',
        'qr_code',
    ];

    /**
     * Get the attendances for the participant.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'participant_id');
    }

    /**
     * Get the doorprize wins for the participant.
     */
    public function doorprizeWinners()
    {
        return $this->hasMany(DoorprizeWinner::class, 'participant_id');
    }
}
