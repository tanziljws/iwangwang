import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './tambahgaleri.css';
const TambahGaleri = ({ loadKategori, kategori = [], onSubmit, onCancel }) => {
  const [form, setForm] = useState({ judul: '', kategori_id: '', deskripsi: '', file: null });
  const [saving, setSaving] = useState(false);
  const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
  const [internalKategori, setInternalKategori] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    // Jika parent tidak memasok kategori, ambil sendiri dari API
    const ensure = async () => {
      if (Array.isArray(kategori) && kategori.length > 0) return;
      try {
        const res = await fetch(`${API_BASE}/kategori`);
        const data = await res.json();
        setInternalKategori(Array.isArray(data) ? data : []);
      } catch (_) { /* ignore */ }
    };
    loadKategori?.();
    ensure();
  }, [loadKategori]);

  const handleSubmit = async (e) => {
    e?.preventDefault();
    try {
      setSaving(true);
      if (onSubmit) {
        await onSubmit({ ...form });
      } else {
        // Opsi A: langsung simpan ke entitas Galeri
        const resGal = await fetch(`${API_BASE}/galeri`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            nama: form.judul,
            kategori_id: form.kategori_id,
            deskripsi: form.deskripsi || null,
            status: 1,
            urutan: 0,
          })
        });
        if (!resGal.ok) {
          const txt = await resGal.text();
          throw new Error(`Gagal membuat galeri (${resGal.status}): ${txt}`);
        }
        const gal = await resGal.json();

        // Upload foto pertama (opsional)
        if (form.file) {
          const fd = new FormData();
          fd.append('galeri_id', gal.id || gal?.data?.id || '');
          fd.append('judul', form.judul || 'Foto');
          if (form.deskripsi) fd.append('deskripsi', form.deskripsi);
          fd.append('file', form.file);
          fd.append('alt_text', form.judul || 'Foto');
          fd.append('urutan', '0');
          const resFoto = await fetch(`${API_BASE}/foto`, { method: 'POST', body: fd });
          if (!resFoto.ok) {
            const txt = await resFoto.text();
            throw new Error(`Gagal mengunggah foto (${resFoto.status}): ${txt}`);
          }
        }
        if (typeof window !== 'undefined') alert('Galeri berhasil ditambahkan');
      }
      onCancel?.();
    } finally {
      setSaving(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="tambah-form">
      <div className="form-group">
        <label className="form-label">Judul Galeri</label>
        <div className="input-group">
          <input className="form-input" placeholder="Judul" value={form.judul} onChange={(e)=> setForm({ ...form, judul: e.target.value })} />
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Kategori</label>
        <div className="input-group">
          <select className="form-input" value={form.kategori_id} onChange={(e)=> setForm({ ...form, kategori_id: e.target.value })}>
            <option value="">Pilih kategori...</option>
            {(kategori && kategori.length ? kategori : internalKategori).map(k => (
              <option key={k.id} value={k.id}>{k.nama || k.name || k.title || `Kategori ${k.id}`}</option>
            ))}
          </select>
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Deskripsi</label>
        <div className="input-group">
          <textarea className="form-textarea" rows={4} placeholder="Deskripsi (opsional)" value={form.deskripsi} onChange={(e)=> setForm({ ...form, deskripsi: e.target.value })} />
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Foto Pertama (opsional)</label>
        <div className="file-upload">
          <label className="file-upload-label">
            <input type="file" className="file-upload-input" accept="image/*" onChange={(e)=> setForm({ ...form, file: e.target.files[0] })} />
            <div className="file-upload-content">
              {form.file ? (
                <>
                  <img src={URL.createObjectURL(form.file)} alt="Preview" className="file-upload-preview" />
                  <span className="file-upload-name">{form.file.name}</span>
                </>
              ) : (
                <>
                  <span className="text-sm text-gray-500">Klik untuk unggah gambar</span>
                  <span className="text-xs text-gray-400 mt-1">JPG/PNG maks 2MB</span>
                </>
              )}
            </div>
          </label>
        </div>
      </div>
      <div className="modal-footer">
        <button
          type="button"
          className="btn btn-secondary"
          onClick={() => {
            onCancel?.();
            navigate('/admin/dashboard');
          }}
        >
          Kembali
        </button>
        <button type="submit" className="btn btn-primary" disabled={saving || !form.judul || !form.kategori_id}>
          {saving ? 'Menyimpan...' : 'Simpan Galeri'}
        </button>
      </div>
    </form>
  );
};

export default TambahGaleri;
