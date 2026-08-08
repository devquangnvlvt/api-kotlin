<?php

namespace App\Services;

use App\Models\CheckinLog;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckinLogService
{
    use ApiResponser;

    // Thưởng theo ngày (streak_day 1-7)
    private const REWARDS = [
        1 => 100,   // Ngày 1
        2 => 200,   // Ngày 2
        3 => 300,   // Ngày 3
        4 => 400,   // Ngày 4
        5 => 500,   // Ngày 5
        6 => 600,  // Ngày 6
        7 => 700,  // Ngày 7 (bonus)
    ];

    public function checkin(int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->error('User not found', 404);
        }

        // 1. Kiểm tra đã điểm danh hôm nay chưa
        $today = Carbon::today();
        $alreadyCheckedIn = CheckinLog::where('user_id', $userId)
            ->whereDate('checkin_date', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return $this->error('Bạn đã điểm danh hôm nay rồi', 400);
        }

        // 2. Tính streak
        $lastCheckinDate = $user->last_checkin_date ? Carbon::parse($user->last_checkin_date) : null;
        $yesterday = Carbon::yesterday();

        if (!$lastCheckinDate || $lastCheckinDate->lt($yesterday)) {
            // Chưa từng checkin hoặc bỏ lỡ ngày hôm qua → reset streak về 1
            $streakDay = 1;
        } elseif ($lastCheckinDate->isSameDay($yesterday)) {
            // Checkin liên tiếp → tăng streak không giới hạn (1, 2, 3...)
            $streakDay = $user->checkin_streak + 1;
        } else {
            // Trường hợp khác
            $streakDay = 1;
        }

        // Nếu streak > 7 thì duy trì mức thưởng ở ngày 7 (150 xu)
        $cycleDay = min($streakDay, 7);
        $rewardAmount = self::REWARDS[$cycleDay];

        // 3. Lấy ví
        $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
        $newBalance = $wallet->balance + $rewardAmount;

        // 4. Thực hiện giao dịch
        DB::transaction(function () use ($userId, $today, $streakDay, $rewardAmount, $newBalance, &$user, &$checkinLog) {
            // 4a. Ghi log checkin
            $checkinLog = CheckinLog::create([
                'user_id'       => $userId,
                'checkin_date'  => $today,
                'streak_day'    => $streakDay,
                'reward_amount' => $rewardAmount,
                'created_at'    => now()
            ]);

            // 4b. Ghi lịch sử wallet (trigger sẽ tự cập nhật wallets.balance)
            WalletTransaction::record(
                userId: $userId,
                type: 'checkin',
                amount: $rewardAmount,
                balanceAfter: $newBalance,
                referenceId: $checkinLog->id,
                description: "Điểm danh ngày {$streakDay}"
            );

            // 4c. Cập nhật user
            $user->update([
                'last_checkin_date' => $today,
                'checkin_streak'    => $streakDay,
            ]);
        });

        return $this->success($checkinLog, 200);
    }

    /**
     * Lấy trạng thái checkin hôm nay
     */
    public function getStatus(int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->error('User not found', 404);
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $hasCheckedIn = CheckinLog::where('user_id', $userId)
            ->whereDate('checkin_date', $today)
            ->exists();

        $lastCheckinDate = $user->last_checkin_date ? Carbon::parse($user->last_checkin_date) : null;

        // Xử lý streak hiện tại và ngày streak tiếp theo
        if ($hasCheckedIn) {
            $currentStreak = $user->checkin_streak;
            $nextStreakDay = null;
        } else {
            if ($lastCheckinDate && $lastCheckinDate->isSameDay($yesterday)) {
                $currentStreak = $user->checkin_streak;
                $nextStreakDay = $user->checkin_streak + 1;
            } else {
                // Đã bỏ lỡ ngày hôm qua hoặc chưa từng checkin -> streak về 0, checkin tiếp theo là ngày 1
                $currentStreak = 0;
                $nextStreakDay = 1;
            }
        }

        // Tính mức thưởng của ngày tiếp theo (tối đa giữ ở ngày 7: 150 xu)
        $nextCycleDay = $nextStreakDay ? min($nextStreakDay, 7) : null;

        return $this->success([
            'has_checked_in_today' => $hasCheckedIn,
            'current_streak'       => $currentStreak,
            'last_checkin_date'    => $user->last_checkin_date,
            'next_streak_day'      => $nextStreakDay,
            'next_reward'          => $nextCycleDay ? self::REWARDS[$nextCycleDay] : null,
        ]);
    }
}
