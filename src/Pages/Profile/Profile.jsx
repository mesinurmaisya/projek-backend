import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import './Profile.css';

const Profile = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    id: user.id,
    nama: user.nama,
    email: user.email,
    biodata: user.biodata || ""
  });
  const [message, setMessage] = useState('');

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleUpdate = async (e) => {
    e.preventDefault();
    try {
      const formData = new FormData();
      formData.append('id', form.id);
      formData.append('nama', form.nama);
      formData.append('email', form.email);
      formData.append('biodata', form.biodata);

      const response = await axios.post(
        'http://localhost/backend/login/update_user.php',
        formData,
        { headers: { 'Content-Type': 'multipart/form-data' } }
      );
      if (response.data.message) {
        setMessage('Profil berhasil diupdate!');
      } else {
        setMessage(response.data.error);
      }
    } catch {
      setMessage('Terjadi error pada server');
    }
  };

  const handleDelete = async () => {
    if (window.confirm('Yakin ingin menghapus akun?')) {
      try {
        const response = await axios.delete(`http://localhost/backend/login/delete_user.php?id=${form.id}`);
        if (response.data.message) {
          setMessage('Akun berhasil dihapus!');
          logout();
        } else {
          setMessage(response.data.error);
        }
      } catch {
        setMessage('Terjadi error pada server');
      }
    }
  };

  return (
    <div className="profile-layout">
      <div className="profile-bg-decor">
        <div className="circle1"></div>
        <div className="circle2"></div>
        <div className="circle3"></div>
      </div>
      {/* Sidebar kiri */}
      <aside className="profile-sidebar">
        <button className="back-btn" onClick={() => navigate('/')}>
          ← Kembali
        </button>
        <div className="sidebar-actions">
          <button
            onClick={handleDelete}
            className="danger-btn"
          >
            Hapus Akun
          </button>
          <button
            onClick={logout}
            className="danger-btn"
          >
            Logout
          </button>
        </div>
      </aside>

      {/* Konten kanan */}
      <main className="profile-content">
        <h2>Profil Saya</h2>
        <form onSubmit={handleUpdate}>
          <div className="info-box">
            <label>Nama:</label>
            <input type="text" name="nama" value={form.nama} onChange={handleChange} />
          </div>

          <div className="info-box">
            <label>Email:</label>
            <input type="email" name="email" value={form.email} onChange={handleChange} />
          </div>

          <div className="info-box">
            <label>Biodata:</label>
            <textarea
              name="biodata"
              value={form.biodata}
              onChange={handleChange}
              rows={3}
              style={{ width: "100%", padding: "10px", borderRadius: "7px", border: "1.5px solid #e0e0e0", fontSize: "1rem" }}
            />
          </div>

          <button type="submit">Update Profil</button>
        </form>
        <div>{message}</div>
      </main>
    </div>
  );
};

export default Profile;
