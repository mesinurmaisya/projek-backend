import React from 'react';
import { NavLink } from 'react-router-dom';
import './Header.css';
import logo from '../../assets/logo-baca.jpeg';


const Header = () => {
  return (
    <header className="header-container">
      <div className="header-logo">
        <img src={logo} alt="Logo" />
        <h2>RUANG BACA</h2>
      </div>
      <nav className="header-nav">
        <NavLink to="/" className={({ isActive }) => isActive ? 'active' : ''}>HOME</NavLink>
        <NavLink to="/about" className={({ isActive }) => isActive ? 'active' : ''}>ABOUT</NavLink>
        <NavLink to="/Category" className={({ isActive }) => isActive ? 'active' : ''}>KATEGORI</NavLink>
        <NavLink to="/contact" className={({ isActive }) => isActive ? 'active' : ''}>CONTACT</NavLink>
      </nav>
    </header>
  );
};

export default Header;
