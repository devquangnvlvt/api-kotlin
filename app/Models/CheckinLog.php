<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    protected $fillable = [
        'user_id',
        'checkin_date',
        'streak_day',
        'reward_amount'
    ];
}
