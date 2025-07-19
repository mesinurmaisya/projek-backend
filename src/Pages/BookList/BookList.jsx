import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DataTable from '../../Components/DataTable/DataTable';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import './BookList.css';

const BookList = () => {
  const navigate = useNavigate();
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    fetch('http://localhost/backend/api/buku.php')
      .then((res) => res.json())
      .then((result) => {
        if (result.success) {
          setBooks(result.data);
        } else {
          setError(result.message || 'Gagal mengambil data buku');
        }
        setLoading(false);
      })
      .catch((err) => {
        setError('Gagal terhubung ke server');
        setLoading(false);
      });
  }, []);

  const columns = [
    { key: 'id_buku', header: 'ID' },
    { key: 'judul', header: 'Judul Buku' },
    { key: 'penulis', header: 'Penulis' },
    { key: 'kategori', header: 'Kategori' },
    { key: 'tahun_terbit', header: 'Tahun Terbit' },
    { key: 'jumlah_halaman', header: 'Jumlah Halaman' },
    { key: 'penerbit', header: 'Penerbit' },
    { 
      key: 'deskripsi', 
      header: 'Deskripsi',
      render: (value) => value && value.length > 50 ? `${value.substring(0, 50)}...` : value
    },
    {
      key: 'gambar',
      header: 'Gambar',
      render: (value) => value ? (
        <img 
          src={`http://localhost/backend/uploads/images/${value}`}
          alt="cover buku"
          style={{ width: 50, height: 70, objectFit: 'cover', borderRadius: 4, border: '1px solid #ccc' }}
        />
      ) : <span style={{color:'#aaa'}}>Tidak ada</span>
    },
    {
      key: 'file_pdf',
      header: 'File PDF',
      render: (value) => value ? (
        <a 
          href={`http://localhost/backend/uploads/pdfs/${value}`}
          target="_blank"
          rel="noopener noreferrer"
          className="pdf-link"
        >
          Lihat/Download
        </a>
      ) : <span style={{color:'#aaa'}}>Tidak ada</span>
    }
  ];

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Memuat data buku...</p>
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
      <div className="book-list-page">
        <div className="back-button-container">
          <button 
            className="back-btn"
            onClick={() => navigate('/')}
          >
            ← Kembali ke Home
          </button>
        </div>
        <DataTable
          title="Daftar Buku"
          data={books}
          columns={columns}
        />
      </div>
      <Footer />
    </>
  );
};

export default BookList; 