import React, { useEffect } from 'react';
import './Category.css';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import CardBook from '../../Components/CardBook/CardBook';
import { useNavigate } from 'react-router-dom';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Dummy data buku
const dummyBooks = [
  { id: 1, title: 'Agama Negara', category: 'AGAMA', author: 'Dr. A. Bakir Ihsan, Dr Cucu Nurhayati', image: 'images/agama1.jpeg' },
  { id: 2, title: 'PPAI', category: 'AGAMA', author: 'M. Ali Mahmudi, Syafruddin, Jumahir, Farid Haluti, Kuni Safingah, Taufik Abdillah Syukur, Isna Nurul Inayati, Sudirman', image: '/images/agama2.jpeg' },
  { id: 3, title: 'Merawat NKRI', category: 'AGAMA', author: 'Dr. H.M. Zaki, S.Ag., M.Pd', image: '/images/agama3.jpg' },
  { id: 4, title: 'Toleransi Agama', category: 'AGAMA', author: 'Dr. Baidi Bukhori, S.Ag., M.Si', image: '/images/agama4.jpeg' },
  { id: 5, title: 'Toleransi', category: 'AGAMA', author: 'Moch. Sya`roni Hasan, M.Pd.I.', image: '/images/agama5.jpeg' },
  { id: 6, title: 'Cantik itu Luka', category: 'FIKSI', author: 'Eka Kurniawan', image: '/images/fiksi1.jpg' },
  { id: 7, title: 'Sang Pemimpi', category: 'FIKSI', author: 'Andrea Hirata', image: '/images/fiksi2.jpg' },
  { id: 8, title: 'Berani Bahagia', category: 'FIKSI', author: 'Ichiro Kishimi, Fumitake Koga', image: '/images/fiksi3.png' },
  { id: 9, title: 'Berjalan di Atas Air', category: 'FIKSI', author: 'Rahman Mangunssara', image: '/images/fiksi4.jpg' },
  { id: 10, title: 'Laut Bercerita', category: 'FIKSI', author: 'Leila S. Chudori', image:'/images/fiksi5.jpeg' },
  { id: 11, title: 'Web Programming', category: 'PEMROGRAMAN', author: 'Ani Oktarini Sari, Ari Abdillah, Sunarti', image: '/images/program1.jpg' },
  { id: 12, title: 'Belajar Web', category: 'PEMROGRAMAN', author: 'Dendy Kurniawan, S.Kom., M.Kom', image: '/images/program2.png' },
  { id: 13, title: 'Membuat Web', category: 'PEMROGRAMAN', author: 'Moh Muthohir, S.Kom., M.Kom', image: '/images/program3.jpg', },
  { id: 14, title: 'Dasar pemrograman', category: 'PEMROGRAMAN', author: 'Trija Fayeldi, M.Si, Tatik Retno Murniasih, S.Si., M.Pd', image: '/images/program4.jpg' },
  { id: 15, title: 'Pemrograman', category: 'PEMROGRAMAN', author: 'Ismah, M.Si', image: '/images/program5.jpeg' },
  { id: 16, title: 'Bola Volly', category: 'OLAHRAGA', author: 'Dwi Yulia Nur Mulyadi, M', image: '/images/olahraga1.jpeg' },
  { id: 17, title: 'Sepakbola', category: 'OLAHRAGA', author: 'Agus Salim', image:'/images/olahraga2.jpeg'},
  { id: 18, title: 'Bola Basket', category: 'OLAHRAGA', author: 'Dr. Saichudin, M.Kes, Sayyid Agil Rifqi Munawar, S.Or', image: '/images/olahraga3.jpeg' },
  { id: 19, title: 'Olahraga Yoga', category: 'OLAHRAGA', author: 'I Wayan Ambartana, S.K.M., M.Fis. Ni Made Yuni Gumala, S.K.M., M.Kes.', image: '/images/olahraga4.jpeg' },
  { id: 20, title: 'Tenis Meja', category: 'OLAHRAGA', author: 'Guntur Firmansyah, Didik Hariyanto', image: '/images/olahraga5.png' },
];

// Daftar kategori
const categories = ['AGAMA', 'FIKSI', 'PEMROGRAMAN', 'OLAHRAGA'];

const Category = () => {
  const navigate = useNavigate();

  useEffect(() => {
    AOS.init({ duration: 700, once: true });
  }, []);

  return (
    <>
      <Header />
      <div className="category-page">
        <div className="class-button">
          <button onClick={() => navigate('/')}>&larr; Kembali</button>
        </div>

        <h2 className="section-title">HALAMAN KATEGORI BUKU</h2>

        {categories.map((cat) => (
          <div key={cat} className="category-section">
            <h3>{cat}</h3>
            <div className="book-grid">
              {dummyBooks
                .filter((book) => book.category === cat)
                .map((book) => (
                  <div
                    key={book.id}
                    className="book-card-wrapper"
                    onClick={() => navigate(`/book/${book.id}`)}
                    style={{ cursor: 'pointer' }}
                    data-aos="fade-up"
                  >
                    <CardBook
                      id={book.id}
                      title={book.title}
                      author={book.author}
                      image={book.image || '/assets/default-cover.jpg'}
                    />
                  </div>
                ))}
            </div>
          </div>
        ))}
      </div>
      <Footer />
    </>
  );
};

export default Category;
