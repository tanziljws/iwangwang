import React from 'react';
import '../styles/Gallery.css';
import '../styles/Home.css';

const Gallery = () => {
  const [active, setActive] = React.useState('Semua');
  const [modal, setModal] = React.useState(null);
  const [items, setItems] = React.useState([]);
  const [categories, setCategories] = React.useState(['Semua']);
  const [loading, setLoading] = React.useState(true);
  const [error, setError] = React.useState('');

  const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
  const ORIGIN_BASE = React.useMemo(() => API_BASE.replace(/\/api\/?$/, ''), [API_BASE]);
  const ASSET_ORIGIN = (import.meta?.env?.VITE_ASSET_ORIGIN || '').replace(/\/$/, '');

  const buildSrcCandidates = React.useCallback((filename) => {
    if (!filename) return [];
    const frontOrigin = typeof window !== 'undefined' ? window.location.origin.replace(/\/$/, '') : '';
    const cands = [];
    if (ASSET_ORIGIN) cands.push(`${ASSET_ORIGIN}/storage/foto/${filename}`);
    cands.push(`${ORIGIN_BASE}/storage/foto/${filename}`);
    if (frontOrigin) cands.push(`${frontOrigin}/storage/foto/${filename}`);
    // Common XAMPP paths when Laravel runs under /backend/public
    if (frontOrigin) cands.push(`${frontOrigin}/backend/public/storage/foto/${filename}`);
    cands.push(`/storage/foto/${filename}`);
    return Array.from(new Set(cands));
  }, [ASSET_ORIGIN, ORIGIN_BASE]);

  React.useEffect(() => {
    let mounted = true;
    const load = async () => {
      setLoading(true);
      setError('');
      try {
        const res = await fetch(`${API_BASE}/galeri`);
        const data = await res.json();
        if (!mounted) return;

        const list = Array.isArray(data) ? data : [];
        // Flatten fotos from each galeri and map to gallery cards
        const photos = [];
        const cats = new Set();
        list.forEach((g) => {
          const catName = g?.kategori?.nama || 'Lainnya';
          if (g?.status === 0) return; // skip inactive gallery if flagged
          (g?.foto || []).forEach((f) => {
            if (f?.status === 0) return; // skip inactive photo
            cats.add(catName);
            const alts = buildSrcCandidates(f.file);
            photos.push({
              id: f.id,
              title: f.judul || g.nama || 'Foto',
              category: catName,
              src: alts[0] || '',
              alts: alts.slice(1),
            });
          });
        });
        setItems(photos);
        setCategories(['Semua', ...Array.from(cats)]);
      } catch (e) {
        setError('Gagal memuat galeri');
      } finally {
        if (mounted) setLoading(false);
      }
    };
    load();
    return () => { mounted = false; };
  }, [API_BASE, ORIGIN_BASE]);

  const filtered = React.useMemo(() => {
    if (active === 'Semua') return items;
    return items.filter((i) => i.category === active);
  }, [active, items]);

  const handleImgError = React.useCallback((e, img) => {
    if (!img || !Array.isArray(img.alts) || img.alts.length === 0) return;
    const next = img.alts.shift();
    if (next && next !== e.currentTarget.src) {
      e.currentTarget.src = next;
    }
  }, []);

  return (
    <div className="gallery-page">
      <section className="gallery-hero">
        <div className="hero-decor">
          <span className="blob"></span>
          <span className="ring r1"></span>
          <span className="ring r2"></span>
        </div>
        <h1>Galeri Sekolah</h1>
        <p>Rangkaian momen terbaik, fasilitas unggulan, dan aktivitas siswa yang menginspirasi.</p>
      </section>

      <div className="gallery-filters" role="tablist" aria-label="Filter Galeri">
        {categories.map((c) => (
          <button
            key={c}
            type="button"
            className={c === active ? 'active' : ''}
            aria-selected={c === active}
            role="tab"
            onClick={() => setActive(c)}
          >
            {c}
          </button>
        ))}
      </div>

      {loading && (
        <div className="gallery-grid"><div style={{gridColumn:'1/-1', textAlign:'center'}}>Memuat...</div></div>
      )}

      {!loading && error && (
        <div className="gallery-grid"><div style={{gridColumn:'1/-1', textAlign:'center'}}>{error}</div></div>
      )}

      {!loading && !error && (
        <div className="gallery-grid">
          {filtered.length === 0 ? (
            <div style={{gridColumn:'1/-1', textAlign:'center'}}>Belum ada foto.</div>
          ) : (
            filtered.map((img, idx) => (
              <button
                key={img.id}
                type="button"
                className="gallery-item fadein"
                style={{ ['--stagger'] : idx }}
                onClick={() => setModal(img)}
                aria-label={`Buka foto ${img.title}`}
              >
                <img src={img.src} alt={img.title} loading="lazy" onError={(e)=>handleImgError(e, img)} />
                <div className="image-overlay">
                  <h3>{img.title}</h3>
                  <span className="badge">{img.category}</span>
                </div>
              </button>
            ))
          )}
        </div>
      )}

      {modal && (
        <div className="modal" role="dialog" aria-modal="true" aria-label={modal.title} onClick={() => setModal(null)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <button className="close" aria-label="Tutup" onClick={() => setModal(null)}>×</button>
            <img src={modal.src} alt={modal.title} />
            <div className="modal-info">
              <h2>{modal.title}</h2>
              <p>{modal.category}</p>
            </div>
          </div>
        </div>
      )}

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

export default Gallery;

