import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../styles/Admin.css';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: ''
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
          jabatan: 'admin',
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
        <div className="admin-auth-box success">
          <h2>Registration Successful!</h2>
          <p>You will be redirected to the login page shortly...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="admin-auth-container">
      <div className="admin-auth-box">
        <h2>Admin Registration</h2>
        {error && <div className="error-message">{error}</div>}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>Full Name</label>
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
            <label>Confirm Password</label>
            <input
              type="password"
              name="confirmPassword"
              value={formData.confirmPassword}
              onChange={handleChange}
              minLength="6"
              required
            />
          </div>
          <button type="submit" className="btn-primary">Register</button>
        </form>
        <p className="auth-link">
          Already have an account? <span onClick={() => navigate('/admin/login')}>Login here</span>
        </p>
      </div>
    </div>
  );
};

export default Register;
