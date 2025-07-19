<?php
include '../config.php';

$success_message = '';
$error_message = '';
$review = null;

// Get review ID from URL
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: update.php');
    exit();
}

// Fetch review data
$stmt = $conn->prepare("SELECT * FROM review WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    header('Location: update.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    $pesan = $_POST['pesan'] ?? '';

    // Validasi input
    if (empty($nama) || empty($email) || empty($pesan)) {
        $error_message = "Nama, email, dan pesan wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid";
    } elseif ($rating < 1 || $rating > 5) {
        $error_message = "Rating harus antara 1-5";
    } elseif (strlen($pesan) < 10) {
        $error_message = "Pesan minimal 10 karakter";
    } else {
        // Update review
        $stmt = $conn->prepare("UPDATE review SET nama = ?, email = ?, rating = ?, pesan = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $nama, $email, $rating, $pesan, $id);
        
        if ($stmt->execute()) {
            $success_message = "✅ Review berhasil diperbarui!";
            // Update review data for display
            $review = array_merge($review, $_POST);
        } else {
            $error_message = "Gagal memperbarui review: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - Backend System</title>
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
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 24px;
            cursor: pointer;
            color: #ddd;
            transition: color 0.2s ease;
        }

        .star.filled {
            color: #ffd700;
        }

        .star:hover,
        .star:hover ~ .star {
            color: #ffd700;
        }

        .rating-text {
            font-size: 14px;
            color: #666;
            margin-left: 10px;
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

        .required {
            color: #dc3545;
        }

        .form-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .form-info ul {
            margin: 10px 0 0 20px;
        }

        .review-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .review-info h4 {
            margin-bottom: 10px;
            color: #495057;
        }

        .review-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        .review-detail .stars {
            color: #ffd700;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="update.php" class="back-btn">← Kembali ke Daftar Update</a>
        
        <div class="header">
            <h1>✏️ Edit Review</h1>
            <p>Perbarui informasi review</p>
        </div>

        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">❌ <?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="review-info">
                <h4>📝 Review Saat Ini:</h4>
                <div class="review-detail">
                    <div>
                        <strong>Nama:</strong> <?php echo htmlspecialchars($review['nama']); ?>
                    </div>
                    <div class="stars">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $review['rating'] ? '★' : '☆';
                        }
                        ?>
                    </div>
                </div>
                <div class="review-detail">
                    <div>
                        <strong>Email:</strong> <?php echo htmlspecialchars($review['email']); ?>
                    </div>
                    <div>
                        <strong>Rating:</strong> <?php echo $review['rating']; ?>/5
                    </div>
                </div>
                <div class="review-detail">
                    <div>
                        <strong>Pesan:</strong> <?php echo htmlspecialchars($review['pesan']); ?>
                    </div>
                </div>
            </div>

            <div class="form-info">
                <strong>📋 Informasi Review:</strong>
                <ul>
                    <li>Rating: Pilih bintang 1-5 untuk menilai perpustakaan</li>
                    <li>Pesan: Minimal 10 karakter untuk memberikan feedback yang bermakna</li>
                    <li>Email: Akan digunakan untuk verifikasi (tidak akan dipublikasikan)</li>
                </ul>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($review['nama']); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="contoh@email.com" value="<?php echo htmlspecialchars($review['email']); ?>">
                </div>

                <div class="form-group">
                    <label>Rating <span class="required">*</span></label>
                    <div class="rating-container">
                        <div class="rating-stars">
                            <?php
                            $current_rating = $review['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                $star_class = $i <= $current_rating ? 'filled' : '';
                                echo "<span class='star $star_class' data-rating='$i'>★</span>";
                            }
                            ?>
                        </div>
                        <span class="rating-text">(<?php echo $current_rating; ?>/5)</span>
                    </div>
                    <input type="hidden" name="rating" id="rating_input" value="<?php echo $current_rating; ?>">
                </div>

                <div class="form-group">
                    <label for="pesan">Pesan/Review <span class="required">*</span></label>
                    <textarea id="pesan" name="pesan" required placeholder="Tulis review atau feedback Anda (minimal 10 karakter)"><?php echo htmlspecialchars($review['pesan']); ?></textarea>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn">✏️ Update Review</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Rating stars functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating_input');
        const ratingText = document.querySelector('.rating-text');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                ratingText.textContent = `(${rating}/5)`;
                
                // Update star display
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('filled');
                    } else {
                        s.classList.remove('filled');
                    }
                });
            });

            star.addEventListener('mouseenter', function() {
                const rating = this.getAttribute('data-rating');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('filled');
                    } else {
                        s.classList.remove('filled');
                    }
                });
            });
        });

        // Reset stars on mouse leave
        document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.classList.add('filled');
                } else {
                    s.classList.remove('filled');
                }
            });
        });
    </script>
</body>
</html> 