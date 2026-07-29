<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['post_id', 'image_url', 'sort_order'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
