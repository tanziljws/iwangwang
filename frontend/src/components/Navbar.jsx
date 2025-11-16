import { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';
import '../styles/Navbar.css';

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const location = useLocation();
  const [scrolled, setScrolled] = useState(false);
  const [lastScrollY, setLastScrollY] = useState(0);
  const navbarRef = useRef(null);
  const menuRef = useRef(null);
  const [user, setUser] = useState(null);
  const [loggedIn, setLoggedIn] = useState(false);

  // Close menu when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Handle scroll effect
  useEffect(() => {
    const navbar = navbarRef.current;
    let ticking = false;

    const handleScroll = () => {
      const currentScrollY = window.scrollY;
      const isScrolled = currentScrollY > 10;
      
      if (isScrolled !== scrolled) {
        setScrolled(isScrolled);
      }

      if (!ticking) {
        window.requestAnimationFrame(() => {
          if (currentScrollY > lastScrollY && currentScrollY > 100) {
            // Scrolling down
            navbar.classList.remove('scrolling-up');
            navbar.classList.add('scrolling-down');
          } else {
            // Scrolling up
            navbar.classList.remove('scrolling-down');
            navbar.classList.add('scrolling-up');
          }
          
          if (currentScrollY < 10) {
            navbar.classList.remove('scrolling-up', 'scrolling-down');
          }
          
          setLastScrollY(currentScrollY);
          ticking = false;
        });
        ticking = true;
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, [lastScrollY, scrolled]);

  // Observe auth changes via localStorage
  useEffect(() => {
    const load = () => {
      try {
        const token = localStorage.getItem('userToken');
        const profile = JSON.parse(localStorage.getItem('userProfile') || '{}');
        setLoggedIn(!!token);
        setUser(profile && Object.keys(profile).length ? profile : null);
      } catch (_) {
        setLoggedIn(false); setUser(null);
      }
    };
    load();
    const onStorage = (e) => {
      if (e.key === 'userToken' || e.key === 'userProfile') load();
    };
    const onAuthChanged = () => load();
    window.addEventListener('storage', onStorage);
    window.addEventListener('auth-changed', onAuthChanged);
    return () => { 
      window.removeEventListener('storage', onStorage);
      window.removeEventListener('auth-changed', onAuthChanged);
    };
  }, []);

  const initials = (name = '') => {
    const parts = String(name).trim().split(/\s+/).slice(0, 2);
    return parts.map(p => p[0]?.toUpperCase() || '').join('') || 'U';
  };

  const toggleMobileMenu = (e) => {
    e.stopPropagation();
    setIsOpen(!isOpen);
  };

  const closeMenu = () => {
    setIsOpen(false);
  };

  return (
    <nav 
      ref={navbarRef}
      className={`navbar ${scrolled ? 'scrolled' : ''}`}
    >
      <div className="navbar-container">
        <div className="logo">
          <Link to="/" onClick={closeMenu} className="logo-container">
            <img src="/images/smkn4.jpg" alt="Logo SMKN 4" className="navbar-logo" />
            <div>
              <span>SMK</span> NEGERI 4 KOTA BOGOR
            </div>
          </Link>
        </div>
        {/* auth moved to right end of nav links */}
        
        <div className={`nav-links ${isOpen ? 'active' : ''}`} ref={menuRef}>
          <Link 
            to="/" 
            className={`nav-link ${location.pathname === '/' ? 'active' : ''}`}
            onClick={closeMenu}
          >
            Beranda
          </Link>
          <Link 
            to="/gallery" 
            className={`nav-link ${location.pathname === '/gallery' ? 'active' : ''}`}
            onClick={closeMenu}
          >
            Galeri
          </Link>
          <Link 
            to="/berita" 
            className={`nav-link ${location.pathname === '/berita' ? 'active' : ''}`}
            onClick={closeMenu}
          >
            Berita
          </Link>
          <Link 
            to="/agenda" 
            className={`nav-link ${location.pathname === '/agenda' ? 'active' : ''}`}
            onClick={closeMenu}
          >
            Agenda
          </Link>
          <Link 
            to="/contact" 
            className={`nav-link ${location.pathname === '/contact' ? 'active' : ''}`}
            onClick={closeMenu}
          >
            Kontak
          </Link>
          <div className="nav-auth-right" style={{marginLeft:'auto', display:'inline-flex', gap:12, alignItems:'center'}}>
            {!loggedIn ? (
              <>
                <Link to="/register" className="auth-link" onClick={closeMenu}>Daftar</Link>
                <Link to="/login" className="auth-link" onClick={closeMenu}>Login</Link>
              </>
            ) : (
              <Link to="/account" className="user-pill" title={user?.name || 'Akun'} onClick={closeMenu}>
                <span className="avatar-circle" aria-label={user?.name || 'Akun'}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                  </svg>
                </span>
              </Link>
            )}
          </div>
        </div>

        <div className="mobile-menu-btn" onClick={toggleMobileMenu}>
          <div className={`hamburger ${isOpen ? 'open' : ''}`}>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
