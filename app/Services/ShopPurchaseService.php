<?

namespace App\Services;

use App\Models\ShopPurchase;
use App\Traits\ApiResponser;

class ShopPurchaseService
{
    use ApiResponser;

    public function getShopPurchases($userId)
    {
        $shopPurchases = ShopPurchase::where('user_id', $userId)->get();
        return $this->success($shopPurchases);
    }

    public function getBadgeHistory($userId)
    {
        $badgeHistory = ShopPurchase::where('user_id', $userId)
            ->where('item_type', 'badge')
            ->get();
        return $this->success($badgeHistory);
    }

    public function getFrameHistory($userId)
    {
        $frameHistory = ShopPurchase::where('user_id', $userId)
            ->where('item_type', 'frame')
            ->get();
        return $this->success($frameHistory);
    }

    public function getStickerHistory($userId)
    {
        $stickerHistory = ShopPurchase::where('user_id', $userId)
            ->where('item_type', 'sticker_pack')
            ->get();
        return $this->success($stickerHistory);
    }
}
