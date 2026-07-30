<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Google_Client;
use App\Http\Resources\UserResource;
use App\Enums\UserStatus;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function handleGoogleToken(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'registration_source' => 'nullable',
        ]);

        try {

            $registration_source = $request->registration_source ?? null;

            $result = $this->authService->handleGoogleToken($request->id_token, $registration_source);
            return response()->json($result, $result['status']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google authentication failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
