import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../styles/Admin.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    
    try {
      const response = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
          email, 
          password,
          device_name: 'web-admin' // You can customize this
        })
      });
      
      const data = await response.json();
      
      if (response.ok) {
        // Save the token and user data
        localStorage.setItem('adminToken', data.token);
        localStorage.setItem('adminUser', JSON.stringify(data.user));
        
        // Redirect to dashboard
        navigate('/admin/dashboard');
      } else {
        setError(data.message || 'Login gagal. Periksa email dan password Anda.');
      }
    } catch (err) {
      setError('An error occurred. Please try again.');
    }
  };

  return (
    <div className="admin-auth-container">
      <div className="admin-auth-box">
        <h2>Admin Login</h2>
        {error && <div className="error-message">{error}</div>}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <button type="submit" className="btn-primary">Login</button>
        </form>
        <p className="auth-link">
          Don't have an account? <span onClick={() => navigate('/admin/register')}>Register here</span>
        </p>
      </div>
    </div>
  );
};

export default Login;
