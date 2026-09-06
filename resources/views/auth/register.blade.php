@extends('layouts.auth')

@section('title', 'Đăng ký — DK Social Commerce')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="fw-bold fs-4 text-primary">🛍️ DK Social Commerce</div>
            <p class="text-muted small mt-1">Tạo tài khoản mới</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Referral code ẩn (từ URL ?ref=CODE) --}}
            @if(session('referral_code'))
                <input type="hidden" name="referral_code" value="{{ session('referral_code') }}">
            @endif

            <div class="mb-3">
                <label class="form-label fw-medium">Họ tên</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Nguyễn Văn A" autofocus required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="ban@example.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Mật khẩu</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Ít nhất 8 ký tự" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Đăng ký
            </button>
        </form>

        <div class="d-flex align-items-center my-3">
            <hr class="flex-grow-1"> <span class="mx-2 text-muted small">hoặc</span> <hr class="flex-grow-1">
        </div>

        <a href="{{ route('auth.google') }}" class="btn btn-google w-100 py-2 d-flex align-items-center justify-content-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Đăng ký với Google
        </a>

        <p class="text-center text-muted small mt-3">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="text-primary fw-medium">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
