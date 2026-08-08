<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    protected $fillable = [
        'pack_id',
        'image_url',
        'sort_order'
    ];
}
