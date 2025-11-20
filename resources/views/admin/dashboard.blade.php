@extends('layouts.app')

@section('title', 'Dashboard Admin - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard" id="adminDashboard">
    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="menu-toggle" id="mobileMenuToggle" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1 id="mobileHeaderTitle">Dashboard</h1>
    </header>

    <!-- Sidebar -->
    <aside class="dashboard-sidebar" id="dashboardSidebar">
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
            <button class="nav-item" onclick="navigateTo('dashboard')" id="nav-dashboard">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span class="nav-text">Statistik</span>
            </button>
            <button class="nav-item" onclick="setActiveTab('kategori')" id="nav-kategori">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="nav-text">Kategori</span>
            </button>
            <button class="nav-item active" onclick="setActiveTab('galeri')" id="nav-galeri">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="nav-text">Galeri</span>
            </button>
            <button class="nav-item" onclick="setActiveTab('berita')" id="nav-berita">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                    <path d="M18 14h-8"></path>
                    <path d="M15 18h-5"></path>
                    <path d="M10 6h8v4h-8V6Z"></path>
                </svg>
                <span class="nav-text">Berita</span>
            </button>
            <button class="nav-item" onclick="setActiveTab('agenda')" id="nav-agenda">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="nav-text">Agenda</span>
            </button>
            <button class="nav-item" onclick="setActiveTab('user')" id="nav-user">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="nav-text">Kelola User</span>
            </button>
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

    <!-- Main Content -->
    <main class="dashboard-content">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="header-title">
                    <h1 class="text-2xl font-bold text-gray-800" id="contentTitle">Kelola Galeri</h1>
                    <p class="text-sm text-gray-500" id="contentSubtitle">Kelola koleksi galeri sekolah</p>
                </div>
                <div class="header-actions" id="headerActions">
                    <!-- Actions will be shown based on active tab -->
                </div>
            </div>

            <!-- Content Body -->
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

                <!-- Dashboard Tab (Stats) -->
                <div id="tab-dashboard" style="display: none;">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Total Foto</div>
                            <div class="stat-value">{{ number_format($totalFotos) }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Total Kategori</div>
                            <div class="stat-value">{{ number_format($totalKategoris) }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Total Galeri</div>
                            <div class="stat-value">{{ number_format($totalGaleris) }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Petugas Aktif</div>
                            <div class="stat-value">{{ number_format($totalPetugas) }}</div>
                        </div>
                    </div>

                    @if($recentFotos->count() > 0)
                    <div style="margin-top: 2rem;">
                        <h2 style="margin-bottom: 1rem;">Foto Terbaru</h2>
                        <div class="gallery-grid">
                            @foreach($recentFotos as $foto)
                            <div class="gallery-card">
                                <div class="card-image-container">
                                    <img src="{{ $foto->file_url }}" alt="{{ $foto->judul }}" class="card-image" loading="lazy">
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title">{{ $foto->judul ?? 'Foto ' . $foto->id }}</h3>
                                    <span class="date">
                                        <i class="far fa-calendar-alt"></i> {{ $foto->created_at->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($recentGaleris->count() > 0)
                    <div style="margin-top: 2rem;">
                        <h2 style="margin-bottom: 1rem;">Galeri Terbaru</h2>
                        <div class="gallery-grid">
                            @foreach($recentGaleris as $galeri)
                            <div class="gallery-card">
                                <div class="card-image-container">
                                    @php
                                        $firstFoto = $galeri->foto->first();
                                        $imageUrl = $firstFoto ? $firstFoto->file_url : secure_asset('images/placeholder.jpg');
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $galeri->nama }}" class="card-image" loading="lazy">
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title">{{ $galeri->nama ?? 'Galeri ' . $galeri->id }}</h3>
                                    <span class="date">
                                        <i class="far fa-calendar-alt"></i> {{ $galeri->created_at->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Kategori Tab -->
                <div id="tab-kategori" style="display: none;">
                    <div class="table-container">
                        @php
                            $kategoris = \App\Models\Kategori::orderBy('urutan')->orderBy('nama')->get();
                        @endphp
                        @if($kategoris->count() > 0)
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kategoris as $k)
                                        <tr>
                                            <td>
                                                <div class="font-medium text-gray-900">{{ $k->nama }}</div>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-600 max-w-xs truncate">{{ $k->deskripsi ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('admin.kategori.edit', $k->id) }}" class="btn-icon btn-edit" title="Edit">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" style="display:inline-block;">
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="empty-icon">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <h3>Belum ada kategori</h3>
                                <p>Tambahkan kategori baru melalui tombol "Tambah Kategori" di bagian Galeri.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Galeri Tab -->
                <div id="tab-galeri">
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
                        @php
                            $galeris = \App\Models\Galeri::with(['kategori', 'foto'])->where('status', 1)->orderBy('urutan')->orderBy('nama')->get();
                        @endphp
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
                            <div class="empty-state">
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

                <!-- Berita Tab -->
                <div id="tab-berita" style="display: none;">
                    <div class="table-container">
                        @php
                            $beritas = \App\Models\Berita::orderByDesc('published_at')->orderByDesc('created_at')->get();
                        @endphp
                        @if($beritas->count() > 0)
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th class="w-1/2">Judul</th>
                                            <th>Tanggal</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($beritas as $b)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px; color: #3b82f6;">
                                                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                                        <path d="M18 14h-8"></path>
                                                        <path d="M15 18h-5"></path>
                                                        <path d="M10 6h8v4h-8V6Z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $b->judul }}</div>
                                                        <div class="text-sm text-gray-500 mt-1">{{ $b->status ?? 'draft' }} • {{ $b->author ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-900">
                                                    {{ $b->published_at ? \Carbon\Carbon::parse($b->published_at)->format('d M Y') : '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" class="btn-icon btn-edit" title="Edit (Coming Soon)" onclick="alert('Fitur edit berita akan segera hadir')">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('admin.berita.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');" style="display:inline-block;">
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="empty-icon">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                </svg>
                                <h3>Belum ada berita</h3>
                                <p>Tambahkan berita baru dengan menekan tombol "Tambah Berita"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Agenda Tab -->
                <div id="tab-agenda" style="display: none;">
                    <div class="table-container">
                        @php
                            $agendas = \App\Models\Agenda::orderBy('date')->latest()->get();
                        @endphp
                        @if($agendas->count() > 0)
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th class="w-1/2">Judul</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Lokasi</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agendas as $a)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px; color: #10b981;">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $a->title }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}
                                                    @if($a->time)
                                                    <div class="text-sm text-gray-500">{{ $a->time }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-900">{{ $a->location ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" class="btn-icon btn-edit" title="Edit (Coming Soon)" onclick="alert('Fitur edit agenda akan segera hadir')">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('admin.agenda.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');" style="display:inline-block;">
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="empty-icon">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <h3>Belum ada agenda</h3>
                                <p>Tambahkan agenda baru dengan menekan tombol "Tambah Agenda"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Tab -->
                <div id="tab-user" style="display: none;">
                    <div class="table-container">
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="empty-icon">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <h3>Fitur Kelola User</h3>
                            <p>Fitur ini akan segera hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
let activeTab = 'galeri';
let sidebarOpen = true;
let isMobile = window.innerWidth < 1024;

function toggleSidebar() {
    sidebarOpen = !sidebarOpen;
    const sidebar = document.getElementById('dashboardSidebar');
    if (isMobile) {
        sidebar.classList.toggle('open', sidebarOpen);
    } else {
        document.getElementById('adminDashboard').classList.toggle('sidebar-collapsed', !sidebarOpen);
    }
}

function setActiveTab(tab) {
    activeTab = tab;
    
    // Hide all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.style.display = 'none');
    
    // Show active tab
    const activeTabEl = document.getElementById('tab-' + tab);
    if (activeTabEl) {
        activeTabEl.style.display = 'block';
    }
    
    // Update nav items
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    const activeNav = document.getElementById('nav-' + tab);
    if (activeNav) {
        activeNav.classList.add('active');
    }
    
    // Update header
    const titles = {
        'dashboard': { title: 'Statistik', subtitle: 'Ringkasan data galeri sekolah' },
        'kategori': { title: 'Kelola Kategori', subtitle: 'Kelola kategori galeri' },
        'galeri': { title: 'Kelola Galeri', subtitle: 'Kelola koleksi galeri sekolah' },
        'berita': { title: 'Kelola Berita', subtitle: 'Kelola berita dan pengumuman' },
        'agenda': { title: 'Kelola Agenda', subtitle: 'Kelola jadwal dan acara sekolah' },
        'user': { title: 'Kelola User', subtitle: 'Kelola akun pengguna galeri' }
    };
    
    const tabInfo = titles[tab] || titles['galeri'];
    document.getElementById('contentTitle').textContent = tabInfo.title;
    document.getElementById('contentSubtitle').textContent = tabInfo.subtitle;
    
    // Update mobile header
    document.getElementById('mobileHeaderTitle').textContent = tabInfo.title;
    
    // Update header actions
    const headerActions = document.getElementById('headerActions');
    if (tab === 'agenda') {
        headerActions.innerHTML = '<button type="button" class="btn btn-primary" onclick="alert(\'Fitur tambah agenda akan segera hadir\')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah Agenda</button>';
    } else if (tab === 'berita') {
        headerActions.innerHTML = '<button type="button" class="btn btn-primary" onclick="alert(\'Fitur tambah berita akan segera hadir\')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah Berita</button>';
    } else {
        headerActions.innerHTML = '';
    }
    
    // Close sidebar on mobile after navigation
    if (isMobile) {
        sidebarOpen = false;
        document.getElementById('dashboardSidebar').classList.remove('open');
    }
}

function navigateTo(route) {
    if (route === 'dashboard') {
        setActiveTab('dashboard');
    } else {
        window.location.href = route;
    }
}

// Handle window resize
window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 1024;
    if (!isMobile) {
        sidebarOpen = true;
        document.getElementById('dashboardSidebar').classList.remove('open');
        document.getElementById('adminDashboard').classList.remove('sidebar-collapsed');
    } else {
        sidebarOpen = false;
        document.getElementById('dashboardSidebar').classList.remove('open');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    setActiveTab('galeri');
    
    // Add mobile menu toggle icon
    const mobileToggle = document.getElementById('mobileMenuToggle');
    if (mobileToggle) {
        mobileToggle.innerHTML = sidebarOpen 
            ? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>'
            : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
    }
});
</script>
@endpush
@endsection
