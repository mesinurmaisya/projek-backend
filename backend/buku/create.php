<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../config.php';

$success_message = '';
$error_message = '';

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
        $gambar = '';
        $file_pdf = '';

        // Upload gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['gambar']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $timestamp = time();
                $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $gambar = $timestamp . '_' . $_FILES['gambar']['name'];
                $upload_path = '../uploads/images/' . $gambar;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    // Gambar berhasil diupload
                } else {
                    $error_message = "Gagal mengupload gambar";
                }
            } else {
                $error_message = "Tipe file gambar tidak didukung. Gunakan JPG, PNG, atau GIF";
            }
        }

        // Upload PDF
        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['application/pdf'];
            $file_type = $_FILES['file_pdf']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $timestamp = time();
                $file_extension = pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION);
                $file_pdf = $timestamp . '_' . $_FILES['file_pdf']['name'];
                $upload_path = '../uploads/pdfs/' . $file_pdf;
                
                if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $upload_path)) {
                    // PDF berhasil diupload
                } else {
                    $error_message = "Gagal mengupload file PDF";
                }
            } else {
                $error_message = "Tipe file tidak didukung. Gunakan file PDF";
            }
        }

        if (empty($error_message)) {
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, kategori, tahun_terbit, jumlah_halaman, penerbit, deskripsi, gambar, file_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $judul, $penulis, $kategori, $tahun_terbit, $jumlah_halaman, $penerbit, $deskripsi, $gambar, $file_pdf);

            if ($stmt->execute()) {
                $success_message = "✅ Buku berhasil ditambahkan! Buku baru telah tersimpan dalam sistem.";
                // Reset form data
                $_POST = array();
            } else {
                $error_message = "Gagal menambahkan buku: " . $stmt->error;
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
    <title>Tambah Buku Baru - Backend System</title>
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

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">← Kembali ke Manajemen Buku</a>
        
        <div class="header">
            <h1>📚 Tambah Buku Baru</h1>
            <p>Tambahkan buku baru ke perpustakaan</p>
        </div>

        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="file-info">
                <strong>📋 Informasi Upload File:</strong>
                <ul>
                    <li>Gambar: JPG, PNG, GIF (maksimal 5MB)</li>
                    <li>PDF: File PDF (maksimal 10MB)</li>
                    <li>File akan otomatis diberi timestamp untuk menghindari konflik nama</li>
                </ul>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Buku <span class="required">*</span></label>
                    <input type="text" id="judul" name="judul" required placeholder="Masukkan judul buku" value="<?php echo htmlspecialchars($_POST['judul'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis <span class="required">*</span></label>
                    <input type="text" id="penulis" name="penulis" required placeholder="Masukkan nama penulis" value="<?php echo htmlspecialchars($_POST['penulis'] ?? ''); ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kategori">Kategori <span class="required">*</span></label>
                        <select id="kategori" name="kategori" required>
                            <option value="">Pilih kategori</option>
                            <option value="AGAMA" <?php echo ($_POST['kategori'] ?? '') === 'AGAMA' ? 'selected' : ''; ?>>AGAMA</option>
                            <option value="FIKSI" <?php echo ($_POST['kategori'] ?? '') === 'FIKSI' ? 'selected' : ''; ?>>FIKSI</option>
                            <option value="PEMROGRAMAN" <?php echo ($_POST['kategori'] ?? '') === 'PEMROGRAMAN' ? 'selected' : ''; ?>>PEMROGRAMAN</option>
                            <option value="OLAHRAGA" <?php echo ($_POST['kategori'] ?? '') === 'OLAHRAGA' ? 'selected' : ''; ?>>OLAHRAGA</option>
                            <option value="NOVEL" <?php echo ($_POST['kategori'] ?? '') === 'NOVEL' ? 'selected' : ''; ?>>NOVEL</option>
                            <option value="LAINNYA" <?php echo ($_POST['kategori'] ?? '') === 'LAINNYA' ? 'selected' : ''; ?>>LAINNYA</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" placeholder="Contoh: 2024" min="1900" max="2030" value="<?php echo htmlspecialchars($_POST['tahun_terbit'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jumlah_halaman">Jumlah Halaman</label>
                        <input type="number" id="jumlah_halaman" name="jumlah_halaman" placeholder="Contoh: 200" min="1" value="<?php echo htmlspecialchars($_POST['jumlah_halaman'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit" value="<?php echo htmlspecialchars($_POST['penerbit'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi/Sinopsis</label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi atau sinopsis buku"><?php echo htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gambar">Cover Buku (Gambar)</label>
                        <input type="file" id="gambar" name="gambar" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="file_pdf">File PDF Buku</label>
                        <input type="file" id="file_pdf" name="file_pdf" accept=".pdf">
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn">📚 Tambah Buku Baru</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
