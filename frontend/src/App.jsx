import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useState, useEffect } from 'react';
import Navbar from './components/Navbar';
import TopBar from './components/TopBar';
import './App.css';
import './styles/Navbar.css';
import './styles/TopBar.css';
import './styles/Admin.css';

// Import your pages here
import Home from './pages/Home';
import Gallery from './pages/Gallery';
import Contact from './pages/Contact';
import Agenda from './pages/Agenda';
import Berita from './pages/Berita';
import UserLogin from './pages/UserLogin';
import UserRegister from './pages/UserRegister';
import UserAccount from './pages/UserAccount';

// Admin Pages
import AdminLogin from './pages/admin/Login';
import AdminRegister from './pages/admin/Register';
import AdminDashboard from './pages/admin/Dashboard';
import Statistik from './pages/admin/Statistik';
import TambahKategori from './pages/admin/tambahkategori';
import TambahGaleri from './pages/admin/tambahgaleri';
import TambahFoto from './pages/admin/tambahfoto';
import EditGaleri from './pages/admin/EditGaleri';

// Protected Route Component
const ProtectedRoute = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  
  useEffect(() => {
    const checkAuth = async () => {
      const token = localStorage.getItem('adminToken');
      if (!token) {
        setIsAuthenticated(false);
        setIsLoading(false);
        return;
      }
      
      try {
        // You might want to add an API call to validate the token
        // For now, we'll just check if it exists
        setIsAuthenticated(!!token);
      } catch (error) {
        console.error('Auth check failed:', error);
        setIsAuthenticated(false);
        localStorage.removeItem('adminToken');
        localStorage.removeItem('adminUser');
      } finally {
        setIsLoading(false);
      }
    };
    
    checkAuth();
  }, []);
  
  if (isLoading) {
    return <div className="loading">Checking authentication...</div>;
  }
  
  if (!isAuthenticated) {
    return <Navigate to="/admin/login" replace />;
  }
  
  return children;
};

const ProtectedUserRoute = ({ children }) => {
  const [ok, setOk] = useState(false);
  const [loadingCheck, setLoadingCheck] = useState(true);

  useEffect(() => {
    const t = localStorage.getItem('userToken');
    setOk(!!t);
    setLoadingCheck(false);
  }, []);

  if (loadingCheck) return <div className="loading">Checking authentication...</div>;
  if (!ok) return <Navigate to="/login" replace />;
  return children;
};

function App() {
  const [images, setImages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedImage, setSelectedImage] = useState(null);

  useEffect(() => {
    // Simulate API call to fetch images
    const fetchImages = async () => {
      try {
        // Replace with your Laravel API endpoint
        // const response = await fetch('/api/gallery');
        // const data = await response.json();
        
        // Mock data for now
        const mockData = [
          { id: 1, url: 'https://picsum.photos/id/10/800/600', title: 'Nature 1', description: 'Beautiful nature landscape' },
          { id: 2, url: 'https://picsum.photos/id/11/800/600', title: 'Nature 2', description: 'Scenic mountain view' },
          { id: 3, url: 'https://picsum.photos/id/12/800/600', title: 'Nature 3', description: 'Peaceful lake' },
          { id: 4, url: 'https://picsum.photos/id/13/800/600', title: 'Nature 4', description: 'Forest path' },
          { id: 5, url: 'https://picsum.photos/id/14/800/600', title: 'Nature 5', description: 'Ocean waves' },
          { id: 6, url: 'https://picsum.photos/id/15/800/600', title: 'Nature 6', description: 'Mountain peak' },
        ];
        
        setImages(mockData);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching images:', error);
        setLoading(false);
      }
    };

    fetchImages();
  }, []);

  const openModal = (image) => {
    setSelectedImage(image);
  };

  const closeModal = () => {
    setSelectedImage(null);
  };

  const handleImageClick = (image) => {
    setSelectedImage(image);
  };

  const handleCloseModal = () => {
    setSelectedImage(null);
  };
  if (loading) {
    return <div className="loading">Loading gallery...</div>;
  }

  // Check route categories to decide showing site chrome
  const path = window.location.pathname;
  const isAdminRoute = path.startsWith('/admin');
  const isAuthRoute = path === '/login' || path === '/register' || path === '/account';

  return (
    <Router>
      <div className="App" style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
        {!isAdminRoute && !isAuthRoute && (
          <>
            <TopBar />
            <Navbar />
          </>
        )}
        <main style={{ flex: 1, width: '100%', margin: 0, padding: 0 }}>
          <Routes>
            <Route 
              path="/" 
              element={
                <Home 
                  images={images} 
                  loading={loading} 
                  onImageClick={openModal} 
                />
              } 
            />
            <Route path="/gallery" element={<Gallery />} />
            <Route path="/login" element={<UserLogin />} />
            <Route path="/register" element={<UserRegister />} />
            <Route path="/account" element={<ProtectedUserRoute><UserAccount /></ProtectedUserRoute>} />
            <Route path="/berita" element={<Berita />} />
            <Route path="/news" element={<Berita />} />
            <Route path="/agenda" element={<Agenda />} />
            <Route path="/contact" element={<Contact />} />
            
            {/* Admin Routes */}
            <Route path="/admin/login" element={<AdminLogin />} />
            <Route path="/admin/register" element={<AdminRegister />} />
            <Route 
              path="/admin/dashboard" 
              element={
                <ProtectedRoute>
                  <AdminDashboard />
                </ProtectedRoute>
              } 
            />
            <Route 
              path="/admin/statistik" 
              element={
                <ProtectedRoute>
                  <Statistik />
                </ProtectedRoute>
              } 
            />
            <Route 
              path="/admin/tambah-kategori" 
              element={
                <ProtectedRoute>
                  <TambahKategori />
                </ProtectedRoute>
              }
            />
            <Route 
              path="/admin/tambah-galeri" 
              element={
                <ProtectedRoute>
                  <TambahGaleri />
                </ProtectedRoute>
              }
            />
            <Route 
              path="/admin/edit-galeri/:id" 
              element={
                <ProtectedRoute>
                  <EditGaleri />
                </ProtectedRoute>
              }
            />
            <Route 
              path="/admin/tambah-foto" 
              element={
                <ProtectedRoute>
                  <TambahFoto />
                </ProtectedRoute>
              }
            />
            <Route path="/admin" element={<Navigate to="/admin/dashboard" replace />} />
          </Routes>
        </main>
        {selectedImage && (
          <div className="modal-overlay" onClick={closeModal}>
            <div className="modal-content" onClick={e => e.stopPropagation()}>
              <img 
                src={selectedImage.url} 
                alt={selectedImage.title} 
                className="modal-image"
              />
              <div className="modal-info">
                <h3>{selectedImage.title}</h3>
                <p>{selectedImage.description}</p>
              </div>
              <button className="close-button" onClick={closeModal}>
                &times;
              </button>
            </div>
          </div>
        )}
      </div>
    </Router>
  );
}

export default App;
