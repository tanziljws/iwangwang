import React from 'react';
import '../styles/Auth.css';
import { useNavigate, Link } from 'react-router-dom';

const API_BASE = (import.meta?.env?.VITE_API_BASE || 'http://localhost:8000/api').replace(/\/$/, '');
const SITE_KEY = import.meta?.env?.VITE_RECAPTCHA_SITE_KEY || '';

export default function UserRegister() {
  const [name, setName] = React.useState('');
  const [email, setEmail] = React.useState('');
  const [password, setPassword] = React.useState('');
  const [confirm, setConfirm] = React.useState('');
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
    if (password !== confirm) {
      setError('Konfirmasi password tidak cocok');
      return;
    }
    setLoading(true);
    try {
      let recaptcha_token = '';
      if (SITE_KEY && window.grecaptcha && captchaReady) {
        recaptcha_token = await window.grecaptcha.execute(SITE_KEY, { action: 'register' });
      }
      const res = await fetch(`${API_BASE}/user/register`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ name, email, password, password_confirmation: confirm, recaptcha_token })
      });
      const data = await res.json().catch(()=>({}));
      if (!res.ok) {
        setError(data?.message || 'Registrasi gagal');
      } else {
        // Registrasi berhasil, arahkan ke halaman login
        navigate('/login');
      }
    } catch (e) {
      setError('Terjadi kesalahan');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page" style={{display:'grid', placeItems:'center', padding:'48px 16px'}}>
      <form onSubmit={onSubmit} className="auth-card" style={{width:'100%', maxWidth:820, background:'#fff', border:'1px solid #e5e7eb', borderRadius:16, padding:0, boxShadow:'0 18px 40px rgba(15,23,42,0.08)'}}>
        <div className="auth-split">
          <div className="auth-split-left">
            <div className="auth-logo-img">
              <img src="/images/smkn4.jpg" alt="Logo SMKN 4" />
            </div>
            <div className="auth-brand">SMKN 4 KOTA BOGOR</div>
            <div className="auth-welcome-text">
              <h2>Daftar akun baru</h2>
              <p>Buat akunmu agar bisa memberi like, komentar, dan mengunduh foto galeri sekolah.</p>
            </div>
          </div>
          <div className="auth-split-right">
            <h3 style={{margin:'0 0 12px'}}>Daftar</h3>
            <p className="auth-subtle" style={{margin:'0 0 18px', fontSize:'.9rem'}}>Isi data di bawah ini dengan benar.</p>
            {error && <div style={{color:'#ef4444', marginBottom:12}}>{error}</div>}
            <div style={{display:'grid', gap:10}}>
              <input className="auth-input" type="text" value={name} onChange={(e)=>setName(e.target.value)} placeholder="Nama" required />
              <input className="auth-input" type="email" value={email} onChange={(e)=>setEmail(e.target.value)} placeholder="Email" required />
              <input className="auth-input" type="password" value={password} onChange={(e)=>setPassword(e.target.value)} placeholder="Password" required />
              <input className="auth-input" type="password" value={confirm} onChange={(e)=>setConfirm(e.target.value)} placeholder="Konfirmasi Password" required />
              <button type="submit" disabled={loading} className="auth-primary">{loading ? 'Memproses...' : 'Daftar'}</button>
              <div style={{textAlign:'center', fontSize:'.9rem'}}>
                Sudah punya akun? <Link to="/login" className="auth-link">Login</Link>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  );
}
