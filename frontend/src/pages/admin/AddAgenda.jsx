import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './AddAgenda.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');

const AddAgenda = () => {
  const [form, setForm] = useState({
    title: '',
    description: '',
    date: '',
    time: '',
    location: '',
  });
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

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');;;;;;
    setSuccess('');
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }

    try {
      setSaving(true);
      const payload = {
        title: form.title,
        description: form.description || '',
        date: form.date,
        time: form.time || '',
        location: form.location || '',
      };

      const res = await fetch(`${API_BASE}/agendas`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const result = await res.json().catch(() => ({}));
        throw new Error(result.message || 'Gagal menambahkan agenda');
      }

      setSuccess('Agenda berhasil ditambahkan');
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
    <div className="add-agenda-container">
      <div className="add-agenda-card">
        <div className="add-agenda-header">
          <div>
            <p className="breadcrumb" onClick={() => navigate('/admin/dashboard')}>
              &larr; Kembali ke Dashboard
            </p>
            <h1>Tambah Agenda Sekolah</h1>
            <p>Isi detail agenda untuk ditampilkan pada halaman publik.</p>
          </div>
        </div>

        <form className="add-agenda-form" onSubmit={handleSubmit}>
          {error && <div className="alert error">{error}</div>}
          {success && <div className="alert success">{success}</div>}

          <div className="form-grid">
            <div className="form-group full">
              <label>Judul Agenda</label>
              <input
                type="text"
                value={form.title}
                onChange={(e) => handleChange('title', e.target.value)}
                placeholder="Contoh: Rapat Orang Tua Siswa"
                required
              />
            </div>

            <div className="form-group">
              <label>Tanggal</label>
              <input
                type="date"
                value={form.date}
                onChange={(e) => handleChange('date', e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label>Waktu (opsional)</label>
              <input
                type="time"
                value={form.time}
                onChange={(e) => handleChange('time', e.target.value)}
              />
            </div>

            <div className="form-group full">
              <label>Lokasi</label>
              <input
                type="text"
                value={form.location}
                onChange={(e) => handleChange('location', e.target.value)}
                placeholder="Contoh: Aula SMKN 4"
              />
            </div>

            <div className="form-group full">
              <label>Deskripsi Agenda</label>
              <textarea
                rows={5}
                value={form.description}
                onChange={(e) => handleChange('description', e.target.value)}
                placeholder="Tuliskan detail agenda..."
              ></textarea>
            </div>
          </div>

          <div className="form-actions">
            <button
              type="button"
              className="btn-secondary"
              onClick={() => navigate('/admin/dashboard')}
            >
              Batal
            </button>
            <button type="submit" className="btn-primary" disabled={saving || !form.title || !form.date}>
              {saving ? 'Menyimpan...' : 'Simpan Agenda'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AddAgenda;
