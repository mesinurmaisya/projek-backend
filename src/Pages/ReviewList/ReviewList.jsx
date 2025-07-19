import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DataTable from '../../Components/DataTable/DataTable';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import './ReviewList.css';

const ReviewList = () => {
  const navigate = useNavigate();
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    fetch('http://localhost/backend/api/review.php')
      .then((res) => res.json())
      .then((result) => {
        if (result.success) {
          setReviews(result.data);
        } else {
          setError(result.message || 'Gagal mengambil data review');
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
    { key: 'email', header: 'Email' },
    {
      key: 'rating',
      header: 'Rating',
      render: (value) => {
        const stars = '★'.repeat(value) + '☆'.repeat(5 - value);
        return <span className="rating-stars">{stars}</span>;
      }
    },
    {
      key: 'pesan',
      header: 'Pesan',
      render: (value) => value && value.length > 50 ? `${value.substring(0, 50)}...` : value
    }
  ];

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Memuat data review...</p>
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
      <div className="review-list-page">
        <div className="back-button-container">
          <button 
            className="back-btn"
            onClick={() => navigate('/')}
          >
            ← Kembali ke Home
          </button>
        </div>
        <DataTable
          title="Daftar Review"
          data={reviews}
          columns={columns}
        />
      </div>
      <Footer />
    </>
  );
};

export default ReviewList; 