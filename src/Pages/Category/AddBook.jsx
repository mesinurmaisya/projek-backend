import React, { useState, useRef } from "react";
import { useNavigate } from "react-router-dom";
import Header from "../../Components/Header/Header";
import Footer from "../../Components/Footer/Footer";
import "./AddBook.css";

const AddBook = () => {
  const navigate = useNavigate();
  const [form, setForm] = useState({
    judul: "",
    penulis: "",
    kategori: "",
    tahun_terbit: "",
    jumlah_halaman: "",
    penerbit: "",
    synopsis: "",
    file_pdf: "",
    gambar: "",
  });
  const [pesan, setPesan] = useState("");
  const [imagePreview, setImagePreview] = useState(null);
  const [pdfFile, setPdfFile] = useState(null);
  const [imageFile, setImageFile] = useState(null);
  const fileInputRef = useRef(null);
  const imageInputRef = useRef(null);

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  const handlePdfUpload = (e) => {
    const file = e.target.files[0];
    if (file && file.type === "application/pdf") {
      setPdfFile(file);
      setForm({ ...form, file_pdf: file.name });
    } else {
      alert("Pilih file PDF!");
    }
  };

  const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file && file.type.startsWith("image/")) {
      setImageFile(file);
      const reader = new FileReader();
      reader.onload = (e) => {
        setImagePreview(e.target.result);
      };
      reader.readAsDataURL(file);
      setForm({ ...form, gambar: file.name });
    } else {
      alert("Pilih file gambar!");
    }
  };

  const handleImageUrl = (e) => {
    const url = e.target.value;
    setForm({ ...form, gambar: url });
    if (url) {
      setImagePreview(url);
    } else {
      setImagePreview(null);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setPesan("");

    // Generate ID otomatis untuk buku baru
    const newId = `d${Date.now()}`; // Menggunakan timestamp sebagai ID

    // Create FormData for file upload
    const formData = new FormData();
    formData.append("id_buku", newId);
    formData.append("judul", form.judul);
    formData.append("penulis", form.penulis);
    formData.append("kategori", form.kategori);
    formData.append("tahun_terbit", form.tahun_terbit);
    formData.append("jumlah_halaman", form.jumlah_halaman);
    formData.append("penerbit", form.penerbit);
    formData.append("synopsis", form.synopsis);
    
    if (pdfFile) {
      formData.append("pdf_file", pdfFile);
    }
    if (imageFile) {
      formData.append("image_file", imageFile);
    }
    formData.append("gambar_url", form.gambar);

    try {
      const res = await fetch("http://localhost/backend/buku/create.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      setPesan(data.pesan);
      if (data.pesan === "Buku berhasil ditambahkan") {
        setForm({
          judul: "",
          penulis: "",
          kategori: "",
          tahun_terbit: "",
          jumlah_halaman: "",
          penerbit: "",
          synopsis: "",
          file_pdf: "",
          gambar: "",
        });
        setImagePreview(null);
        setPdfFile(null);
        setImageFile(null);
        if (fileInputRef.current) fileInputRef.current.value = "";
        if (imageInputRef.current) imageInputRef.current.value = "";
        
        // Navigate back to category page after successful addition
        setTimeout(() => {
          navigate('/category');
        }, 2000);
      }
    } catch (err) {
      setPesan("Terjadi kesalahan: " + err.message);
    }
  };

  return (
    <>
      <Header />
      <div className="add-book-container">
        <div className="add-book-form">
          <div className="form-header">
            <button className="back-button" onClick={() => navigate('/category')}>
              &larr; Kembali
            </button>
            <h2>Tambah Buku Baru</h2>
          </div>
          
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label>Judul Buku *</label>
              <input 
                name="judul" 
                placeholder="Masukkan judul buku" 
                value={form.judul} 
                onChange={handleChange} 
                required 
              />
            </div>

            <div className="form-group">
              <label>Penulis *</label>
              <input 
                name="penulis" 
                placeholder="Masukkan nama penulis" 
                value={form.penulis} 
                onChange={handleChange} 
                required 
              />
            </div>

            <div className="form-group">
              <label>Kategori *</label>
              <select name="kategori" value={form.kategori} onChange={handleChange} required>
                <option value="">Pilih kategori</option>
                <option value="AGAMA">AGAMA</option>
                <option value="FIKSI">FIKSI</option>
                <option value="PEMROGRAMAN">PEMROGRAMAN</option>
                <option value="OLAHRAGA">OLAHRAGA</option>
              </select>
            </div>

            <div className="form-row">
              <div className="form-group">
                <label>Tahun Terbit *</label>
                <input 
                  name="tahun_terbit" 
                  type="number" 
                  placeholder="2024" 
                  value={form.tahun_terbit} 
                  onChange={handleChange} 
                  required 
                />
              </div>

              <div className="form-group">
                <label>Jumlah Halaman *</label>
                <input 
                  name="jumlah_halaman" 
                  type="number" 
                  placeholder="200" 
                  value={form.jumlah_halaman} 
                  onChange={handleChange} 
                  required 
                />
              </div>
            </div>

            <div className="form-group">
              <label>Penerbit *</label>
              <input 
                name="penerbit" 
                placeholder="Masukkan nama penerbit" 
                value={form.penerbit} 
                onChange={handleChange} 
                required 
              />
            </div>

            <div className="form-group">
              <label>Sinopsis</label>
              <textarea 
                name="synopsis" 
                placeholder="Masukkan sinopsis buku" 
                value={form.synopsis} 
                onChange={handleChange} 
                rows="4"
              />
            </div>

            <div className="form-group">
              <label>File PDF</label>
              <input 
                type="file" 
                accept=".pdf" 
                onChange={handlePdfUpload}
                ref={fileInputRef}
              />
              {pdfFile && <p className="file-info">File: {pdfFile.name}</p>}
            </div>

            <div className="form-group">
              <label>Gambar Buku</label>
              <div className="image-inputs">
                <div className="image-upload">
                  <label>Upload Gambar:</label>
                  <input 
                    type="file" 
                    accept="image/*" 
                    onChange={handleImageUpload}
                    ref={imageInputRef}
                  />
                </div>
                <div className="image-url">
                  <label>Atau URL Gambar:</label>
                  <input 
                    type="url" 
                    placeholder="https://example.com/image.jpg" 
                    onChange={handleImageUrl}
                  />
                </div>
              </div>
            </div>

            {imagePreview && (
              <div className="image-preview">
                <label>Preview Gambar:</label>
                <img src={imagePreview} alt="Preview" />
              </div>
            )}

            <div className="form-actions">
              <button type="submit" className="submit-btn">Tambah Buku</button>
              <button type="button" className="reset-btn" onClick={() => {
                setForm({
                  judul: "", penulis: "", kategori: "", tahun_terbit: "",
                  jumlah_halaman: "", penerbit: "", synopsis: "", file_pdf: "", gambar: ""
                });
                setImagePreview(null);
                setPdfFile(null);
                setImageFile(null);
              }}>
                Reset Form
              </button>
            </div>
          </form>

          {pesan && (
            <div className={`message ${pesan.includes('berhasil') ? 'success' : 'error'}`}>
              {pesan}
            </div>
          )}
        </div>
      </div>
      <Footer />
    </>
  );
};

export default AddBook;
