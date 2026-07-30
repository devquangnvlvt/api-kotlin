<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}
    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $result = $this->userService->profile($request->user());
        return response()->json($result, $result['status']);
    }

    /**
     * Update user profile
     */
    // Controller
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'full_name'  => 'sometimes|string|max:150',
            'username'   => 'sometimes|string|max:50|unique:users,username,' . $request->user()->id,
            'bio'        => 'sometimes|nullable|string|max:500',
            'avatar_url' => 'sometimes|nullable|url|max:500',
        ]);

        $result = $this->userService->updateProfile($request->user(), $validated);
        return response()->json($result, $result['status'] ?? 200);
    }
}
