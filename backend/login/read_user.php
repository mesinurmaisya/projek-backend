<?php
include '../config.php';

// Ambil semua data user
$sql = "SELECT id, nama, email, created_at FROM users ORDER BY id DESC";
$query = mysqli_query($conn, $sql);

$users = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - Backend System</title>
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

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .users-table th,
        .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }

        .users-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .users-table tr:hover {
            background: #f8f9fa;
        }

        .user-avatar {
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

        .no-users {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        .user-name {
            font-weight: 600;
            color: #333;
        }

        .user-email {
            color: #666;
            font-size: 14px;
        }

        .user-date {
            color: #999;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .users-table {
                font-size: 14px;
            }
            
            .users-table th,
            .users-table td {
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
        <a href="index.php" class="back-btn">← Kembali ke Manajemen User</a>
        
        <div class="header">
            <h1>👥 Daftar User</h1>
            <p>Kelola semua user dalam sistem</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="create_user.php" class="add-btn">👤 Tambah User Baru</a>
        </div>

        <div class="table-container">
            <?php if (empty($users)): ?>
                <div class="no-users">
                    <h3>👥 Belum ada user</h3>
                    <p>Klik "Tambah User Baru" untuk menambahkan user pertama</p>
                </div>
            <?php else: ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Nama & Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user['nama'], 0, 1)); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-name"><?php echo htmlspecialchars($user['nama']); ?></div>
                                    <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                </td>
                                <td>
                                    <div class="user-date">
                                        <?php 
                                        if (isset($user['created_at'])) {
                                            echo date('d/m/Y H:i', strtotime($user['created_at']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view_user.php?id=<?php echo $user['id']; ?>" class="btn btn-view">👁️ Lihat</a>
                                        <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">✏️ Edit</a>
                                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus user ini?')">🗑️ Hapus</a>
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
