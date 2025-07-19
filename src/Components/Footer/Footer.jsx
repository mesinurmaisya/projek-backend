import React from 'react';
import { Link } from 'react-router-dom';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="footer-container">
      <p className="footer-created"><i>Created By Kelompok 6</i></p>

      <div className="footer-icons">
        <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer">
          <i className="fa fa-instagram" />
        </a>
        <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
          <i className="fa fa-facebook" />
        </a>
        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer">
          <i className="fa fa-twitter" />
        </a>
      </div>

      <div className="footer-nav">
        <Link to="/">HOME</Link>
        <Link to="/about">ABOUT</Link>
        <Link to="/category">CATEGORY</Link>
        <Link to="/contact">CONTACT</Link>
      </div>

      <p className="copyright">
        © 2025 Rumah Baca Digital. Semua hak dilindungi.
      </p>
    </footer>
  );
};

export default Footer;