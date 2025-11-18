import { useEffect, useState } from 'react';
import { FaCalendarAlt, FaUser, FaArrowRight, FaNewspaper } from 'react-icons/fa';
import '../styles/Berita.css';
import '../styles/Home.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
const ORIGIN_BASE = (import.meta?.env?.VITE_ORIGIN_BASE || 'http://localhost:8000').replace(/\/$/, '');

const resolveImage = (path) => {
  if (!path) return '/images/agenda/placeholder.jpg';
  if (path.startsWith('http')) return path;
  const normalized = path.startsWith('storage/') ? path : `storage/${path}`;
  return `${ORIGIN_BASE}/${normalized}`;
};

const Berita = () => {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchNews = async () => {
      try {
        setLoading(true);
        const res = await fetch(`${API_BASE}/berita`);
        if (!res.ok) throw new Error('Gagal memuat berita');
        const data = await res.json();
        setItems(Array.isArray(data) ? data : []);
        setError('');
      } catch (e) {
        setError(e.message || 'Gagal memuat berita');
      } finally {
        setLoading(false);
      }
    };

    fetchNews();
  }, []);

  const formatDate = (date) => {
    if (!date) return '-';
    try {
      return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } catch {
      return date;
    }
  };

  return (
    <div className="berita-page">
      <section className="berita-hero">
        <div className="hero-decor">
          <span className="blob"></span>
          <span className="ring r1"></span>
          <span className="ring r2"></span>
        </div>
        <div className="header-wrap">
          <FaNewspaper className="header-icon" />
          <div>
            <h1>Berita Sekolah</h1>
            <p>Update informasi terbaru seputar kegiatan, prestasi, dan pengumuman sekolah.</p>
          </div>
        </div>
      </section>

      <section className="berita-section">
        <div className="berita-grid">
          {loading && (
            <div className="agenda-loading" style={{ gridColumn: '1 / -1' }}>Memuat berita...</div>
          )}
          {!loading && error && (
            <div className="agenda-error" style={{ gridColumn: '1 / -1' }}>{error}</div>
          )}
          {!loading && !error && items.length === 0 && (
            <div className="agenda-empty" style={{ gridColumn: '1 / -1' }}>Belum ada berita.</div>
          )}
          {!loading && !error && items.map((news) => (
            <article key={news.id} className="berita-card">
              <div className="berita-image">
                <img src={resolveImage(news.cover_image)} alt={news.title} />
                {news.category && <div className="berita-category">{news.category}</div>}
              </div>
              <div className="berita-content">
                <div className="berita-meta">
                  <span className="meta-item"><FaCalendarAlt /> {formatDate(news.published_at)}</span>
                  <span className="meta-item"><FaUser /> {news.author || 'Admin Sekolah'}</span>
                </div>
                <h3>{news.title}</h3>
                <p>{news.excerpt || news.content?.slice(0, 160) + (news.content?.length > 160 ? '...' : '')}</p>
                <button className="read-more" type="button" disabled>
                  Baca Selengkapnya <FaArrowRight />
                </button>
              </div>
            </article>
          ))}
        </div>
      </section>

      <footer className="site-footer">
        <div className="container">
          <div className="footer-inner">
            <div className="footer-meta"><p>2025 SMKN 4 Bogor. All rights reserved.</p></div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Berita;
