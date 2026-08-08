<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickerPack extends Model
{
    protected $fillable = [
        'name',
        'cover_image_url',
        'price',
        'is_active',
    ];

    public function stickers()
    {
        return $this->hasMany(Sticker::class, 'pack_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function scopeSale($query)
    {
        return $query->where('price', '>', 0);
    }
}
