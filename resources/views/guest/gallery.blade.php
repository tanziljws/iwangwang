@extends('layouts.app')

@section('title', 'Galeri - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Gallery.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="gallery-page">
    <section class="gallery-hero">
        <div class="hero-decor">
            <span class="blob"></span>
            <span class="ring r1"></span>
            <span class="ring r2"></span>
        </div>
        <h1>Galeri Sekolah</h1>
        <p>Rangkaian momen terbaik, fasilitas unggulan, dan aktivitas siswa yang menginspirasi.</p>
    </section>

    <section class="gallery-section">
        <div class="gallery-container">
            @php
                $allPhotos = [];
                foreach($galeris as $galeri) {
                    foreach($galeri->foto as $foto) {
                        $allPhotos[] = [
                            'id' => $foto->id,
                            'title' => $foto->judul ?? $galeri->nama,
                            'category' => $galeri->kategori->nama ?? 'Lainnya',
                            'src' => $foto->file_url,
                            'galeri' => $galeri->nama
                        ];
                    }
                }
                $categories = collect($allPhotos)->pluck('category')->unique()->prepend('Semua');
            @endphp
            
            @if(count($allPhotos) > 0)
                <div class="gallery-filters">
                    @foreach($categories as $cat)
                        <button class="filter-btn {{ $loop->first ? 'active' : '' }}" data-category="{{ $cat }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
                
                <div class="gallery-grid" id="galleryGrid">
                    @foreach($allPhotos as $photo)
                        <div class="gallery-item" data-category="{{ $photo['category'] }}">
                            <img src="{{ $photo['src'] }}" alt="{{ $photo['title'] }}" loading="lazy">
                            <div class="gallery-overlay">
                                <h4>{{ $photo['title'] }}</h4>
                                <span>{{ $photo['category'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: #666; grid-column: 1 / -1;">Belum ada foto di galeri.</p>
            @endif
        </div>
    </section>
    
    @push('scripts')
    <script>
        // Gallery filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                document.querySelectorAll('.gallery-item').forEach(item => {
                    if (category === 'Semua' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endpush
</div>
@endsection

