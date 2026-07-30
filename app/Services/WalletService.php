<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiResponser;

class WalletService
{
    use ApiResponser;

    /**
     * Get wallet balance for user
     */
    public function getWallet(User $user): array
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        return $this->success([
            'balance'    => $wallet->balance,
            'updated_at' => $wallet->updated_at,
        ]);
    }

    /**
     * Get transactions history for user
     */
    public function getTransactions(User $user, int $perPage = 20): array
    {
        $wallet = Wallet::where('user_id', $user->id)->first();
        $transactions = $wallet?->transactions()
            ->orderByDesc('created_at')
            ->paginate($perPage);

        if (!$transactions) {
            return $this->success([], 200);
        }

        return $this->success($transactions);
    }
}
