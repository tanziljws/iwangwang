import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Image, Layers, Newspaper, Calendar, Users } from 'lucide-react';
import './Statistik.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');

const Statistik = () => {
  const navigate = useNavigate();
  const [counts, setCounts] = useState({ galeri: 0, kategori: 0, berita: 0, agenda: 0, pengunjung: 0 });
  const [categoryDist, setCategoryDist] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }
  }, [navigate]);

  useEffect(() => {
    const loadStats = async () => {
      try {
        setLoading(true);
        setError('');
        const token = localStorage.getItem('adminToken');
        const authHeaders = token
          ? { Authorization: `Bearer ${token}`, Accept: 'application/json' }
          : { Accept: 'application/json' };

        const fetchJson = async (path, options = {}) => {
          const res = await fetch(path, {
            headers: { ...authHeaders, ...(options.headers || {}) },
            ...options,
          });
          if (!res.ok) {
            const details = await res.json().catch(() => ({}));
            throw new Error(details.message || `Gagal memuat ${path}`);
          }
          return res.json();
        };

        const [galeriData, kategoriData, beritaData, agendaData] = await Promise.all([
          fetchJson(`${API_BASE}/galeri`),
          fetchJson(`${API_BASE}/kategori`),
          fetchJson(`${API_BASE}/berita`),
          fetchJson(`${API_BASE}/agendas`),
        ]);

        let pengunjungCount = 0;
        try {
          const usersData = await fetchJson(`${API_BASE}/users`);
          pengunjungCount = Array.isArray(usersData) ? usersData.length : 0;
        } catch (err) {
          console.warn('Gagal memuat data pengunjung:', err);
        }

        const galeriSafe = Array.isArray(galeriData) ? galeriData : [];
        const kategoriSafe = Array.isArray(kategoriData) ? kategoriData : [];

        const kategoriCountMap = galeriSafe.reduce((acc, item) => {
          const key = item?.kategori_id;
          if (!key) return acc;
          acc[key] = (acc[key] || 0) + 1;
          return acc;
        }, {});

        const distribution = kategoriSafe
          .map((kategori) => ({
            id: kategori.id,
            label: kategori.nama || 'Tanpa Nama',
            count: kategoriCountMap[kategori.id] || 0,
          }))
          .filter((item) => item.count > 0);

        setCategoryDist(distribution);

        setCounts({
          galeri: galeriSafe.length,
          kategori: kategoriSafe.length,
          berita: Array.isArray(beritaData) ? beritaData.length : 0,
          agenda: Array.isArray(agendaData) ? agendaData.length : 0,
          pengunjung: pengunjungCount,
        });
      } catch (e) {
        setError(e.message || 'Gagal memuat statistik');
      } finally {
        setLoading(false);
      }
    };

    loadStats();
  }, []);

  return (
    <div className="statistik-page">
      <div className="statistik-hero-blur" />

      <header className="statistik-header">
        <div className="statistik-header-left">
          <h1>Statistik Galeri</h1>
          <p>Ringkasan jumlah data galeri, kategori, berita, dan agenda untuk memantau aktivitas konten.</p>
        </div>
        <button className="btn-back-dashboard" onClick={() => navigate('/admin/dashboard')}>
          Kembali ke Dashboard
        </button>
      </header>

      {loading && (
        <div className="statistik-alert info">Memuat data statistik terbaru...</div>
      )}
      {!loading && error && (
        <div className="statistik-alert error">{error}</div>
      )}

      <section className="statistik-grid">
        <div className="statistik-card primary">
          <div className="statistik-card-header">
            <span className="statistik-icon-badge primary">
              <Image size={20} />
            </span>
            <span className="statistik-label">Total Galeri</span>
          </div>
          <div className="statistik-value">{counts.galeri}</div>
          <div className="statistik-sub">Jumlah galeri aktif di sistem</div>
        </div>
        <div className="statistik-card">
          <div className="statistik-card-header">
            <span className="statistik-icon-badge neutral">
              <Layers size={18} />
            </span>
            <span className="statistik-label">Total Kategori</span>
          </div>
          <div className="statistik-value">{counts.kategori}</div>
          <div className="statistik-sub">Pengelompokan galeri yang tersedia</div>
        </div>
        <div className="statistik-card">
          <div className="statistik-card-header">
            <span className="statistik-icon-badge neutral">
              <Newspaper size={18} />
            </span>
            <span className="statistik-label">Total Berita</span>
          </div>
          <div className="statistik-value">{counts.berita}</div>
          <div className="statistik-sub">Konten berita yang ditampilkan</div>
        </div>
        <div className="statistik-card">
          <div className="statistik-card-header">
            <span className="statistik-icon-badge neutral">
              <Calendar size={18} />
            </span>
            <span className="statistik-label">Total Agenda</span>
          </div>
          <div className="statistik-value">{counts.agenda}</div>
          <div className="statistik-sub">Agenda dan jadwal kegiatan</div>
        </div>
        <div className="statistik-card">
          <div className="statistik-card-header">
            <span className="statistik-icon-badge neutral">
              <Users size={18} />
            </span>
            <span className="statistik-label">Total Pengunjung</span>
          </div>
          <div className="statistik-value">{counts.pengunjung}</div>
          <div className="statistik-sub">Akun pengguna/pengunjung yang terdaftar</div>
        </div>
      </section>

      <section className="statistik-secondary">
        <div className="statistik-chart-card">
          <div className="statistik-chart-header">
            <h2>Aktivitas Konten</h2>
            <span className="statistik-chart-period">Data diperbarui saat halaman dimuat</span>
          </div>
          <div className="statistik-chart-placeholder">
            <div className="statistik-chart-line" />
            <div className="statistik-chart-dots">
              <span />
              <span />
              <span />
              <span />
              <span />
            </div>
          </div>
        </div>

        <div className="statistik-side-cards">
          <CategoryDistributionCard data={categoryDist} total={counts.galeri} loading={loading} />
          <div className="statistik-mini-card">
            <div className="mini-label">Snapshot Data</div>
            <ul className="mini-list">
              <li><strong>{counts.galeri}</strong> galeri terdaftar</li>
              <li><strong>{counts.kategori}</strong> kategori aktif</li>
              <li><strong>{counts.berita}</strong> berita dipublikasikan</li>
              <li><strong>{counts.agenda}</strong> agenda terjadwal</li>
              <li><strong>{counts.pengunjung}</strong> pengunjung terdaftar</li>
            </ul>
          </div>
        </div>
      </section>
    </div>
  );
};

