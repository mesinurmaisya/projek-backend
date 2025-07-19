<?php
include '../config.php';

$success_message = '';
$error_message = '';
$user = null;

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $biodata = $_POST['biodata'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi
    if (empty($nama) || empty($email)) {
        $error_message = "Nama dan email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        // Cek apakah email sudah digunakan user lain
        $cek = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $cek->bind_param("si", $email, $id);
        $cek->execute();
        $cek->store_result();
        
        if ($cek->num_rows > 0) {
            $error_message = "Email sudah digunakan user lain.";
        } else {
            // Handle password update if provided
            $password_update = "";
            $params = [$nama, $email, $biodata];
            $types = "sss";
            
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $error_message = "Password minimal 6 karakter.";
                } elseif ($new_password !== $confirm_password) {
                    $error_message = "Password dan konfirmasi password tidak sama.";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $params[] = $hashed_password;
                    $types .= "s";
                    $password_update = ", password = ?";
                }
            }
            
            if (empty($error_message)) {
                $params[] = $id;
                $types .= "i";
                
                $sql = "UPDATE users SET nama = ?, email = ?, biodata = ?" . $password_update . " WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                
                if ($stmt->execute()) {
                    $success_message = "User berhasil diperbarui!";
                    // Refresh user data
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                } else {
                    $error_message = "Gagal memperbarui user: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Backend System</title>
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
            max-width: 700px;
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

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        }

        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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

        .password-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .password-section h3 {
            color: #667eea;
            margin-bottom: 15px;
        }

        .optional-text {
            color: #666;
            font-size: 14px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="read_user.php" class="back-btn">← Kembali ke Daftar User</a>
        
        <div class="header">
            <h1>✏️ Edit User</h1>
            <p>Update informasi user: <?php echo htmlspecialchars($user['nama']); ?></p>
        </div>

        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="biodata">Biodata</label>
                    <textarea id="biodata" name="biodata" placeholder="Tulis biodata user (opsional)"><?php echo htmlspecialchars($user['biodata'] ?? ''); ?></textarea>
                </div>

                <div class="password-section">
                    <h3>🔐 Update Password</h3>
                    <p class="optional-text">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn">✏️ Update User</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
