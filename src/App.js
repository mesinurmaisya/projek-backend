// App.js
import React from 'react';
import { Routes, Route } from 'react-router-dom';

// Import pages
import Home from './Pages/Home/Home.jsx';
import About from './Pages/About/About.jsx';
import Category from './Pages/Category/Category.jsx';
import BookDetail from './Components/BookDetail/BookDetail'; // Make sure the path is correct
import Contact from './Pages/Contact/Contact.jsx';
import BookList from './Pages/BookList/BookList.jsx';
import ReviewList from './Pages/ReviewList/ReviewList.jsx';
import UserList from './Pages/UserList/UserList.jsx';

const App = () => {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/about" element={<About />} />
      <Route path="/category" element={<Category />} />
      <Route path="/book/:id" element={<BookDetail />} /> {/* Route for BookDetail page */}
      <Route path="/contact" element={<Contact />} />
      <Route path="/books" element={<BookList />} />
      <Route path="/reviews" element={<ReviewList />} />
      <Route path="/users" element={<UserList />} />
    </Routes>
  );
};

export default App;
