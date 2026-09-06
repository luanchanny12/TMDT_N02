<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService extends BaseService
{
    /**
     * Đăng ký user mới.
     * Tự động generate referral_code duy nhất.
     * Nếu có referral_code từ URL → lưu lại để xử lý sau khi login.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'role'          => 'customer',
            'referral_code' => $this->generateUniqueReferralCode(),
        ]);

        event(new Registered($user));

        return $user;
    }

    /**
     * Đăng nhập bằng email + password.
     * Trả về true nếu thành công.
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember);
    }

    /**
     * Đăng xuất user hiện tại.
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Gửi email reset password.
     * Trả về status string từ Laravel Password broker.
     */
    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /**
     * Reset password bằng token.
     */
    public function resetPassword(array $data): string
    {
        return Password::reset(
            [
                'email'                 => $data['email'],
                'password'              => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token'                 => $data['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );
    }

    /**
     * Xử lý Google OAuth callback.
     * Tạo hoặc cập nhật user từ dữ liệu Google.
     */
    public function handleGoogleCallback(): User
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['provider_id' => $googleUser->getId(), 'provider' => 'google'],
            [
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'referral_code'     => fn ($attr) =>
                    User::where('email', $googleUser->getEmail())->exists()
                        ? User::where('email', $googleUser->getEmail())->value('referral_code')
                        : $this->generateUniqueReferralCode(),
            ]
        );

        // Đảm bảo referral_code không bị null (user cũ chưa có)
        if (! $user->referral_code) {
            $user->update(['referral_code' => $this->generateUniqueReferralCode()]);
        }

        return $user;
    }

    /**
     * Generate referral_code ngẫu nhiên và duy nhất (8 ký tự hoa).
     */
    public function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
