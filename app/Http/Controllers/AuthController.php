<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::guard('petugas')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Show the admin registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        if (Auth::guard('petugas')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_petugas' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:petugas,username',
            'email' => 'required|string|email|max:255|unique:petugas,email',
            'no_hp' => 'nullable|string|max:15',
            'jabatan' => 'required|string|in:admin,petugas,editor',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nama_petugas.required' => 'Nama lengkap harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'jabatan.required' => 'Jabatan harus dipilih',
            'jabatan.in' => 'Jabatan tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $petugas = Petugas::create([
                'nama_petugas' => $request->nama_petugas,
                'username' => $request->username,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'password' => Hash::make($request->password),
                'status' => 'aktif',
            ]);

            return redirect()->route('admin.login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun yang baru dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Debug: Log credentials (remove in production)
        \Log::info('Admin login attempt', [
            'username' => $credentials['username'],
            'has_password' => !empty($credentials['password'])
        ]);

        // Try to find user first
        $petugas = \App\Models\Petugas::where('username', $credentials['username'])->first();
        
        if (!$petugas) {
            \Log::warning('Admin login failed: User not found', ['username' => $credentials['username']]);
            return back()
                ->withInput($request->only('username', 'remember'))
                ->with('error', 'Username atau password salah!');
        }

        // Check password manually first
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $petugas->password)) {
            \Log::warning('Admin login failed: Password mismatch', ['username' => $credentials['username']]);
            return back()
                ->withInput($request->only('username', 'remember'))
                ->with('error', 'Username atau password salah!');
        }

        // Check if user is active
        if ($petugas->status !== 'aktif') {
            \Log::warning('Admin login failed: Account inactive', ['username' => $credentials['username']]);
            return back()
                ->withInput($request->only('username', 'remember'))
                ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // If all checks pass, login the user
        Auth::guard('petugas')->login($petugas, $request->filled('remember'));
        $request->session()->regenerate();
        
        // Store user data in session
        $request->session()->put([
            'admin_id' => $petugas->id,
            'admin_name' => $petugas->nama_petugas,
            'admin_role' => $petugas->jabatan,
        ]);

        \Log::info('Admin login successful', ['username' => $credentials['username'], 'id' => $petugas->id]);

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang, ' . $petugas->nama_petugas . '!');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Get the current user before logging out
        $user = Auth::guard('petugas')->user();
        
        // Logout the user
        Auth::guard('petugas')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear all session data
        $request->session()->flush();
        
        // Clear specific session keys
        $request->session()->forget([
            'admin_id', 
            'admin_name', 
            'admin_role',
            'password_hash_' . 'petugas',
            'password_hash_sanctum',
        ]);
        
        // Clear all cookies
        $cookies = $request->cookies->all();
        foreach ($cookies as $name => $value) {
            if (strpos($name, 'XSRF-TOKEN') !== false || strpos($name, 'laravel_session') !== false) {
                $request->cookies->remove($name);
            }
        }
        
        // Clear the remember token if exists
        if ($user && $request->hasCookie(Auth::guard('petugas')->getRecallerName())) {
            $recaller = $request->cookies->get(Auth::guard('petugas')->getRecallerName());
            $user->setRememberToken(null);
            $user->save();
            
            // Delete the remember me cookie
            $cookie = Cookie::forget(Auth::guard('petugas')->getRecallerName());
            
            return redirect()
                ->route('admin.login')
                ->withCookie($cookie)
                ->with('success', 'Anda berhasil logout!');
        }
        
        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout!');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        try {
            // Check if user is authenticated with petugas guard
            if (!Auth::guard('petugas')->check()) {
                return redirect()->route('admin.login')
                    ->with('error', 'Anda harus login terlebih dahulu.');
            }

            $petugas = Auth::guard('petugas')->user();
            
            // Check if user is active
            if ($petugas->status !== 'aktif') {
                Auth::guard('petugas')->logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
            }
            
            // Get statistics
            $totalFotos = \App\Models\Foto::count();
            $totalKategoris = \App\Models\Kategori::count();
            $totalGaleris = \App\Models\Galeri::count();
            $totalPetugas = \App\Models\Petugas::where('status', 'aktif')->count();
            
            // Get recent photos with error handling
            $recentFotos = collect();
            try {
                $recentFotos = \App\Models\Foto::with(['galeri.kategori'])
                    ->latest()
                    ->take(6)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching recent photos: ' . $e->getMessage());
            }
            
            // Get recent galleries with error handling
            $recentGaleris = collect();
            try {
                $recentGaleris = \App\Models\Galeri::with('kategori')
                    ->latest()
                    ->take(4)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching recent galleries: ' . $e->getMessage());
            }
            
            return view('admin.dashboard', [
                'petugas' => $petugas,
                'totalFotos' => $totalFotos,
                'totalKategoris' => $totalKategoris,
                'totalGaleris' => $totalGaleris,
                'totalPetugas' => $totalPetugas,
                'recentFotos' => $recentFotos,
                'recentGaleris' => $recentGaleris
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // If user is logged in, show error page
            if (Auth::guard('petugas')->check()) {
                return view('errors.500', [
                    'message' => 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi nanti.',
                ]);
            }
            
            // If not logged in, redirect to login
            return redirect()->route('admin.login')
                ->with('error', 'Terjadi kesalahan. Silakan login kembali.');
        }
    }
}
