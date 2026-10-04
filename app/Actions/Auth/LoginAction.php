<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class LoginAction
{
    public function __construct(protected AuthService $authService) {}

    public function execute(array $credentials): array
    {
        return $this->authService->login($credentials);
    }
}
