<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Selamat Datang</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f8f9fa;
            }
            .welcome-container {
                text-align: center;
                padding: 2rem;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #2d3748;
                margin-bottom: 1rem;
            }
            p {
                color: #4a5568;
                margin-bottom: 2rem;
            }
        </style>
    </head>
    <body>
        <div class="welcome-container">
            <h1>Selamat Datang</h1>
            <p>Ini adalah halaman utama aplikasi.</p>
        </div>
    </body>
</html>
