<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'reference_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at'    => 'datetime',
        ];
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id');
    }

    /**
     * Ghi lịch sử giao dịch xu
     * amount dương = cộng xu, âm = trừ xu
     */
    public static function record(int $userId, string $type, int $amount, int $balanceAfter, ?int $referenceId = null, ?string $description = null): self
    {
        return self::create([
            'user_id'       => $userId,
            'type'          => $type,
            'amount'        => $amount,
            'balance_after' => $balanceAfter,
            'reference_id'  => $referenceId,
            'description'   => $description,
        ]);
    }
}
