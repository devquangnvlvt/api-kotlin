<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StickerPackResource extends JsonResource
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
            'name' => $this->name,
            'cover_image_url' => $this->cover_image_url,
            'price' => $this->price,
            'is_active' => $this->is_active,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'stickers' => $this->stickers ? $this->stickers->map(function ($sticker) {
                return [
                    'id' => $sticker->id,
                    'pack_id' => $sticker->pack_id,
                    'image_url' => $sticker->image_url,
                    'sort_order' => $sticker->sort_order,
                    'created_at' => $sticker->created_at,
                ];
            }) : null,
        ];
    }
}
