<?php

namespace App\Services;

use App\Http\Resources\StickerPackResource;
use App\Models\ShopPurchase;
use App\Models\Sticker;
use App\Models\StickerPack;
use App\Models\User;
use App\Models\UserStickerPack;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class StickerPackService
{
    use ApiResponser;

    /**
     * Lấy ra toàn bộ sticker
     */
    public function getAll()
    {
        $stickerPacks = StickerPack::active()->get();
        return $this->success(StickerPackResource::collection($stickerPacks));
    }
    public function buyStickerPack($userId, $stickerPackId)
    {
        // 1. check xem sticker pack có tồn tại không 
        $sticker = StickerPack::active()->findOrFail($stickerPackId);
        if (!$sticker) {
            return $this->error('Không tìm thấy sticker pack');
        }
        // 2. check xem user đã sở hữu sticker pack chưa 
        $userStickerPack = UserStickerPack::where('user_id', $userId)->where('pack_id', $stickerPackId)->first();
        if ($userStickerPack) {
            return $this->error('Bạn đã sở hữu sticker pack này');
        }
        // 3. Kiểm tra số dư ví
        $wallet = Wallet::where('user_id', $userId)->first();
        if (!$wallet || $wallet->balance < $sticker->price) {
            return $this->error('Số dư không đủ để mua sticker này', 400);
        }
        // 4. Thực hiện giao dịch (dùng DB transaction để đảm bảo toàn vẹn)
        DB::transaction(function () use ($userId, $sticker, $wallet) {
            $newBalance = $wallet->balance - $sticker->price;

            // 4a. Ghi lịch sử mua hàng
            $purchase = ShopPurchase::record($userId, 'sticker_pack', $sticker->id, $sticker->price);

            // 4b. Ghi lịch sử giao dịch xu (trigger sẽ tự cập nhật wallets.balance)
            WalletTransaction::record(
                userId: $userId,
                type: 'shop_purchase',
                amount: -$sticker->price,
                balanceAfter: $newBalance,
                referenceId: $purchase->id,
                description: "Mua sticker pack: {$sticker->name}"
            );

            // 4c. Cấp sticker pack cho user
            UserStickerPack::create([
                'user_id'  => $userId,
                'pack_id' => $sticker->id,
            ]);
        });

        return $this->success('Mua sticker pack thành công');
    }

    public function getStickerPack($id)
    {
        return $this->success(StickerPackResource::make(StickerPack::with('stickers')->active()->findOrFail($id)));
    }
}
