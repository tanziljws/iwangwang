import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { FaCalendarAlt, FaArrowRight, FaMapMarkerAlt, FaClock, FaUser, FaNewspaper, FaPhone, FaEnvelope, FaPaperPlane } from 'react-icons/fa';
import '../styles/Home.css';

const Home = () => {
  // State untuk form kontak
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });

  // Fungsi untuk menangani perubahan input
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value
    }));
  };

  // Fungsi untuk menangani submit form
  const handleSubmit = (e) => {
    e.preventDefault();
    // Handle form submission logic here
    console.log('Form submitted:', formData);
    alert('Pesan Anda telah terkirim! Terima kasih.');
    // Reset form
    setFormData({ name: '', email: '', message: '' });
  };

  // Fungsi untuk menangani klik tombol
  const handleButtonClick = (e) => {
    const button = e.currentTarget;
    button.classList.add('active');
    
    // Hapus class active setelah animasi selesai
    setTimeout(() => {
      button.classList.remove('active');
    }, 200);
  };
  return (
    <div className="home" style={{ width: '100%', margin: 0, padding: 0 }}>
      {/* Hero Section */}
      <section className="hero">
        <div className="container">
          <div className="hero-content hero-centered">
            {/* hero text removed as requested */}
          </div>
        </div>
      </section>

      {/* Quick Links Section */}
      <section className="quick-links-section">
        <div className="container">
          <div className="section-title">
            <h2 style={{color: 'white'}}>Quick Links</h2>
          </div>
          <div className="rujukan-grid">
            <Link
              to="#"
              onClick={(e) => { e.preventDefault(); document.querySelector('.about-school')?.scrollIntoView({ behavior: 'smooth' }); }}
              className="rujukan-card"
              style={{ textDecoration: 'none' }}
            >
              <div className="rujukan-icon">
                <i className="fas fa-book"></i>
              </div>
              <h3>Tentang</h3>
            </Link>
            
            <Link
              to="#"
              onClick={(e) => { e.preventDefault(); document.querySelector('.features-section')?.scrollIntoView({ behavior: 'smooth' }); }}
              className="rujukan-card"
              style={{ textDecoration: 'none' }}
            >
              <div className="rujukan-icon keunggulan-icon">
                <i className="fas fa-star"></i>
              </div>
              <h3>Keunggulan</h3>
            </Link>
            
            <Link to="/agenda" className="rujukan-card" style={{ textDecoration: 'none' }}>
              <div className="rujukan-icon agenda-icon">
                <i className="fas fa-calendar-alt"></i>
              </div>
              <h3>Agenda</h3>
            </Link>
            
            <Link
              to="#"
              onClick={(e) => { e.preventDefault(); document.querySelector('.news-section')?.scrollIntoView({ behavior: 'smooth' }); }}
              className="rujukan-card"
              style={{ textDecoration: 'none' }}
            >
              <div className="rujukan-icon berita-icon">
                <i className="fas fa-newspaper"></i>
              </div>
              <h3>Berita</h3>
            </Link>
          </div>
        </div>
      </section>

      {/* About School Section */}
      <section className="about-school">
        <div className="container">
          <div className="section-title">
            <h2>Tentang</h2>
            <h3>SMKN 4 Kota Bogor</h3>
          </div>
          <div className="about-content">
            <div className="about-text">
              <p className="about-description">SMK Negeri 4 Kota Bogor adalah sekolah kejuruan terkemuka di Kota Bogor yang telah berdiri sejak 2009, berkomitmen menghasilkan lulusan kompeten dan siap kerja di dunia industri. Kami menyelenggarakan pendidikan berbasis kompetensi dengan fasilitas lengkap dan kurikulum yang selalu diperbarui sesuai kebutuhan industri terkini.</p>
              
              <div className="about-features">
                <div className="feature">
                  <div className="feature-icon">🏫</div>
                  <h3>Fasilitas Lengkap</h3>
                  <p>Ruang kelas nyaman, laboratorium, dan fasilitas pendukung lainnya untuk menunjang proses belajar mengajar.</p>
                </div>
                
                <div className="feature">
                  <div className="feature-icon">👨‍🏫</div>
                  <h3>Guru Berpengalaman</h3>
                  <p>Diajar oleh tenaga pendidik yang profesional dan berpengalaman di bidangnya masing-masing.</p>
                </div>
                
                <div className="feature">
                  <div className="feature-icon">🏆</div>
                  <h3>Prestasi Siswa</h3>
                  <p>Banyak prestasi yang telah diraih oleh siswa-siswi kami di berbagai kompetisi.</p>
                </div>

                <div className="feature">
                  <div className="feature-icon">🌐</div>
                  <h3>Kurikulum Modern</h3>
                  <p>Kurikulum berbasis industri yang selalu diperbarui sesuai perkembangan teknologi terkini.</p>
                </div>
              </div>
              
              <div className="text-center" style={{ marginTop: '2rem' }}>
                <Link to="/about" className="btn btn-secondary">Selengkapnya</Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Departments Logos Row (above Features) */}
      <section className="departments-bar">
        <div className="container">
          <div className="departments-row" aria-label="Daftar Jurusan">
            <div className="dept-card">
              <img src="/images/pplg.png" alt="Logo PPLG" />
              <div className="dept-name">PPLG</div>
            </div>
            <div className="dept-card">
              <img src="/images/tjkt.png" alt="Logo TJKT" />
              <div className="dept-name">TJKT</div>
            </div>
            <div className="dept-card">
              <img src="/images/tkro.png" alt="Logo TKRO" />
              <div className="dept-name">TKRO</div>
            </div>
            <div className="dept-card">
              <img src="/images/tpfl.png" alt="Logo TPFL" />
              <div className="dept-name">TPFL</div>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="features-section">
        <div className="container">
          <div className="section-header">
            <h2>Keunggulan Kami</h2>
            <p>Beberapa keunggulan yang membuat kami berbeda dari yang lain</p>
          </div>
          
          <div className="features-grid">
            <div className="feature-item">
              <div className="feature-icon">🏫</div>
              <h3>Lingkungan Belajar Nyaman</h3>
              <p>Ruang kelas yang nyaman dan fasilitas lengkap untuk mendukung proses belajar mengajar yang optimal.</p>
            </div>
            
            <div className="feature-item">
              <div className="feature-icon">👨‍🏫</div>
              <h3>Pengajar Berkualitas</h3>
              <p>Diajar oleh tenaga pendidik yang profesional dan berpengalaman di bidangnya masing-masing.</p>
            </div>
            
            <div className="feature-item">
              <div className="feature-icon">💻</div>
              <h3>Teknologi Terkini</h3>
              <p>Kurikulum berbasis industri dengan peralatan dan teknologi terbaru untuk mempersiapkan masa depan.</p>
            </div>
            
            <div className="feature-item">
              <div className="feature-icon">🤝</div>
              <h3>Kemitraan Industri</h3>
              <p>Kerjasama dengan berbagai perusahaan ternama untuk peluang magang dan kerja bagi siswa.</p>
            </div>
            
            <div className="feature-item">
              <div className="feature-icon">🌍</div>
              <h3>Berwawasan Global</h3>
              <p>Pembelajaran yang mengikuti perkembangan global dengan standar internasional.</p>
            </div>
            
            <div className="feature-item">
              <div className="feature-icon">🎯</div>
              <h3>Prestasi Membanggakan</h3>
              <p>Banyak prestasi yang telah diraih baik di tingkat regional, nasional, maupun internasional.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Agenda Section */}
      <section className="home-agenda-section">
        <div className="container">
          <div className="section-header">
            <h2>Agenda Terdekat</h2>
            <Link to="/agenda" className="view-all">
              Lihat Semua <FaArrowRight className="arrow-icon" />
            </Link>
          </div>
          
          <div className="agenda-grid">
            {[
              {
                id: 1,
                title: 'Pembelajaran Tatap Muka',
                date: '25 Okt 2023',
                time: '07:30 - 14:00 WIB',
                location: 'SMKN 4 Bogor',
                type: 'academic'
              },
              {
                id: 2,
                title: 'Workshop Teknologi',
                date: '28 Okt 2023',
                time: '09:00 - 15:00 WIB',
                location: 'Aula Utama',
                type: 'workshop'
              },
              {
                id: 3,
                title: 'Bimbingan Karir',
                date: '30 Okt 2023',
                time: '08:00 - 12:00 WIB',
                location: 'Ruang Multimedia',
                type: 'career'
              }
            ].map((event) => (
              <div key={event.id} className="agenda-card">
                <div className="agenda-image">
                  <img src="/images/hero-bg.jpg" alt={event.title} />
                  <div className="agenda-date">
                    <span className="day">{event.date.split(' ')[0]}</span>
                    <span className="month">{event.date.split(' ')[1]} {event.date.split(' ')[2]}</span>
                  </div>
                </div>
                <div className="agenda-content">
                  <h3>{event.title}</h3>
                  <div className="agenda-meta">
                    <span className="agenda-time">
                      <FaClock /> {event.time}
                    </span>
                    <span className="agenda-location">
                      <FaMapMarkerAlt /> {event.location}
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Berita Section */}
      <section className="news-section">
        <div className="container">
          <div className="section-header">
            <div className="section-header-content">
              <FaNewspaper className="section-icon" />
              <div>
                <h2>Berita Terkini</h2>
                <p className="section-subtitle">Informasi dan update terbaru seputar sekolah</p>
              </div>
            </div>
            <Link to="/news" className="view-all">
              Lihat Semua <FaArrowRight className="arrow-icon" />
            </Link>
          </div>
          
          <div className="news-grid">
            {[
              {
                id: 1,
                title: 'Prestasi Gemilang di Kompetisi Nasional',
                excerpt: 'Siswa SMKN 4 Bogor berhasil meraih juara 1 dalam kompetisi pemrograman tingkat nasional yang diselenggarakan di Jakarta.',
                date: '22 Oktober 2023',
                author: 'Admin',
                category: 'Prestasi',
                image: '/images/hero-bg.jpg'
              },
              {
                id: 2,
                title: 'Kerjasama dengan Industri Teknologi',
                excerpt: 'Sekolah menjalin kerjasama dengan perusahaan teknologi ternama untuk program magang siswa dan pengembangan kurikulum.',
                date: '20 Oktober 2023',
                author: 'Admin',
                category: 'Kerjasama',
                image: '/images/hero-bg.jpg'
              },
              {
                id: 3,
                title: 'Workshop Teknologi Terbaru',
                excerpt: 'Mengadakan workshop tentang teknologi AI dan Machine Learning untuk meningkatkan kompetensi siswa di bidang teknologi.',
                date: '18 Oktober 2023',
                author: 'Admin',
                category: 'Workshop',
                image: '/images/hero-bg.jpg'
              }
            ].map((news) => (
              <article key={news.id} className="news-card">
                <div className="news-image">
                  <img src={news.image} alt={news.title} />
                  <div className="news-category">{news.category}</div>
                </div>
                <div className="news-content">
                  <div className="news-meta">
                    <span className="news-date">
                      <FaCalendarAlt /> {news.date}
                    </span>
                    <span className="news-author">
                      <FaUser /> {news.author}
                    </span>
                  </div>
                  <h3>{news.title}</h3>
                  <p>{news.excerpt}</p>
                  <Link to={`/news/${news.id}`} className="read-more">
                    Baca Selengkapnya <FaArrowRight />
                  </Link>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      {/* Footer (replacing Contact) */}
      <footer className="site-footer">
        <div className="container">
          <div className="footer-inner">
            <div className="footer-meta"><p>2025 SMKN 4 Bogor. All rights reserved.</p></div>
          </div>
        </div>
      </footer>

    </div>
  );
};

export default Home;
