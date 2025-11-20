@extends('layouts.app')

@section('title', 'Tambah Kategori - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/tambahkategori.css') }}">
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
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="nav-item active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="nav-text">Kategori</span>
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
                    <h1>Tambah Kategori</h1>
                    <p>Buat kategori baru untuk mengorganisir galeri</p>
                </div>
            </div>

            <div class="content-body">
                @if($errors->any())
                    <div style="background: #fee2e2; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.kategori.store') }}" class="tambah-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Kategori</label>
                        <div class="input-group">
                            <input
                                type="text"
                                name="nama"
                                class="form-input"
                                placeholder="Nama kategori"
                                value="{{ old('nama') }}"
                                required
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <div class="input-group">
                            <textarea
                                name="deskripsi"
                                class="form-textarea"
                                rows="4"
                                placeholder="Deskripsi (opsional)"
                            >{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

