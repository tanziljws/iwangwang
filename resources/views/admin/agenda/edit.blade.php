@extends('layouts.admin')

@section('title', 'Edit Agenda - Admin')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Dashboard.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/AddAgenda.css') }}">
@endpush

@section('content')
<div class="add-agenda-container">
    <div class="add-agenda-card">
        <div class="add-agenda-header">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb">← Kembali ke Dashboard</a>
                <h1>Edit Agenda Sekolah</h1>
                <p>Ubah detail agenda untuk ditampilkan pada halaman publik.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.agenda.update', $agenda->id) }}" class="add-agenda-form">
            @csrf
            @method('PUT')
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
                    <label>Judul Agenda</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $agenda->title) }}"
                        placeholder="Contoh: Rapat Orang Tua Siswa"
                        required
                    />
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input
                        type="date"
                        name="date"
                        value="{{ old('date', $agenda->date) }}"
                        required
                    />
                </div>

                <div class="form-group">
                    <label>Waktu (opsional)</label>
                    <input
                        type="time"
                        name="time"
                        value="{{ old('time', $agenda->time) }}"
                    />
                </div>

                <div class="form-group full">
                    <label>Lokasi</label>
                    <input
                        type="text"
                        name="location"
                        value="{{ old('location', $agenda->location) }}"
                        placeholder="Contoh: Aula SMKN 4"
                    />
                </div>

                <div class="form-group full">
                    <label>Deskripsi Agenda</label>
                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Tuliskan detail agenda..."
                    >{{ old('description', $agenda->description) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

