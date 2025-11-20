@extends('layouts.admin')

@section('title', 'Login Admin - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Auth.css') }}">
<style>
    .admin-login-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .admin-login-left {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .admin-login-left h2 {
        color: white;
    }
    .admin-login-left p {
        color: rgba(255, 255, 255, 0.9);
    }
    .admin-login-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 16px;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="auth-page" style="display:grid; place-items:center; padding:48px 16px;">
    <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-card admin-login-card" style="width:100%; max-width:820px; background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:0; box-shadow:0 18px 40px rgba(15,23,42,0.08);">
        @csrf
        <div class="auth-split">
            <div class="auth-split-left admin-login-left">
                <div class="auth-back-link-wrapper">
                    <a href="{{ route('home') }}" class="auth-link auth-back-link" style="color: rgba(255,255,255,0.9);">← Beranda</a>
                </div>
                <div class="auth-logo-img">
                    <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="Logo SMKN 4">
                </div>
                <div class="auth-brand" style="color: white;">SMKN 4 KOTA BOGOR</div>
                <div class="auth-welcome-text">
                    <span class="admin-login-badge">🔐 ADMIN PANEL</span>
                    <h2 style="color: white;">Halo, Admin!</h2>
                    <p style="color: rgba(255,255,255,0.9);">Masuk ke panel admin untuk mengelola konten website sekolah.</p>
                </div>
            </div>
            <div class="auth-split-right">
                <h3 style="margin:0 0 12px; color: #667eea;">Login Admin</h3>
                <p class="auth-subtle" style="margin:0 0 18px; font-size:.9rem; color: #64748b;">Gunakan username dan password admin yang sudah terdaftar.</p>
                
                @if(session('error'))
                    <div style="color:#ef4444; margin-bottom:12px; padding:8px; background:#fee2e2; border-radius:6px; border-left:4px solid #ef4444;">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div style="color:#059669; margin-bottom:12px; padding:8px; background:#d1fae5; border-radius:6px; border-left:4px solid #059669;">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div style="color:#ef4444; margin-bottom:12px; padding:8px; background:#fee2e2; border-radius:6px; border-left:4px solid #ef4444;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div style="display:grid; gap:10px;">
                    <div>
                        <label style="display:block; margin-bottom:4px; font-size:0.9rem; color:#475569; font-weight:500;">Username</label>
                        <input class="auth-input" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username admin" required autofocus style="border: 2px solid #e2e8f0;">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:4px; font-size:0.9rem; color:#475569; font-weight:500;">Password</label>
                        <input class="auth-input" type="password" name="password" placeholder="Masukkan password" required style="border: 2px solid #e2e8f0;">
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" style="font-size:.9rem; color:#64748b;">Ingat saya</label>
                    </div>
                    <button type="submit" class="auth-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600;">Masuk sebagai Admin</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

