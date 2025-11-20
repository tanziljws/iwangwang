@extends('layouts.app')

@section('title', 'Agenda - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Agenda.css') }}">
<link rel="stylesheet" href="{{ asset('css/Home.css') }}">
@endpush

@section('content')
<div class="agenda-page">
    <section class="agenda-hero">
        <div class="hero-decor">
            <span class="blob"></span>
            <span class="ring r1"></span>
            <span class="ring r2"></span>
        </div>
        <div class="header-wrap">
            <i class="fas fa-calendar-alt header-icon"></i>
            <div>
                <h1>Agenda Sekolah</h1>
                <p>Jadwal kegiatan dan acara sekolah yang akan datang</p>
            </div>
        </div>
    </section>

    <section class="agenda-section">
        <div class="agenda-grid">
            @forelse($agendas as $agenda)
                <div class="agenda-card">
                    <div class="agenda-date">
                        <span class="date-day">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                        <span class="date-month">{{ \Carbon\Carbon::parse($agenda->date)->format('M') }}</span>
                    </div>
                    <div class="agenda-content">
                        <h3>{{ $agenda->title }}</h3>
                        @if($agenda->description)
                            <p>{{ Str::limit($agenda->description, 100) }}</p>
                        @endif
                        <div class="agenda-meta">
                            <p><i class="fas fa-clock"></i> {{ $agenda->time ?? '-' }}</p>
                            <p><i class="fas fa-map-marker-alt"></i> {{ $agenda->location ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="agenda-empty" style="grid-column: 1 / -1;">Belum ada agenda.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection

