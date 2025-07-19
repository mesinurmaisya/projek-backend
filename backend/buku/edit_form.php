<?php
include '../config.php';

$success_message = '';
$error_message = '';
$book = null;

// Get book ID from URL
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: update.php');
    exit();
}

// Fetch book data
$stmt = $conn->prepare("SELECT * FROM buku WHERE id_buku = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header('Location: update.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $tahun_terbit = $_POST['tahun_terbit'] ?? '';
    $jumlah_halaman = $_POST['jumlah_halaman'] ?? '';
    $penerbit = $_POST['penerbit'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi input
    if (empty($judul) || empty($penulis) || empty($kategori)) {
        $error_message = "Judul, penulis, dan kategori wajib diisi";
    } else {
        $gambar = $book['gambar']; // Keep existing image
        $file_pdf = $book['file_pdf']; // Keep existing PDF

        // Upload new image if provided
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['gambar']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $timestamp = time();
                $gambar = $timestamp . '_' . $_FILES['gambar']['name'];
                $upload_path = '../uploads/images/' . $gambar;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if (!empty($book['gambar']) && file_exists('../uploads/images/' . $book['gambar'])) {
                        unlink('../uploads/images/' . $book['gambar']);
                    }
                } else {
                    $error_message = "Gagal mengupload gambar";
                }
            } else {
                $error_message = "Tipe file gambar tidak didukung. Gunakan JPG, PNG, atau GIF";
            }
        }

        // Upload new PDF if provided
        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['application/pdf'];
            $file_type = $_FILES['file_pdf']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $timestamp = time();
                $file_pdf = $timestamp . '_' . $_FILES['file_pdf']['name'];
                $upload_path = '../uploads/pdfs/' . $file_pdf;
                
                if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $upload_path)) {
                    // Delete old PDF if exists
                    if (!empty($book['file_pdf']) && file_exists('../uploads/pdfs/' . $book['file_pdf'])) {
                        unlink('../uploads/pdfs/' . $book['file_pdf']);
                    }
                } else {
                    $error_message = "Gagal mengupload file PDF";
                }
            } else {
                $error_message = "Tipe file tidak didukung. Gunakan file PDF";
            }
        }

        if (empty($error_message)) {
            // Update database
            $stmt = $conn->prepare("UPDATE buku SET judul = ?, penulis = ?, kategori = ?, tahun_terbit = ?, jumlah_halaman = ?, penerbit = ?, deskripsi = ?, gambar = ?, file_pdf = ? WHERE id_buku = ?");
            $stmt->bind_param("sssssssssi", $judul, $penulis, $kategori, $tahun_terbit, $jumlah_halaman, $penerbit, $deskripsi, $gambar, $file_pdf, $id);

            if ($stmt->execute()) {
                $success_message = "✅ Buku berhasil diperbarui!";
                // Update book data for display
                $book = array_merge($book, $_POST);
                $book['gambar'] = $gambar;
                $book['file_pdf'] = $file_pdf;
            } else {
                $error_message = "Gagal memperbarui buku: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Backend System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .file-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .file-info ul {
            margin: 10px 0 0 20px;
        }

        .required {
            color: #dc3545;
        }

        .current-files {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .current-files h4 {
            margin-bottom: 10px;
            color: #495057;
        }

        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        .file-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .file-item .file-info {
            flex: 1;
            background: none;
            padding: 0;
            margin: 0;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="update.php" class="back-btn">← Kembali ke Daftar Update</a>
        
        <div class="header">
            <h1>✏️ Edit Buku</h1>
            <p>Perbarui informasi buku</p>
        </div>

        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="current-files">
                <h4>📁 File Saat Ini:</h4>
                <?php if (!empty($book['gambar'])): ?>
                    <div class="file-item">
                        <img src="../uploads/images/<?php echo $book['gambar']; ?>" alt="Current cover">
                        <div class="file-info">
                            <strong>Cover:</strong> <?php echo $book['gambar']; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($book['file_pdf'])): ?>
                    <div class="file-item">
                        <div class="file-info">
                            <strong>PDF:</strong> <?php echo $book['file_pdf']; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="file-info">
                <strong>📋 Informasi Upload File:</strong>
                <ul>
                    <li>Kosongkan field file jika tidak ingin mengubah file yang ada</li>
                    <li>Gambar: JPG, PNG, GIF (maksimal 5MB)</li>
                    <li>PDF: File PDF (maksimal 10MB)</li>
                </ul>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Buku <span class="required">*</span></label>
                    <input type="text" id="judul" name="judul" required placeholder="Masukkan judul buku" value="<?php echo htmlspecialchars($book['judul']); ?>">
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis <span class="required">*</span></label>
                    <input type="text" id="penulis" name="penulis" required placeholder="Masukkan nama penulis" value="<?php echo htmlspecialchars($book['penulis']); ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kategori">Kategori <span class="required">*</span></label>
                        <select id="kategori" name="kategori" required>
                            <option value="">Pilih kategori</option>
                            <option value="AGAMA" <?php echo $book['kategori'] === 'AGAMA' ? 'selected' : ''; ?>>AGAMA</option>
                            <option value="FIKSI" <?php echo $book['kategori'] === 'FIKSI' ? 'selected' : ''; ?>>FIKSI</option>
                            <option value="PEMROGRAMAN" <?php echo $book['kategori'] === 'PEMROGRAMAN' ? 'selected' : ''; ?>>PEMROGRAMAN</option>
                            <option value="OLAHRAGA" <?php echo $book['kategori'] === 'OLAHRAGA' ? 'selected' : ''; ?>>OLAHRAGA</option>
                            <option value="NOVEL" <?php echo $book['kategori'] === 'NOVEL' ? 'selected' : ''; ?>>NOVEL</option>
                            <option value="LAINNYA" <?php echo $book['kategori'] === 'LAINNYA' ? 'selected' : ''; ?>>LAINNYA</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" placeholder="Contoh: 2024" min="1900" max="2030" value="<?php echo htmlspecialchars($book['tahun_terbit']); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jumlah_halaman">Jumlah Halaman</label>
                        <input type="number" id="jumlah_halaman" name="jumlah_halaman" placeholder="Contoh: 200" min="1" value="<?php echo htmlspecialchars($book['jumlah_halaman']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit" value="<?php echo htmlspecialchars($book['penerbit']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi/Sinopsis</label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi atau sinopsis buku"><?php echo htmlspecialchars($book['deskripsi']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gambar">Cover Buku Baru (Opsional)</label>
                        <input type="file" id="gambar" name="gambar" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="file_pdf">File PDF Baru (Opsional)</label>
                        <input type="file" id="file_pdf" name="file_pdf" accept=".pdf">
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn">✏️ Update Buku</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 