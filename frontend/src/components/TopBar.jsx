import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPhone, faEnvelope, faMapMarkerAlt } from '@fortawesome/free-solid-svg-icons';
import { faInstagram, faYoutube, faTiktok } from '@fortawesome/free-brands-svg-icons';
import '../styles/TopBar.css';

const TopBar = () => {
  const handleSocialClick = (platform) => {
    const urls = {
      instagram: 'https://www.instagram.com/smkn4kotabogor?igsh=MWFlbTB2YjFjems1Zg==',
      youtube: 'https://youtube.com/@smknegeri4bogor905?si=gaLYG1N0cKs57mPn',
      tiktok: 'https://www.tiktok.com/@smkn4kotabogor?_r=1&_t=ZS-91M3b2bbZBf',
    };
    
    if (urls[platform]) {
      window.open(urls[platform], '_blank', 'noopener,noreferrer');
    }
  };
  return (
    <div className="top-bar">
      <div className="top-bar-container">
        <div className="top-bar-info">
          <span className="info-text">
            <FontAwesomeIcon icon={faPhone} style={{ color: 'white', fontSize: '1em', marginRight: '5px' }} />
            <a href="tel:02518321374" className="info-link">(0251) 8321374</a>
          </span>
          <span className="info-text">
            <FontAwesomeIcon icon={faEnvelope} style={{ color: 'white', fontSize: '1em', marginRight: '5px' }} />
            <a href="mailto:info@smkn4-bogor.sch.id" className="info-link">info@smkn4-bogor.sch.id</a>
          </span>
          <span className="info-text">
            <FontAwesomeIcon icon={faMapMarkerAlt} style={{ color: 'white', fontSize: '1em', marginRight: '5px' }} />
            <span>Jl. Raya Tajur No. 35, Bogor</span>
          </span>
        </div>
        <div className="top-bar-social">
          <a 
            href="https://www.instagram.com/smkn4kota_bogor" 
            className="social-icon" 
            aria-label="Instagram"
            target="_blank"
            rel="noopener noreferrer"
            style={{ color: 'white' }}
            onClick={(e) => {
              e.preventDefault();
              handleSocialClick('instagram');
            }}
          >
            <FontAwesomeIcon icon={faInstagram} style={{ color: 'inherit' }} />
          </a>
          <a 
            href="https://www.youtube.com/@smkn4kota_bogor" 
            className="social-icon" 
            aria-label="YouTube"
            target="_blank"
            rel="noopener noreferrer"
            style={{ color: 'white' }}
            onClick={(e) => {
              e.preventDefault();
              handleSocialClick('youtube');
            }}
          >
            <FontAwesomeIcon icon={faYoutube} style={{ color: 'inherit' }} />
          </a>
          <a 
            href="https://www.tiktok.com/@smkn4kota_bogor" 
            className="social-icon" 
            aria-label="TikTok"
            target="_blank"
            rel="noopener noreferrer"
            style={{ color: 'white' }}
            onClick={(e) => {
              e.preventDefault();
              handleSocialClick('tiktok');
            }}
          >
            <FontAwesomeIcon icon={faTiktok} style={{ color: 'inherit' }} />
          </a>
        </div>
      </div>
    </div>
  );
};

export default TopBar;
