import React from 'react';
import { FaMapMarkerAlt, FaPhone, FaEnvelope, FaClock } from 'react-icons/fa';
import '../styles/Contact.css';

const Contact = () => {
  return (
    <div className="contact-page">
      <div className="contact-container">
        <div className="contact-header">
          <h1>Kontak Kami</h1>
          <p>Hubungi kami untuk informasi lebih lanjut</p>
        </div>

        <div className="contact-content">
          <div className="contact-info">
            <div className="info-item">
              <div className="info-icon">
                <FaMapMarkerAlt />
              </div>
              <div className="info-text">
                <h3>Alamat</h3>
                <p>Jl. Raya Pajajaran No. 84, Bogor Tengah, Kota Bogor, Jawa Barat 16128</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaPhone />
              </div>
              <div className="info-text">
                <h3>Telepon</h3>
                <p>+62 251 832-1744</p>
                <p>+62 813-8000-0000 (WA)</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaEnvelope />
              </div>
              <div className="info-text">
                <h3>Email</h3>
                <p>info@smkn1bogor.sch.id</p>
              </div>
            </div>

            <div className="info-item">
              <div className="info-icon">
                <FaClock />
              </div>
              <div className="info-text">
                <h3>Jam Operasional</h3>
                <p>Senin - Jumat: 07:00 - 15:00 WIB</p>
                <p>Sabtu: 08:00 - 12:00 WIB</p>
                <p>Minggu & Tanggal Merah: Tutup</p>
              </div>
            </div>
          </div>

          <div className="contact-map">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.073647866012!2d106.79807231529462!3d-6.637383366254416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d1a5d9f8b5%3A0x5f5f5f5f5f5f5f5f!2sSMK%20Negeri%201%20Kota%20Bogor!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid"
              width="100%"
              height="100%"
              style={{ border: 0 }}
              allowFullScreen=""
              loading="lazy"
              title="Lokasi SMK Negeri 1 Kota Bogor"
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
