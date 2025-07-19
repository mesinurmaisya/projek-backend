import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../auth/RegisterForm/RegisterForm.css';
import axios from 'axios';

const RegisterForm = () => {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    username: '',
    email: '',
    password: '',
  });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost/backend/login/create_user.php', {
        nama: form.username,
        email: form.email,
        password: form.password
      });
      if (response.data.message) {
        navigate('/login');
      } else {
        alert(response.data.error);
      }
    } catch {
      alert('Terjadi error pada server');
    }
  };

  return (
    <form className="register-form" onSubmit={handleSubmit}>
      <h2>Register</h2>

      <label>
        Username:
        <input
          type="text"
          name="username"
          required
          value={form.username}
          onChange={handleChange}
        />
      </label>

      <label>
        Email:
        <input
          type="email"
          name="email"
          required
          value={form.email}
          onChange={handleChange}
        />
      </label>

      <label>
        Password:
        <input
          type="password"
          name="password"
          required
          value={form.password}
          onChange={handleChange}
        />
      </label>

      <button type="submit">Daftar</button>
    </form>
  );
};

export default RegisterForm;
