<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    public $timestamps = false;

    protected $fillable = ['comment_id', 'user_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
