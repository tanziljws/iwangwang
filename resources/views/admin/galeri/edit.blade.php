@extends('layouts.admin')

@section('title', 'Edit Galeri - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/tambahgaleri.css') }}">
@endpush

@section('content')
<div class="tambah-page-wrapper">
    <div class="tambah-card">
        <h1 class="tambah-title">Edit Galeri</h1>
        <p class="tambah-subtitle">Ubah informasi galeri</p>

        @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.galeri.update', $galeri->id) }}" enctype="multipart/form-data" class="tambah-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul Galeri</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-input" placeholder="Judul" value="{{ old('nama', $galeri->nama) }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <div class="input-group">
                    <select name="kategori_id" class="form-input" required>
                        <option value="">Pilih kategori...</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $galeri->kategori_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <div class="input-group">
                    <textarea name="deskripsi" class="form-textarea" rows="4" placeholder="Deskripsi (opsional)">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <div class="input-group">
                    <input type="number" name="urutan" class="form-input" placeholder="0" value="{{ old('urutan', $galeri->urutan) }}" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="status" value="1" {{ old('status', $galeri->status) ? 'checked' : '' }}>
                    Aktif
                </label>
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

