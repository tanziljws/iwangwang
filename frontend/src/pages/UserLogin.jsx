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
  const [forgotMessage, setForgotMessage] = React.useState('');
  const [forgotError, setForgotError] = React.useState('');
  const [forgotLoading, setForgotLoading] = React.useState(false);
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
        navigate('/account');
      }
    } catch (e) {
      setError('Terjadi kesalahan');
    } finally {
      setLoading(false);
    }
  };

  const handleForgotPassword = async () => {
    setForgotMessage('');
    setForgotError('');
    if (!email) {
      setForgotError('Masukkan email akun terlebih dahulu agar admin bisa membantu.');
      return;
    }
    setForgotLoading(true);
    try {
      const res = await fetch(`${API_BASE}/user/forgot-password`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email })
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        throw new Error(data?.message || 'Gagal mengirim permintaan reset');
      }
      setForgotMessage(data?.message || 'Permintaan reset sudah diteruskan ke admin.');
    } catch (err) {
      setForgotError(err.message || 'Terjadi kesalahan. Silakan coba lagi.');
    } finally {
      setForgotLoading(false);
    }
  };

  return (
    <div className="auth-page" style={{display:'grid', placeItems:'center', padding:'48px 16px'}}>
      <form onSubmit={onSubmit} className="auth-card" style={{width:'100%', maxWidth:820, background:'#fff', border:'1px solid #e5e7eb', borderRadius:16, padding:0, boxShadow:'0 18px 40px rgba(15,23,42,0.08)'}}>
        <div className="auth-split">
          <div className="auth-split-left">
            <div className="auth-back-link-wrapper">
              <Link to="/" className="auth-link auth-back-link">
                ← Beranda
              </Link>
            </div>
            <div className="auth-logo-img">
              <img src="/images/smkn4.jpg" alt="Logo SMKN 4" />
            </div>
            <div className="auth-brand">SMKN 4 KOTA BOGOR</div>
            <div className="auth-welcome-text">
              <h2>Halo, selamat datang!</h2>
              <p>Masuk ke akunmu untuk memberi like, komentar, dan mengunduh foto galeri sekolah.</p>
            </div>
          </div>
          <div className="auth-split-right">
            <h3 style={{margin:'0 0 12px'}}>Login</h3>
            <p className="auth-subtle" style={{margin:'0 0 18px', fontSize:'.9rem'}}>Gunakan email dan password yang sudah terdaftar.</p>
            {error && <div style={{color:'#ef4444', marginBottom:12}}>{error}</div>}
            <div style={{display:'grid', gap:10}}>
              <input className="auth-input" type="email" value={email} onChange={(e)=>setEmail(e.target.value)} placeholder="Email" required />
              <input className="auth-input" type="password" value={password} onChange={(e)=>setPassword(e.target.value)} placeholder="Password" required />
              <button
                type="button"
                className="auth-link auth-forgot-link"
                onClick={handleForgotPassword}
                disabled={forgotLoading}
              >
                {forgotLoading ? 'Memproses permintaan...' : 'Lupa password?'}
              </button>
              {(forgotMessage || forgotError) && (
                <div className={`auth-forgot-info ${forgotError ? 'error' : 'success'}`}>
                  {forgotError || forgotMessage}
                </div>
              )}
              <button type="submit" disabled={loading} className="auth-primary">{loading ? 'Memproses...' : 'Login'}</button>
              <div style={{textAlign:'center', fontSize:'.9rem'}}>
                Belum punya akun? <Link to="/register" className="auth-link">Daftar</Link>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  );
}
