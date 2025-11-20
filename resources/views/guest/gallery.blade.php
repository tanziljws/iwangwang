@extends('layouts.app')

@section('title', 'Galeri - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Gallery.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="gallery-page">
    <section class="gallery-hero">
        <div class="hero-decor">
            <span class="blob"></span>
            <span class="ring r1"></span>
            <span class="ring r2"></span>
        </div>
        <h1>Galeri Sekolah</h1>
        <p>Rangkaian momen terbaik, fasilitas unggulan, dan aktivitas siswa yang menginspirasi.</p>
    </section>

    <section class="gallery-section">
        <div class="gallery-container">
            @php
                $allPhotos = [];
                foreach($galeris as $galeri) {
                    foreach($galeri->foto as $foto) {
                        $allPhotos[] = [
                            'id' => $foto->id,
                            'title' => $foto->judul ?? $galeri->nama,
                            'category' => $galeri->kategori->nama ?? 'Lainnya',
                            'src' => $foto->file_url,
                            'galeri' => $galeri->nama
                        ];
                    }
                }
                $categories = collect($allPhotos)->pluck('category')->unique()->prepend('Semua');
            @endphp
            
            @if(count($allPhotos) > 0)
                <div class="gallery-filters" role="tablist" aria-label="Filter Galeri">
                    @foreach($categories as $cat)
                        <button type="button" class="filter-btn {{ $loop->first ? 'active' : '' }}" data-category="{{ $cat }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
                
                <div class="gallery-grid" id="galleryGrid">
                    @foreach($allPhotos as $idx => $photo)
                        <button type="button" class="gallery-item fadein" data-photo-id="{{ $photo['id'] }}" data-category="{{ $photo['category'] }}" style="--stagger: {{ $idx }}" aria-label="Buka foto {{ $photo['title'] }}">
                            <img src="{{ $photo['src'] }}" alt="{{ $photo['title'] }}" loading="lazy">
                            <div class="image-overlay">
                                <h3>{{ $photo['title'] }}</h3>
                                <span class="badge">{{ $photo['category'] }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; color: #666; grid-column: 1 / -1;">Belum ada foto di galeri.</div>
            @endif
        </div>
    </section>

    <!-- Gallery Viewer (muncul ketika foto dipilih) -->
    <section class="gallery-viewer" id="galleryViewer" aria-label="Foto terpilih" style="display: none;">
        <div class="gallery-viewer-card">
            <div class="gallery-viewer-header">
                <h2 id="viewerTitle"></h2>
                <button type="button" class="gallery-viewer-close" aria-label="Tutup tampilan foto" onclick="closeViewer()">×</button>
            </div>
            <div class="gallery-viewer-image-wrap">
                <img id="viewerImage" src="" alt="">
            </div>
            <div class="gallery-viewer-meta">
                <span class="badge" id="viewerCategory"></span>
            </div>

            <div class="gallery-actions">
                <div class="actions-left">
                    <button type="button" id="likeBtn" onclick="toggleLike()" class="btn-heart" aria-label="Suka">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41.81 4.5 2.09C12.59 4.81 14.26 4 16 4 18.5 4 20.5 6 20.5 8.5c0 3.78-3.4 6.86-8.05 11.54L12 21.35z"></path>
                        </svg>
                    </button>
                    <span class="like-count" id="likeCount">0 suka</span>
                </div>
                <div class="like-end">
                    <button type="button" class="btn-comment" aria-label="Komentar" onclick="focusCommentInput()">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                        </svg>
                    </button>
                    <button type="button" onclick="downloadPhoto()" class="btn-download" aria-label="Unduh">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="comments" style="padding:8px 12px 16px">
                <form id="commentForm" onsubmit="sendComment(event)" style="display:flex; gap:8px; margin-bottom:12px">
                    <input type="text" id="commentInput" placeholder="Tulis komentar..." style="flex:1; color:#000" required>
                    <button type="submit" id="commentSubmitBtn" class="btn-send" aria-label="Kirim komentar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </form>
                <div class="comment-list" id="commentList" style="display:grid; gap:8px">
                    <div style="opacity:.7; color:#000">Memuat komentar...</div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Always use /api routes, but with session cookies for session-based auth
    const API_BASE = '{{ url("/api") }}';
    let selectedPhoto = null;
    let likeCount = 0;
    let liked = false;
    let likeBusy = false;
    let comments = [];
    let commentBusy = false;

    // Get CSRF token from meta tag
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Check if user is logged in via session
    function isLoggedInViaSession() {
        // Check if user is logged in via Laravel session
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name] = cookie.trim().split('=');
            if (name === 'laravel_session' || name === 'XSRF-TOKEN') {
                return true; // Session cookie exists
            }
        }
        return false;
    }

    // Check if user has API token
    function hasApiToken() {
        return !!localStorage.getItem('userToken');
    }

    function getAuthHeaders() {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        // If user has API token (not session), add it
        const token = localStorage.getItem('userToken');
        if (token && !isLoggedInViaSession()) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        
        return headers;
    }

    function requireLogin() {
        if (!isLoggedInViaSession() && !hasApiToken()) {
            alert('Silakan login terlebih dahulu untuk melakukan aksi ini.');
            window.location.href = '{{ route("user.login") }}';
            return false;
        }
        return true;
    }

    // Gallery filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const category = this.dataset.category;
            document.querySelectorAll('.gallery-item').forEach(item => {
                if (category === 'Semua' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Open photo viewer when clicked
    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', function() {
            const photoId = this.dataset.photoId;
            const photoData = {
                id: parseInt(photoId),
                title: this.querySelector('h3').textContent,
                category: this.dataset.category,
                src: this.querySelector('img').src
            };
            openViewer(photoData);
        });
    });

    function openViewer(photo) {
        selectedPhoto = photo;
        document.getElementById('viewerTitle').textContent = photo.title;
        document.getElementById('viewerImage').src = photo.src;
        document.getElementById('viewerImage').alt = photo.title;
        document.getElementById('viewerCategory').textContent = photo.category;
        document.getElementById('galleryViewer').style.display = 'block';
        
        // Load likes and comments
        loadLikes();
        loadComments();
        
        // Scroll to viewer
        document.getElementById('galleryViewer').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function closeViewer() {
        document.getElementById('galleryViewer').style.display = 'none';
        selectedPhoto = null;
        likeCount = 0;
        liked = false;
        comments = [];
        updateLikeUI();
        updateCommentsUI();
    }

    async function loadLikes() {
        if (!selectedPhoto) return;
        try {
            const res = await fetch(`${API_BASE}/foto/${selectedPhoto.id}/likes/count`, {
                headers: { 'Accept': 'application/json', ...getAuthHeaders() },
                credentials: 'same-origin' // Include cookies for session
            });
            if (res.ok) {
                const data = await res.json();
                likeCount = data.count || 0;
                // Check if user already liked (from localStorage)
                const likedIds = JSON.parse(localStorage.getItem('userLikedPhotoIds') || '[]');
                liked = likedIds.includes(selectedPhoto.id);
                updateLikeUI();
            }
        } catch (e) {
            console.error('Failed to load likes:', e);
        }
    }

    async function toggleLike() {
        if (!selectedPhoto || !requireLogin()) return;
        if (likeBusy) return;
        
        likeBusy = true;
        const btn = document.getElementById('likeBtn');
        btn.disabled = true;
        
        try {
            const res = await fetch(`${API_BASE}/foto/${selectedPhoto.id}/like`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', ...getAuthHeaders() },
                credentials: 'same-origin' // Include cookies for session
            });
            const data = await res.json();
            if (res.ok) {
                if (typeof data.count === 'number') likeCount = data.count;
                if (data.status === 'liked') {
                    liked = true;
                    const likedIds = JSON.parse(localStorage.getItem('userLikedPhotoIds') || '[]');
                    if (!likedIds.includes(selectedPhoto.id)) {
                        likedIds.push(selectedPhoto.id);
                        localStorage.setItem('userLikedPhotoIds', JSON.stringify(likedIds));
                    }
                } else if (data.status === 'unliked') {
                    liked = false;
                    const likedIds = JSON.parse(localStorage.getItem('userLikedPhotoIds') || '[]');
                    const filtered = likedIds.filter(id => id !== selectedPhoto.id);
                    localStorage.setItem('userLikedPhotoIds', JSON.stringify(filtered));
                }
                updateLikeUI();
            } else {
                alert(data.message || 'Gagal mengubah like');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan');
        } finally {
            likeBusy = false;
            btn.disabled = false;
        }
    }

    function updateLikeUI() {
        const btn = document.getElementById('likeBtn');
        const countEl = document.getElementById('likeCount');
        if (liked) {
            btn.classList.add('is-liked');
            btn.setAttribute('aria-label', 'Batalkan suka');
        } else {
            btn.classList.remove('is-liked');
            btn.setAttribute('aria-label', 'Suka');
        }
        countEl.textContent = `${likeCount} suka`;
    }

    async function loadComments() {
        if (!selectedPhoto) return;
        try {
            const res = await fetch(`${API_BASE}/foto/${selectedPhoto.id}/comments`, {
                headers: { 'Accept': 'application/json', ...getAuthHeaders() },
                credentials: 'same-origin' // Include cookies for session
            });
            if (res.ok) {
                const data = await res.json();
                comments = Array.isArray(data) ? data : [];
                updateCommentsUI();
            }
        } catch (e) {
            console.error('Failed to load comments:', e);
            comments = [];
            updateCommentsUI();
        }
    }

    async function sendComment(e) {
        e.preventDefault();
        if (!selectedPhoto || !requireLogin()) return;
        if (commentBusy) return;
        
        const input = document.getElementById('commentInput');
        const text = input.value.trim();
        if (!text) return;
        
        commentBusy = true;
        const btn = document.getElementById('commentSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '...';
        
        try {
            const res = await fetch(`${API_BASE}/foto/${selectedPhoto.id}/comments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', ...getAuthHeaders() },
                body: JSON.stringify({ body: text }),
                credentials: 'same-origin' // Include cookies for session
            });
            const data = await res.json();
            if (res.ok) {
                comments.unshift(data);
                input.value = '';
                updateCommentsUI();
                // Update comment count in localStorage
                const count = parseInt(localStorage.getItem('userCommentCount') || '0') + 1;
                localStorage.setItem('userCommentCount', String(count));
            } else {
                alert(data.message || 'Gagal mengirim komentar');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan');
        } finally {
            commentBusy = false;
            btn.disabled = false;
            btn.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
            `;
        }
    }

    function updateCommentsUI() {
        const list = document.getElementById('commentList');
        if (comments.length === 0) {
            list.innerHTML = '<div style="opacity:.7; color:#000">Belum ada komentar.</div>';
        } else {
            list.innerHTML = comments.map(c => `
                <div class="comment-item" style="border:1px solid #eee; border-radius:8px; padding:8px; background:#fff">
                    <div style="font-weight:600; font-size:13px; color:#000; margin-bottom:4px">${c.user?.name || 'Anonim'}</div>
                    <div style="font-size:14px; color:#000; line-height:1.5">${c.body || ''}</div>
                </div>
            `).join('');
        }
    }

    function focusCommentInput() {
        document.getElementById('commentInput').focus();
        document.getElementById('commentInput').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    async function downloadPhoto() {
        if (!selectedPhoto || !requireLogin()) return;
        try {
            const res = await fetch(`${API_BASE}/foto/${selectedPhoto.id}/download`, {
                headers: { ...getAuthHeaders() },
                credentials: 'same-origin' // Include cookies for session
            });
            if (!res.ok) {
                const data = await res.json();
                alert(data.message || 'Gagal mengunduh file');
                return;
            }
            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = (selectedPhoto.title || 'foto') + '.jpg';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            // Update download count in localStorage
            const count = parseInt(localStorage.getItem('userDownloadCount') || '0') + 1;
            localStorage.setItem('userDownloadCount', String(count));
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan saat mengunduh');
        }
    }

    // Close viewer on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && selectedPhoto) {
            closeViewer();
        }
    });
</script>
@endpush
@endsection
