import React from 'react';
import { useNavigate } from 'react-router-dom';
import '../styles/Account.css';

export default function UserAccount() {
  const navigate = useNavigate();
  const [profile, setProfile] = React.useState(null);
  const [likedCount, setLikedCount] = React.useState(0);
  const [commentCount, setCommentCount] = React.useState(0);
  const [downloadCount, setDownloadCount] = React.useState(0);

  React.useEffect(() => {
    try {
      const p = JSON.parse(localStorage.getItem('userProfile') || '{}');
      setProfile(p);
    } catch (_) {
      setProfile(null);
    }
    try {
      const raw = localStorage.getItem('userLikedPhotoIds') || '[]';
      const arr = JSON.parse(raw);
      setLikedCount(Array.isArray(arr) ? arr.length : 0);
    } catch (_) {
      setLikedCount(0);
    }
    try {
      const cRaw = localStorage.getItem('userCommentCount') || '0';
      setCommentCount(Number(cRaw) || 0);
    } catch (_) {
      setCommentCount(0);
    }
    try {
      const dRaw = localStorage.getItem('userDownloadCount') || '0';
      setDownloadCount(Number(dRaw) || 0);
    } catch (_) {
      setDownloadCount(0);
    }
  }, []);

  const logout = () => {
    localStorage.removeItem('userToken');
    localStorage.removeItem('userProfile');
    navigate('/');
  };

  return (
    <div className="account-page">
      <div className="account-card">
        <div className="account-main">
          <h2>Akun Saya</h2>
          {profile ? (
            <div className="account-info">
              <div><strong>Nama:</strong> {profile.name || '-'}</div>
              <div><strong>Email:</strong> {profile.email || '-'}</div>
            </div>
          ) : (
            <div>Profil tidak tersedia.</div>
          )}
          <div className="account-actions">
            <button onClick={()=>navigate('/gallery')} className="btn-secondary">Ke Galeri</button>
            <button onClick={logout} className="auth-primary">Logout</button>
          </div>
        </div>
        <div className="account-stats">
          <div className="account-stat-card">
            <h3><span className="stat-icon">❤</span> Galeri yang disukai</h3>
            <div className="stat-value">{likedCount}</div>
          </div>
          <div className="account-stat-card">
            <h3><span className="stat-icon">💬</span> Komentar dikirim</h3>
            <div className="stat-value">{commentCount}</div>
          </div>
          <div className="account-stat-card">
            <h3><span className="stat-icon">⬇</span> Foto diunduh</h3>
            <div className="stat-value">{downloadCount}</div>
          </div>
        </div>
      </div>
    </div>
  );
}
