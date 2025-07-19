<?php
include '../config.php';

// Ambil ID user dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: read_user.php');
    exit;
}

// Ambil data user berdasarkan ID
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: read_user.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Backend System</title>
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

        .user-detail {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .user-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 32px;
            margin-right: 30px;
        }

        .user-info h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .user-email {
            color: #666;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .user-status {
            background: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }

        .user-content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .content-text {
            color: #555;
            line-height: 1.8;
            font-size: 16px;
        }

        .user-meta {
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
            .user-section {
                flex-direction: column;
                text-align: center;
            }
            
            .user-avatar {
                margin-right: 0;
                margin-bottom: 20px;
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
        <a href="read_user.php" class="back-btn">← Kembali ke Daftar User</a>
        
        <div class="header">
            <h1>👤 Detail User</h1>
            <p>Informasi lengkap user: <?php echo htmlspecialchars($user['nama']); ?></p>
        </div>

        <div class="user-detail">
            <div class="user-section">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['nama'], 0, 1)); ?>
                </div>
                <div class="user-info">
                    <h2><?php echo htmlspecialchars($user['nama']); ?></h2>
                    <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    <div class="user-status">✅ User Aktif</div>
                </div>
            </div>

            <?php if (!empty($user['biodata'])): ?>
            <div class="user-content">
                <div class="content-title">📝 Biodata</div>
                <div class="content-text">
                    <?php echo nl2br(htmlspecialchars($user['biodata'])); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="user-meta">
                <div class="meta-title">Informasi User</div>
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-label">ID User</div>
                        <div class="meta-value">#<?php echo $user['id']; ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Status</div>
                        <div class="meta-value">Aktif</div>
                    </div>
                    <?php if (isset($user['created_at'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">Tanggal Daftar</div>
                        <div class="meta-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Waktu Daftar</div>
                        <div class="meta-value"><?php echo date('H:i', strtotime($user['created_at'])); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($user['biodata'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">Panjang Biodata</div>
                        <div class="meta-value"><?php echo strlen($user['biodata']); ?> karakter</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="action-buttons">
                <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">✏️ Edit User</a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-delete">🗑️ Hapus User</a>
            </div>
        </div>
    </div>
</body>
</html> 