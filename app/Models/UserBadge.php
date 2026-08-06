<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{

     public $timestamps = false;
    protected $fillable = [
        'user_id',
        'badge_id',
        'pinned_order',
        'acquired_at'
    ];

    protected function casts(): array
    {
        return [
            'acquired_at' => 'datetime',
        ];
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
