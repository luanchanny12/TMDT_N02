<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Referral\ReferralService;

class ProfileController extends Controller
{
    public function __construct(
        private ReferralService $referralService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $referralCode = $user->referral_code ?? strtoupper(uniqid('REF-'));

        if (!$user->referral_code) {
            $user->update(['referral_code' => $referralCode]);
        }

        $stats = $this->referralService->getUserStatistics($user);

        return view('pages.profile', compact('user', 'referralCode', 'stats'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công!');
    }
}
