<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../config.php';

// Ambil semua data dari tabel buku
// Coba dengan nama kolom yang mungkin benar
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
    <title>Daftar Buku - Backend System</title>
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

        .btn-edit {
            background: #ffc107;
            color: #212529;
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
            <h1>📋 Daftar Buku</h1>
            <p>Kelola semua buku dalam sistem</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="create.php" class="add-btn">➕ Tambah Buku Baru</a>
        </div>

        <div class="table-container">
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
                                        <a href="update.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-edit">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus</a>
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