const colorPalette = ['#2563eb', '#0ea5e9', '#14b8a6', '#f97316', '#a78bfa', '#94a3b8'];

const CategoryDistributionCard = ({ data, total, loading }) => {
  const hasData = total > 0 && data.length > 0;
  const segments = hasData
    ? data.map((item, index) => ({
        ...item,
        color: colorPalette[index % colorPalette.length],
        percentage: ((item.count / total) * 100).toFixed(1),
      }))
    : [];

  let cumulative = 0;
  const gradientStops = segments
    .map((segment) => {
      const start = cumulative;
      cumulative += Number(segment.percentage);
      return `${segment.color} ${start}% ${Math.min(cumulative, 100)}%`;
    })
    .join(', ');

  return (
    <div className="statistik-mini-card distribution-card">
      <div className="mini-label">Proporsi Galeri per Kategori</div>
      <div className="distribution-content">
        <div
          className={`distribution-donut ${!hasData ? 'empty' : ''}`}
          aria-label="Diagram proporsi galeri per kategori"
          role="img"
          style={hasData ? { background: `conic-gradient(${gradientStops})` } : undefined}
        >
          <div className="distribution-donut-center">
            {loading ? (
              <span>...</span>
            ) : (
              <>
                <strong>{total}</strong>
                <small>galeri</small>
              </>
            )}
          </div>
        </div>
        <div className="distribution-legend">
          {loading && <p className="mini-caption">Memuat proporsi kategori...</p>}
          {!loading && !hasData && (
            <p className="mini-caption">Belum ada data galeri untuk ditampilkan.</p>
          )}
          {!loading && hasData && (
            <ul>
              {segments.map((segment) => (
                <li key={segment.id}>
                  <span className="legend-dot" style={{ backgroundColor: segment.color }} />
                  <span className="legend-label">{segment.label}</span>
                  <span className="legend-value">{segment.percentage}%</span>
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>
    </div>
  );
};

export default Statistik;
