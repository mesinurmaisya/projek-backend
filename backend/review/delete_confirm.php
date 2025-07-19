<?php
include '../config.php';

$success_message = '';
$error_message = '';
$review = null;

// Ambil ID review dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: delete.php');
    exit;
}

// Ambil data review berdasarkan ID
$sql = "SELECT * FROM review WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    header('Location: delete.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm_delete = $_POST['confirm_delete'] ?? '';
    
    if ($confirm_delete === 'yes') {
        // Delete from database
        $sql = "DELETE FROM review WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success_message = "✅ Review berhasil dihapus!";
            $review = null; // Clear review data after successful deletion
        } else {
            $error_message = "Gagal menghapus review: " . mysqli_error($conn);
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
    <title>Konfirmasi Hapus Review - Backend System</title>
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

        .review-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #dc3545;
        }

        .reviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin: 0 auto 15px;
        }

        .reviewer-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .review-details {
            color: #666;
            line-height: 1.6;
            text-align: center;
        }

        .rating-display {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 10px 0;
        }

        .star {
            color: #ffc107;
            font-size: 18px;
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
            <h1>🗑️ Konfirmasi Hapus Review</h1>
            <p>Konfirmasi penghapusan review dari sistem</p>
        </div>

        <div class="delete-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                    <br><br>
                    <a href="delete.php" class="btn btn-secondary">Kembali ke Daftar Hapus</a>
                </div>
            <?php elseif ($review): ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="review-info">
                    <div class="reviewer-avatar">
                        <?php echo strtoupper(substr($review['nama'], 0, 1)); ?>
                    </div>
                    <div class="reviewer-name"><?php echo htmlspecialchars($review['nama']); ?></div>
                    <div class="review-details">
                        <strong>Email:</strong> <?php echo htmlspecialchars($review['email']); ?><br>
                        <strong>Rating:</strong> 
                        <div class="rating-display">
                            <?php 
                            $rating = (int)$review['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<span class="star">⭐</span>';
                                } else {
                                    echo '<span class="star">☆</span>';
                                }
                            }
                            ?>
                        </div>
                        <strong>Pesan:</strong> <?php echo htmlspecialchars(substr($review['pesan'], 0, 100)) . (strlen($review['pesan']) > 100 ? '...' : ''); ?>
                    </div>
                </div>

                <div class="warning-box">
                    <div class="warning-title">
                        ⚠️ PERINGATAN
                    </div>
                    <div class="warning-text">
                        <strong>Tindakan ini tidak dapat dibatalkan!</strong><br>
                        Review dan rating akan dihapus secara permanen dari sistem.
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="confirm_delete">Konfirmasi Penghapusan *</label>
                        <select id="confirm_delete" name="confirm_delete" required>
                            <option value="">Pilih konfirmasi</option>
                            <option value="no">Tidak, saya tidak ingin menghapus</option>
                            <option value="yes">Ya, saya yakin ingin menghapus review ini</option>
                        </select>
                    </div>

                    <div class="button-group">
                        <a href="delete.php" class="btn btn-secondary">❌ Batal</a>
                        <button type="submit" class="btn btn-danger">🗑️ Hapus Review</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 