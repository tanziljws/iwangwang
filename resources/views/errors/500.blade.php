<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 - Internal Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .error-container {
            background: white;
            border-radius: 12px;
            padding: 48px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-code {
            font-size: 72px;
            font-weight: 700;
            color: #ef4444;
            margin-bottom: 16px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .error-message {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 32px;
        }
        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6b7280;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Internal Server Error</h1>
        <p class="error-message">
            {{ $message ?? 'Terjadi kesalahan pada server. Silakan coba lagi nanti atau hubungi administrator.' }}
        </p>
        <div class="error-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn">Kembali ke Dashboard</a>
            <a href="{{ route('home') }}" class="btn btn-secondary">Ke Beranda</a>
        </div>
    </div>
</body>
</html>

