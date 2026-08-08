<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStickerPack extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pack_id',
        'acquired_at',
    ];

    public function stickerPack()
    {
        return $this->belongsTo(StickerPack::class, 'pack_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
