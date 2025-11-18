import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../styles/Admin.css';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    jabatan: 'admin'
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    
    if (formData.password !== formData.confirmPassword) {
      setError('Password dan konfirmasi password tidak cocok');
      return;
    }
    
    try {
      const response = await fetch('http://localhost:8000/api/auth/register', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          nama_petugas: formData.name,
          username: formData.email.split('@')[0], // Generate username from email
          email: formData.email,
          password: formData.password,
          password_confirmation: formData.confirmPassword,
          no_hp: '',
          jabatan: formData.jabatan || 'admin',
          status: 'aktif'
        }),
      });
      
      const data = await response.json();
      
      if (response.ok) {
        setSuccess(true);
        setTimeout(() => {
          navigate('/admin/login');
        }, 2000);
      } else {
        console.log('Registration error:', data); // Log error response
        if (data.errors) {
          // Jika ada multiple errors, gabungkan menjadi satu string
          const errorMessages = Object.values(data.errors).flat().join(' ');
          setError(errorMessages || 'Registrasi gagal. Silakan periksa data yang dimasukkan.');
        } else {
          setError(data.message || 'Registrasi gagal. Silakan coba lagi.');
        }
      }
    } catch (err) {
      setError('An error occurred. Please try again.');
    }
  };

  if (success) {
    return (
      <div className="admin-auth-container">
        <div className="admin-auth-card success">
          <div className="admin-auth-logo" aria-hidden="true">
            <img src="/images/smkn4.jpg" alt="Logo SMKN 4" />
          </div>
          <h2>Pendaftaran berhasil!</h2>
          <p>Anda akan diarahkan ke halaman login sebentar lagi...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="admin-auth-container">
      <div className="admin-auth-card">
        <div className="admin-auth-logo" aria-hidden="true">
          <img src="/images/smkn4.jpg" alt="Logo SMKN 4" />
        </div>
        <h2 className="admin-auth-title">Buat Akun Admin</h2>
        <p className="admin-auth-subtitle">Isi data berikut untuk mengaktifkan akun admin sekolah.</p>
        {error && <div className="error-message">{error}</div>}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>Nama lengkap</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label>Email</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              minLength="6"
              required
            />
          </div>
          <div className="form-group">
            <label>Konfirmasi password</label>
            <input
              type="password"
              name="confirmPassword"
              value={formData.confirmPassword}
              onChange={handleChange}
              minLength="6"
              required
            />
          </div>
          <div className="form-group">
            <label>Jabatan</label>
            <input
              type="text"
              name="jabatan"
              value={formData.jabatan}
              onChange={handleChange}
              placeholder="Contoh: Admin Galeri"
              required
            />
          </div>
          <button type="submit" className="btn-primary">Daftar</button>
        </form>
        <div className="auth-link">
          <span>Sudah punya akun?</span>{' '}
          <button type="button" onClick={() => navigate('/admin/login')}>
            Masuk di sini
          </button>
        </div>
      </div>
    </div>
  );
};

export default Register;
