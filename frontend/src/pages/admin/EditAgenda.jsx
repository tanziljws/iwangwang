import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import './EditAgenda.css';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
const EditAgenda = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    title: '',
    description: '',
    date: '',
    time: '',
    location: '',
  });
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }
    const fetchAgenda = async () => {
      try {
        const res = await fetch(`${API_BASE}/agendas/${id}`);
        if (!res.ok) throw new Error('Agenda tidak ditemukan');
        const data = await res.json();
        setForm({
          title: data.title || '',
          description: data.description || '',
          date: data.date ? data.date.substring(0, 10) : '',
          time: data.time || '',
          location: data.location || '',
        });
      } catch (e) {
        setError(e.message || 'Gagal memuat agenda');
      } finally {
        setLoading(false);
      }
    };
    fetchAgenda();
  }, [id, navigate]);

  const handleChange = (field, value) => {
    setForm((prev) => ({ ...prev, [field]: value }));
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
      const payload = {
        title: form.title,
        description: form.description || '',
        date: form.date || '',
        time: form.time || '',
        location: form.location || '',
      };

      const res = await fetch(`${API_BASE}/agendas/${id}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      if (!res.ok) {
        const result = await res.json().catch(() => ({}));
        throw new Error(result.message || 'Gagal memperbarui agenda');
      }

      setSuccess('Agenda berhasil diperbarui');
      setTimeout(() => {
        navigate('/admin/dashboard');
      }, 800);
    } catch (err) {
      setError(err.message || 'Terjadi kesalahan');
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="edit-agenda-container"><div className="edit-agenda-card">Memuat data agenda...</div></div>;
  }

  return (
    <div className="edit-agenda-container">
      <div className="edit-agenda-card">
        <div className="edit-agenda-header">
          <div>
            <p className="agenda-breadcrumb" onClick={() => navigate('/admin/dashboard')}>
              &larr; Kembali ke Dashboard
            </p>
            <h1>Edit Agenda</h1>
            <p>Perbarui informasi agenda sekolah.</p>
          </div>
        </div>

        <form className="edit-agenda-form" onSubmit={handleSubmit}>
          {error && <div className="alert error">{error}</div>}
          {success && <div className="alert success">{success}</div>}

          <div className="form-grid">
            <div className="form-group full">
              <label>Judul Agenda</label>
              <input
                type="text"
                value={form.title}
                onChange={(e) => handleChange('title', e.target.value)}
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
                placeholder="Masukkan lokasi acara"
              />
            </div>

            <div className="form-group full">
              <label>Deskripsi</label>
              <textarea
                rows={5}
                value={form.description}
                onChange={(e) => handleChange('description', e.target.value)}
                placeholder="Masukkan deskripsi agenda..."
              ></textarea>
            </div>
          </div>

          <div className="form-actions">
            <button type="button" className="btn-secondary" onClick={() => navigate('/admin/dashboard')}>
              Batal
            </button>
            <button type="submit" className="btn-primary" disabled={saving || !form.title || !form.date}>
              {saving ? 'Menyimpan...' : 'Update Agenda'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default EditAgenda;
