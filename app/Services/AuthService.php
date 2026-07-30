<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserStatus;
use App\Http\Resources\UserResource;
use Google_Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Handle Google Token Login / Registration
     */
    public function handleGoogleToken(string $idToken, ?string $registrationSource = null): array
    {
        $client = new Google_Client();
        $payload = $client->verifyIdToken($idToken);

        if (!$payload) {
            return [
                'success' => false,
                'status' => 401,
                'data' => [
                    'error' => 'Invalid token',
                    'message' => 'The provided Google ID token is invalid or expired',
                ],
            ];
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        $fullName = $payload['name'] ?? $email;
        $avatarUrl = $payload['picture'] ?? null;

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
                'registration_source' => $registrationSource,
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

        if ($user->status === UserStatus::Deleted) {
            return [
                'success' => false,
                'status' => 403,
                'data' => [
                    'error' => 'account_locked',
                    'message' => 'Your account has been locked. Please contact support.',
                ],
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'status' => 200,
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    /**
     * Revoke user's current token
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
