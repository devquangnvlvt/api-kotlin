<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponser;

class UserService
{
    use ApiResponser;
    /**
     * Get authenticated user profile
     */
    public function profile(User $user)
    {
        return $this->success(new UserResource($user));
    }
    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $validated): array
    {
        $user->update($validated);
        return $this->success(new UserResource($user));
    }
}
