import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DataTable from '../../Components/DataTable/DataTable';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import './UserList.css';

const UserList = () => {
  const navigate = useNavigate();
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    fetch('http://localhost/backend/api/user.php')
      .then((res) => res.json())
      .then((result) => {
        if (result.success) {
          setUsers(result.data);
        } else {
          setError(result.message || 'Gagal mengambil data user');
        }
        setLoading(false);
      })
      .catch((err) => {
        setError('Gagal terhubung ke server');
        setLoading(false);
      });
  }, []);

  const columns = [
    { key: 'id', header: 'ID' },
    { key: 'nama', header: 'Nama' },
    { key: 'email', header: 'Email' }
  ];

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Memuat data user...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="error-container">
        <p>Error: {error}</p>
        <button className="back-btn" onClick={() => navigate('/')}>Kembali ke Home</button>
      </div>
    );
  }

  return (
    <>
      <Header />
      <div className="user-list-page">
        <div className="back-button-container">
          <button 
            className="back-btn"
            onClick={() => navigate('/')}
          >
            ← Kembali ke Home
          </button>
        </div>
        <DataTable
          title="Daftar User"
          data={users}
          columns={columns}
        />
      </div>
      <Footer />
    </>
  );
};

export default UserList; 