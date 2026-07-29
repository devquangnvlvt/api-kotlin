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
            'amount'       => 'integer',
            'balance_after'=> 'integer',
            'created_at'   => 'datetime',
        ];
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id');
    }
}
