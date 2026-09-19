<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'participant_id',
        'scanned_at',
    ];

    /**
     * Get the participant that owns this attendance.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }
}
    