<?php
include '../config.php';

// Ambil ID review dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: read.php');
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
    header('Location: read.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Review - Backend System</title>
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

        .review-detail {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .reviewer-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }

        .reviewer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            margin-right: 20px;
        }

        .reviewer-info h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .reviewer-email {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .rating-section {
            margin-bottom: 30px;
        }

        .rating-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }

        .star {
            color: #ffc107;
            font-size: 24px;
        }

        .star.empty {
            color: #ddd;
        }

        .rating-text {
            color: #666;
            font-size: 14px;
        }

        .review-content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .review-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .review-message {
            color: #555;
            line-height: 1.8;
            font-size: 16px;
        }

        .review-meta {
            background: #e9ecef;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .meta-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .meta-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
        }

        .meta-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .meta-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
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

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .reviewer-section {
                flex-direction: column;
                text-align: center;
            }
            
            .reviewer-avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .meta-grid {
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
        <a href="read.php" class="back-btn">← Kembali ke Daftar Review</a>
        
        <div class="header">
            <h1>⭐ Detail Review</h1>
            <p>Informasi lengkap review dari <?php echo htmlspecialchars($review['nama']); ?></p>
        </div>

        <div class="review-detail">
            <div class="reviewer-section">
                <div class="reviewer-avatar">
                    <?php echo strtoupper(substr($review['nama'], 0, 1)); ?>
                </div>
                <div class="reviewer-info">
                    <h2><?php echo htmlspecialchars($review['nama']); ?></h2>
                    <div class="reviewer-email"><?php echo htmlspecialchars($review['email']); ?></div>
                </div>
            </div>

            <div class="rating-section">
                <div class="rating-title">Rating</div>
                <div class="rating-stars">
                    <?php 
                    $rating = (int)$review['rating'];
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<span class="star">⭐</span>';
                        } else {
                            echo '<span class="star empty">☆</span>';
                        }
                    }
                    ?>
                </div>
                <div class="rating-text">
                    <?php 
                    $rating_texts = [
                        1 => 'Sangat Kurang',
                        2 => 'Kurang', 
                        3 => 'Cukup',
                        4 => 'Bagus',
                        5 => 'Sangat Bagus'
                    ];
                    echo $rating_texts[$rating] ?? 'Tidak ada rating';
                    ?>
                </div>
            </div>

            <div class="review-content">
                <div class="review-title">Review/Pesan</div>
                <div class="review-message">
                    <?php echo nl2br(htmlspecialchars($review['pesan'])); ?>
                </div>
            </div>

            <div class="review-meta">
                <div class="meta-title">Informasi Review</div>
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-label">ID Review</div>
                        <div class="meta-value">#<?php echo $review['id']; ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Rating</div>
                        <div class="meta-value"><?php echo $review['rating']; ?>/5</div>
                    </div>
                    <?php if (isset($review['created_at'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">Tanggal Review</div>
                        <div class="meta-value"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="meta-item">
                        <div class="meta-label">Panjang Pesan</div>
                        <div class="meta-value"><?php echo strlen($review['pesan']); ?> karakter</div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="update.php?id=<?php echo $review['id']; ?>" class="btn btn-edit">✏️ Edit Review</a>
                <a href="delete.php?id=<?php echo $review['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus review ini?')">🗑️ Hapus Review</a>
            </div>
        </div>
    </div>
</body>
</html> 