@extends('layouts.app')

@section('title', $berita->title . ' - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Berita.css') }}">
@endpush

@section('content')
<div class="berita-detail-page">
    <article class="berita-detail">
        <div class="berita-detail-header">
            <a href="{{ route('berita') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Berita
            </a>
            <div class="berita-detail-meta">
                <span><i class="fas fa-calendar-alt"></i> {{ $berita->published_at ? \Carbon\Carbon::parse($berita->published_at)->format('d M Y') : '-' }}</span>
                <span><i class="fas fa-user"></i> {{ $berita->author ?? 'Admin Sekolah' }}</span>
                @if($berita->category)
                    <span><i class="fas fa-tag"></i> {{ $berita->category }}</span>
                @endif
            </div>
        </div>
        
        <h1 class="berita-detail-title">{{ $berita->title }}</h1>
        
        @if($berita->cover_image_url)
            <div class="berita-detail-image">
                <img src="{{ $berita->cover_image_url }}" alt="{{ $berita->title }}">
            </div>
        @endif
        
        <div class="berita-detail-content">
            {!! nl2br(e($berita->content)) !!}
        </div>
    </article>
</div>
@endsection

