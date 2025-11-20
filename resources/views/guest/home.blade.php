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

    <!-- Departments Logos Row -->
    <section class="departments-bar">
        <div class="container">
            <div class="departments-row" aria-label="Daftar Jurusan">
                <div class="dept-card">
                    <img src="{{ secure_asset('images/pplg.png') }}" alt="Logo PPLG">
                    <div class="dept-name">PPLG</div>
                </div>
                <div class="dept-card">
                    <img src="{{ secure_asset('images/tjkt.png') }}" alt="Logo TJKT">
                    <div class="dept-name">TJKT</div>
                </div>
                <div class="dept-card">
                    <img src="{{ secure_asset('images/tkro.png') }}" alt="Logo TKRO">
                    <div class="dept-name">TKRO</div>
                </div>
                <div class="dept-card">
                    <img src="{{ secure_asset('images/tpfl.png') }}" alt="Logo TPFL">
                    <div class="dept-name">TPFL</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="keunggulan">
        <div class="container">
            <div class="section-header">
                <h2>Keunggulan Kami</h2>
                <p>Beberapa keunggulan yang membuat kami berbeda dari yang lain</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🏫</div>
                    <h3>Lingkungan Belajar Nyaman</h3>
                    <p>Ruang kelas yang nyaman dan fasilitas lengkap untuk mendukung proses belajar mengajar yang optimal.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">👨‍🏫</div>
                    <h3>Pengajar Berkualitas</h3>
                    <p>Diajar oleh tenaga pendidik yang profesional dan berpengalaman di bidangnya masing-masing.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">💻</div>
                    <h3>Teknologi Terkini</h3>
                    <p>Kurikulum berbasis industri dengan peralatan dan teknologi terbaru untuk mempersiapkan masa depan.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🤝</div>
                    <h3>Kemitraan Industri</h3>
                    <p>Kerjasama dengan berbagai perusahaan ternama untuk peluang magang dan kerja bagi siswa.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🌍</div>
                    <h3>Berwawasan Global</h3>
                    <p>Pembelajaran yang mengikuti perkembangan global dengan standar internasional.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <h3>Prestasi Membanggakan</h3>
                    <p>Banyak prestasi yang telah diraih baik di tingkat regional, nasional, maupun internasional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Agenda Section -->
    <section class="home-agenda-section">
        <div class="container">
            <div class="section-header">
                <h2>Agenda Terdekat</h2>
                <a href="{{ route('agenda') }}" class="view-all">
                    Lihat Semua <i class="fas fa-arrow-right arrow-icon"></i>
                </a>
            </div>
            
            <div class="agenda-grid">
                @php
                    $agendas = \App\Models\Agenda::orderBy('date')->latest()->take(3)->get();
                @endphp
                @forelse($agendas as $event)
                    @php
                        $dateObj = \Carbon\Carbon::parse($event->date);
                        $dayLabel = $dateObj->format('d');
                        $monthLabel = $dateObj->format('M Y');
                    @endphp
                    <div class="agenda-card agenda-card-flat">
                        <div class="agenda-date agenda-date-pill">
                            <span class="day">{{ $dayLabel }}</span>
                            <span class="month">{{ $monthLabel }}</span>
                        </div>
                        <div class="agenda-content">
                            <h3>{{ $event->title }}</h3>
                            <div class="agenda-meta">
                                <span class="agenda-time">
                                    <i class="fas fa-clock"></i> {{ $event->time ?? 'Waktu menyusul' }}
                                </span>
                                <span class="agenda-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $event->location ?? 'Lokasi menyusul' }}
                                </span>
                            </div>
                            <p class="agenda-description-home">{{ $event->description ?? '-' }}</p>
                            <div class="agenda-footer-meta">
                                <i class="fas fa-calendar-alt"></i> {{ $dateObj->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="agenda-empty" style="grid-column: 1 / -1;">Belum ada agenda yang dijadwalkan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section" id="berita">
        <div class="container">
            <div class="section-header">
                <div class="section-header-content">
                    <i class="fas fa-newspaper section-icon"></i>
                    <div>
                        <h2>Berita Terkini</h2>
                        <p class="section-subtitle">Informasi dan update terbaru seputar sekolah</p>
                    </div>
                </div>
                <a href="{{ route('berita') }}" class="view-all">
                    Lihat Semua <i class="fas fa-arrow-right arrow-icon"></i>
                </a>
            </div>
            
            <div class="news-grid">
                @php
                    $berita = \App\Models\Berita::where('status', 'published')
                        ->orderByDesc('published_at')
                        ->orderByDesc('created_at')
                        ->take(3)
                        ->get();
                @endphp
                @forelse($berita as $news)
                    <article class="news-card">
                        <div class="news-image">
                            <img src="{{ $news->cover_image_url ?? secure_asset('images/placeholder.jpg') }}" alt="{{ $news->title }}">
                            @if($news->category)
                                <div class="news-category">{{ $news->category }}</div>
                            @endif
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="fas fa-calendar-alt"></i> {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d F Y') : '-' }}
                                </span>
                                <span class="news-author">
                                    <i class="fas fa-user"></i> {{ $news->author ?? 'Admin' }}
                                </span>
                            </div>
                            <h3>{{ $news->title }}</h3>
                            <p>{{ Str::limit($news->excerpt ?? $news->content, 140) }}</p>
                            <a href="{{ route('berita.show', $news->id) }}" class="read-more">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="agenda-empty" style="grid-column: 1 / -1;">Belum ada berita.</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
