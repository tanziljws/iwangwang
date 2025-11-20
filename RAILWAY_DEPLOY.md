# Railway Deployment Instructions

## Setelah Deploy atau Update Routes/Middleware

Jika terjadi error "Route not defined" atau masalah routing setelah deploy, jalankan perintah berikut di Railway:

### Via Railway CLI:
```bash
railway run php artisan route:clear
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
railway run php artisan optimize:clear
```

### Via Railway Dashboard:
1. Buka Railway Dashboard
2. Pilih project Anda
3. Buka tab "Deployments"
4. Klik pada deployment terbaru
5. Buka "Shell" atau "Logs"
6. Jalankan perintah clear cache:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### Atau gunakan script yang sudah disediakan:
```bash
railway run ./clear-cache.sh
```

## Troubleshooting

### Error: "Route [login] not defined"
- Pastikan route `admin.login` dan `user.login` sudah didefinisikan
- Clear semua cache (route, config, application, view)
- Pastikan middleware `Authenticate` tidak memanggil `route('login')`

### Error: "Route [admin.login] not defined"
- Pastikan route sudah didefinisikan di `routes/web.php`:
  ```php
  Route::prefix('admin')->name('admin.')->group(function () {
      Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
  });
  ```

### Error: Middleware tidak bekerja
- Clear route cache: `php artisan route:clear`
- Pastikan middleware sudah terdaftar di `app/Http/Kernel.php` atau `bootstrap/app.php`

