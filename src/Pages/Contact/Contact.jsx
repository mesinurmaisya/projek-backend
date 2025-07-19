import React, { useState, useEffect } from 'react';
import './Contact.css';
import Header from '../../Components/Header/Header.jsx';
import Footer from '../../Components/Footer/Footer.jsx';
import { useNavigate } from 'react-router-dom';
import AOS from 'aos';
import 'aos/dist/aos.css';

const Contact = () => {
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: '',
  });

  useEffect(() => {
    AOS.init({ duration: 1000 });
  }, []);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (event) => {
    event.preventDefault();
    alert('Form submitted!');
    navigate('/');
  };

  return (
    <>
      <Header />

      <div className="contact-container">
        <div className="class-button" data-aos="fade-down">
          <button className="back-button" onClick={() => navigate('/')}>
            &larr; Kembali
          </button>
        </div>

        <h2 className="section-title" data-aos="fade-up">HALAMAN CONTACT</h2>

        <h2 className="contact-title" data-aos="fade-up">
          CONTACT <span>US</span>
        </h2>
        <p className="contact-desc" data-aos="fade-up">
          Kami senang mendengar dari Anda. Silahkan hubungi kami melalui informasi berikut atau kirim pesan langsung.
        </p>

        <div className="contact-content">
          <form
            className="contact-form"
            onSubmit={handleSubmit}
            data-aos="fade-right"
          >
            <label>Nama:</label>
            <input
              type="text"
              placeholder="Masukkan nama Anda"
              name="name"
              value={formData.name}
              onChange={handleInputChange}
              required
            />

            <label>Email:</label>
            <input
              type="email"
              placeholder="Masukkan email Anda"
              name="email"
              value={formData.email}
              onChange={handleInputChange}
              required
            />

            <label>Pesan:</label>
            <textarea
              rows="4"
              placeholder="Tulis pesan Anda"
              name="message"
              value={formData.message}
              onChange={handleInputChange}
              required
            ></textarea>

            <button type="submit">KIRIM</button>
          </form>

          <div className="contact-info" data-aos="fade-left">
            <h3>Info</h3>
            <p>
              <i className="fa fa-envelope"></i> ruangbaca33@gmail.com
            </p>
            <p>
              <i className="fa fa-phone"></i> +62 876-5987-3407
            </p>
            <p>
              <i className="fa fa-clock-o"></i> 09.00–17.00 WIB
            </p>

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
          </div>
        </div>
      </div>

      <Footer />
    </>
  );
};

export default Contact;
