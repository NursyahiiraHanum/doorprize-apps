<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    protected $table = 'prizes';

    protected $fillable = [
        'name',
        'quantity',
        'sesi',
        'employee_status',
    ];

    /**
     * Get the doorprize winners associated with this prize.
     */
    public function doorprizeWinners()
    {
        return $this->hasMany(DoorprizeWinner::class, 'prize_id');
    }
}
