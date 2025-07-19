<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../config.php';

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
    <title>Daftar Review - Backend System</title>
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

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .review-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-right: 15px;
        }

        .reviewer-info h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .reviewer-email {
            color: #666;
            font-size: 14px;
        }

        .rating-stars {
            margin: 10px 0;
        }

        .star {
            color: #ffc107;
            font-size: 18px;
        }

        .star.empty {
            color: #ddd;
        }

        .review-message {
            color: #555;
            line-height: 1.6;
            margin: 15px 0;
            font-size: 14px;
        }

        .review-date {
            color: #999;
            font-size: 12px;
            margin-top: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 15px;
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

        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .reviews-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .review-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">← Kembali ke Sistem Review</a>
        
        <div class="header">
            <h1>⭐ Daftar Review</h1>
            <p>Kelola semua review dalam sistem</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="create.php" class="add-btn">⭐ Tambah Review Baru</a>
        </div>

        <div class="reviews-grid">
            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <h3>⭐ Belum ada review</h3>
                    <p>Klik "Tambah Review Baru" untuk menambahkan review pertama</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-avatar">
                                <?php echo strtoupper(substr($review['nama'], 0, 1)); ?>
                            </div>
                            <div class="reviewer-info">
                                <h3><?php echo htmlspecialchars($review['nama']); ?></h3>
                                <div class="reviewer-email"><?php echo htmlspecialchars($review['email']); ?></div>
                            </div>
                        </div>

                        <div class="rating-stars">
                            <?php 
                            $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<span class="star">⭐</span>';
                                } else {
                                    echo '<span class="star empty">☆</span>';
                                }
                            }
                            ?>
                            <span style="margin-left: 10px; color: #666; font-size: 14px;">
                                (<?php echo $rating; ?>/5)
                            </span>
                        </div>

                        <div class="review-message">
                            <?php echo nl2br(htmlspecialchars($review['pesan'])); ?>
                        </div>

                        <div class="review-date">
                            <?php 
                            if (isset($review['created_at'])) {
                                echo date('d/m/Y H:i', strtotime($review['created_at']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>

                        <div class="action-buttons">
                            <a href="view.php?id=<?php echo $review['id']; ?>" class="btn btn-view">👁️ Lihat</a>
                            <a href="update.php?id=<?php echo $review['id']; ?>" class="btn btn-edit">✏️ Edit</a>
                            <a href="delete.php?id=<?php echo $review['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus review ini?')">🗑️ Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
