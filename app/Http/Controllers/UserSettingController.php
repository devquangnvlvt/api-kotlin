<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSettingService;

class UserSettingController extends Controller
{
    public function __construct(private UserSettingService $userSettingService) {}

    /**
     * Lấy settings của user đang đăng nhập
     */
    public function show(Request $request)
    {
        $result = $this->userSettingService->getSettings($request->user());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * Cập nhật settings của user đang đăng nhập
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'language'       => 'sometimes|string|in:vi,en',
            'notify_like'    => 'sometimes|boolean',
            'notify_comment' => 'sometimes|boolean',
            'notify_follow'  => 'sometimes|boolean',
            'notify_mention' => 'sometimes|boolean',
        ]);

        $result = $this->userSettingService->updateSettings($request->user(), $validated);

        return response()->json([
            'settings' => $result['data'],
            'message'  => 'Settings updated successfully'
        ], $result['status']);
    }
}
