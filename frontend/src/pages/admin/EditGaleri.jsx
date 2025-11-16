import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import './tambahgaleri.css';

const API_BASE = 'http://localhost:8000/api';

const EditGaleri = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({ judul: '', kategori_id: '', deskripsi: '', file: null });
  const [kategori, setKategori] = useState([]);
  const [currentFoto, setCurrentFoto] = useState(null); // foto pertama galeri (kalau ada)
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }
  }, [navigate]);

  useEffect(() => {
    const loadInitial = async () => {
      try {
        const [katRes, galRes] = await Promise.all([
          fetch(`${API_BASE}/kategori`),
          fetch(`${API_BASE}/galeri/${id}`),
        ]);
        const katData = await katRes.json();
        const galData = await galRes.json();
        setKategori(Array.isArray(katData) ? katData : []);
        // Ambil foto aktif terakhir (sesuai yang dipakai Dashboard sebagai thumbnail)
        const fotos = Array.isArray(galData.foto) ? galData.foto.filter(f => f && f.status !== 0) : [];
        const latestFoto = fotos.length > 0 ? fotos[fotos.length - 1] : null;
        setCurrentFoto(latestFoto);
        setForm({
          judul: galData.nama || '',
          kategori_id: galData.kategori_id || '',
          deskripsi: galData.deskripsi || '',
          file: null,
        });
      } catch (e) {
        console.error('Gagal memuat data galeri', e);
      }
    };
    loadInitial();
  }, [id]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      setSaving(true);
      const res = await fetch(`${API_BASE}/galeri/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          nama: form.judul,
          kategori_id: form.kategori_id,
          deskripsi: form.deskripsi || null,
        }),
      });
      if (!res.ok) {
        const txt = await res.text();
        throw new Error(`Gagal mengupdate galeri (${res.status}): ${txt}`);
      }
      // Jika ada file baru, kita selalu buat via POST /api/foto.
      // Backend akan menghapus foto-foto lama galeri ini dan menyisakan hanya foto baru.
      if (form.file) {
        const fdNew = new FormData();
        fdNew.append('galeri_id', id);
        fdNew.append('judul', form.judul || (currentFoto?.judul ?? 'Foto'));
        if (form.deskripsi || currentFoto?.deskripsi) {
          fdNew.append('deskripsi', form.deskripsi || currentFoto?.deskripsi || '');
        }
        fdNew.append('file', form.file);
        fdNew.append('alt_text', form.judul || currentFoto?.alt_text || 'Foto');
        fdNew.append('urutan', String(currentFoto?.urutan ?? 0));
        fdNew.append('status', currentFoto?.status ? '1' : '0');

        const resFotoCreate = await fetch(`${API_BASE}/foto`, { method: 'POST', body: fdNew });
        if (!resFotoCreate.ok) {
          const txt = await resFotoCreate.text();
          throw new Error(`Galeri terupdate, tapi gagal mengunggah foto baru (${resFotoCreate.status}): ${txt}`);
        }
      }

      alert('Galeri berhasil diperbarui');
      navigate('/admin/dashboard');
      // Paksa Dashboard memuat ulang data galeri terbaru (menghindari state lama)
      if (typeof window !== 'undefined') {
        window.location.reload();
      }
    } catch (err) {
      alert(err.message || 'Gagal memperbarui galeri');
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="tambah-page-wrapper">
      <div className="tambah-card">
        <h1 className="tambah-title">Edit Galeri</h1>
        <p className="tambah-subtitle">Perbarui informasi galeri dan tambahkan foto baru jika diperlukan.</p>
        <form onSubmit={handleSubmit} className="tambah-form">
          <div className="form-group">
            <label className="form-label">Judul Galeri</label>
            <div className="input-group">
              <input
                className="form-input"
                placeholder="Judul"
                value={form.judul}
                onChange={(e) => setForm({ ...form, judul: e.target.value })}
              />
            </div>
          </div>
          <div className="form-group">
            <label className="form-label">Kategori</label>
            <div className="input-group">
              <select
                className="form-input"
                value={form.kategori_id}
                onChange={(e) => setForm({ ...form, kategori_id: e.target.value })}
              >
                <option value="">Pilih kategori...</option>
                {kategori.map((k) => (
                  <option key={k.id} value={k.id}>
                    {k.nama || k.name || k.title || `Kategori ${k.id}`}
                  </option>
                ))}
              </select>
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
          <div className="form-group">
            <label className="form-label">Tambah Foto Baru (opsional)</label>
            <div className="file-upload">
              <label className="file-upload-label">
                <input
                  type="file"
                  className="file-upload-input"
                  accept="image/*"
                  onChange={(e) => setForm({ ...form, file: e.target.files[0] })}
                />
                <div className="file-upload-content">
                  {form.file ? (
                    <>
                      <img
                        src={URL.createObjectURL(form.file)}
                        alt="Preview"
                        className="file-upload-preview"
                      />
                      <span className="file-upload-name">{form.file.name}</span>
                    </>
                  ) : (
                    <>
                      <span className="text-sm text-gray-500">Klik untuk unggah gambar</span>
                      <span className="text-xs text-gray-400 mt-1">JPG/PNG maks 8MB</span>
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
              onClick={() => navigate('/admin/dashboard')}
            >
              Batal
            </button>
            <button
              type="submit"
              className="btn btn-primary"
              disabled={saving || !form.judul || !form.kategori_id}
            >
              {saving ? 'Menyimpan...' : 'Simpan Perubahan'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default EditGaleri;
