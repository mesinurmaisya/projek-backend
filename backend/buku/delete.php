<?php
include '../config.php';

$success_message = '';
$error_message = '';

// Handle form submission for deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id_buku'] ?? '';
    $confirm_delete = $_POST['confirm_delete'] ?? '';
    
    if ($confirm_delete === 'yes') {
        // Get book data first
        $stmt = $conn->prepare("SELECT gambar, file_pdf FROM buku WHERE id_buku = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        
        if ($book) {
            // Delete associated files first
            if (!empty($book['file_pdf']) && file_exists('../uploads/pdfs/' . $book['file_pdf'])) {
                unlink('../uploads/pdfs/' . $book['file_pdf']);
            }
            
            if (!empty($book['gambar']) && !filter_var($book['gambar'], FILTER_VALIDATE_URL) && file_exists('../uploads/images/' . $book['gambar'])) {
                unlink('../uploads/images/' . $book['gambar']);
            }
            
            // Delete from database
            $stmt = $conn->prepare("DELETE FROM buku WHERE id_buku = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success_message = "✅ Buku berhasil dihapus!";
            } else {
                $error_message = "Gagal menghapus buku: " . $stmt->error;
            }
        } else {
            $error_message = "Buku tidak ditemukan.";
        }
    } else {
        $error_message = "Konfirmasi penghapusan diperlukan.";
    }
}

// Ambil semua data buku
$sql = "SELECT * FROM buku ORDER BY id_buku DESC";
$query = mysqli_query($conn, $sql);

$books = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Buku - Backend System</title>
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
            max-width: 1200px;
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

        .add-btn {
            display: inline-block;
            padding: 12px 25px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .add-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .books-table th,
        .books-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }

        .books-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .books-table tr:hover {
            background: #f8f9fa;
        }

        .book-cover {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .no-books {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        .book-title {
            font-weight: 600;
            color: #333;
            max-width: 200px;
        }

        .book-author {
            color: #666;
            font-size: 14px;
        }

        .book-category {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            color: #495057;
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

        @media (max-width: 768px) {
            .books-table {
                font-size: 14px;
            }
            
            .books-table th,
            .books-table td {
                padding: 8px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">← Kembali ke Manajemen Buku</a>
        
        <div class="header">
            <h1>🗑️ Hapus Buku</h1>
            <p>Pilih buku yang ingin dihapus</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="create.php" class="add-btn">➕ Tambah Buku Baru</a>
        </div>

        <div class="table-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="warning-box">
                <div class="warning-title">
                    ⚠️ PERINGATAN
                </div>
                <div class="warning-text">
                    <strong>Tindakan penghapusan tidak dapat dibatalkan!</strong><br>
                    Semua data buku, file PDF, dan gambar cover akan dihapus secara permanen dari sistem.
                </div>
            </div>

            <?php if (empty($books)): ?>
                <div class="no-books">
                    <h3>📚 Belum ada buku</h3>
                    <p>Klik "Tambah Buku Baru" untuk menambahkan buku pertama</p>
                </div>
            <?php else: ?>
                <table class="books-table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Judul & Penulis</th>
                            <th>Kategori</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Halaman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($book['gambar'])): ?>
                                        <?php if (filter_var($book['gambar'], FILTER_VALIDATE_URL)): ?>
                                            <img src="<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover" class="book-cover">
                                        <?php else: ?>
                                            <img src="../uploads/images/<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover" class="book-cover">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div style="width: 60px; height: 80px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #666;">
                                            📚
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="book-title"><?php echo htmlspecialchars($book['judul']); ?></div>
                                    <div class="book-author"><?php echo htmlspecialchars($book['penulis']); ?></div>
                                </td>
                                <td>
                                    <span class="book-category"><?php echo htmlspecialchars($book['kategori']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($book['penerbit']); ?></td>
                                <td><?php echo htmlspecialchars($book['tahun_terbit']); ?></td>
                                <td><?php echo htmlspecialchars($book['jumlah_halaman']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-view">👁️ Lihat</a>
                                        <a href="delete_confirm.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-delete">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
