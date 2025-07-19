import React, { useState, useEffect } from "react";
import "./BookList.css";

const BookList = () => {
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetchBooks();
  }, []);

  const fetchBooks = async () => {
    try {
      const response = await fetch("http://localhost/backend/buku/read.php");
      const data = await response.json();
      
      if (data.success) {
        setBooks(data.data);
      } else {
        setError(data.message || "Gagal mengambil data buku");
      }
    } catch (err) {
      setError("Terjadi kesalahan saat mengambil data");
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm("Apakah Anda yakin ingin menghapus buku ini?")) {
      try {
        const response = await fetch(`http://localhost/backend/buku/delete.php`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id: id }),
        });
        const data = await response.json();
        
        if (data.success) {
          fetchBooks(); // Refresh list
          alert("Buku berhasil dihapus");
        } else {
          alert("Gagal menghapus buku");
        }
      } catch (err) {
        alert("Terjadi kesalahan");
      }
    }
  };

  const getImageUrl = (gambar) => {
    if (gambar.startsWith('http')) {
      return gambar; // External URL
    } else if (gambar) {
      return `http://localhost/backend/uploads/images/${gambar}`;
    }
    return '/images/default-book.jpg'; // Default image
  };

  const getPdfUrl = (file_pdf) => {
    if (file_pdf) {
      return `http://localhost/backend/uploads/pdfs/${file_pdf}`;
    }
    return null;
  };

  if (loading) {
    return (
      <div className="book-list-container">
        <div className="loading">Loading...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="book-list-container">
        <div className="error">{error}</div>
      </div>
    );
  }

  return (
    <div className="book-list-container">
      <div className="book-list-header">
        <h2>Daftar Buku</h2>
        <button onClick={fetchBooks} className="refresh-btn">
          🔄 Refresh
        </button>
      </div>

      <div className="books-grid">
        {books.map((book) => (
          <div key={book.id_buku} className="book-card">
            <div className="book-image">
              <img 
                src={getImageUrl(book.gambar)} 
                alt={book.judul}
                onError={(e) => {
                  e.target.src = '/images/default-book.jpg';
                }}
              />
            </div>
            
            <div className="book-info">
              <h3 className="book-title">{book.judul}</h3>
              <p className="book-author">Penulis: {book.penulis}</p>
              <p className="book-category">Kategori: {book.kategori}</p>
              <p className="book-publisher">Penerbit: {book.penerbit}</p>
              <p className="book-year">Tahun: {book.tahun_terbit}</p>
              <p className="book-pages">Halaman: {book.jumlah_halaman}</p>
              
              <div className="book-actions">
                {getPdfUrl(book.file_pdf) && (
                  <a 
                    href={getPdfUrl(book.file_pdf)} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="pdf-link"
                  >
                    📖 Baca PDF
                  </a>
                )}
                
                <button 
                  onClick={() => handleDelete(book.id_buku)}
                  className="delete-btn"
                >
                  🗑️ Hapus
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {books.length === 0 && (
        <div className="no-books">
          <p>Belum ada buku yang ditambahkan</p>
        </div>
      )}
    </div>
  );
};

export default BookList; 