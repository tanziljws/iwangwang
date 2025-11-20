<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }
        .header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        .btn:hover { background: #5568d3; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .gallery-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .gallery-card-header {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .gallery-card-title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 8px;
            color: #111827;
        }
        .gallery-card-meta {
            font-size: 12px;
            color: #6b7280;
        }
        .gallery-card-body {
            padding: 16px;
        }
        .gallery-card-description {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .gallery-card-stats {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 12px;
        }
        .gallery-card-actions {
            display: flex;
            gap: 8px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .alert-success {
            background: #d1fae5;
            color: #059669;
            border-left: 4px solid #059669;
        }
        .alert-error {
            background: #fee2e2;
            color: #ef4444;
            border-left: 4px solid #ef4444;
        }
        .empty-state {
            text-align: center;
            padding: 48px;
            background: white;
            border-radius: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kelola Galeri</h1>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.galeri.create') }}" class="btn">+ Tambah Galeri</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($galeris->count() > 0)
        <div class="gallery-grid">
            @foreach($galeris as $galeri)
            <div class="gallery-card">
                <div class="gallery-card-header">
                    <div class="gallery-card-title">{{ $galeri->nama ?? 'Galeri ' . $galeri->id }}</div>
                    <div class="gallery-card-meta">
                        @if($galeri->kategori)
                        Kategori: {{ $galeri->kategori->nama }}
                        @endif
                        @if($galeri->status == 1)
                        <span style="margin-left: 8px; padding: 2px 6px; background: #d1fae5; color: #059669; border-radius: 4px; font-size: 11px;">Aktif</span>
                        @else
                        <span style="margin-left: 8px; padding: 2px 6px; background: #fee2e2; color: #ef4444; border-radius: 4px; font-size: 11px;">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="gallery-card-body">
                    <div class="gallery-card-description">
                        {{ $galeri->deskripsi ?? 'Tidak ada deskripsi' }}
                    </div>
                    <div class="gallery-card-stats">
                        Total Foto: {{ $galeri->foto->count() ?? 0 }}
                    </div>
                    <div class="gallery-card-actions">
                        <a href="{{ route('admin.galeri.edit', $galeri) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; flex: 1;">Edit</a>
                        <form method="POST" action="{{ route('admin.galeri.destroy', $galeri) }}" style="display: inline; flex: 1;" onsubmit="return confirm('Yakin hapus galeri ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; width: 100%;">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>Belum ada galeri. <a href="{{ route('admin.galeri.create') }}" style="color: #667eea;">Tambah galeri baru</a></p>
        </div>
        @endif
    </div>
</body>
</html>

