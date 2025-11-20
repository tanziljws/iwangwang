@extends('layouts.app')

@section('title', 'Beranda - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="home" style="width: 100%; margin: 0; padding: 0;">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content hero-centered">
                <div class="hero-background-shapes" aria-hidden="true">
                    <span class="decorative-circle circle-1"></span>
                    <span class="decorative-circle circle-2"></span>
                    <span class="decorative-circle circle-3"></span>
                    <span class="decorative-circle circle-4"></span>
                    <span class="decorative-circle circle-5"></span>
                    <span class="decorative-line line-1"></span>
                    <span class="decorative-line line-2"></span>
                    <span class="decorative-line line-3"></span>
                </div>
                <h1 class="hero-title hero-title-minimal">Website Gallery</h1>
                <p class="hero-subtitle">
                    Arsip visual kegiatan, prestasi, dan momen penting SMKN 4 Bogor yang dapat dinikmati kapan saja.
                </p>
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="quick-links-section">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">Quick Links</h2>
            </div>
            <div class="rujukan-grid">
                <a href="#tentang" class="rujukan-card" style="text-decoration: none;" onclick="document.querySelector('.about-school')?.scrollIntoView({ behavior: 'smooth' }); return false;">
                    <div class="rujukan-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Tentang</h3>
                </a>
                
                <a href="#keunggulan" class="rujukan-card" style="text-decoration: none;" onclick="document.querySelector('.features-section')?.scrollIntoView({ behavior: 'smooth' }); return false;">
                    <div class="rujukan-icon keunggulan-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Keunggulan</h3>
                </a>
                
                <a href="{{ route('agenda') }}" class="rujukan-card" style="text-decoration: none;">
                    <div class="rujukan-icon agenda-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Agenda</h3>
                </a>
                
                <a href="#berita" class="rujukan-card" style="text-decoration: none;" onclick="document.querySelector('.news-section')?.scrollIntoView({ behavior: 'smooth' }); return false;">
                    <div class="rujukan-icon berita-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Berita</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- About School Section -->
    <section class="about-school" id="tentang">
        <div class="container">
            <div class="section-title">
                <h2>Tentang</h2>
                <h3>SMKN 4 Kota Bogor</h3>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <p class="about-description">SMK Negeri 4 Kota Bogor adalah sekolah kejuruan terkemuka di Kota Bogor yang telah berdiri sejak 2009, berkomitmen menghasilkan lulusan kompeten dan siap kerja di dunia industri. Kami menyelenggarakan pendidikan berbasis kompetensi dengan fasilitas lengkap dan kurikulum yang selalu diperbarui sesuai kebutuhan industri terkini.</p>
                    
                    <div class="about-features">
                        <div class="feature">
                            <div class="feature-icon">🏫</div>
                            <h3>Fasilitas Lengkap</h3>
                            <p>Ruang kelas nyaman, laboratorium, dan fasilitas pendukung lainnya untuk menunjang proses belajar mengajar.</p>
                        </div>
                        
                        <div class="feature">
                            <div class="feature-icon">👨‍🏫</div>
                            <h3>Guru Berpengalaman</h3>
                            <p>Diajar oleh tenaga pendidik yang profesional dan berpengalaman di bidangnya masing-masing.</p>
                        </div>
                        
                        <div class="feature">
                            <div class="feature-icon">🏆</div>
                            <h3>Prestasi Siswa</h3>
                            <p>Banyak prestasi yang telah diraih oleh siswa-siswi kami di berbagai kompetisi.</p>
                        </div>

                        <div class="feature">
                            <div class="feature-icon">🌐</div>
                            <h3>Kurikulum Modern</h3>
                            <p>Kurikulum berbasis industri yang selalu diperbarui sesuai perkembangan teknologi terkini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Agenda Section -->
    <section class="agenda-section">
        <div class="container">
            <div class="section-title">
                <h2>Agenda Terdekat</h2>
                <p>Jadwal kegiatan sekolah yang akan datang</p>
            </div>
            <div class="agenda-grid" id="agendaGrid">
                @php
                    $agendas = \App\Models\Agenda::orderBy('date')->latest()->take(3)->get();
                @endphp
                @forelse($agendas as $agenda)
                    <div class="agenda-card">
                        <div class="agenda-date">
                            <span class="date-day">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                            <span class="date-month">{{ \Carbon\Carbon::parse($agenda->date)->format('M') }}</span>
                        </div>
                        <div class="agenda-content">
                            <h3>{{ $agenda->title }}</h3>
                            <p><i class="fas fa-clock"></i> {{ $agenda->time ?? '-' }}</p>
                            <p><i class="fas fa-map-marker-alt"></i> {{ $agenda->location ?? '-' }}</p>
                            <a href="{{ route('agenda') }}" class="btn btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #666;">Belum ada agenda.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section" id="berita">
        <div class="container">
            <div class="section-title">
                <h2>Berita Terbaru</h2>
                <p>Update informasi terbaru seputar kegiatan sekolah</p>
            </div>
            <div class="news-grid" id="newsGrid">
                @php
                    $berita = \App\Models\Berita::where('status', 'published')
                        ->orderByDesc('published_at')
                        ->orderByDesc('created_at')
                        ->take(3)
                        ->get();
                @endphp
                @forelse($berita as $item)
                    <article class="news-card">
                        <div class="news-image">
                            <img src="{{ $item->cover_image_url ?? secure_asset('images/placeholder.jpg') }}" alt="{{ $item->title }}">
                            @if($item->category)
                                <div class="news-category">{{ $item->category }}</div>
                            @endif
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <span><i class="fas fa-calendar-alt"></i> {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}</span>
                                <span><i class="fas fa-user"></i> {{ $item->author ?? 'Admin Sekolah' }}</span>
                            </div>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ Str::limit($item->excerpt ?? $item->content, 160) }}</p>
                            <a href="{{ route('berita.show', $item->id) }}" class="read-more">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #666;">Belum ada berita.</p>
                @endforelse
            </div>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="{{ route('berita') }}" class="btn btn-primary">Lihat Semua Berita</a>
            </div>
        </div>
    </section>
</div>
@endsection

