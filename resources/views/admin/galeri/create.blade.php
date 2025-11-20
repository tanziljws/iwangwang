@extends('layouts.app')

@section('title', 'Tambah Galeri - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/tambahgaleri.css') }}">
@endpush

@section('content')
<div class="tambah-page-wrapper">
    <div class="tambah-card">
        <h1 class="tambah-title">Tambah Galeri</h1>
        <p class="tambah-subtitle">Buat galeri baru untuk mengorganisir foto-foto</p>

        @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="tambah-form">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul Galeri</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-input" placeholder="Judul" value="{{ old('nama') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <div class="input-group">
                    <select name="kategori_id" class="form-input" required>
                        <option value="">Pilih kategori...</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <div class="input-group">
                    <textarea name="deskripsi" class="form-textarea" rows="4" placeholder="Deskripsi (opsional)">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Galeri</button>
            </div>
        </form>
    </div>
</div>
@endsection

