<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Google_Client;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function handleGoogleToken(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // Initialize Google Client
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

            // Verify the ID token
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'error' => 'Invalid token',
                    'message' => 'The provided Google ID token is invalid or expired'
                ], 401);
            }

            // Extract user info from payload
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $fullName = $payload['name'] ?? $email;
            $avatarUrl = $payload['picture'] ?? null;

            // Find or create user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'google_uid' => $googleId,
                    'username' => explode('@', $email)[0] . '_' . substr(md5($googleId), 0, 6),
                    'email' => $email,
                    'full_name' => $fullName,
                    'avatar_url' => $avatarUrl,
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'user',
                    'status' => 'active',
                ]);
            } else {
                if (!$user->google_uid) {
                    $user->update([
                        'google_uid' => $googleId,
                        'avatar_url' => $avatarUrl,
                    ]);
                }
            }

            // Check if account is locked
            if ($user->status === 'deleted') {
                return response()->json([
                    'error' => 'account_locked',
                    'message' => 'Your account has been locked. Please contact support.'
                ], 403);
            }

            // Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'message' => 'Login successful'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google authentication failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
