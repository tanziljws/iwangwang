import { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Image, Newspaper, Calendar, Plus, Edit, Trash2, Search, LogOut, Menu, X, User } from 'lucide-react';
import './Dashboard.css';

const Dashboard = () => {
  const [activeTab, setActiveTab] = useState('galeri');
  const [showAddModal, setShowAddModal] = useState(false);
  const [modalType, setModalType] = useState('galeri'); // 'kategori' | 'galeri' | 'foto'
  const [newItem, setNewItem] = useState({ title: '', description: '', image: null });
  const [saving, setSaving] = useState(false);
  const navigate = useNavigate();
  
  // Data galeri
  const [galeri, setGaleri] = useState([]);
  
  const [berita, setBerita] = useState([
    { id: 1, title: 'Penerimaan Siswa Baru 2024', date: '2023-11-15', excerpt: 'Pendaftaran dibuka mulai 1 Januari 2024' },
    { id: 2, title: 'Workshop Teknologi Terkini', date: '2023-11-05', excerpt: 'Untuk seluruh siswa jurusan Teknik Komputer' },
  ]);
  
  const [agenda, setAgenda] = useState([
    { id: 1, title: 'Rapat Orang Tua', date: '2023-12-10', time: '09:00', location: 'Aula Sekolah' },
    { id: 2, title: 'Ujian Akhir Semester', date: '2023-12-15', time: '07:30', location: 'Ruang Kelas' },
  ]);

  // Sidebar greeting uses static label to indicate admin

  // Check if user is authenticated
  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      navigate('/admin/login');
      return;
    }
  }, [navigate]);

  // API base config: untuk menghindari salah konfigurasi env,
  // kita pakai base tetap ke Laravel lokal.
  const API_BASE = 'http://localhost:8000/api';
  const ORIGIN_BASE = 'http://localhost:8000';

  // Kategori list (dipakai untuk select & tab Kategori)
  const [kategori, setKategori] = useState([]);
  const loadKategori = async () => {
    try {
      const res = await fetch(`${API_BASE}/kategori`);
      const data = await res.json();
      setKategori(Array.isArray(data) ? data : []);
    } catch {}
  };

  // Galeri options for Foto form
  const [galeriOptions, setGaleriOptions] = useState([]);
  const loadGaleriOptions = async () => {
    try {
      const res = await fetch(`${API_BASE}/galeri`);
      const data = await res.json();
      setGaleriOptions(Array.isArray(data) ? data : []);
    } catch {}
  };

  // Form states for modalType
  const [katForm, setKatForm] = useState({ nama: '', deskripsi: '' });
  const [galForm, setGalForm] = useState({ judul: '', kategori_id: '', deskripsi: '', file: null });
  const [fotoForm, setFotoForm] = useState({ galeri_id: '', judul: '', deskripsi: '', file: null });

  const handleLogout = () => {
    localStorage.removeItem('adminToken');
    localStorage.removeItem('adminUser');
    navigate('/admin/login');
  };

  // Quick-add handlers connected to backend
  const createKategori = async (nama, deskripsi = '') => {
    const res = await fetch(`${API_BASE}/kategori`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nama, deskripsi })
    });
    if (!res.ok) throw new Error('Gagal menambah kategori');
    await loadKategori();
  };

  const createGaleri = async ({ judul, kategori_id, deskripsi }) => {
    const p = await fetch(`${API_BASE}/post`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ judul, kategori_id, isi: deskripsi || '-', petugas_id: 1, status: 'published' })
    });
    if (!p.ok) throw new Error('Gagal membuat post');
    const post = await p.json();
    const g = await fetch(`${API_BASE}/galery`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ post_id: post.id, position: 0, status: true })
    });
    if (!g.ok) throw new Error('Gagal membuat galeri');
    return await g.json();
  };

  const uploadFoto = async ({ galeri_id, judul, deskripsi, file }) => {
    const fd = new FormData();
    fd.append('galeri_id', galeri_id);
    fd.append('judul', judul || 'Foto');
    if (deskripsi) fd.append('deskripsi', deskripsi);
    fd.append('file', file);
    fd.append('alt_text', judul || 'Foto');
    fd.append('urutan', '0');
    const r = await fetch(`${API_BASE}/foto`, { method: 'POST', body: fd });
    if (!r.ok) throw new Error('Gagal mengunggah foto');
  };

  const deleteKategori = async (id) => {
    const res = await fetch(`${API_BASE}/kategori/${id}`, { method: 'DELETE' });
    if (!res.ok) {
      throw new Error('Gagal menghapus kategori');
    }
  };

  const deleteGaleri = async (id) => {
    const res = await fetch(`${API_BASE}/galeri/${id}`, { method: 'DELETE' });
    if (!res.ok) {
      throw new Error('Gagal menghapus galeri');
    }
  };
  
  const handleAddItem = () => {
    // In a real app, this would make an API call to add the item
    if (activeTab === 'galeri') {
      const newGaleriItem = {
        id: Date.now(),
        title: newItem.title,
        image: URL.createObjectURL(newItem.image),
        date: new Date().toISOString().split('T')[0]
      };
      setGaleri([...galeri, newGaleriItem]);
    } else if (activeTab === 'berita') {
      const newBeritaItem = {
        id: Date.now(),
        title: newItem.title,
        excerpt: newItem.description,
        date: new Date().toISOString().split('T')[0]
      };
      setBerita([...berita, newBeritaItem]);
    } else if (activeTab === 'agenda') {
      const newAgendaItem = {
        id: Date.now(),
        title: newItem.title,
        date: newItem.date,
        time: newItem.time || '00:00',
        location: newItem.location || ''
      };
      setAgenda([...agenda, newAgendaItem]);
    }
    
    setShowAddModal(false);
    setNewItem({ title: '', description: '', image: null });
  };
  
  const handleDeleteItem = async (id) => {
    if (!window.confirm('Apakah Anda yakin ingin menghapus item ini?')) return;

    try {
      if (activeTab === 'galeri') {
        await deleteGaleri(id);
        setGaleri(galeri.filter(item => item.id !== id));
      } else if (activeTab === 'kategori') {
        await deleteKategori(id);
        setKategori(kategori.filter(item => item.id !== id));
      } else if (activeTab === 'berita') {
        setBerita(berita.filter(item => item.id !== id));
      } else if (activeTab === 'agenda') {
        setAgenda(agenda.filter(item => item.id !== id));
      }
    } catch (e) {
      alert(e.message || 'Gagal menghapus data');
    }
  };
  
  const filteredItems = () => {
    const items = activeTab === 'galeri' ? galeri :
                 activeTab === 'berita' ? berita : agenda;
    return items;
  };

  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 1024);

  // Load kategori & galeri awal
  useEffect(() => {
    loadKategori();
  }, []);

  // Load galeri from backend (uses Galeri API)
  useEffect(() => {
    const load = async () => {
      try {
        const res = await fetch(`${API_BASE}/galeri`);
        if (!res.ok) return;
        const data = await res.json();
        const mapped = (Array.isArray(data) ? data : []).map(g => {
          const fotos = Array.isArray(g?.foto) ? g.foto.filter(f => f && f.status !== 0) : [];
          const main = fotos.length > 0 ? fotos[fotos.length - 1] : null; // pakai foto terbaru
          const version = main?.updated_at || g.updated_at || g.created_at || '';
          const img = main?.file
            ? `${ORIGIN_BASE}/media/foto/${main.file}${version ? `?v=${encodeURIComponent(version)}` : ''}`
            : '';
          return {
            id: g.id,
            title: g.nama || `Galeri ${g.id}`,
            image: img || '/images/placeholder.png',
            date: g.created_at || new Date().toISOString(),
            _raw: g,
          };
        });
        setGaleri(mapped);
      } catch (_) {}
    };
    load();
  }, []);

  // Handle window resize for responsive design
  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 1024);
      if (window.innerWidth >= 1024) {
        setSidebarOpen(true);
      } else {
        setSidebarOpen(false);
      }
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen);
  };

  return (
    <div className={`dashboard ${!sidebarOpen ? 'sidebar-collapsed' : ''}`}>
      {/* Mobile Header */}
      <header className="mobile-header">
        <button className="menu-toggle" onClick={toggleSidebar}>
          {sidebarOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
        <h1>
          {activeTab === 'galeri' && 'Galeri'}
          {activeTab === 'kategori' && 'Kategori'}
          {activeTab === 'berita' && 'Berita'}
          {activeTab === 'agenda' && 'Agenda'}
        </h1>
      </header>

      {/* Sidebar */}
      <aside className={`dashboard-sidebar ${sidebarOpen ? 'open' : ''}`}>
        <div className="sidebar-header">
          <div className="sidebar-brand">
            <img src="/images/smkn4.jpg" alt="SMKN 4" className="sidebar-logo-img" />
            <h2>GALERI SMKN4</h2>
          </div>
        </div>
        <div className="sidebar-stats">
          <div className="sidebar-stat-card">
            <span className="sidebar-stat-label"><User size={16} className="sidebar-stat-icon"/> Halo, admin <span className="wave" aria-hidden="true">👋</span></span>
          </div>
        </div>
        
        <nav className="sidebar-nav">
          <button 
            className={`nav-item`}
            onClick={() => {
              navigate('/admin/statistik');
              if (isMobile) setSidebarOpen(false);
            }}
          >
            <Newspaper className="nav-icon" size={20} />
            <span className="nav-text">Statistik</span>
          </button>
          <button 
            className={`nav-item ${activeTab === 'kategori' ? 'active' : ''}`}
            onClick={() => {
              setActiveTab('kategori');
              if (isMobile) setSidebarOpen(false);
            }}
          >
            <Newspaper className="nav-icon" size={20} />
            <span className="nav-text">Kategori</span>
          </button>
          <button 
            className={`nav-item ${activeTab === 'galeri' ? 'active' : ''}`}
            onClick={() => {
              setActiveTab('galeri');
              if (isMobile) setSidebarOpen(false);
            }}
          >
            <Image className="nav-icon" size={20} />
            <span className="nav-text">Galeri</span>
          </button>
          <button 
            className={`nav-item ${activeTab === 'berita' ? 'active' : ''}`}
            onClick={() => {
              setActiveTab('berita');
              if (isMobile) setSidebarOpen(false);
            }}
          >
            <Newspaper className="nav-icon" size={20} />
            <span className="nav-text">Berita</span>
          </button>
          <button 
            className={`nav-item ${activeTab === 'agenda' ? 'active' : ''}`}
            onClick={() => {
              setActiveTab('agenda');
              if (isMobile) setSidebarOpen(false);
            }}
          >
            <Calendar className="nav-icon" size={20} />
            <span className="nav-text">Agenda</span>
          </button>
        </nav>
        <div className="sidebar-footer">
          <button className="btn btn-logout" onClick={handleLogout}>
            <LogOut size={18} className="mr-2" />
            <span>Keluar</span>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="dashboard-content">
        <div className="content-wrapper">
          <div className="content-header">
            <div className="header-title">
              <h1 className="text-2xl font-bold text-gray-800">
                {activeTab === 'galeri' && 'Kelola Galeri'}
                {activeTab === 'kategori' && 'Kelola Kategori'}
                {activeTab === 'berita' && 'Kelola Berita'}
                {activeTab === 'agenda' && 'Kelola Agenda'}
              </h1>
              <p className="text-sm text-gray-500">
                {activeTab === 'galeri' && 'Kelola koleksi galeri sekolah'}
                {activeTab === 'kategori' && 'Kelola kategori galeri'}
                {activeTab === 'berita' && 'Kelola berita dan pengumuman'}
                {activeTab === 'agenda' && 'Kelola jadwal dan acara sekolah'}
              </p>
            </div>
            <div className="header-actions" />
          </div>

          {/* Content Body */}
          <div className="content-body">
            {activeTab === 'kategori' && (
              <div className="table-container">
                {kategori.length > 0 ? (
                  <div className="table-responsive">
                    <table className="data-table">
                      <thead>
                        <tr>
                          <th>Nama Kategori</th>
                          <th>Deskripsi</th>
                          <th className="text-right">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        {kategori.map(k => (
                          <tr key={k.id} className="hover:bg-gray-50">
                            <td>
                              <div className="font-medium text-gray-900">{k.nama || k.name || `Kategori ${k.id}`}</div>
                            </td>
                            <td>
                              <div className="text-sm text-gray-600 max-w-xs truncate">{k.deskripsi || '-'}</div>
                            </td>
                            <td>
                              <div className="flex justify-end space-x-2">
                                <button 
                                  className="btn-icon btn-delete"
                                  onClick={() => handleDeleteItem(k.id)}
                                  aria-label={`Hapus kategori ${k.nama}`}
                                >
                                  <Trash2 size={16} />
                                </button>
                              </div>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                ) : (
                  <div className="empty-state">
                    <Newspaper size={48} className="empty-icon" />
                    <h3>Belum ada kategori</h3>
                    <p>Tambahkan kategori baru melalui tombol "Tambah Kategori" di bagian Galeri.</p>
                  </div>
                )}
              </div>
            )}
            {activeTab === 'galeri' && (
              <>
                <div className="quick-add-row">
                  <Link to="/admin/tambah-kategori" className="quick-add-box">
                    <span className="quick-add-title">Tambah Kategori</span>
                    <span className="quick-add-desc">Buat kategori baru</span>
                  </Link>
                  <Link to="/admin/tambah-galeri" className="quick-add-box">
                    <span className="quick-add-title">Tambah Galeri</span>
                    <span className="quick-add-desc">Pilih kategori terlebih dulu</span>
                  </Link>
                  <Link to="/admin/tambah-foto" className="quick-add-box">
                    <span className="quick-add-title">Tambah Foto</span>
                    <span className="quick-add-desc">Unggah foto ke galeri</span>
                  </Link>
                </div>
                <div className="gallery-grid">
                  {filteredItems().length > 0 ? (
                    filteredItems().map(item => (
                    <div key={item.id} className="gallery-card">
                      <div className="card-image-container">
                        <img 
                          src={item.image} 
                          alt={item.title} 
                          className="card-image"
                          loading="lazy"
                        />
                        <div className="card-overlay">
                          <div className="card-actions">
                            <button 
                              className="btn-icon btn-edit"
                              aria-label={`Edit ${item.title}`}
                              onClick={() => navigate(`/admin/edit-galeri/${item.id}`)}
                            >
                              <Edit size={16} />
                            </button>
                            <button 
                              className="btn-icon btn-delete"
                              onClick={() => handleDeleteItem(item.id)}
                              aria-label={`Hapus ${item.title}`}
                            >
                              <Trash2 size={16} />
                            </button>
                          </div>
                        </div>
                      </div>
                      <div className="card-content">
                        <h3 className="card-title">{item.title}</h3>
                        <div className="card-meta">
                          <span className="date">{new Date(item.date).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                          })}</span>
                        </div>
                      </div>
                    </div>
                    ))
                  ) : (
                    <div className="empty-state">
                      <Image size={48} className="empty-icon" />
                      <h3>Tidak ada data galeri</h3>
                      <p>Tambahkan gambar baru dengan menekan tombol "Tambah Baru"</p>
                    </div>
                  )}
                </div>
              </>
            )}

          {(activeTab === 'berita' || activeTab === 'agenda') && (
            <div className="table-container">
              {filteredItems().length > 0 ? (
                <div className="table-responsive">
                  <table className="data-table">
                    <thead>
                      <tr>
                        <th className="w-1/2">Judul</th>
                        <th>{activeTab === 'berita' ? 'Tanggal' : 'Tanggal & Waktu'}</th>
                        {activeTab === 'agenda' && <th>Lokasi</th>}
                        <th className="text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      {filteredItems().map(item => (
                        <tr key={item.id} className="hover:bg-gray-50">
                          <td>
                            <div className="flex items-center">
                              {activeTab === 'berita' && <Newspaper size={16} className="mr-3 text-blue-500" />}
                              {activeTab === 'agenda' && <Calendar size={16} className="mr-3 text-green-500" />}
                              <div>
                                <div className="font-medium text-gray-900">{item.title}</div>
                                {activeTab === 'berita' && (
                                  <div className="text-sm text-gray-500 mt-1 line-clamp-2">
                                    {item.excerpt}
                                  </div>
                                )}
                              </div>
                            </div>
                          </td>
                          <td>
                            <div className="text-sm text-gray-900">
                              {new Date(item.date).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                              })}
                              {activeTab === 'agenda' && item.time && (
                                <div className="text-sm text-gray-500">{item.time}</div>
                              )}
                            </div>
                          </td>
                          {activeTab === 'agenda' && (
                            <td>
                              <div className="text-sm text-gray-900">{item.location}</div>
                            </td>
                          )}
                          <td>
                            <div className="flex justify-end space-x-2">
                              <button 
                                className="btn-icon btn-edit"
                                aria-label="Edit"
                              >
                                <Edit size={16} />
                              </button>
                              <button 
                                className="btn-icon btn-delete"
                                onClick={() => handleDeleteItem(item.id)}
                                aria-label="Hapus"
                              >
                                <Trash2 size={16} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <div className="empty-state">
                  {activeTab === 'berita' ? (
                    <>
                      <Newspaper size={48} className="empty-icon" />
                      <h3>Belum ada berita</h3>
                      <p>Tambahkan berita baru dengan menekan tombol "Tambah Baru"</p>
                    </>
                  ) : (
                    <>
                      <Calendar size={48} className="empty-icon" />
                      <h3>Belum ada agenda</h3>
                      <p>Tambahkan agenda baru dengan menekan tombol "Tambah Baru"</p>
                    </>
                  )}
                </div>
              )}
            </div>
          )}
        </div>
      </div>
      </main>

      {/* Add New Item Modal */}
      {showAddModal && (
        <div className="modal-overlay" onClick={() => setShowAddModal(false)}>
          <div className="modal" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2 className="text-xl font-semibold text-gray-800">
                {modalType === 'kategori' && 'Tambah Kategori'}
                {modalType === 'galeri' && 'Tambah Galeri'}
                {modalType === 'foto' && 'Tambah Foto'}
                {activeTab === 'berita' && 'Tulis Berita Baru'}
                {activeTab === 'agenda' && 'Buat Agenda Baru'}
              </h2>
              <button 
                className="modal-close" 
                onClick={() => setShowAddModal(false)}
                aria-label="Tutup modal"
              >
                <X size={24} />
              </button>
            </div>
            <div className="modal-body">
              {modalType === 'kategori' && (
                <>
                  <div className="form-group">
                    <label className="form-label">Nama Kategori</label>
                    <div className="input-group">
                      <input className="form-input" value={katForm.nama} onChange={(e)=> setKatForm({ ...katForm, nama: e.target.value })} placeholder="Nama kategori" />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Deskripsi</label>
                    <div className="input-group">
                      <textarea className="form-textarea" rows="4" value={katForm.deskripsi} onChange={(e)=> setKatForm({ ...katForm, deskripsi: e.target.value })} placeholder="Deskripsi (opsional)" />
                    </div>
                  </div>
                </>
              )}
              {modalType === 'galeri' && (
                <>
                  <div className="form-group">
                    <label className="form-label">Judul Galeri</label>
                    <div className="input-group">
                      <input className="form-input" value={galForm.judul} onChange={(e)=> setGalForm({ ...galForm, judul: e.target.value })} placeholder="Judul" />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Kategori</label>
                    <div className="input-group">
                      <select className="form-input" value={galForm.kategori_id} onChange={(e)=> setGalForm({ ...galForm, kategori_id: e.target.value })} onFocus={loadKategori}>
                        <option value="">Pilih kategori...</option>
                        {kategori.map(k => (
                          <option key={k.id} value={k.id}>{k.nama || k.name || k.title || `Kategori ${k.id}`}</option>
                        ))}
                      </select>
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Deskripsi</label>
                    <div className="input-group">
                      <textarea className="form-textarea" rows="4" value={galForm.deskripsi} onChange={(e)=> setGalForm({ ...galForm, deskripsi: e.target.value })} placeholder="Deskripsi (opsional)" />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Foto Pertama (opsional)</label>
                    <div className="file-upload">
                      <label className="file-upload-label">
                        <input type="file" className="file-upload-input" accept="image/*" onChange={(e)=> setGalForm({ ...galForm, file: e.target.files[0] })} />
                        <div className="file-upload-content">
                          {galForm.file ? (
                            <>
                              <img src={URL.createObjectURL(galForm.file)} alt="Preview" className="file-upload-preview" />
                              <span className="file-upload-name">{galForm.file.name}</span>
                            </>
                          ) : (
                            <>
                              <span className="text-sm text-gray-500">Klik untuk unggah gambar</span>
                              <span className="text-xs text-gray-400 mt-1">JPG/PNG maks 2MB</span>
                            </>
                          )}
                        </div>
                      </label>
                    </div>
                  </div>
                </>
              )}
              {modalType === 'foto' && (
                <>
                  <div className="form-group">
                    <label className="form-label">Pilih Galeri</label>
                    <div className="input-group">
                      <select className="form-input" value={fotoForm.galeri_id} onChange={(e)=> setFotoForm({ ...fotoForm, galeri_id: e.target.value })} onFocus={loadGaleriOptions}>
                        <option value="">Pilih galeri...</option>
                        {galeriOptions.map(g => (
                          <option key={g.id} value={g.id}>{g.post?.title || g.post?.judul || `Galeri ${g.id}`}</option>
                        ))}
                      </select>
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Judul Foto</label>
                    <div className="input-group">
                      <input className="form-input" value={fotoForm.judul} onChange={(e)=> setFotoForm({ ...fotoForm, judul: e.target.value })} placeholder="Judul foto" />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Deskripsi (opsional)</label>
                    <div className="input-group">
                      <textarea className="form-textarea" rows="3" value={fotoForm.deskripsi} onChange={(e)=> setFotoForm({ ...fotoForm, deskripsi: e.target.value })} placeholder="Deskripsi" />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Gambar</label>
                    <div className="file-upload">
                      <label className="file-upload-label">
                        <input type="file" className="file-upload-input" accept="image/*" onChange={(e)=> setFotoForm({ ...fotoForm, file: e.target.files[0] })} />
                        <div className="file-upload-content">
                          {fotoForm.file ? (
                            <>
                              <img src={URL.createObjectURL(fotoForm.file)} alt="Preview" className="file-upload-preview" />
                              <span className="file-upload-name">{fotoForm.file.name}</span>
                            </>
                          ) : (
                            <>
                              <span className="text-sm text-gray-500">Klik untuk unggah gambar</span>
                              <span className="text-xs text-gray-400 mt-1">JPG/PNG maks 2MB</span>
                            </>
                          )}
                        </div>
                      </label>
                    </div>
                  </div>
                </>
              )}
              <div className="form-group">
                <label className="form-label">Judul</label>
                <div className="input-group">
                  <input 
                    type="text" 
                    className="form-input"
                    value={newItem.title}
                    onChange={(e) => setNewItem({...newItem, title: e.target.value})}
                    placeholder={`Masukkan judul ${activeTab}`}
                    autoFocus
                  />
                </div>
              </div>
              
              {activeTab === 'galeri' && (
                <div className="form-group">
                  <label className="form-label">Gambar</label>
                  <div className="file-upload">
                    <label className="file-upload-label">
                      <input 
                        type="file" 
                        className="file-upload-input"
                        accept="image/*"
                        onChange={(e) => setNewItem({...newItem, image: e.target.files[0]})}
                      />
                      <div className="file-upload-content">
                        {newItem.image ? (
                          <>
                            <img 
                              src={URL.createObjectURL(newItem.image)} 
                              alt="Preview" 
                              className="file-upload-preview"
                            />
                            <span className="file-upload-name">{newItem.image.name}</span>
                          </>
                        ) : (
                          <>
                            <Plus size={32} className="text-gray-400 mb-2" />
                            <span className="text-sm text-gray-500">Klik untuk mengunggah gambar</span>
                            <span className="text-xs text-gray-400 mt-1">Format: JPG, PNG (Maks. 5MB)</span>
                          </>
                        )}
                      </div>
                    </label>
                  </div>
                </div>
              )}
              
              {(activeTab === 'berita' || activeTab === 'agenda') && (
                <div className="form-group">
                  <label className="form-label">
                    {activeTab === 'berita' ? 'Isi Berita' : 'Deskripsi Agenda'}
                  </label>
                  <div className="input-group">
                    <textarea 
                      className="form-textarea"
                      value={newItem.description}
                      onChange={(e) => setNewItem({...newItem, description: e.target.value})}
                      placeholder={activeTab === 'berita' ? 'Tulis isi berita di sini...' : 'Masukkan deskripsi agenda...'}
                      rows="6"
                    ></textarea>
                  </div>
                </div>
              )}
              
              {activeTab === 'agenda' && (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="form-group">
                    <label className="form-label">Tanggal</label>
                    <div className="input-group">
                      <input 
                        type="date" 
                        className="form-input"
                        value={newItem.date || ''}
                        onChange={(e) => setNewItem({...newItem, date: e.target.value})}
                      />
                    </div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Waktu</label>
                    <div className="input-group">
                      <input 
                        type="time" 
                        className="form-input"
                        value={newItem.time || ''}
                        onChange={(e) => setNewItem({...newItem, time: e.target.value})}
                      />
                    </div>
                  </div>
                  <div className="md:col-span-2 form-group">
                    <label className="form-label">Lokasi</label>
                    <div className="input-group">
                      <input 
                        type="text" 
                        className="form-input"
                        value={newItem.location || ''}
                        onChange={(e) => setNewItem({...newItem, location: e.target.value})}
                        placeholder="Masukkan lokasi acara"
                      />
                    </div>
                  </div>
                </div>
              )}
            </div>
            <div className="modal-footer">
              <button 
                type="button"
                className="btn btn-secondary"
                onClick={() => setShowAddModal(false)}
              >
                Batal
              </button>
              {modalType === 'kategori' && (
                <button className="btn btn-primary" type="button" disabled={saving || !katForm.nama} onClick={async ()=>{ try { setSaving(true); await createKategori(katForm.nama, katForm.deskripsi); setKatForm({ nama: '', deskripsi: '' }); setShowAddModal(false); } catch(e){ alert(e.message);} finally{ setSaving(false);} }}>
                  <Plus size={18} className="mr-2" /> {saving ? 'Menyimpan...' : 'Simpan Kategori'}
                </button>
              )}
              {modalType === 'galeri' && (
                <button className="btn btn-primary" type="button" disabled={saving || !galForm.judul || !galForm.kategori_id} onClick={async ()=>{ try { setSaving(true); const gal = await createGaleri(galForm); if (galForm.file) await uploadFoto({ galeri_id: gal.id, judul: galForm.judul, deskripsi: galForm.deskripsi, file: galForm.file }); setGalForm({ judul: '', kategori_id: '', deskripsi: '', file: null }); setShowAddModal(false);} catch(e){ alert(e.message);} finally{ setSaving(false);} }}>
                  <Plus size={18} className="mr-2" /> {saving ? 'Menyimpan...' : 'Simpan Galeri'}
                </button>
              )}
              {modalType === 'foto' && (
                <button className="btn btn-primary" type="button" disabled={saving || !fotoForm.galeri_id || !fotoForm.file} onClick={async ()=>{ try { setSaving(true); await uploadFoto(fotoForm); setFotoForm({ galeri_id: '', judul: '', deskripsi: '', file: null }); setShowAddModal(false);} catch(e){ alert(e.message);} finally{ setSaving(false);} }}>
                  <Plus size={18} className="mr-2" /> {saving ? 'Mengunggah...' : 'Simpan Foto'}
                </button>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Dashboard;
