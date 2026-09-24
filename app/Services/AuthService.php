<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate user and issue Passport token.
     */
    public function login(array $credentials): array
    {
        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->load('campus');

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Register a new user and generate a Passport token.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'] ?? null,
            'campus_id' => $data['campus_id'] ?? null,
        ]);

        $user->load('campus');

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Logout and revoke the current Passport token.
     */
    public function logout(User $user): bool
    {
        return (bool) $user->token()?->revoke();
    }
}
