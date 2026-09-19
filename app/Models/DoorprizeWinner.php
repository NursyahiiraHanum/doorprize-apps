<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorprizeWinner extends Model
{
    protected $table = 'doorprize_winners';

    protected $fillable = [
        'participant_id',
        'prize_id',
        'session',
        'won_at',
    ];

    /**
     * Get the participant who won.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    /**
     * Get the prize that was won.
     */
    public function prize()
    {
        return $this->belongsTo(Prize::class, 'prize_id');
    }
}
