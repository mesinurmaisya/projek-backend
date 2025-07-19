<?php
include '../config.php';

$success_message = '';
$error_message = '';

// Handle form submission for deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? '';
    $confirm_delete = $_POST['confirm_delete'] ?? '';
    
    if ($confirm_delete === 'yes') {
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM review WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success_message = "✅ Review berhasil dihapus!";
        } else {
            $error_message = "Gagal menghapus review: " . $stmt->error;
        }
    } else {
        $error_message = "Konfirmasi penghapusan diperlukan.";
    }
}

// Ambil semua data review
$sql = "SELECT * FROM review ORDER BY id DESC";
$query = mysqli_query($conn, $sql);

$reviews = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $reviews[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Review - Backend System</title>
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

        .reviews-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .reviews-table th,
        .reviews-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }

        .reviews-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .reviews-table tr:hover {
            background: #f8f9fa;
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

        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        .review-name {
            font-weight: 600;
            color: #333;
        }

        .review-email {
            color: #666;
            font-size: 14px;
        }

        .review-rating {
            color: #ffd700;
            font-size: 18px;
        }

        .review-message {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            .reviews-table {
                font-size: 14px;
            }
            
            .reviews-table th,
            .reviews-table td {
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
        <a href="index.php" class="back-btn">← Kembali ke Manajemen Review</a>
        
        <div class="header">
            <h1>🗑️ Hapus Review</h1>
            <p>Pilih review yang ingin dihapus</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="create.php" class="add-btn">➕ Tambah Review Baru</a>
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
                    Semua data review akan dihapus secara permanen dari sistem.
                </div>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <h3>⭐ Belum ada review</h3>
                    <p>Klik "Tambah Review Baru" untuk menambahkan review pertama</p>
                </div>
            <?php else: ?>
                <table class="reviews-table">
                    <thead>
                        <tr>
                            <th>Nama & Email</th>
                            <th>Rating</th>
                            <th>Pesan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td>
                                    <div class="review-name"><?php echo htmlspecialchars($review['nama']); ?></div>
                                    <div class="review-email"><?php echo htmlspecialchars($review['email']); ?></div>
                                </td>
                                <td>
                                    <div class="review-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $review['rating'] ? '★' : '☆';
                                        }
                                        ?>
                                        <span style="color: #666; font-size: 14px;">(<?php echo $review['rating']; ?>/5)</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-message" title="<?php echo htmlspecialchars($review['pesan']); ?>">
                                        <?php echo htmlspecialchars($review['pesan']); ?>
                                    </div>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'] ?? 'now')); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $review['id']; ?>" class="btn btn-view">👁️ Lihat</a>
                                        <a href="delete_confirm.php?id=<?php echo $review['id']; ?>" class="btn btn-delete">🗑️ Hapus</a>
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
