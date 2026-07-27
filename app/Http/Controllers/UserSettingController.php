<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class UserSettingController extends Controller
{
    /**
     * Lấy settings của user đang đăng nhập
     */
    public function show(Request $request)
    {
        $settings = UserSetting::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'language'       => 'vi',
                'notify_like'    => true,
                'notify_comment' => true,
                'notify_follow'  => true,
                'notify_mention' => true,
            ]
        );

        return response()->json(['settings' => $settings], 200);
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

        $settings = UserSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json([
            'settings' => $settings,
            'message'  => 'Settings updated successfully'
        ], 200);
    }
}
