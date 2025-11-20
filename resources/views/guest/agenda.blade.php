@extends('layouts.app')

@section('title', 'Agenda - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Agenda.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="agenda-page">
    <!-- Hero Section -->
    <section class="agenda-hero">
        <div class="container">
            <h1>Agenda Sekolah</h1>
            <p>Jadwal kegiatan dan acara SMKN 4 Kota Bogor</p>
        </div>
    </section>

    <!-- Agenda Grid -->
    <section class="agenda-grid-section">
        <div class="container">
            @if(count($agendas) === 0)
                <div class="agenda-empty">Belum ada agenda yang dijadwalkan.</div>
            @endif
            <div class="agenda-grid">
                @foreach($agendas as $item)
                    @php
                        $dateObj = \Carbon\Carbon::parse($item->date);
                        $dayLabel = $dateObj->format('d');
                        $monthLabel = $dateObj->format('M Y');
                        $fullDate = $dateObj->format('d F Y');
                    @endphp
                    <div class="agenda-card agenda-card-plain">
                        <div class="agenda-date-badge">
                            <span class="day">{{ $dayLabel }}</span>
                            <span class="month">{{ $monthLabel }}</span>
                        </div>
                        <div class="agenda-content">
                            <h3>{{ $item->title }}</h3>
                            <div class="agenda-meta">
                                <span class="location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $item->location ?? 'Lokasi menyusul' }}
                                </span>
                                <span class="date">
                                    <i class="far fa-calendar-alt"></i> {{ $fullDate }}
                                    @if($item->time)
                                        &bull; {{ $item->time }}
                                    @endif
                                </span>
                            </div>
                            <p>{{ $item->description ?? 'Belum ada deskripsi tambahan.' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
