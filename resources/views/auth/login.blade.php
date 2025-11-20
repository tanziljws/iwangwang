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
            <div class="auth-split-left">
                <div class="auth-back-link-wrapper">
                    <a href="{{ route('home') }}" class="auth-link auth-back-link">← Beranda</a>
                </div>
                <div class="auth-logo-img">
                    <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="Logo SMKN 4">
                </div>
                <div class="auth-brand">SMKN 4 KOTA BOGOR</div>
                <div class="auth-welcome-text">
                    <h2>Halo, selamat datang!</h2>
                    <p>Masuk ke akunmu untuk memberi like, komentar, dan mengunduh foto galeri sekolah.</p>
                </div>
            </div>
            <div class="auth-split-right">
                <h3 style="margin:0 0 12px;">Login</h3>
                <p class="auth-subtle" style="margin:0 0 18px; font-size:.9rem;">Gunakan email dan password yang sudah terdaftar.</p>
                
                @if($errors->any())
                    <div style="color:#ef4444; margin-bottom:12px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div style="display:grid; gap:10px;">
                    <input class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                    <input class="auth-input" type="password" name="password" placeholder="Password" required>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember" style="font-size:.9rem;">Ingat saya</label>
                    </div>
                    <button type="submit" class="auth-primary">Login</button>
                    <div style="text-align:center; font-size:.9rem;">
                        Belum punya akun? <a href="{{ route('user.register') }}" class="auth-link">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

