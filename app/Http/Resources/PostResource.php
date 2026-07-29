<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userId = $request->user()?->id;

        return [
            'id'             => $this->id,
            'caption'        => $this->caption,
            'status'         => $this->status,
            'likes_count'    => $this->likes_count,
            'comments_count' => $this->comments_count,
            'is_liked'       => $this->isLikedBy($userId),
            'is_saved'       => $this->isSavedBy($userId),
            'images'         => $this->images->pluck('image_url'),
            'user'           => [
                'id'         => $this->user->id,
                'username'   => $this->user->username,
                'full_name'  => $this->user->full_name,
                'avatar_url' => $this->user->avatar_url,
            ],
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
