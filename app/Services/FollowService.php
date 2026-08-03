<?php

namespace App\Services;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FollowService
{

    public function follow(User $follower, int $followingId)
    {
        if ($follower->id === $followingId) {
            return [
                'success' => false,
                'message' => 'You cannot follow yourself',
            ];
        }

        return DB::transaction(function () use ($follower, $followingId) {
            $followingUser = User::findOrFail($followingId);
            $follow = Follow::where('follower_id', $follower->id)
                ->where('following_id', $followingId)
                ->first();

            if ($follow) {
                $follow->delete();
                $followed = false;

                $followingUser->decrement('followers_count');
                $follower->decrement('following_count');
            } else {
                Follow::create([
                    'follower_id'  => $follower->id,
                    'following_id' => $followingId,
                ]);
                $followed = true;

                $followingUser->increment('followers_count');
                $follower->increment('following_count');
            }

            return [
                'followed'        => $followed,
                'followers_count' => $followingUser->fresh()->followers_count,
            ];
        });
    }

    // User A (tài khoản hiện tại) có ĐANG THEO DÕI User B hay không?
    public function isFollowing(User $follower, int $followingId)
    {
        return Follow::where('follower_id', $follower->id)
            ->where('following_id', $followingId)
            ->exists();
    }

    // người follow
    public function getFollowers(int $userId, int $perPage = 20)
    {
        return Follow::where('following_id', $userId)
            ->with('follower')
            ->paginate($perPage);
    }

    // người đang follow
    public function getFollowing(int $userId, int $perPage = 20)
    {
        return Follow::where('follower_id', $userId)
            ->with('following')
            ->paginate($perPage);
    }
}
