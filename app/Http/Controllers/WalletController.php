<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WalletService;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    /**
     * Lấy số dư ví của user đang đăng nhập
     */
    public function show(Request $request)
    {
        $result = $this->walletService->getWallet($request->user());

        return response()->json([
            'wallet' => $result['data']
        ], $result['status']);
    }

    /**
     * Lịch sử giao dịch của user (có phân trang)
     */
    public function transactions(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $result = $this->walletService->getTransactions($request->user(), $perPage);

        if (empty($result['data'])) {
            return response()->json([
                'data' => [],
                'message' => 'No wallet found'
            ], 200);
        }

        return response()->json($result['data'], $result['status']);
    }
}
