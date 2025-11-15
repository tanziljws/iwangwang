import { useState } from 'react';
import './tambahkategori.css';

const TambahKategori = ({ onSubmit, onCancel }) => {
  const [form, setForm] = useState({ nama: '', deskripsi: '' });
  const [saving, setSaving] = useState(false);
  const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');

  const handleSubmit = async (e) => {
    e?.preventDefault();
    try {
      setSaving(true);
      if (onSubmit) {
        await onSubmit({ ...form });
      } else {
        const res = await fetch(`${API_BASE}/kategori`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ nama: form.nama, deskripsi: form.deskripsi })
        });
        if (!res.ok) throw new Error('Gagal menambah kategori');
        if (typeof window !== 'undefined') alert('Kategori berhasil ditambahkan');
      }
      onCancel?.();
    } finally {
      setSaving(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div className="form-group">
        <label className="form-label">Nama Kategori</label>
        <div className="input-group">
          <input
            className="form-input"
            placeholder="Nama kategori"
            value={form.nama}
            onChange={(e) => setForm({ ...form, nama: e.target.value })}
          />
        </div>
      </div>
      <div className="form-group">
        <label className="form-label">Deskripsi</label>
        <div className="input-group">
          <textarea
            className="form-textarea"
            rows={4}
            placeholder="Deskripsi (opsional)"
            value={form.deskripsi}
            onChange={(e) => setForm({ ...form, deskripsi: e.target.value })}
          />
        </div>
      </div>
      <div className="modal-footer">
        <button type="button" className="btn btn-secondary" onClick={onCancel}>Batal</button>
        <button type="submit" className="btn btn-primary" disabled={saving || !form.nama}>
          {saving ? 'Menyimpan...' : 'Simpan Kategori'}
        </button>
      </div>
    </form>
  );
};

export default TambahKategori;
