@extends('layouts.app')

@section('title', 'Login - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Auth.css') }}">
@endpush

@section('content')
<div class="auth-page" style="display:grid; place-items:center; padding:48px 16px;">
    <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-card" style="width:100%; max-width:820px; background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:0; box-shadow:0 18px 40px rgba(15,23,42,0.08);">
        @csrf
        <div class="auth-split">
            <div class="auth-split-left">
                <div class="auth-back-link-wrapper">
                    <a href="{{ route('home') }}" class="auth-link auth-back-link">← Beranda</a>
                </div>
                <div class="auth-logo-img">
                    <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="Logo SMKN 4">
                </div>
                <div class="auth-brand">SMKN 4 KOTA BOGOR</div>
                <div class="auth-welcome-text">
                    <h2>Halo, Admin!</h2>
                    <p>Masuk ke panel admin untuk mengelola konten website sekolah.</p>
                </div>
            </div>
            <div class="auth-split-right">
                <h3 style="margin:0 0 12px;">Login Admin</h3>
                <p class="auth-subtle" style="margin:0 0 18px; font-size:.9rem;">Gunakan username dan password admin yang sudah terdaftar.</p>
                
                @if(session('error'))
                    <div style="color:#ef4444; margin-bottom:12px; padding:8px; background:#fee2e2; border-radius:6px;">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div style="color:#059669; margin-bottom:12px; padding:8px; background:#d1fae5; border-radius:6px;">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div style="color:#ef4444; margin-bottom:12px; padding:8px; background:#fee2e2; border-radius:6px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div style="display:grid; gap:10px;">
                    <input class="auth-input" type="text" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                    <input class="auth-input" type="password" name="password" placeholder="Password" required>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" style="font-size:.9rem;">Ingat saya</label>
                    </div>
                    <button type="submit" class="auth-primary">Login</button>
                    @if(config('auth.registration_enabled', true))
                    <div style="text-align:center; font-size:.9rem;">
                        Belum punya akun? <a href="{{ route('admin.register') }}" class="auth-link">Daftar</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

