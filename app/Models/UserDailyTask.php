<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailyTask extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'task_date',
        'status',
        'progress_current',
        'completed_at',
        'claimed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(DailyTask::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
