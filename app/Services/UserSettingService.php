<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSetting;
use App\Traits\ApiResponser;

class UserSettingService
{
    use ApiResponser;

    /**
     * Get settings of authenticated user
     */
    public function getSettings(User $user): array
    {
        $settings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'language'       => 'vi',
                'notify_like'    => true,
                'notify_comment' => true,
                'notify_follow'  => true,
                'notify_mention' => true,
            ]
        );

        return $this->success($settings);
    }

    /**
     * Update settings of authenticated user
     */
    public function updateSettings(User $user, array $validated): array
    {
        $settings = UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return $this->success($settings);
    }
}
