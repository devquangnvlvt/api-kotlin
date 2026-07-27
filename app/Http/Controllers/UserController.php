<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {   
        
        return response()->json([
            'user' => new UserResource($request->user())
        ], 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        // sometimes — CHỈ validate NẾU có gửi lên, không gửi thì bỏ qua
        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'sometimes|nullable|string|max:500',
            'avatar_url' => 'sometimes|nullable|url',
        ]);

        $user->update($validated);

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Profile updated successfully'
        ], 200);
    }
}
