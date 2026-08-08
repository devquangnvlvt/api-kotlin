<?php

namespace App\Services;

use App\Http\Resources\BadgeResource;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\ShopPurchase;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    use ApiResponser;

    /**
     * Lấy tất cả badge đang hoạt động (is_active = 1)
     */
    public function getAllActiveBadges()
    {
        $badges = Badge::active()->get();
        return $this->success(BadgeResource::collection($badges));
    }

    /**
     * Lấy tất cả badge user đang sở hữu
     */
    public function getUserBadges(int $userId)
    {
        $userBadges = UserBadge::with('badge')
            ->where('user_id', $userId)
            ->get();

        return $this->success(BadgeResource::collection($userBadges->pluck('badge')));
    }

    /**
     * Mua huy hiệu bằng xu
     */
    public function buyBadge(int $userId, int $badgeId)
    {
        // 1. Kiểm tra badge tồn tại, đang active và có thể mua (price > 0)
        $badge = Badge::active()
            ->where('id', $badgeId)
            // ->where('price', '>', 0) // price = 0 là huy hiệu thành tích, không bán
            ->first();

        if (!$badge) {
            return $this->error('Huy hiệu không tồn tại hoặc không còn bán', 404);
        }

        // 2. Kiểm tra user đã có badge này chưa
        $alreadyOwned = UserBadge::where('user_id', $userId)
            ->where('badge_id', $badgeId)
            ->exists();

        if ($alreadyOwned) {
            return $this->error('Bạn đã sở hữu huy hiệu này rồi', 409);
        }

        // 3. Kiểm tra số dư ví
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!$wallet || $wallet->balance < $badge->price) {
            return $this->error('Số dư không đủ để mua huy hiệu này', 400);
        }

        // 4. Thực hiện giao dịch (dùng DB transaction để đảm bảo toàn vẹn)
        DB::transaction(function () use ($userId, $badge, $wallet) {
            $newBalance = $wallet->balance - $badge->price;

            // 4a. Ghi lịch sử mua hàng
            $purchase = ShopPurchase::record($userId, 'badge', $badge->id, $badge->price);

            // 4b. Ghi lịch sử giao dịch xu (trigger sẽ tự cập nhật wallets.balance)
            WalletTransaction::record(
                userId: $userId,
                type: 'shop_purchase',
                amount: -$badge->price,
                balanceAfter: $newBalance,
                referenceId: $purchase->id,
                description: "Mua huy hiệu: {$badge->name}"
            );

            // 4c. Cấp badge cho user
            UserBadge::create([
                'user_id'  => $userId,
                'badge_id' => $badge->id,
            ]);
        });

        return $this->success('Mua huy hiệu thành công');
    }
    /**
     * Nhận huy hiệu thành tích
     */
    public function receiveBadge(int $userId, int $badgeId)
    {
        // 1  check xem huy hiệu đó còn hoạt động không
        $badge = Badge::active()
            ->where('id', $badgeId)
            ->where('price', '=', 0)
            ->first();

        if (!$badge) {
            return $this->error('Huy hiệu không tồn tại hoặc không còn bán', 404);
        }
        // 2. Kiểm tra user đã có badge này chưa
        $alreadyOwned = UserBadge::where('user_id', $userId)
            ->where('badge_id', $badgeId)
            ->exists();

        if ($alreadyOwned) {
            return $this->error('Bạn đã sở hữu huy hiệu này rồi', 409);
        }

        ShopPurchase::record($userId, 'badge', $badge->id, $badge->price);



        // 3. Cấp huy hiệu cho user
        UserBadge::create([
            'user_id'  => $userId,
            'badge_id' => $badge->id,
        ]);

        return $this->success('Nhận huy hiệu thành công');
    }
}
