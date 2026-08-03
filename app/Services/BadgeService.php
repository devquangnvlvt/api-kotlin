<?php

namespace App\Services;

use App\Http\Resources\BadgeResource;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Traits\ApiResponser;
use Mockery\Matcher\Any;

class BadgeService
{

    use ApiResponser;
    // lấy ra tất cả badge hoạt động (đang bán/ hiển thị trong shop is_active = 1)
    public function getAllActiveBadges()
    {
        $badge =  Badge::active()->get();

        return $this->success(BadgeResource::collection($badge));
    }
    public function getUserBadges($userId)
    {
        $badges = UserBadge::where('user_id', $userId)->get();

        return $this->success(BadgeResource::collection($badges));
    }
    
}
