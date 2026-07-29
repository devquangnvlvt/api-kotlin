<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostSave extends Model
{
    public $timestamps = false;

    protected $fillable = ['post_id', 'user_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
