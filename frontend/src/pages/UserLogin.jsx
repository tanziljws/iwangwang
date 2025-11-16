import React from 'react';
import '../styles/Auth.css';
import { useNavigate, Link } from 'react-router-dom';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
const SITE_KEY = import.meta?.env?.VITE_RECAPTCHA_SITE_KEY || '';

export default function UserLogin() {
  const [email, setEmail] = React.useState('');
  const [password, setPassword] = React.useState('');
  const [loading, setLoading] = React.useState(false);
  const [error, setError] = React.useState('');
  const navigate = useNavigate();
  const [captchaReady, setCaptchaReady] = React.useState(false);

  React.useEffect(() => {
    if (!SITE_KEY) { setCaptchaReady(true); return; }
    if (window.grecaptcha) { setCaptchaReady(true); return; }
    const id = 'recaptcha-script';
    if (document.getElementById(id)) { setCaptchaReady(true); return; }
    const s = document.createElement('script');
    s.id = id;
    s.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(SITE_KEY)}`;
    s.async = true;
    s.onload = () => setCaptchaReady(true);
    document.body.appendChild(s);
  }, []);

  const onSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      let recaptcha_token = '';
      if (SITE_KEY && window.grecaptcha && captchaReady) {
        recaptcha_token = await window.grecaptcha.execute(SITE_KEY, { action: 'login' });
      }
      const res = await fetch(`${API_BASE}/user/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email, password, device_name: 'web', recaptcha_token })
      });
      const data = await res.json().catch(()=>({}));
      if (!res.ok) {
        setError(data?.message || 'Login gagal');
      } else {
        localStorage.setItem('userToken', data.token);
        localStorage.setItem('userProfile', JSON.stringify(data.user || {}));
        window.dispatchEvent(new Event('auth-changed'));
        navigate(-1);
      }
    } catch (e) {
      setError('Terjadi kesalahan');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page" style={{display:'grid', placeItems:'center', padding:'48px 16px'}}>
      <form onSubmit={onSubmit} className="auth-card" style={{width:'100%', maxWidth:420, background:'#fff', border:'1px solid #e5e7eb', borderRadius:12, padding:20, boxShadow:'0 10px 24px rgba(15,23,42,0.06)'}}>
        <h2 style={{margin:'0 0 12px'}}>Login</h2>
        <p className="auth-subtle" style={{margin:'0 0 18px'}}>Masuk untuk memberi like, komentar, dan unduh foto.</p>
        {error && <div style={{color:'#ef4444', marginBottom:12}}>{error}</div>}
        <div style={{display:'grid', gap:10}}>
          <input className="auth-input" type="email" value={email} onChange={(e)=>setEmail(e.target.value)} placeholder="Email" required />
          <input className="auth-input" type="password" value={password} onChange={(e)=>setPassword(e.target.value)} placeholder="Password" required />
          <button type="submit" disabled={loading} className="auth-primary">{loading ? 'Memproses...' : 'Login'}</button>
          <div style={{textAlign:'center'}}>
            Belum punya akun? <Link to="/register" className="auth-link">Daftar</Link>
          </div>
        </div>
      </form>
    </div>
  );
}
