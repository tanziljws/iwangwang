@extends('layouts.app')

@section('title', 'Tambah Berita - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/AddBerita.css') }}">
@endpush

@section('content')
<div class="add-berita-container">
    <div class="add-berita-card">
        <div class="add-berita-header">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="berita-breadcrumb">← Kembali ke Dashboard</a>
                <h1>Tambah Berita Sekolah</h1>
                <p>Isi detail berita untuk ditampilkan pada halaman publik.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="add-berita-form">
            @csrf
            @if($errors->any())
                <div class="alert error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <div class="form-group full">
                    <label>Judul Berita</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: SMKN 4 Raih Juara Nasional"
                        required
                    />
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input
                        type="text"
                        name="category"
                        value="{{ old('category') }}"
                        placeholder="Prestasi, Kerjasama, dsb"
                    />
                </div>

                <div class="form-group">
                    <label>Penulis</label>
                    <input
                        type="text"
                        name="author"
                        value="{{ old('author') }}"
                        placeholder="Admin Sekolah"
                    />
                </div>

                <div class="form-group">
                    <label>Tanggal Terbit</label>
                    <input
                        type="date"
                        name="published_at"
                        value="{{ old('published_at') }}"
                    />
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Ringkasan</label>
                    <textarea
                        name="excerpt"
                        rows="3"
                        placeholder="Deskripsi singkat berita..."
                    >{{ old('excerpt') }}</textarea>
                </div>

                <div class="form-group full">
                    <label>Isi Berita</label>
                    <textarea
                        name="content"
                        rows="8"
                        placeholder="Tulis isi berita lengkap di sini..."
                        required
                    >{{ old('content') }}</textarea>
                </div>

                <div class="form-group full">
                    <label>Cover Image (opsional)</label>
                    <div class="file-field">
                        <input
                            type="file"
                            name="cover_image"
                            accept="image/*"
                            id="berita-cover"
                            onchange="previewImage(this)"
                        />
                        <label for="berita-cover" class="file-label">
                            Unggah Gambar
                        </label>
                        <div id="previewContainer"></div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Berita</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const container = document.getElementById('previewContainer');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `<div class="preview"><img src="${e.target.result}" alt="Preview"></div>`;
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.innerHTML = '';
    }
}
</script>
@endpush
@endsection

