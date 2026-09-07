<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $referralCode = $user->referral_code ?? strtoupper(uniqid('REF-'));

        if (!$user->referral_code) {
            $user->update(['referral_code' => $referralCode]);
        }

        return view('pages.profile', compact('user', 'referralCode'));
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
