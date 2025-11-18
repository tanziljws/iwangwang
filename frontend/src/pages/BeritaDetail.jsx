import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { FaCalendarAlt, FaUser, FaTag, FaArrowLeft } from 'react-icons/fa';
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

const formatDate = (date) => {
  if (!date) return '-';
  try {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
  } catch {
    return date;
  }
};

const BeritaDetail = () => {
  const { id } = useParams();
  const [news, setNews] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchDetail = async () => {
      try {
        setLoading(true);
        const res = await fetch(`${API_BASE}/berita/${id}`);
        if (!res.ok) throw new Error('Berita tidak ditemukan');
        const data = await res.json();
        setNews(data);
        setError('');
      } catch (err) {
        setError(err.message || 'Gagal memuat berita');
      } finally {
        setLoading(false);
      }
    };

    fetchDetail();
  }, [id]);

  if (loading) {
    return <div className="agenda-loading" style={{ padding: '4rem 0', textAlign: 'center' }}>Memuat berita...</div>;
  }

  if (error) {
    return (
      <div className="agenda-error" style={{ padding: '4rem 0', textAlign: 'center' }}>
        {error}
        <div style={{ marginTop: '1rem' }}>
          <Link to="/berita" className="btn btn-primary">Kembali ke daftar berita</Link>
        </div>
      </div>
    );
  }

  if (!news) {
    return null;
  }

  return (
    <div className="berita-page">
      <section className="berita-hero">
        <div className="header-wrap">
          <h1 style={{ marginTop: '.35rem' }}>{news.title}</h1>
        </div>
      </section>

      <section className="berita-section" style={{ maxWidth: '900px' }}>
        <article className="berita-card" style={{ overflow: 'visible' }}>
          <div className="berita-image" style={{ borderBottomLeftRadius: 0, borderBottomRightRadius: 0 }}>
            <img src={resolveImage(news.cover_image)} alt={news.title} />
            {news.category && (
              <div className="berita-category" style={{ display: 'inline-flex', alignItems: 'center', gap: '.35rem' }}>
                <FaTag /> {news.category}
              </div>
            )}
          </div>
          <div className="berita-content" style={{ gap: '1rem' }}>
            <div className="berita-meta" style={{ gap: '1rem', flexWrap: 'wrap' }}>
              <span className="meta-item">
                <FaCalendarAlt /> {formatDate(news.published_at)}
              </span>
              <span className="meta-item">
                <FaUser /> {news.author || 'Admin Sekolah'}
              </span>
            </div>
            {news.excerpt && (
              <blockquote style={{ margin: 0, padding: '1rem 1.25rem', background: '#f8fafc', borderRadius: '12px', color: '#475569' }}>
                {news.excerpt}
              </blockquote>
            )}
            <div className="berita-body" style={{ color: '#1f2a44', lineHeight: 1.8 }}>
              {news.content?.split('\n').map((paragraph, idx) => (
                <p key={idx} style={{ marginBottom: '1rem' }}>
                  {paragraph}
                </p>
              ))}
            </div>
            <div style={{ marginTop: '1.5rem' }}>
              <Link to="/berita" className="read-more" style={{ fontWeight: 600, display: 'inline-flex', alignItems: 'center', gap: '.35rem' }}>
                <FaArrowLeft /> Kembali ke berita
              </Link>
            </div>
          </div>
        </article>
      </section>
    </div>
  );
};

export default BeritaDetail;
