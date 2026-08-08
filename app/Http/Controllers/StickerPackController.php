<?php

namespace App\Http\Controllers;

use App\Models\StickerPack;
use App\Services\StickerPackService;
use Illuminate\Http\Request;

class StickerPackController extends Controller
{
    public function __construct(protected StickerPackService $stickerPackService) {}

    public function index()
    {
        return $this->stickerPackService->getAll();
    }
    public function show(StickerPack $id)
    {
        return $this->stickerPackService->getStickerPack($id->id);
    }
    public function buy(Request $request, StickerPack $id)
    {
        return $this->stickerPackService->buyStickerPack($request->user()->id, $id->id);
    }
    // public function getUserStickerPacks(Request $request)
    // {
    //     return $this->stickerPackService->getUserStickerPacks($request->user()->id);
    // }
}
