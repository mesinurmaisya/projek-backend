<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend Management System</title>
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
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .nav-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .nav-card h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .nav-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .nav-card .features {
            list-style: none;
            margin-top: 15px;
        }

        .nav-card .features li {
            padding: 5px 0;
            color: #555;
            font-size: 0.9rem;
        }

        .nav-card .features li:before {
            content: "✓";
            color: #667eea;
            font-weight: bold;
            margin-right: 8px;
        }

        .status {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: white;
            margin-top: 30px;
        }

        .status h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
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
                gap: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Backend Management System</h1>
            <p>Sistem manajemen backend untuk buku, login, dan review</p>
        </div>

        <div class="nav-grid">
            <a href="buku/" class="nav-card">
                <h2>📖 Manajemen Buku</h2>
                <p>Kelola data buku dengan operasi CRUD lengkap</p>
                <ul class="features">
                    <li>Create - Tambah buku baru</li>
                    <li>Read - Lihat daftar buku</li>
                    <li>Update - Edit data buku</li>
                    <li>Delete - Hapus buku</li>
                </ul>
            </a>

            <a href="login/" class="nav-card">
                <h2>🔐 Manajemen User</h2>
                <p>Sistem autentikasi dan manajemen pengguna</p>
                <ul class="features">
                    <li>Login - Masuk ke sistem</li>
                    <li>Create - Daftar user baru</li>
                    <li>Read - Lihat data user</li>
                    <li>Update - Edit profil user</li>
                    <li>Delete - Hapus user</li>
                </ul>
            </a>

            <a href="review/" class="nav-card">
                <h2>⭐ Sistem Review</h2>
                <p>Kelola review dan rating buku</p>
                <ul class="features">
                    <li>Create - Tambah review baru</li>
                    <li>Read - Lihat semua review</li>
                    <li>Update - Edit review</li>
                    <li>Delete - Hapus review</li>
                </ul>
            </a>
        </div>

        <div class="status">
            <h3>🟢 Status Sistem</h3>
            <p>Backend PHP berjalan dengan baik</p>
            <p>Waktu server: <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>
    </div>
</body>
</html> 