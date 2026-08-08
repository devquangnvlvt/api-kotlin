<?php

namespace App\Http\Controllers;

use App\Services\FrameService;
use Illuminate\Http\Request;

class FrameController extends Controller
{
    public function __construct(
        private FrameService $frameService
    ) {}

    public function index()
    {
        return response()->json($this->frameService->getAllFrames(), 200);
    }
    public function getUserFrames(Request $request)
    {
        return response()->json($this->frameService->getUserFrames($request->user()->id), 200);
    }
    /**
     * Mua khung bằng xu
     */
    public function buy(Request $request, int $frameId)
    {
        return response()->json($this->frameService->buyFrame($request->user()->id, $frameId), 200);
    }
    /**
     * Nhận khung thành tích
     */
    public function receive(Request $request, int $frameId)
    {
        return response()->json($this->frameService->receiveFrame($request->user()->id, $frameId));
    }
    /**
     * Đeo / tháo khung viền
     */
    public function userActiveFrame(Request $request, int $frameId)
    {
        return response()->json($this->frameService->activeFrame($request->user()->id, $frameId));
    }
}
