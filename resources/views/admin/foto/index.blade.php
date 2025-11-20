<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Foto - Admin</title>
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
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .photo-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .photo-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .photo-card-body {
            padding: 16px;
        }
        .photo-card-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        .photo-card-meta {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .photo-card-actions {
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
            <h1>Kelola Foto</h1>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.foto.create') }}" class="btn">+ Tambah Foto</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($fotos->count() > 0)
        <div class="photo-grid">
            @foreach($fotos as $foto)
            <div class="photo-card">
                @if($foto->file_url)
                <img src="{{ $foto->file_url }}" alt="{{ $foto->judul }}">
                @else
                <div style="width: 100%; height: 200px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                    No Image
                </div>
                @endif
                <div class="photo-card-body">
                    <div class="photo-card-title">{{ $foto->judul ?? 'Foto ' . $foto->id }}</div>
                    <div class="photo-card-meta">
                        @if($foto->galeri)
                        Galeri: {{ $foto->galeri->nama ?? 'N/A' }}
                        @if($foto->galeri->kategori)
                        • {{ $foto->galeri->kategori->nama }}
                        @endif
                        @endif
                    </div>
                    <div class="photo-card-actions">
                        <a href="{{ route('admin.foto.edit', $foto) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; flex: 1;">Edit</a>
                        <form method="POST" action="{{ route('admin.foto.destroy', $foto) }}" style="display: inline; flex: 1;" onsubmit="return confirm('Yakin hapus foto ini?');">
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
            <p>Belum ada foto. <a href="{{ route('admin.foto.create') }}" style="color: #667eea;">Tambah foto baru</a></p>
        </div>
        @endif
    </div>
</body>
</html>

