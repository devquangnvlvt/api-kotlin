<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFrame extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'frame_id',
        'acquired_at',
    ];

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }
}
