import React, { useState } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import '../../auth/LoginForm/LoginForm.css';
import { useAuth } from '../../../hooks/useAuth';
import axios from 'axios';

const LoginForm = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const { login } = useAuth();

  const [form, setForm] = useState({
    email: '',
    password: '',
  });

  const [error, setError] = useState('');

  // ambil path sebelumnya, default ke "/profile" jika tidak ada
  const from = location.state?.from || '/profile';

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost/backend/login/login_user.php', form);
      if (response.data.user) {
        login({ user: response.data.user, token: 'fake-jwt-token' });
        navigate(from);
      } else {
        setError(response.data.error);
      }
    } catch {
      setError('Terjadi error pada server');
    }
  };

  return (
    <div style={{ position: 'relative' }}>
      <Link
        to="/"
        style={{
          position: 'absolute',
          top: 16,
          left: 16,
          color: '#007bff',
          textDecoration: 'underline',
          fontWeight: 'bold',
          fontSize: '1.1rem',
          zIndex: 2
        }}
      >
        ← Kembali ke Home
      </Link>
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Login</h2>

        {error && <p className="error-message">{error}</p>}

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

        <button type="submit">Masuk</button>

        <p className="redirect-register">
          Belum punya akun? <Link to="/register">Daftar di sini</Link>
        </p>
      </form>
    </div>
  );
};

export default LoginForm;
