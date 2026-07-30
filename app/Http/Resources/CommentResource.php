<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userId = $request->user()?->id;

        return [
            'id'                => $this->id,
            'post_id'           => $this->post_id,
            'parent_comment_id' => $this->parent_comment_id,
            'content'           => $this->content,
            'sticker_id'        => $this->sticker_id,
            'likes_count'       => $this->likes_count,
            'is_liked'          => $this->isLikedBy($userId),
            'user'              => [
                'id'         => $this->user->id,
                'username'   => $this->user->username,
                'full_name'  => $this->user->full_name,
                'avatar_url' => $this->user->avatar_url,
            ],
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
