import { useEffect, useState } from 'react';
import '../styles/Agenda.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');

const getDayLabel = (date) => {
  const parsed = new Date(date);
  return Number.isNaN(parsed.getTime()) ? '--' : parsed.getDate();
};

const getMonthLabel = (date) => {
  const parsed = new Date(date);
  return Number.isNaN(parsed.getTime())
    ? ''
    : parsed.toLocaleString('id-ID', { month: 'short', year: 'numeric' });
};

const Agenda = () => {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchAgenda = async () => {
      try {
        setLoading(true);
        const res = await fetch(`${API_BASE}/agendas`);
        if (!res.ok) throw new Error('Gagal memuat agenda');
        const data = await res.json();
        setItems(Array.isArray(data) ? data : []);
        setError('');
      } catch (e) {
        setError(e.message || 'Gagal memuat agenda');
      } finally {
        setLoading(false);
      }
    };

    fetchAgenda();
  }, []);

  return (
    <div className="agenda-page">
      {/* Hero Section */}
      <section className="agenda-hero">
        <div className="container">
          <h1>Agenda Sekolah</h1>
          <p>Jadwal kegiatan dan acara SMKN 4 Kota Bogor</p>
        </div>
      </section>

      {/* Agenda Grid */}
      <section className="agenda-grid-section">
        <div className="container">
          {loading && (
            <div className="agenda-loading">Memuat agenda...</div>
          )}
          {error && !loading && (
            <div className="agenda-error">{error}</div>
          )}
          {!loading && !error && items.length === 0 && (
            <div className="agenda-empty">Belum ada agenda yang dijadwalkan.</div>
          )}
          <div className="agenda-grid">
            {items.map((item) => {
              const dayLabel = getDayLabel(item.date);
              const monthLabel = getMonthLabel(item.date);
              const fullDate = new Date(item.date);

              return (
                <div key={item.id} className="agenda-card agenda-card-plain">
                  <div className="agenda-date-badge">
                    <span className="day">{dayLabel}</span>
                    <span className="month">{monthLabel}</span>
                  </div>
                  <div className="agenda-content">
                    <h3>{item.title}</h3>
                    <div className="agenda-meta">
                      <span className="location">
                        <i className="fas fa-map-marker-alt"></i> {item.location || 'Lokasi menyusul'}
                      </span>
                      <span className="date">
                        <i className="far fa-calendar-alt"></i>{' '}
                        {Number.isNaN(fullDate.getTime())
                          ? '-'
                          : fullDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                        {item.time && (
                          <> &bull; {item.time}</>
                        )}
                      </span>
                    </div>
                    <p>{item.description || 'Belum ada deskripsi tambahan.'}</p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* Footer (same style as Home) */}
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

export default Agenda;
