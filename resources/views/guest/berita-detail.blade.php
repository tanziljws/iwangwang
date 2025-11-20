@extends('layouts.app')

@section('title', $berita->title . ' - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Berita.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="berita-page">
    <section class="berita-hero">
        <div class="header-wrap">
            <h1 style="margin-top: .35rem;">{{ $berita->title }}</h1>
        </div>
    </section>

    <section class="berita-section" style="max-width: 900px;">
        <article class="berita-card" style="overflow: visible;">
            <div class="berita-image" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <img src="{{ $berita->cover_image_url ?? secure_asset('images/placeholder.jpg') }}" alt="{{ $berita->title }}">
                @if($berita->category)
                    <div class="berita-category" style="display: inline-flex; align-items: center; gap: .35rem;">
                        <i class="fas fa-tag"></i> {{ $berita->category }}
                    </div>
                @endif
            </div>
            <div class="berita-content" style="gap: 1rem;">
                <div class="berita-meta" style="gap: 1rem; flex-wrap: wrap;">
                    <span class="meta-item">
                        <i class="fas fa-calendar-alt"></i> {{ $berita->published_at ? \Carbon\Carbon::parse($berita->published_at)->format('d F Y') : '-' }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-user"></i> {{ $berita->author ?? 'Admin Sekolah' }}
                    </span>
                </div>
                @if($berita->excerpt)
                    <blockquote style="margin: 0; padding: 1rem 1.25rem; background: #f8fafc; border-radius: 12px; color: #475569;">
                        {{ $berita->excerpt }}
                    </blockquote>
                @endif
                <div class="berita-body" style="color: #1f2a44; line-height: 1.8;">
                    @foreach(explode("\n", $berita->content) as $paragraph)
                        @if(trim($paragraph))
                            <p style="margin-bottom: 1rem;">{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('berita') }}" class="read-more" style="font-weight: 600; display: inline-flex; align-items: center; gap: .35rem;">
                        <i class="fas fa-arrow-left"></i> Kembali ke berita
                    </a>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection
