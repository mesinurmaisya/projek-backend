<?php
include '../config.php';

$success_message = '';
$error_message = '';
$book = null;

// Ambil ID buku dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: delete.php');
    exit;
}

// Ambil data buku berdasarkan ID
$sql = "SELECT * FROM buku WHERE id_buku = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header('Location: delete.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm_delete = $_POST['confirm_delete'] ?? '';
    
    if ($confirm_delete === 'yes') {
        // Delete associated files first
        if (!empty($book['file_pdf']) && file_exists('../uploads/pdfs/' . $book['file_pdf'])) {
            unlink('../uploads/pdfs/' . $book['file_pdf']);
        }
        
        if (!empty($book['gambar']) && !filter_var($book['gambar'], FILTER_VALIDATE_URL) && file_exists('../uploads/images/' . $book['gambar'])) {
            unlink('../uploads/images/' . $book['gambar']);
        }
        
        // Delete from database
        $sql = "DELETE FROM buku WHERE id_buku = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success_message = "✅ Buku berhasil dihapus!";
            $book = null; // Clear book data after successful deletion
        } else {
            $error_message = "Gagal menghapus buku: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Konfirmasi penghapusan diperlukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Buku - Backend System</title>
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
            max-width: 600px;
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

        .delete-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .book-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #dc3545;
        }

        .book-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .book-details {
            color: #666;
            line-height: 1.6;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .warning-title {
            color: #856404;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .warning-text {
            color: #856404;
            line-height: 1.6;
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

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: #dc3545;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
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

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="delete.php" class="back-btn">← Kembali ke Daftar Hapus</a>
        
        <div class="header">
            <h1>🗑️ Konfirmasi Hapus Buku</h1>
            <p>Konfirmasi penghapusan buku dari sistem</p>
        </div>

        <div class="delete-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                    <br><br>
                    <a href="delete.php" class="btn btn-secondary">Kembali ke Daftar Hapus</a>
                </div>
            <?php elseif ($book): ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="book-info">
                    <div class="book-title"><?php echo htmlspecialchars($book['judul']); ?></div>
                    <div class="book-details">
                        <strong>Penulis:</strong> <?php echo htmlspecialchars($book['penulis']); ?><br>
                        <strong>Kategori:</strong> <?php echo htmlspecialchars($book['kategori']); ?><br>
                        <strong>Penerbit:</strong> <?php echo htmlspecialchars($book['penerbit']); ?><br>
                        <strong>Tahun Terbit:</strong> <?php echo htmlspecialchars($book['tahun_terbit']); ?><br>
                        <strong>Jumlah Halaman:</strong> <?php echo htmlspecialchars($book['jumlah_halaman']); ?>
                    </div>
                </div>

                <div class="warning-box">
                    <div class="warning-title">
                        ⚠️ PERINGATAN
                    </div>
                    <div class="warning-text">
                        <strong>Tindakan ini tidak dapat dibatalkan!</strong><br>
                        Semua data buku, file PDF, dan gambar cover akan dihapus secara permanen dari sistem.
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="confirm_delete">Konfirmasi Penghapusan *</label>
                        <select id="confirm_delete" name="confirm_delete" required>
                            <option value="">Pilih konfirmasi</option>
                            <option value="no">Tidak, saya tidak ingin menghapus</option>
                            <option value="yes">Ya, saya yakin ingin menghapus buku ini</option>
                        </select>
                    </div>

                    <div class="button-group">
                        <a href="delete.php" class="btn btn-secondary">❌ Batal</a>
                        <button type="submit" class="btn btn-danger">🗑️ Hapus Buku</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 