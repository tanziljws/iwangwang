@extends('layouts.app')

@section('title', 'Kelola Galeri - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard">
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="SMKN 4" class="sidebar-logo-img">
                <h2>GALERI SMKN4</h2>
            </div>
        </div>
        <div class="sidebar-stats">
            <div class="sidebar-stat-card">
                <span class="sidebar-stat-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="sidebar-stat-icon">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Halo, {{ $petugas->nama_petugas }} <span class="wave" aria-hidden="true">👋</span>
                </span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span class="nav-text">Statistik</span>
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="nav-text">Kategori</span>
            </a>
            <a href="{{ route('admin.galeri.index') }}" class="nav-item active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="nav-text">Galeri</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="nav-item" onclick="event.preventDefault(); setActiveTab('berita');">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                </svg>
                <span class="nav-text">Berita</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="nav-item" onclick="event.preventDefault(); setActiveTab('agenda');">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="nav-text">Agenda</span>
            </a>
            <a href="{{ route('admin.user.index') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="nav-text">Kelola User</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" style="display: block; width: 100%;">
                @csrf
                <button type="submit" class="btn btn-logout" style="width: 100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="header-title">
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Galeri</h1>
                    <p class="text-sm text-gray-500">Kelola koleksi galeri sekolah</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Galeri
                    </a>
                </div>
            </div>

            <div class="content-body">
                @if(session('success'))
                    <div style="background: #d1fae5; color: #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #059669;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #fee2e2; color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #ef4444;">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="quick-add-row">
                    <a href="{{ route('admin.kategori.create') }}" class="quick-add-box">
                        <span class="quick-add-title">Tambah Kategori</span>
                        <span class="quick-add-desc">Buat kategori baru</span>
                    </a>
                    <a href="{{ route('admin.galeri.create') }}" class="quick-add-box">
                        <span class="quick-add-title">Tambah Galeri</span>
                        <span class="quick-add-desc">Pilih kategori terlebih dulu</span>
                    </a>
                    <a href="{{ route('admin.foto.create') }}" class="quick-add-box">
                        <span class="quick-add-title">Tambah Foto</span>
                        <span class="quick-add-desc">Unggah foto ke galeri</span>
                    </a>
                </div>

                <div class="gallery-grid">
                    @if($galeris->count() > 0)
                        @foreach($galeris as $galeri)
                            @php
                                $firstFoto = $galeri->foto->where('status', 1)->first();
                                $imageUrl = $firstFoto ? $firstFoto->file_url : secure_asset('images/placeholder.jpg');
                            @endphp
                            <div class="gallery-card">
                                <div class="card-image-container">
                                    <img src="{{ $imageUrl }}" alt="{{ $galeri->nama }}" class="card-image" loading="lazy">
                                    <div class="card-overlay">
                                        <div class="card-actions">
                                            <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="btn-icon btn-edit" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.galeri.destroy', $galeri->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?');" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title">{{ $galeri->nama }}</h3>
                                    <span class="date">
                                        <i class="far fa-calendar-alt"></i> {{ $galeri->created_at->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="empty-icon">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <h3>Tidak ada data galeri</h3>
                            <p>Tambahkan gambar baru dengan menekan tombol "Tambah Baru"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
