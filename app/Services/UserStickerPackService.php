<?php

namespace App\Services;

use App\Http\Resources\StickerPackResource;
use App\Models\StickerPack;
use App\Models\UserStickerPack;
use App\Traits\ApiResponser;

class UserStickerPackService
{
    use ApiResponser;

    public function getUserStickerPacks($userId)
    {
        $userStickerPacks = UserStickerPack::with('stickerPack')
            ->where('user_id', $userId)
            ->get()
            ->pluck('stickerPack');
        return $this->success(StickerPackResource::collection($userStickerPacks));
    }
}
