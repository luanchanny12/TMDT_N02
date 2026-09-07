<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Redirect sang trang đăng nhập của Google.
     */
    public function redirect(): RedirectResponse
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Google callback — xử lý sau khi user đồng ý cấp quyền.
     */
    public function callback(): RedirectResponse
    {
        try {
            $user = $this->authService->handleGoogleCallback();
            Auth::login($user, remember: true);
            request()->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home')
                ->with('success', 'Đăng nhập bằng Google thành công!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Failed: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Đăng nhập Google thất bại. Vui lòng thử lại.']);
        }
    }
}
