<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'language',
        'notify_like',
        'notify_comment',
        'notify_follow',
        'notify_mention',
    ];

    protected function casts(): array
    {
        return [
            'notify_like'    => 'boolean',
            'notify_comment' => 'boolean',
            'notify_follow'  => 'boolean',
            'notify_mention' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
