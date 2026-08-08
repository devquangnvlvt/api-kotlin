<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = [
        'name',
        'image_url',
        'price',
        'is_active'

    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
