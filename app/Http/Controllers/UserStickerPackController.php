<?php

namespace App\Http\Controllers;

use App\Services\StickerPackService;
use App\Services\UserStickerPackService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class UserStickerPackController extends Controller
{
    public function __construct(protected UserStickerPackService $userStickerPackService) {}

    public function index(Request $request)
    {
        return $this->userStickerPackService->getUserStickerPacks($request->user()->id);
    }
    // public function getUserStickers(Request $request)
    // {
    //     return $this->stickerPackService->getUserStickers($request->user()->id);
    // }
    // public function getUserStickerPacks(Request $request)
    // {
    //     return $this->stickerPackService->getUserStickerPacks($request->user()->id);
    // }
    // public function receiveStickerPack(Request $request)
    // {
    //     return $this->stickerPackService->receiveStickerPack($request->user()->id);
    // }
}
