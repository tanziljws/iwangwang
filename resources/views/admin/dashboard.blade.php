<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK NEGERI 4 KOTA BOGOR</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
            line-height: 1.6;
        }
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }
        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .dashboard-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .dashboard-header p {
            color: #6b7280;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        .stat-card .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #111827;
        }
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .content-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        .photo-item {
            border-radius: 8px;
            overflow: hidden;
            background: #f9fafb;
        }
        .photo-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .photo-item .photo-title {
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
        .gallery-list {
            display: grid;
            gap: 12px;
        }
        .gallery-item {
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .gallery-item h3 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        .gallery-item p {
            font-size: 14px;
            color: #6b7280;
        }
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #9ca3af;
        }
        .empty-state p {
            margin-top: 8px;
        }
        .nav-links {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .nav-link {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .nav-link:hover {
            background: #5568d3;
        }
        .nav-link.secondary {
            background: #6b7280;
        }
        .nav-link.secondary:hover {
            background: #4b5563;
        }
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, {{ $petugas->nama_petugas }}! 👋</p>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #059669;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fee2e2; color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #ef4444;">
                {{ session('error') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Foto</h3>
                <div class="stat-value">{{ number_format($totalFotos) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Kategori</h3>
                <div class="stat-value">{{ number_format($totalKategoris) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Galeri</h3>
                <div class="stat-value">{{ number_format($totalGaleris) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Petugas Aktif</h3>
                <div class="stat-value">{{ number_format($totalPetugas) }}</div>
            </div>
        </div>

        @if($recentFotos->count() > 0)
        <div class="content-section">
            <h2>Foto Terbaru</h2>
            <div class="photo-grid">
                @foreach($recentFotos as $foto)
                <div class="photo-item">
                    @if($foto->file_url)
                    <img src="{{ $foto->file_url }}" alt="{{ $foto->judul }}">
                    @else
                    <div style="width: 100%; height: 150px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                        No Image
                    </div>
                    @endif
                    <div class="photo-title">{{ $foto->judul ?? 'Foto ' . $foto->id }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($recentGaleris->count() > 0)
        <div class="content-section">
            <h2>Galeri Terbaru</h2>
            <div class="gallery-list">
                @foreach($recentGaleris as $galeri)
                <div class="gallery-item">
                    <h3>{{ $galeri->nama ?? 'Galeri ' . $galeri->id }}</h3>
                    <p>{{ $galeri->deskripsi ?? '-' }}</p>
                    @if($galeri->kategori)
                    <p style="margin-top: 8px; font-size: 12px; color: #667eea;">
                        Kategori: {{ $galeri->kategori->nama }}
                    </p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="nav-links">
            <a href="{{ route('admin.galeri.index') }}" class="nav-link">Kelola Galeri</a>
            <a href="{{ route('admin.foto.index') }}" class="nav-link">Kelola Foto</a>
            <a href="{{ route('admin.kategori.index') }}" class="nav-link">Kelola Kategori</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link secondary" style="border: none; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>

