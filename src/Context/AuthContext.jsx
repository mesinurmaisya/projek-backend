import { createContext, useContext, useEffect, useState } from 'react';

// Membuat context untuk auth
const AuthContext = createContext(null);

// Provider auth global
export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);     // menyimpan data user
  const [token, setToken] = useState(null);   // menyimpan token login

  // Saat app load pertama kali, coba ambil dari localStorage
  useEffect(() => {
    const savedUser = localStorage.getItem('user');
    const savedToken = localStorage.getItem('token');

    if (savedUser && savedToken) {
      setUser(JSON.parse(savedUser));
      setToken(savedToken);
    }
  }, []);

  // Fungsi untuk login (dipanggil dari Login/Register form)
  const login = ({ user, token }) => {
    setUser(user);
    setToken(token);
    localStorage.setItem('user', JSON.stringify(user));
    localStorage.setItem('token', token);
  };

  // Fungsi untuk logout
  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('user');
    localStorage.removeItem('token');
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

// Export AuthContext agar bisa dipakai hook
export const useAuthContext = () => useContext(AuthContext);
