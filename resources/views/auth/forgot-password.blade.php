@extends('layouts.auth')

@section('title', 'Quên mật khẩu — DK Social Commerce')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="fw-bold fs-4 text-primary">🛍️ DK Social Commerce</div>
            <p class="text-muted small mt-1">Đặt lại mật khẩu của bạn</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <p class="text-muted small mb-3">
            Nhập email của bạn và chúng tôi sẽ gửi link để đặt lại mật khẩu.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="ban@example.com" autofocus required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Gửi link đặt lại mật khẩu
            </button>
        </form>

        <p class="text-center text-muted small mt-3">
            <a href="{{ route('login') }}" class="text-primary">← Quay lại đăng nhập</a>
        </p>
    </div>
</div>
@endsection
