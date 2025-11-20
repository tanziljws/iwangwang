@extends('layouts.app')

@section('title', 'Akun Saya - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Account.css') }}">
@endpush

@section('content')
<div class="account-page">
    <div class="account-card">
        <div class="account-main">
            <h2>Akun Saya</h2>
            <div class="account-info">
                <div><strong>Nama:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
            </div>
            <div class="account-actions">
                <a href="{{ route('gallery') }}" class="btn-secondary">Ke Galeri</a>
                <form method="POST" action="{{ route('user.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="auth-primary">Logout</button>
                </form>
            </div>
        </div>
        <div class="account-stats">
            <div class="account-stat-card">
                <h3><span class="stat-icon">❤</span> Galeri yang disukai</h3>
                <div class="stat-value" id="likedCount">0</div>
            </div>
            <div class="account-stat-card">
                <h3><span class="stat-icon">💬</span> Komentar dikirim</h3>
                <div class="stat-value" id="commentCount">0</div>
            </div>
            <div class="account-stat-card">
                <h3><span class="stat-icon">⬇</span> Foto diunduh</h3>
                <div class="stat-value" id="downloadCount">0</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load stats from localStorage (same as frontend)
    function loadStats() {
        try {
            // Liked photos count
            const likedIds = JSON.parse(localStorage.getItem('userLikedPhotoIds') || '[]');
            document.getElementById('likedCount').textContent = Array.isArray(likedIds) ? likedIds.length : 0;
        } catch (e) {
            document.getElementById('likedCount').textContent = '0';
        }
        
        try {
            // Comment count
            const commentCount = parseInt(localStorage.getItem('userCommentCount') || '0');
            document.getElementById('commentCount').textContent = commentCount || 0;
        } catch (e) {
            document.getElementById('commentCount').textContent = '0';
        }
        
        try {
            // Download count
            const downloadCount = parseInt(localStorage.getItem('userDownloadCount') || '0');
            document.getElementById('downloadCount').textContent = downloadCount || 0;
        } catch (e) {
            document.getElementById('downloadCount').textContent = '0';
        }
    }
    
    // Load stats on page load
    loadStats();
    
    // Listen for storage changes (if user likes/comments/downloads from another tab)
    window.addEventListener('storage', function(e) {
        if (e.key === 'userLikedPhotoIds' || e.key === 'userCommentCount' || e.key === 'userDownloadCount') {
            loadStats();
        }
    });
</script>
@endpush
@endsection
