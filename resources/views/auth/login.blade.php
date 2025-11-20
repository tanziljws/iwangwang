@extends('layouts.app')

@section('title', 'Login - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Auth.css') }}">
@endpush

@section('content')
<div class="auth-page" style="display:grid; place-items:center; padding:48px 16px;">
    <form method="POST" action="{{ route('user.login') }}" class="auth-card" style="width:100%; max-width:820px; background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:0; box-shadow:0 18px 40px rgba(15,23,42,0.08);">
        @csrf
        <div class="auth-split">
            <div class="auth-split-left" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <div class="auth-back-link-wrapper">
                    <a href="{{ route('home') }}" class="auth-link auth-back-link" style="color: rgba(255,255,255,0.9);">← Beranda</a>
                </div>
                <div class="auth-logo-img">
                    <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="Logo SMKN 4">
                </div>
                <div class="auth-brand" style="color: white;">SMKN 4 KOTA BOGOR</div>
                <div class="auth-welcome-text">
                    <h2 style="color: white;">Halo, selamat datang!</h2>
                    <p style="color: rgba(255,255,255,0.9);">Masuk ke akunmu untuk memberi like, komentar, dan mengunduh foto galeri sekolah.</p>
                </div>
            </div>
            <div class="auth-split-right">
                <h3 style="margin:0 0 12px; color: #0d6efd;">Login</h3>
                <p class="auth-subtle" style="margin:0 0 18px; font-size:.9rem; color: #64748b;">Gunakan email dan password yang sudah terdaftar.</p>
                
                @if($errors->any())
                    <div style="color:#ef4444; margin-bottom:12px; padding:8px; background:#fee2e2; border-radius:6px; border-left:4px solid #ef4444;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div style="display:grid; gap:10px;">
                    <div>
                        <label style="display:block; margin-bottom:4px; font-size:0.9rem; color:#475569; font-weight:500;">Email</label>
                        <input class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus style="border: 2px solid #e2e8f0;">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:4px; font-size:0.9rem; color:#475569; font-weight:500;">Password</label>
                        <input class="auth-input" type="password" name="password" placeholder="Masukkan password" required style="border: 2px solid #e2e8f0;">
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" style="font-size:.9rem; color:#64748b;">Ingat saya</label>
                    </div>
                    <button type="submit" class="auth-primary" style="background: #0d6efd; border: none; font-weight: 600;">Login</button>
                    <div style="text-align:center; font-size:.9rem; color:#64748b;">
                        Belum punya akun? <a href="{{ route('user.register') }}" class="auth-link" style="color: #0d6efd;">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

