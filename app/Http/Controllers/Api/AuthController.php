<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type'],
            ],
        ]);
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type'],
            ],
        ], 201);
    }

    /**
     * Return authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('campus');

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Handle user logout and revoke token.
     */
    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        $action->execute($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }
}
