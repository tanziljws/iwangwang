<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Auth::guard('petugas')->user();
        $petugasList = Petugas::orderBy('created_at', 'desc')->get();
        
        return view('admin.petugas.index', compact('petugas', 'petugasList'));
    }

    public function create()
    {
        $petugas = Auth::guard('petugas')->user();
        return view('admin.petugas.create', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username',
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
            Petugas::create([
                'nama_petugas' => $request->nama_petugas,
                'username' => $request->username,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'password' => Hash::make($request->password),
                'status' => 'aktif',
            ]);

            return redirect()->route('admin.petugas.index')
                ->with('success', 'Petugas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan saat menambah petugas. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $petugas = Auth::guard('petugas')->user();
        $petugasItem = Petugas::findOrFail($id);
        
        return view('admin.petugas.edit', compact('petugas', 'petugasItem'));
    }

    public function update(Request $request, $id)
    {
        $petugasItem = Petugas::findOrFail($id);
        
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username,' . $id,
            'email' => 'required|string|email|max:255|unique:petugas,email,' . $id,
            'no_hp' => 'nullable|string|max:15',
            'jabatan' => 'required|string|in:admin,petugas,editor',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nama_petugas.required' => 'Nama lengkap harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'jabatan.required' => 'Jabatan harus dipilih',
            'jabatan.in' => 'Jabatan tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $data = [
                'nama_petugas' => $request->nama_petugas,
                'username' => $request->username,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $petugasItem->update($data);

            return redirect()->route('admin.petugas.index')
                ->with('success', 'Data petugas berhasil diupdate!');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan saat mengupdate petugas. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $petugasItem = Petugas::findOrFail($id);
            
            // Jangan hapus diri sendiri
            if ($petugasItem->id === Auth::guard('petugas')->id()) {
                return redirect()->route('admin.petugas.index')
                    ->with('error', 'Tidak dapat menghapus akun sendiri!');
            }
            
            $petugasItem->delete();
            
            return redirect()->route('admin.petugas.index')
                ->with('success', 'Petugas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.petugas.index')
                ->with('error', 'Terjadi kesalahan saat menghapus petugas. Silakan coba lagi.');
        }
    }
}
