<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'character_id',
        'caption',
        'status',  // published | hidden | deleted | followers_only
    ];

    protected function casts(): array
    {
        return [
            'likes_count'    => 'integer',
            'comments_count' => 'integer',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
            'deleted_at'     => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function saves()
    {
        return $this->hasMany(PostSave::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Kiểm tra user hiện tại đã like chưa
    public function isLikedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Kiểm tra user hiện tại đã save chưa
    public function isSavedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->saves()->where('user_id', $userId)->exists();
    }
}
