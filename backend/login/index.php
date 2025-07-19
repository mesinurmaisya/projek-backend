<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Backend System</title>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
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

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .nav-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            text-align: center;
        }

        .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .nav-card h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .nav-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .nav-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .nav-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../" class="back-btn">← Kembali ke Dashboard</a>
        
        <div class="header">
            <h1>🔐 Manajemen User</h1>
            <p>Sistem autentikasi dan manajemen pengguna</p>
        </div>

        <div class="nav-grid">
            <a href="login_user.php" class="nav-card">
                <span class="icon">🔑</span>
                <h2>Login User</h2>
                <p>Masuk ke sistem dengan akun yang sudah ada</p>
            </a>

            <a href="create_user.php" class="nav-card">
                <span class="icon">👤</span>
                <h2>Create User</h2>
                <p>Daftar user baru ke dalam sistem</p>
            </a>

            <a href="read_user.php" class="nav-card">
                <span class="icon">📋</span>
                <h2>Read User</h2>
                <p>Lihat daftar semua user yang terdaftar</p>
            </a>

            <a href="update_user.php" class="nav-card">
                <span class="icon">✏️</span>
                <h2>Update User</h2>
                <p>Edit dan update data user yang ada</p>
            </a>

            <a href="delete_user.php" class="nav-card">
                <span class="icon">🗑️</span>
                <h2>Delete User</h2>
                <p>Hapus user dari sistem</p>
            </a>
        </div>
    </div>
</body>
</html> 
