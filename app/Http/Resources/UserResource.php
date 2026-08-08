<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'google_uid' => $this->google_uid,
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'avatar_url' => $this->avatar_url,
            'bio' => $this->bio,
            'role' => $this->role,
            'status' => $this->status,
            'checkin_streak' => $this->checkin_streak,
            'last_checkin_date' => $this->last_checkin_date,
            'posts_count' => $this->posts_count,
            'followers_count' => $this->followers_count,
            'following_count' => $this->following_count,
            'active_frame_id' => $this->frame ? [
                'name'      => $this->frame->name,
                'image_url' => $this->frame->image_url,
            ] : null,
            'registration_source' => $this->registration_source,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
