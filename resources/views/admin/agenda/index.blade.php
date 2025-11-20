@extends('layouts.admin')

@section('title', 'Kelola Agenda - Admin')

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
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="nav-item active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="nav-text">Agenda</span>
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
                    <h1>Kelola Agenda</h1>
                    <p>Kelola jadwal dan acara sekolah</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Agenda
                    </a>
                </div>
            </div>

            <div class="content-body">
                @if(session('success'))
                    <div style="background: #d1fae5; color: #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-container">
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
                                                <a href="{{ route('admin.agenda.edit', $a->id) }}" class="btn-icon btn-edit" title="Edit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
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
        </div>
    </main>
</div>
@endsection

