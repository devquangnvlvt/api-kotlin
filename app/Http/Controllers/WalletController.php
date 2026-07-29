<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * Lấy số dư ví của user đang đăng nhập
     */
    public function show(Request $request)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['balance' => 0]
        );

        return response()->json([
            'wallet' => [
                'balance'    => $wallet->balance,
                'updated_at' => $wallet->updated_at,
            ]
        ], 200);
    }

    /**
     * Lịch sử giao dịch của user (có phân trang)
     */
    public function transactions(Request $request)
    {
        $perPage = $request->query('per_page', 20);

        $transactions = Wallet::where('user_id', $request->user()->id)
            ->first()
            ?->transactions()  // null ->dừng lại ở đây luôn không gọi nữa 
            ->orderByDesc('created_at')
            ->paginate($perPage);

        if (!$transactions) {
            return response()->json([
                'data' => [],
                'message' => 'No wallet found'
            ], 200);
        }

        return response()->json($transactions, 200);
    }
}
