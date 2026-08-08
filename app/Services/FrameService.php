<?php

namespace App\Services;

use App\Http\Resources\FrameResource;
use App\Models\Frame;
use App\Models\ShopPurchase;
use App\Models\User;
use App\Models\UserFrame;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class FrameService
{
    use ApiResponser;

    /**
     * lấy ra  tất cả các khung đang được bán is_active = 1;
     */
    public function getAllFrames()
    {
        $frame =  Frame::active()->get();
        return $this->success(FrameResource::collection($frame));
    }

    /**
     * Lấy tất cả badge user đang sở hữu
     */
    public function getUserFrames(int $userId)
    {
        $userFrames = UserFrame::with('frame')
            ->where('user_id', $userId)
            ->get();

        return $this->success(FrameResource::collection($userFrames->pluck('frame')));
    }

    public function buyFrame(int $userId, int $frameId)
    {

        // 1. Kiểm tra badge tồn tại, đang active và có thể mua (price > 0)
        $frame = Frame::active()
            ->where('id', $frameId)
            // ->where('price', '>', 0)
            ->first();

        if (!$frame) {
            return $this->error('Khung không tồn tại hoặc không còn bán', 404);
        }
        // 2. Kiểm tra user đã có frame này chưa
        $alreadyOwned = UserFrame::where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if ($alreadyOwned) {
            return $this->error('Bạn đã sở hữu khung này rồi', 409);
        }

        // 3. Kiểm tra số dư ví
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!$wallet || $wallet->balance < $frame->price) {
            return $this->error('Số dư không đủ để mua khung này', 400);
        }
        // 4. Thực hiện giao dịch (dùng DB transaction để đảm bảo toàn vẹn)
        DB::transaction(function () use ($userId, $frame, $wallet) {
            $newBalance = $wallet->balance - $frame->price;

            // 4a. Ghi lịch sử mua hàng
            $purchase = ShopPurchase::record($userId, 'frame', $frame->id, $frame->price);

            // 4b. Ghi lịch sử giao dịch xu (trigger sẽ tự cập nhật wallets.balance)
            WalletTransaction::record(
                userId: $userId,
                type: 'shop_purchase',
                amount: -$frame->price,
                balanceAfter: $newBalance,
                referenceId: $purchase->id,
                description: "Mua khung: {$frame->name}"
            );

            // 4c. Cấp khung cho user
            UserFrame::create([
                'user_id'  => $userId,
                'frame_id' => $frame->id,
            ]);
        });

        return $this->success('Mua khung thành công');
    }
    public function receiveFrame(int $userId, int $frameId)
    {
        // 1  check xem huy hiệu đó còn hoạt động không
        $frame = Frame::active()
            ->where('id', $frameId)
            ->where('price', '=', 0)
            ->first();

        if (!$frame) {
            return $this->error('Khung không tồn tại hoặc không còn bán', 404);
        }
        // 2. Kiểm tra user đã có khung này chưa
        $alreadyOwned = UserFrame::where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if ($alreadyOwned) {
            return $this->error('Bạn đã sở hữu khung này rồi', 409);
        }

        ShopPurchase::record($userId, 'frame', $frame->id, $frame->price);

        // 3. Cấp huy hiệu cho user
        UserFrame::create([
            'user_id'  => $userId,
            'frame_id' => $frame->id,
        ]);

        return $this->success('Nhận khung thành công');
    }

    public function activeFrame(int $userId, int $frameId)
    {
        // 1. kiểm tra khung viền đó mua chưa
        $userFrame = UserFrame::where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if (!$userFrame) {
            return $this->error('Bạn chưa sở hữu khung này', 404);
        }

        // 2. Đeo khung viền
        $user = User::find($userId);
        if ($user) {
            $user->active_frame_id = $frameId;
            $user->save();
        }
        return $this->success('Đeo khung viền thành công');
    }
}
