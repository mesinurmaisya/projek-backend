<?php
include '../config.php';

// Ambil ID buku dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: read.php');
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
    header('Location: read.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - Backend System</title>
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
            max-width: 900px;
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

        .book-detail {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
        }

        .book-cover-section {
            text-align: center;
        }

        .book-cover {
            width: 280px;
            height: 380px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .book-cover-placeholder {
            width: 280px;
            height: 380px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .book-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .book-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .book-author {
            font-size: 1.3rem;
            color: #666;
            font-style: italic;
        }

        .book-category {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .book-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .book-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .description-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .description-text {
            color: #555;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-download {
            background: #28a745;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .file-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .file-info h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .file-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .book-detail {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 25px;
            }
            
            .book-cover, .book-cover-placeholder {
                width: 250px;
                height: 340px;
            }
            
            .book-title {
                font-size: 1.8rem;
            }
            
            .book-details {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="read.php" class="back-btn">← Kembali ke Daftar Buku</a>
        
        <div class="header">
            <h1>📖 Detail Buku</h1>
            <p>Informasi lengkap buku</p>
        </div>

        <div class="book-detail">
            <div class="book-cover-section">
                <?php if (!empty($book['gambar'])): ?>
                    <?php if (filter_var($book['gambar'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover Buku" class="book-cover">
                    <?php else: ?>
                        <img src="../uploads/images/<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover Buku" class="book-cover">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="book-cover-placeholder">📚</div>
                <?php endif; ?>
            </div>

            <div class="book-info">
                <div>
                    <h1 class="book-title"><?php echo htmlspecialchars($book['judul']); ?></h1>
                    <p class="book-author">Oleh: <?php echo htmlspecialchars($book['penulis']); ?></p>
                    <span class="book-category"><?php echo htmlspecialchars($book['kategori']); ?></span>
                </div>

                <div class="book-details">
                    <div class="detail-item">
                        <div class="detail-label">Penerbit</div>
                        <div class="detail-value"><?php echo htmlspecialchars($book['penerbit']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tahun Terbit</div>
                        <div class="detail-value"><?php echo htmlspecialchars($book['tahun_terbit']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jumlah Halaman</div>
                        <div class="detail-value"><?php echo htmlspecialchars($book['jumlah_halaman']); ?> halaman</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">ID Buku</div>
                        <div class="detail-value">#<?php echo htmlspecialchars($book['id_buku']); ?></div>
                    </div>
                </div>

                <?php if (!empty($book['file_pdf'])): ?>
                    <div class="file-info">
                        <h4>📄 File PDF Tersedia</h4>
                        <a href="../uploads/pdfs/<?php echo htmlspecialchars($book['file_pdf']); ?>" class="file-link" target="_blank">
                            📥 Download PDF
                        </a>
                    </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="update.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-edit">✏️ Edit Buku</a>
                    <a href="delete.php?id=<?php echo $book['id_buku']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus Buku</a>
                    <?php if (!empty($book['file_pdf'])): ?>
                        <a href="../uploads/pdfs/<?php echo htmlspecialchars($book['file_pdf']); ?>" class="btn btn-download" target="_blank">📥 Download PDF</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 