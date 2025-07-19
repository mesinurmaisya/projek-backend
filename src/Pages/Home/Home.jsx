import React, { useRef, useEffect } from 'react';
import './Home.css';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import CardBook from '../../Components/CardBook/CardBook';
import hero from '../../assets/hero.jpeg';
import { useNavigate } from 'react-router-dom';
import AOS from 'aos';
import 'aos/dist/aos.css';

const daftarBuku = [
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

const bukuPopuler = [
    {
    id: 1,
    title: 'Agama Negara',
    category: 'Agama',
    image: '/images/agama1.jpeg',
  },
  {
    id: 2,
    title: 'PPAI',
    category: 'Agama',
    image: '/images/agama2.jpeg',
  },
  {
    id: 3,
    title: 'Merawat NKRI',
    category: 'Agama',
    image: '/images/agama3.jpg',
  },
  {
    id: 4,
    title: 'Toleransi Agama',
    category: 'Agama',
    image: '/images/agama4.jpeg',
  },
  {
    id: 5,
    title: 'Toleransi',
    category: 'Agama',
    image: '/images/agama5.jpeg',
  },
  {
    id: 6,
    title: 'Cantik itu Luka',
    category: 'Fiksi',
    image: '/images/fiksi1.jpg',
  },
  {
    id: 7,
    title: 'Sang Pemimpi',
    category: 'Fiksi',
    image: '/images/fiksi2.jpg',
  },
  {
    id: 8,
    title: 'Berani Bahagia',
    category: 'Fiksi',
    image: '/images/program4.jpg',
  },
];

function Home() {
  const daftarRef = useRef(null);
  const populerRef = useRef(null);
  const navigate = useNavigate();
  


  useEffect(() => {
    AOS.init({ duration: 1000, once: true });
  }, []);

  const scroll = (ref, direction) => {
    const container = ref.current;
    const scrollAmount = 250;

    if (container) {
      if (direction === 'right') {
        if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
          container.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
      } else {
        if (container.scrollLeft <= 0) {
          container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
        } else {
          container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
      }
    }
  };

  useEffect(() => {
    const handleScroll = () => {
      const scrollTop = window.scrollY;
      const bookLists = document.querySelectorAll('.book-list-horizontal');

      bookLists.forEach((list) => {
        if (scrollTop > 50) {
          list.classList.add('scroll-effect');
        } else {
          list.classList.remove('scroll-effect');
        }
      });

      if (scrollTop > 50) {
        document.body.classList.add('scrolled');
      } else {
        document.body.classList.remove('scrolled');
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    const moveCarousel = (ref, scrollAmount) => {
      if (ref.current) {
        const container = ref.current;

        if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
          container.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
      }
    };

  const daftarInterval = setInterval(() => moveCarousel(daftarRef, 200), 2500); // Lebih cepat & scroll lebih lambat
  const populerInterval = setInterval(() => moveCarousel(populerRef, 300), 4000); // Lebih lambat & scroll lebih jauh

  return () => {
    clearInterval(daftarInterval);
    clearInterval(populerInterval);
  };
}, []);


  const handleClickBook = (bookId) => {
    navigate(`/book/${bookId}`);
  };



  return (
    <>
      <Header />
      <div className="home-container">
        <div className="hero-image" data-aos="fade-down">
          <img src={hero} alt="Hero" />
        </div>

        {/* TOMBOL NAVIGASI DATA */}
        <div className="data-navigation-section" data-aos="fade-up">
          <h3>Data Management</h3>
          <div className="nav-buttons">
            <button 
              className="nav-btn book-btn"
              onClick={() => navigate('/books')}
            >
              📚 Daftar Buku
            </button>
            <button 
              className="nav-btn review-btn"
              onClick={() => navigate('/reviews')}
            >
              ⭐ Daftar Review
            </button>
            <button 
              className="nav-btn user-btn"
              onClick={() => navigate('/users')}
            >
              👥 Daftar User
            </button>
          </div>
        </div>

        {/* DAFTAR BUKU */}
        <h3 data-aos="fade-up">DAFTAR BUKU</h3>
        <div className="scroll-wrapper">
          <button className="scroll-btn left" onClick={() => scroll(daftarRef, 'left')}>&lt;</button>
          <div className="book-list-horizontal" ref={daftarRef}>
            {daftarBuku.map((book, index) => (
              <div
                key={book.id}
                className="card-book"
                onClick={() => handleClickBook(book.id)}
                style={{ cursor: 'pointer' }}
                data-aos="zoom-in"
                data-aos-delay={index * 100}
              >
                <CardBook
                  title={book.title}
                  author={book.category}
                  image={book.image}
                />
              </div>
            ))}
          </div>
          <button className="scroll-btn right" onClick={() => scroll(daftarRef, 'right')}>&gt;</button>
        </div>

        {/* BUKU POPULER */}
        <h3 data-aos="fade-up">BUKU POPULER</h3>
        <div className="scroll-wrapper">
          <button className="scroll-btn left" onClick={() => scroll(populerRef, 'left')}>&lt;</button>
          <div className="book-list-horizontal" ref={populerRef}>
            {bukuPopuler.map((book, index) => (
              <div
                key={`popular-${book.id}`}
                className="card-book"
                onClick={() => handleClickBook(book.id)}
                style={{ cursor: 'pointer' }}
                data-aos="zoom-in"
                data-aos-delay={index * 100}
              >
                <CardBook
                  title={book.title}
                  author={book.category}
                  image={book.image}
                />
              </div>
            ))}
          </div>
          <button className="scroll-btn right" onClick={() => scroll(populerRef, 'right')}>&gt;</button>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default Home;