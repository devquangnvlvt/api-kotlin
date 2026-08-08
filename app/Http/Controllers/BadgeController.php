<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function __construct(
        private BadgeService $badgeService
    ) {}

    /**
     * Lấy tất cả badge đang bán trong shop
     */
    public function index()
    {
        return response()->json($this->badgeService->getAllActiveBadges());
    }

    /**
     * Lấy tất cả badge user hiện tại đang sở hữu
     */
    public function getUserBadges(Request $request)
    {
        return response()->json($this->badgeService->getUserBadges($request->user()->id));
    }

    /**
     * Mua huy hiệu bằng xu
     */
    public function buy(Request $request, int $badgeId)
    {
        return response()->json($this->badgeService->buyBadge($request->user()->id, $badgeId));
    }

    /**
     * Nhận huy hiệu thành tích
     */
    public function receive(Request $request, int $badgeId) {
         return response()->json($this->badgeService->receiveBadge($request->user()->id, $badgeId));
    }
}
