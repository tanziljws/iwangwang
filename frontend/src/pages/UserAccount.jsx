import React from 'react';
import { useNavigate } from 'react-router-dom';

export default function UserAccount() {
  const navigate = useNavigate();
  const [profile, setProfile] = React.useState(null);

  React.useEffect(() => {
    try {
      const p = JSON.parse(localStorage.getItem('userProfile') || '{}');
      setProfile(p);
    } catch (_) {
      setProfile(null);
    }
  }, []);

  const logout = () => {
    localStorage.removeItem('userToken');
    localStorage.removeItem('userProfile');
    navigate('/');
  };

  return (
    <div style={{display:'grid', placeItems:'center', padding:'48px 16px'}}>
      <div style={{width:'100%', maxWidth:520, background:'#fff', border:'1px solid #e5e7eb', borderRadius:12, padding:24, boxShadow:'0 10px 24px rgba(15,23,42,0.06)'}}>
        <h2 style={{marginTop:0}}>Akun Saya</h2>
        {profile ? (
          <div style={{lineHeight:1.7}}>
            <div><strong>Nama:</strong> {profile.name || '-'}</div>
            <div><strong>Email:</strong> {profile.email || '-'}</div>
          </div>
        ) : (
          <div>Profil tidak tersedia.</div>
        )}
        <div style={{display:'flex', gap:8, marginTop:16}}>
          <button onClick={()=>navigate('/gallery')} className="btn-secondary">Ke Galeri</button>
          <button onClick={logout} className="auth-primary">Logout</button>
        </div>
      </div>
    </div>
  );
}
