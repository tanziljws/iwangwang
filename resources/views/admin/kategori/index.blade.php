<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
        }
        .container {
            max-width: 1200px;
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
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
            text-transform: uppercase;
        }
        td {
            color: #111827;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 48px;
            color: #9ca3af;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kelola Kategori</h1>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.kategori.create') }}" class="btn">+ Tambah Kategori</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="table-container">
            @if($kategoris->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Icon</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                    <tr>
                        <td><strong>{{ $kategori->nama }}</strong></td>
                        <td>{{ $kategori->deskripsi ?? '-' }}</td>
                        <td>{{ $kategori->icon ?? '-' }}</td>
                        <td>{{ $kategori->urutan ?? 0 }}</td>
                        <td>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $kategori->status == 1 ? '#d1fae5' : '#fee2e2' }}; color: {{ $kategori->status == 1 ? '#059669' : '#ef4444' }};">
                                {{ $kategori->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="actions">
                                <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Edit</a>
                                <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" style="display: inline;" onsubmit="return confirm('Yakin hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <p>Belum ada kategori. <a href="{{ route('admin.kategori.create') }}" style="color: #667eea;">Tambah kategori baru</a></p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>

