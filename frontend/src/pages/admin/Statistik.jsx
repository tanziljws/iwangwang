import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Image, Layers, Newspaper, Calendar } from 'lucide-react';
import './Statistik.css';

const API_BASE = 'http://localhost:8000/api';

const Statistik = () => {
  const navigate = useNavigate();
  const [counts, setCounts] = useState({ galeri: 0, kategori: 0, berita: 2, agenda: 2 });

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
        const [galeriRes, kategoriRes] = await Promise.all([
          fetch(`${API_BASE}/galeri`),
          fetch(`${API_BASE}/kategori`),
        ]);

        const galeriData = await galeriRes.json();
        const kategoriData = await kategoriRes.json();

        setCounts(prev => ({
          ...prev,
          galeri: Array.isArray(galeriData) ? galeriData.length : 0,
          kategori: Array.isArray(kategoriData) ? kategoriData.length : 0,
        }));
      } catch (e) {
        console.error('Gagal memuat statistik', e);
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
      </section>

      <section className="statistik-secondary">
        <div className="statistik-chart-card">
          <div className="statistik-chart-header">
            <h2>Aktivitas Konten</h2>
            <span className="statistik-chart-period">7 hari terakhir (dummy)</span>
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
          <div className="statistik-mini-card">
            <div className="mini-label">Proporsi Galeri per Kategori</div>
            <div className="mini-badges">
              <span className="mini-badge blue" />
              <span className="mini-badge cyan" />
              <span className="mini-badge gray" />
            </div>
            <p className="mini-caption">Ilustrasi visual sederhana, bisa diisi grafik sungguhan nanti.</p>
          </div>
          <div className="statistik-mini-card">
            <div className="mini-label">Snapshot Data</div>
            <ul className="mini-list">
              <li><strong>{counts.galeri}</strong> galeri terdaftar</li>
              <li><strong>{counts.kategori}</strong> kategori aktif</li>
              <li><strong>{counts.berita}</strong> berita dipublikasikan</li>
              <li><strong>{counts.agenda}</strong> agenda terjadwal</li>
            </ul>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Statistik;
