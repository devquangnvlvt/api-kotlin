<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPurchase extends Model
{

    public $timestamps = false;

    protected $fillable = ['user_id','item_type','item_id','price_paid','purchased_at'];


    /**
     * Tạo bản ghi mua hàng và trả về instance
     */
    public static function record(int $userId, string $itemType, int $itemId, int $pricePaid): self
    {
        return self::create([
            'user_id'      => $userId,
            'item_type'    => $itemType,
            'item_id'      => $itemId,
            'price_paid'   => $pricePaid,
            'purchased_at' => now(),
        ]);
    }

}
