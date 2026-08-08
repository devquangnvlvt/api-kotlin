<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    protected $fillable = [
        'name',
        'description',
        'task_type',
        'is_active',
        'target_count',
        'reward_amount',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function userDailyTasks()
    {
        return $this->hasMany(UserDailyTask::class, 'task_id');
    }
}
