<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
