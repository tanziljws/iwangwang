import React from 'react';
import { FaCalendarAlt, FaUser, FaArrowRight, FaNewspaper } from 'react-icons/fa';
import '../styles/Berita.css';
import '../styles/Home.css';

const mockNews = [
  {
    id: 1,
    title: 'Siswa SMKN 4 Raih Juara Nasional',
    excerpt: 'Tim siswa berhasil meraih juara 1 pada kompetisi tingkat nasional berkat inovasi aplikasi pendidikan.',
    date: '12 Nov 2025',
    author: 'Admin Sekolah',
    category: 'Prestasi',
    image: '/images/hero-bg.jpg'
  },
  {
    id: 2,
    title: 'Kerja Sama Industri: Program Magang 2026',
    excerpt: 'Sekolah menandatangani MoU dengan perusahaan teknologi untuk membuka peluang magang bagi siswa kelas XI & XII.',
    date: '05 Nov 2025',
    author: 'Humas',
    category: 'Kerjasama',
    image: '/images/hero-bg.jpg'
  },
  {
    id: 3,
    title: 'Workshop AI dan IoT untuk Siswa',
    excerpt: 'Kegiatan workshop menghadirkan pemateri dari industri untuk memperkenalkan teknologi terbaru AI dan IoT.',
    date: '28 Okt 2025',
    author: 'Panitia',
    category: 'Workshop',
    image: '/images/hero-bg.jpg'
  },
  {
    id: 4,
    title: 'Renovasi Perpustakaan Rampung',
    excerpt: 'Perpustakaan baru kini lebih nyaman dengan ruang baca modern dan koleksi buku yang lebih lengkap.',
    date: '18 Okt 2025',
    author: 'Admin',
    category: 'Fasilitas',
    image: '/images/hero-bg.jpg'
  }
];

const Berita = () => {
  return (
    <div className="berita-page">
      <section className="berita-hero">
        <div className="hero-decor">
          <span className="blob"></span>
          <span className="ring r1"></span>
          <span className="ring r2"></span>
        </div>
        <div className="header-wrap">
          <FaNewspaper className="header-icon" />
          <div>
            <h1>Berita Sekolah</h1>
            <p>Update informasi terbaru seputar kegiatan, prestasi, dan pengumuman sekolah.</p>
          </div>
        </div>
      </section>

      <section className="berita-section">
        <div className="berita-grid">
          {mockNews.map((news) => (
            <article key={news.id} className="berita-card">
              <div className="berita-image">
                <img src={news.image} alt={news.title} />
                <div className="berita-category">{news.category}</div>
              </div>
              <div className="berita-content">
                <div className="berita-meta">
                  <span className="meta-item"><FaCalendarAlt /> {news.date}</span>
                  <span className="meta-item"><FaUser /> {news.author}</span>
                </div>
                <h3>{news.title}</h3>
                <p>{news.excerpt}</p>
                <button className="read-more" type="button">
                  Baca Selengkapnya <FaArrowRight />
                </button>
              </div>
            </article>
          ))}
        </div>
      </section>

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

export default Berita;
