<?php
include '../config.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input kosong
    if (empty($nama) || empty($email) || empty($password)) {
        $error_message = "Nama, email, dan password wajib diisi";
    } elseif ($password !== $confirm_password) {
        $error_message = "Password dan konfirmasi password tidak sama";
    } elseif (strlen($password) < 6) {
        $error_message = "Password minimal 6 karakter";
    } else {
        // Cek apakah email sudah digunakan
        $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error_message = "Email sudah digunakan";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $hashed);

            if ($stmt->execute()) {
                $success_message = "User berhasil ditambahkan! User baru telah tersimpan dalam sistem.";
                $_POST = array(); // reset form
            } else {
                $error_message = "Gagal menambahkan user: " . $stmt->error;
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
    <title>Daftar User Baru - Backend System</title>
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

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
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

        .notif-success {
            background: #d4edda;
            color: #155724;
            border: 1.5px solid #c3e6cb;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .notif-icon {
            font-size: 22px;
            margin-right: 8px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">← Kembali ke Manajemen User</a>
        <div class="header">
            <h1>👤 Daftar User Baru</h1>
        </div>

        <!-- Notifikasi sukses/gagal -->
        <?php if (!empty($success_message)): ?>
            <div class="notif-success">
                <span class="notif-icon">&#10004;</span>
                <?= $success_message ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Tambah User</button>
            </form>
        </div>
    </div>
</body>
</html> 