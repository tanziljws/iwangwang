@extends('layouts.app')

@section('title', 'Kontak - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/Contact.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
@endpush

@section('content')
<div class="contact-page">
    <div class="contact-container">
        <div class="contact-header">
            <h1>Kontak Kami</h1>
            <p>Hubungi SMKN 4 Bogor untuk informasi lebih lanjut</p>
        </div>

        <div class="contact-content">
            <div class="contact-info">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-text">
                        <h3>Alamat</h3>
                        <p>Jl. Raya Tajur No. 4, Muarasari, Bogor Selatan, Kota Bogor, Jawa Barat 16137</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-text">
                        <h3>Telepon</h3>
                        <p>+62 251 835-2104</p>
                        <p>+62 813-8884-7400 (WA)</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-text">
                        <h3>Email</h3>
                        <p>info@smkn4bogor.sch.id</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-text">
                        <h3>Jam Operasional</h3>
                        <p>Senin - Jumat: 07:00 - 15:30 WIB</p>
                        <p>Sabtu: 08:00 - 12:00 WIB</p>
                        <p>Minggu & Tanggal Merah: Tutup</p>
                    </div>
                </div>
            </div>

            <div class="contact-map">
                <iframe
                    src="https://maps.google.com/maps?q=SMK%20Negeri%204%20Bogor&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="100%"
                    style="border: 0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi SMK Negeri 4 Bogor"
                ></iframe>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <div class="footer-meta"><p>2025 SMKN 4 Bogor. All rights reserved.</p></div>
            </div>
        </div>
    </footer>
</div>
@endsection
