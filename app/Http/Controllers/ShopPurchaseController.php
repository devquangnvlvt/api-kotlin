<?php

namespace App\Http\Controllers;

use App\Services\ShopPurchaseService;
use Illuminate\Http\Request;

class ShopPurchaseController extends Controller
{
    public function __construct(
        private ShopPurchaseService $shopPurchaseService
    ) {}

    public function index(Request $request)
    {
        return $this->shopPurchaseService->getShopPurchases($request->user()->id);
    }

    public function getBadgeHistory(Request $request)
    {
        return $this->shopPurchaseService->getBadgeHistory($request->user()->id);
    }

    public function getFrameHistory(Request $request)
    {
        return $this->shopPurchaseService->getFrameHistory($request->user()->id);
    }

    public function getStickerHistory(Request $request)
    {
        return $this->shopPurchaseService->getStickerHistory($request->user()->id);
    }
}
