import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './AddBerita.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');

const AddBerita = () => {
  const [form, setForm] = useState({
    title: '',
    category: '',
    author: '',
    excerpt: '',
    content: '',
    published_at: '',
    status: 'published',
    cover_image: null,
  });
  const [preview, setPreview] = useState('');
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
    }
  }, [navigate]);

  const handleChange = (field, value) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const handleFileChange = (file) => {
    setForm(prev => ({ ...prev, cover_image: file }));
    setPreview(file ? URL.createObjectURL(file) : '');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }

    try {
      setSaving(true);
      const data = new FormData();
      data.append('title', form.title);
      if (form.category) data.append('category', form.category);
      if (form.author) data.append('author', form.author);
      if (form.excerpt) data.append('excerpt', form.excerpt);
      data.append('content', form.content);
      if (form.published_at) data.append('published_at', form.published_at);
      if (form.status) data.append('status', form.status);
      if (form.cover_image instanceof File) data.append('cover_image', form.cover_image);

      const res = await fetch(`${API_BASE}/berita`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: data,
      });

      if (!res.ok) {
        const result = await res.json().catch(() => ({}));
        throw new Error(result.message || 'Gagal menambahkan berita');
      }

      setSuccess('Berita berhasil disimpan');
      setTimeout(() => {
        navigate('/admin/dashboard');
      }, 800);
    } catch (err) {
      setError(err.message || 'Terjadi kesalahan');
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="add-berita-container">
      <div className="add-berita-card">
        <div className="add-berita-header">
          <div>
            <p className="berita-breadcrumb" onClick={() => navigate('/admin/dashboard')}>
              &larr; Kembali ke Dashboard
            </p>
            <h1>Tambah Berita Sekolah</h1>
            <p>Isi detail berita untuk ditampilkan pada halaman publik.</p>
          </div>
        </div>

        <form className="add-berita-form" onSubmit={handleSubmit}>
          {error && <div className="alert error">{error}</div>}
          {success && <div className="alert success">{success}</div>}

          <div className="form-grid">
            <div className="form-group full">
              <label>Judul Berita</label>
              <input
                type="text"
                value={form.title}
                onChange={(e) => handleChange('title', e.target.value)}
                placeholder="Contoh: SMKN 4 Raih Juara Nasional"
                required
              />
            </div>

            <div className="form-group">
              <label>Kategori</label>
              <input
                type="text"
                value={form.category}
                onChange={(e) => handleChange('category', e.target.value)}
                placeholder="Prestasi, Kerjasama, dsb"
              />
            </div>

            <div className="form-group">
              <label>Penulis</label>
              <input
                type="text"
                value={form.author}
                onChange={(e) => handleChange('author', e.target.value)}
                placeholder="Admin Sekolah"
              />
            </div>

            <div className="form-group">
              <label>Tanggal Terbit</label>
              <input
                type="date"
                value={form.published_at}
                onChange={(e) => handleChange('published_at', e.target.value)}
              />
            </div>

            <div className="form-group">
              <label>Status</label>
              <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>
            </div>

            <div className="form-group full">
              <label>Ringkasan</label>
              <textarea
                rows={3}
                value={form.excerpt}
                onChange={(e) => handleChange('excerpt', e.target.value)}
                placeholder="Deskripsi singkat berita..."
              ></textarea>
            </div>

            <div className="form-group full">
              <label>Isi Berita</label>
              <textarea
                rows={8}
                value={form.content}
                onChange={(e) => handleChange('content', e.target.value)}
                placeholder="Tulis isi berita lengkap di sini..."
                required
              ></textarea>
            </div>

            <div className="form-group full">
              <label>Cover Image (opsional)</label>
              <div className="file-field">
                <input
                  type="file"
                  accept="image/*"
                  id="berita-cover"
                  onChange={(e) => handleFileChange(e.target.files?.[0])}
                />
                <label htmlFor="berita-cover" className="file-label">
                  Unggah Gambar
                </label>
                {preview && (
                  <div className="preview">
                    <img src={preview} alt="Preview" />
                  </div>
                )}
              </div>
            </div>
          </div>

          <div className="form-actions">
            <button type="button" className="btn-secondary" onClick={() => navigate('/admin/dashboard')}>
              Batal
            </button>
            <button type="submit" className="btn-primary" disabled={saving || !form.title || !form.content}>
              {saving ? 'Menyimpan...' : 'Simpan Berita'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AddBerita;
