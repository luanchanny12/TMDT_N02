<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\Services\Referral\ReferralService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private ReferralService $referralService
    ) {}

    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register($request->validated());

        // Xử lý referral code từ session (được lưu bởi TrackReferral middleware)
        $referralCode = session()->pull('referral_code');
        if ($referralCode) {
            $this->referralService->createReferral($user, $referralCode);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Đăng ký thành công! Chào mừng bạn đến với DK Social Commerce.');
    }
}
