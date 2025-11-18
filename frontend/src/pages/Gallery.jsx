import React from 'react';
import { useNavigate } from 'react-router-dom';
import '../styles/Gallery.css';
import '../styles/Home.css';

const Gallery = () => {
  const [active, setActive] = React.useState('Semua');
  const [selected, setSelected] = React.useState(null); // viewer di bawah grid, bukan modal full-screen
  const [items, setItems] = React.useState([]);
  const [categories, setCategories] = React.useState(['Semua']);
  const [loading, setLoading] = React.useState(true);
  const [error, setError] = React.useState('');
  const [likeCount, setLikeCount] = React.useState(0);
  const [likeBusy, setLikeBusy] = React.useState(false);
  const [liked, setLiked] = React.useState(false);
  const [comments, setComments] = React.useState([]);
  const [commentText, setCommentText] = React.useState('');
  const [commentBusy, setCommentBusy] = React.useState(false);
  const commentInputRef = React.useRef(null);

  const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
  const navigate = (typeof window !== 'undefined') ? useNavigate() : () => {};
  const ORIGIN_BASE = React.useMemo(() => API_BASE.replace(/\/api\/?$/, ''), [API_BASE]);
  const ASSET_ORIGIN = (import.meta?.env?.VITE_ASSET_ORIGIN || '').replace(/\/$/, '');

  const buildSrcCandidates = React.useCallback((filename) => {
    if (!filename) return [];
    const frontOrigin = typeof window !== 'undefined' ? window.location.origin.replace(/\/$/, '') : '';
    const cands = [];

    // 1) Utamakan route /media yang sudah didefinisikan di web.php
    cands.push(`${ORIGIN_BASE}/media/foto/${filename}`);
    if (frontOrigin) cands.push(`${frontOrigin}/media/foto/${filename}`);

    // 2) Fallback ke /storage bila /media tidak ada / tidak aktif
    if (ASSET_ORIGIN) cands.push(`${ASSET_ORIGIN}/storage/foto/${filename}`);
    cands.push(`${ORIGIN_BASE}/storage/foto/${filename}`);
    if (frontOrigin) cands.push(`${frontOrigin}/storage/foto/${filename}`);

    // 3) Fallback tambahan umum (misal Laravel di subfolder backend/public)
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
            const version = f.updated_at || g.updated_at || g.created_at || '';
            const filenameWithVersion = version ? `${f.file}?v=${encodeURIComponent(version)}` : f.file;
            const alts = buildSrcCandidates(filenameWithVersion);
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

  // Helpers for auth header (pakai userToken agar tidak bentrok dengan admin)
  const authHeaders = React.useCallback(() => {
    const token = localStorage.getItem('userToken');
    return token ? { Authorization: `Bearer ${token}` } : {};
  }, []);

  // Load likes count & comments when a photo is selected
  React.useEffect(() => {
    if (!selected) return;
    let mounted = true;
    (async () => {
      // reset local states when change selected
      setLiked(false);
      try {
        // like count (public di kode backend butuh auth, maka kirim token jika ada)
        const likeRes = await fetch(`${API_BASE}/foto/${selected.id}/likes/count`, {
          headers: { 'Accept': 'application/json', ...authHeaders() }
        });
        if (likeRes.ok) {
          const likeData = await likeRes.json();
          if (mounted) setLikeCount(likeData?.count ?? 0);
        } else {
          if (mounted) setLikeCount(0);
        }
      } catch (_) { setLikeCount(0); }

      try {
        const cRes = await fetch(`${API_BASE}/foto/${selected.id}/comments`, {
          headers: { 'Accept': 'application/json', ...authHeaders() }
        });
        if (cRes.ok) {
          const cData = await cRes.json();
          if (mounted) setComments(Array.isArray(cData) ? cData : []);
        } else {
          if (mounted) setComments([]);
        }
      } catch (_) { setComments([]); }
    })();
    return () => { mounted = false; };
  }, [API_BASE, selected, authHeaders]);

  const requireLogin = () => {
    const token = localStorage.getItem('userToken');
    if (!token) {
      alert('Silakan login terlebih dahulu untuk melakukan aksi ini.');
      try { navigate('/login'); } catch (e) {}
      return false;
    }
    return true;
  };

  const onToggleLike = async () => {
    if (!selected) return;
    if (!requireLogin()) return;
    setLikeBusy(true);
    try {
      const res = await fetch(`${API_BASE}/foto/${selected.id}/like`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', ...authHeaders() },
      });
      const data = await res.json().catch(() => ({}));
      if (res.ok) {
        if (typeof data.count === 'number') setLikeCount(data.count);
        if (data.status === 'liked') {
          setLiked(true);
          try {
            const raw = localStorage.getItem('userLikedPhotoIds') || '[]';
            const arr = Array.isArray(JSON.parse(raw)) ? JSON.parse(raw) : [];
            if (!arr.includes(selected.id)) {
              arr.push(selected.id);
              localStorage.setItem('userLikedPhotoIds', JSON.stringify(arr));
            }
          } catch (_) {}
        }
        if (data.status === 'unliked') {
          setLiked(false);
          try {
            const raw = localStorage.getItem('userLikedPhotoIds') || '[]';
            const arr = Array.isArray(JSON.parse(raw)) ? JSON.parse(raw) : [];
            const next = arr.filter((id) => id !== selected.id);
            localStorage.setItem('userLikedPhotoIds', JSON.stringify(next));
          } catch (_) {}
        }
      } else {
        alert(data?.message || 'Gagal mengubah like');
      }
    } catch (e) {
      console.error(e);
      alert('Terjadi kesalahan');
    } finally {
      setLikeBusy(false);
    }
  };

  const onSendComment = async (e) => {
    e?.preventDefault?.();
    if (!selected) return;
    if (!requireLogin()) return;
    if (!commentText.trim()) return;
    setCommentBusy(true);
    try {
      const res = await fetch(`${API_BASE}/foto/${selected.id}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', ...authHeaders() },
        body: JSON.stringify({ body: commentText.trim() })
      });
      const data = await res.json().catch(() => ({}));
      if (res.ok) {
        setComments((prev) => [data, ...prev]);
        setCommentText('');
        try {
          const raw = localStorage.getItem('userCommentCount') || '0';
          const n = Number(raw) || 0;
          localStorage.setItem('userCommentCount', String(n + 1));
        } catch (_) {}
      } else {
        alert(data?.message || 'Gagal mengirim komentar');
      }
    } catch (e) {
      console.error(e);
      alert('Terjadi kesalahan');
    } finally {
      setCommentBusy(false);
    }
  };

  const onDownload = async () => {
    if (!selected) return;
    if (!requireLogin()) return;
    try {
      const res = await fetch(`${API_BASE}/foto/${selected.id}/download`, {
        headers: { ...authHeaders() }
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        alert(data?.message || 'Gagal mengunduh file');
        return;
      }
      const blob = await res.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = (selected.title || 'foto') + '.jpg';
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);
      try {
        const raw = localStorage.getItem('userDownloadCount') || '0';
        const n = Number(raw) || 0;
        localStorage.setItem('userDownloadCount', String(n + 1));
      } catch (_) {}
    } catch (e) {
      console.error(e);
      alert('Terjadi kesalahan saat mengunduh');
    }
  };

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
                onClick={() => setSelected(img)}
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

      {selected && (
        <section className="gallery-viewer" aria-label="Foto terpilih">
          <div className="gallery-viewer-card">
            <div className="gallery-viewer-header">
              <h2>{selected.title}</h2>
              <button
                type="button"
                className="gallery-viewer-close"
                aria-label="Tutup tampilan foto"
                onClick={() => setSelected(null)}
              >
                ×
              </button>
            </div>
            <div className="gallery-viewer-image-wrap">
              <img src={selected.src} alt={selected.title} />
            </div>
            <div className="gallery-viewer-meta">
              <span className="badge">{selected.category}</span>
            </div>

            <div className="gallery-actions">
              <div className="actions-left">
                <button type="button" onClick={onToggleLike} disabled={likeBusy} className={`btn-heart ${liked ? 'is-liked' : ''}`} aria-label={liked ? 'Batalkan suka' : 'Suka'}>
                  {likeBusy ? (
                    '...'
                  ) : (
                    <svg width="32" height="32" viewBox="0 0 24 24" fill={'currentColor'} aria-hidden="true">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41.81 4.5 2.09C12.59 4.81 14.26 4 16 4 18.5 4 20.5 6 20.5 8.5c0 3.78-3.4 6.86-8.05 11.54L12 21.35z"></path>
                    </svg>
                  )}
                </button>
                <span className="like-count">{likeCount} suka</span>
              </div>
              <div className="like-end">
                <button type="button" className="btn-comment" aria-label="Komentar" onClick={() => { commentInputRef.current?.focus(); commentInputRef.current?.scrollIntoView({behavior:'smooth', block:'nearest'}); }}>
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                  </svg>
                </button>
                <button type="button" onClick={onDownload} className="btn-download" aria-label="Unduh">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                  </svg>
                </button>
              </div>
            </div>

            <div className="comments" style={{padding:'8px 12px 16px'}}>
              <form onSubmit={onSendComment} style={{display:'flex', gap:8, marginBottom:12}}>
                <input
                  type="text"
                  placeholder="Tulis komentar..."
                  value={commentText}
                  onChange={(e)=>setCommentText(e.target.value)}
                  ref={commentInputRef}
                  style={{flex:1}}
                />
                <button type="submit" disabled={commentBusy || !commentText.trim()} className="btn-send" aria-label="Kirim komentar">
                  {commentBusy ? (
                    '...'
                  ) : (
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                      <line x1="22" y1="2" x2="11" y2="13"/>
                      <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                  )}
                </button>
              </form>
              <div className="comment-list" style={{display:'grid', gap:8}}>
                {comments.length === 0 ? (
                  <div style={{opacity:.7}}>Belum ada komentar.</div>
                ) : (
                  comments.map((c) => (
                    <div key={c.id} className="comment-item" style={{border:'1px solid #eee', borderRadius:8, padding:8}}>
                      <div style={{fontWeight:600, fontSize:13}}>{c.user?.name || 'Anonim'}</div>
                      <div style={{fontSize:14}}>{c.body}</div>
                    </div>
                  ))
                )}
              </div>
            </div>
          </div>
        </section>
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

