@extends('layouts.auth')

@section('title', 'Admin Dashboard — DK Social Commerce')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="text-center">
        <h1 class="display-6 fw-bold text-primary">🛍️ Admin Dashboard</h1>
        <p class="text-muted">Trang quản trị đang được xây dựng (Day 6+)</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger">Đăng xuất</button>
        </form>
    </div>
</div>
@endsection
