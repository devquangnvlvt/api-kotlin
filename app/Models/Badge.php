<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'icon_url',
        'price',
        'is_active',
    ];

    public function scopeActive()
    {
        return $this->where('is_active', 1);
    }
}
