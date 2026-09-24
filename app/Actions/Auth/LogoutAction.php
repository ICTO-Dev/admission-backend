<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Services\AuthService;

class LogoutAction
{
    public function __construct(protected AuthService $authService) {}

    public function execute(User $user): bool
    {
        return $this->authService->logout($user);
    }
}
