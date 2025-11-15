import React from 'react';
import { Link } from 'react-router-dom';
import '../styles/Agenda.css';

const Agenda = () => {
  // Sample agenda data
  const agendaItems = [
    {
      id: 1,
      title: 'Pembukaan Tahun Ajaran Baru',
      date: '15 Juli 2023',
      location: 'Lapangan Utama',
      image: '/images/agenda/agenda1.jpg',
      description: 'Acara pembukaan tahun ajaran baru 2023/2024 dengan berbagai kegiatan seru dan menarik.'
    },
    {
      id: 2,
      title: 'Pekan Olahraga Sekolah',
      date: '20-25 Agustus 2023',
      location: 'Lapangan Olahraga',
      image: '/images/agenda/agenda2.jpg',
      description: 'Kompetisi olahraga tahunan antar kelas dengan berbagai cabang olahraga.'
    },
    {
      id: 3,
      title: 'Peringatan Hari Guru',
      date: '25 November 2023',
      location: 'Aula Sekolah',
      image: '/images/agenda/agenda3.jpg',
      description: 'Acara penghargaan dan apresiasi untuk para guru SMKN 4 Bogor.'
    },
    {
      id: 4,
      title: 'Ujian Akhir Semester',
      date: '4-15 Desember 2023',
      location: 'Ruang Kelas',
      image: '/images/agenda/agenda4.jpg',
      description: 'Ujian akhir semester ganjil tahun ajaran 2023/2024.'
    },
    {
      id: 5,
      title: 'Pekan Kreativitas Siswa',
      date: '10-14 Januari 2024',
      location: 'Halaman Sekolah',
      image: '/images/agenda/agenda5.jpg',
      description: 'Ajang tahunan untuk menampilkan bakat dan kreativitas siswa.'
    },
    {
      id: 6,
      title: 'Studi Banding',
      date: '20 Januari 2024',
      location: 'Sekolah Mitra',
      image: '/images/agenda/agenda6.jpg',
      description: 'Kunjungan studi banding ke sekolah mitra untuk pertukaran ilmu.'
    }
  ];

  return (
    <div className="agenda-page">
      {/* Hero Section */}
      <section className="agenda-hero">
        <div className="container">
          <h1>Agenda Sekolah</h1>
          <p>Jadwal kegiatan dan acara SMKN 4 Kota Bogor</p>
        </div>
      </section>

      {/* Agenda Grid */}
      <section className="agenda-grid-section">
        <div className="container">
          <div className="agenda-grid">
            {agendaItems.map((item) => (
              <div key={item.id} className="agenda-card">
                <div className="agenda-image">
                  <img src={item.image} alt={item.title} />
                  <div className="agenda-date">
                    <span className="day">{item.date.split(' ')[0]}</span>
                    <span className="month">{item.date.split(' ')[1]}</span>
                  </div>
                </div>
                <div className="agenda-content">
                  <h3>{item.title}</h3>
                  <div className="agenda-meta">
                    <span className="location">
                      <i className="fas fa-map-marker-alt"></i> {item.location}
                    </span>
                    <span className="date">
                      <i className="far fa-calendar-alt"></i> {item.date}
                    </span>
                  </div>
                  <p>{item.description}</p>
                  <Link to={`/agenda/${item.id}`} className="btn-detail">
                    Lihat Detail <i className="fas fa-arrow-right"></i>
                  </Link>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Footer (same style as Home) */}
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

export default Agenda;
