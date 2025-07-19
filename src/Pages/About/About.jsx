import React, { useEffect } from 'react';
import './About.css';
import Header from '../../Components/Header/Header';
import Footer from '../../Components/Footer/Footer';
import { useNavigate } from 'react-router-dom';
import perpus from '../../assets/perpus.jpeg';
import { motion } from 'framer-motion';

const About = () => {
  const navigate = useNavigate();

  useEffect(() => {
    const handleScroll = () => {
      const revealElements = document.querySelectorAll('.scroll-reveal');
      revealElements.forEach((el) => {
        const top = el.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        if (top < windowHeight - 100) {
          el.classList.add('visible');
        }
      });
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Panggil langsung supaya langsung muncul jika sudah di posisi
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <>
      <Header />
      <motion.div
        className="about-container"
        initial={{ opacity: 0, y: 40 }}
        animate={{ opacity: 1, y: 0 }}
        exit={{ opacity: 0, y: -40 }}
        transition={{ duration: 0.6 }}
      >
        <div className="class-button">
          <button onClick={() => navigate('/')}>&larr; kembali</button>
          <h2 className="section-title scroll-reveal">HALAMAN ABOUT</h2>

          <div className="about-image scroll-reveal">
            <img src={perpus} alt="Perpustakaan" />
          </div>

          <div className="about-description scroll-reveal">
            <h3>Tentang Kami:</h3>
            <p>
              Ruang Baca adalah komunitas literasi yang menyediakan tempat nyaman untuk membaca, berdiskusi, dan berbagi pengetahuan.
              Didirikan pada tahun 2025, kami berkomitmen untuk meningkatkan minat baca di kalangan masyarakat, terutama generasi muda.
            </p>
          </div>

          <div className="visi-misi scroll-reveal">
            <div className="visi">
              <h4>Visi</h4>
              <ul>
                <li>Menjadi pusat literasi yang inklusif dan inspiratif di Indonesia.</li>
              </ul>
            </div>
            <div className="misi">
              <h4>Misi</h4>
              <ul>
                <li>Menyediakan ruang baca yang nyaman dan gratis</li>
                <li>Menyelenggarakan kegiatan literasi seperti diskusi buku, bedah karya, dan kelas menulis</li>
              </ul>
            </div>
          </div>
        </div>
      </motion.div>
      <Footer />
    </>
  );
};

export default About;
