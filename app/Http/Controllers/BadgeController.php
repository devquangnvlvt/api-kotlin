<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\Request;

class BadgeController extends Controller
{

    public function __construct(
        private BadgeService $badgeService
    ) {}

    public function index()
    {
        $badges = $this->badgeService->getAllActiveBadges();
        return response()->json($badges);
    }

    public function getUserBadges(Request $request)
    {
        $userId = $request->user()->id;
        $badges = $this->badgeService->getUserBadges($userId);
        return response()->json($badges);
    }
}
