@extends('layouts.app')

@section('title', 'Berita - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Berita.css') }}">
@endpush

@section('content')
<div class="berita-page">
    <section class="berita-hero">
        <div class="hero-decor">
            <span class="blob"></span>
            <span class="ring r1"></span>
            <span class="ring r2"></span>
        </div>
        <div class="header-wrap">
            <i class="fas fa-newspaper header-icon"></i>
            <div>
                <h1>Berita Sekolah</h1>
                <p>Update informasi terbaru seputar kegiatan, prestasi, dan pengumuman sekolah.</p>
            </div>
        </div>
    </section>

    <section class="berita-section">
        <div class="berita-grid">
            @forelse($berita as $news)
                <article class="berita-card">
                    <div class="berita-image">
                        <img src="{{ $news->cover_image_url ?? secure_asset('images/placeholder.jpg') }}" alt="{{ $news->title }}">
                        @if($news->category)
                            <div class="berita-category">{{ $news->category }}</div>
                        @endif
                    </div>
                    <div class="berita-content">
                        <div class="berita-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d M Y') : '-' }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-user"></i> 
                                {{ $news->author ?? 'Admin Sekolah' }}
                            </span>
                        </div>
                        <h3>{{ $news->title }}</h3>
                        <p>{{ Str::limit($news->excerpt ?? $news->content, 160) }}</p>
                        <a href="{{ route('berita.show', $news->id) }}" class="read-more">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="agenda-empty" style="grid-column: 1 / -1;">Belum ada berita.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection

