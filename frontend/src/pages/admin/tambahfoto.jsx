import { useEffect, useState } from 'react';
import './tambahfoto.css';
const TambahFoto = ({ loadGaleri, galeriOptions = [], onSubmit, onCancel }) => {
  const [form, setForm] = useState({ galeri_id: '', judul: '', deskripsi: '', file: null });
  const [saving, setSaving] = useState(false);
  const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
  const [internalGaleri, setInternalGaleri] = useState([]);

  useEffect(() => {
    const ensure = async () => {
      if (Array.isArray(galeriOptions) && galeriOptions.length > 0) return;
      try {
        const res = await fetch(`${API_BASE}/galeri`);
        const data = await res.json();
        setInternalGaleri(Array.isArray(data) ? data : []);
      } catch (_) { /* ignore */ }
    };
    loadGaleri?.();
    ensure();
  }, [loadGaleri]);

  const handleSubmit = async (e) => {
    e?.preventDefault();
    try {
      setSaving(true);
      if (onSubmit) {
        await onSubmit({ ...form });
      } else {
        const fd = new FormData();
        fd.append('galeri_id', form.galeri_id);
        fd.append('judul', form.judul || 'Foto');
        if (form.deskripsi) fd.append('deskripsi', form.deskripsi);
        fd.append('file', form.file);
        fd.append('alt_text', form.judul || 'Foto');
        fd.append('urutan', '0');
        const res = await fetch(`${API_BASE}/foto`, { method: 'POST', body: fd });
        if (!res.ok) throw new Error('Gagal menambah foto');
        if (typeof window !== 'undefined') alert('Foto berhasil ditambahkan');
      }
      onCancel?.();
    } finally {
      setSaving(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div className="form-group">
        <label className="form-label">Pilih Galeri</label>
        <div className="input-group">
          <select className="form-input" value={form.galeri_id} onChange={(e)=> setForm({ ...form, galeri_id: e.target.value })}>
            <option value="">Pilih galeri...</option>
            {(galeriOptions && galeriOptions.length ? galeriOptions : internalGaleri).map(g => (
              <option key={g.id} value={g.id}>{g.nama || g.post?.title || g.post?.judul || `Galeri ${g.id}`}</option>
            ))}
          </select>
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Judul Foto</label>
        <div className="input-group">
          <input className="form-input" placeholder="Judul foto" value={form.judul} onChange={(e)=> setForm({ ...form, judul: e.target.value })} />
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Deskripsi (opsional)</label>
        <div className="input-group">
          <textarea className="form-textarea" rows={3} placeholder="Deskripsi" value={form.deskripsi} onChange={(e)=> setForm({ ...form, deskripsi: e.target.value })} />
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Gambar</label>
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
        <button type="button" className="btn btn-secondary" onClick={onCancel}>Batal</button>
        <button type="submit" className="btn btn-primary" disabled={saving || !form.galeri_id || !form.file}>
          {saving ? 'Mengunggah...' : 'Simpan Foto'}
        </button>
      </div>
    </form>
  );
};

export default TambahFoto;
