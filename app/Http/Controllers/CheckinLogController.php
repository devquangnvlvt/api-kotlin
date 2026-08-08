<?php

namespace App\Http\Controllers;

use App\Services\CheckinLogService;
use Illuminate\Http\Request;

class CheckinLogController extends Controller
{
    public function __construct(
        private CheckinLogService $checkinLogService
    ) {}

    /**
     * Điểm danh hằng ngày
     */
    public function checkin(Request $request)
    {
        return response()->json(
            $this->checkinLogService->checkin($request->user()->id),
            200
        );
    }

    /**
     * Lấy trạng thái checkin hôm nay
     */
    public function status(Request $request)
    {
        return response()->json($this->checkinLogService->getStatus($request->user()->id));
    }
}
