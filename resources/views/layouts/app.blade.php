<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMK NEGERI 4 KOTA BOGOR')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ secure_asset('css/Home.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/Navbar.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/TopBar.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="topbar">
        <div class="topbar-container">
            <div class="topbar-left">
                <span><i class="fas fa-phone"></i> (0251) 8321234</span>
                <span><i class="fas fa-envelope"></i> info@smkn4bogor.sch.id</span>
            </div>
            <div class="topbar-right">
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <div class="logo">
                <a href="{{ route('home') }}" class="logo-container">
                    <img src="{{ secure_asset('images/smkn4.jpg') }}" alt="Logo SMKN 4" class="navbar-logo">
                    <div>
                        <span>SMK</span> NEGERI 4 KOTA BOGOR
                    </div>
                </a>
            </div>
            
            <div class="nav-links" id="navLinks">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}">Galeri</a>
                <a href="{{ route('berita') }}" class="nav-link {{ request()->routeIs('berita*') ? 'active' : '' }}">Berita</a>
                <a href="{{ route('agenda') }}" class="nav-link {{ request()->routeIs('agenda*') ? 'active' : '' }}">Agenda</a>
                <a href="{{ route('kontak') }}" class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">Kontak</a>
                
                <div class="nav-auth-right" style="margin-left:auto; display:inline-flex; gap:12; align-items:center;">
                    @auth('web')
                        <a href="{{ route('user.account') }}" class="user-pill" title="{{ auth('web')->user()->name }}">
                            <span class="avatar-circle">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                                </svg>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('user.register') }}" class="auth-link">Daftar</a>
                        <a href="{{ route('user.login') }}" class="auth-link">Login</a>
                    @endauth
                </div>
            </div>

            <div class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <div class="footer-meta">
                    <p>2025 SMKN 4 Bogor. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Navbar scroll effect
        let lastScrollY = 0;
        const navbar = document.getElementById('navbar');
        
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            const isScrolled = currentScrollY > 10;
            
            if (isScrolled) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                navbar.classList.remove('scrolling-up');
                navbar.classList.add('scrolling-down');
            } else {
                navbar.classList.remove('scrolling-down');
                navbar.classList.add('scrolling-up');
            }
            
            if (currentScrollY < 10) {
                navbar.classList.remove('scrolling-up', 'scrolling-down');
            }
            
            lastScrollY = currentScrollY;
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            const hamburger = document.getElementById('hamburger');
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('open');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            const navLinks = document.getElementById('navLinks');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            if (!navLinks.contains(e.target) && !mobileBtn.contains(e.target)) {
                navLinks.classList.remove('active');
                document.getElementById('hamburger').classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

