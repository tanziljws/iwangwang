import React from 'react';
import { FaMapMarkerAlt, FaPhone, FaEnvelope, FaClock } from 'react-icons/fa';
import '../styles/Contact.css';

const Contact = () => {
  return (
    <div className="contact-page">
      <div className="contact-container">
        <div className="contact-header">
          <h1>Kontak Kami</h1>
          <p>Hubungi SMKN 4 Bogor untuk informasi lebih lanjut</p>
        </div>

        <div className="contact-content">
          <div className="contact-info">
            <div className="info-item">
              <div className="info-icon">
                <FaMapMarkerAlt />
              </div>
              <div className="info-text">
                <h3>Alamat</h3>
                <p>Jl. Raya Tajur No. 4, Muarasari, Bogor Selatan, Kota Bogor, Jawa Barat 16137</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaPhone />
              </div>
              <div className="info-text">
                <h3>Telepon</h3>
                <p>+62 251 835-2104</p>
                <p>+62 813-8884-7400 (WA)</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaEnvelope />
              </div>
              <div className="info-text">
                <h3>Email</h3>
                <p>info@smkn4bogor.sch.id</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaClock />
              </div>
              <div className="info-text">
                <h3>Jam Operasional</h3>
                <p>Senin - Jumat: 07:00 - 15:30 WIB</p>
                <p>Sabtu: 08:00 - 12:00 WIB</p>
                <p>Minggu & Tanggal Merah: Tutup</p>
              </div>
            </div>
          </div>

          <div className="contact-map">
            <iframe
              src="https://maps.google.com/maps?q=SMK%20Negeri%204%20Bogor&t=&z=17&ie=UTF8&iwloc=&output=embed"
              width="100%"
              height="100%"
              style={{ border: 0 }}
              allowFullScreen=""
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              title="Lokasi SMK Negeri 4 Bogor"
            ></iframe>
          </div>
        </div>
      </div>

      {/* Footer (same style as Home & Agenda) */}
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

export default Contact;
