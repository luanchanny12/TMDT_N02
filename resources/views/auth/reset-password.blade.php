@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu — DK Social Commerce')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="fw-bold fs-4 text-primary">🛍️ DK Social Commerce</div>
            <p class="text-muted small mt-1">Tạo mật khẩu mới</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $email ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Ít nhất 8 ký tự" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Xác nhận mật khẩu mới</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Nhập lại mật khẩu mới" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Đặt lại mật khẩu
            </button>
        </form>
    </div>
</div>
@endsection
