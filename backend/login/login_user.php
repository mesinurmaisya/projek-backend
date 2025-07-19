<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../config.php';

// CSS untuk tampilan form dan hasil login
$css = '<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 400px;
        margin: 60px auto;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 30px 30px 20px 30px;
    }
    .back-btn {
        display: inline-block;
        padding: 10px 30px;
        background: rgba(102,126,234,0.7);
        color: white;
        text-decoration: none;
        border-radius: 25px;
        margin-bottom: 25px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        text-align: center;
    }
    .back-btn:hover {
        background: rgba(102,126,234,0.9);
        transform: translateY(-2px);
    }
    h2 {
        text-align: center;
        color: #667eea;
        margin-bottom: 25px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    input[type="text"], input[type="password"] {
        padding: 12px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }
    input[type="text"]:focus, input[type="password"]:focus {
        outline: none;
        border-color: #667eea;
    }
    button {
        padding: 12px;
        background: #667eea;
        color: #fff;
        border: none;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.3s ease;
    }
    button:hover {
        background: #5a6fd8;
        transform: translateY(-2px);
    }
    .msg {
        text-align: center;
        margin-bottom: 15px;
        padding: 12px;
        border-radius: 8px;
        font-size: 15px;
    }
    .msg-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .msg-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    pre {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        font-size: 14px;
        overflow-x: auto;
    }
    a {
        display: inline-block;
        margin-top: 10px;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    a:hover {
        color: #764ba2;
    }
</style>';

$backBtn = '<a href="index.php" class="back-btn">&larr; Kembali ke Manajemen User</a>';

// Jika GET, tampilkan form login HTML
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login User</title>' . $css . '</head><body>';
    echo '<div class="container">';
    echo $backBtn;
    echo '<h2>Form Login User</h2>';
    echo '<form method="post">';
    echo '<input type="text" name="email" placeholder="Email">';
    echo '<input type="password" name="password" placeholder="Password">';
    echo '<button type="submit">Login</button>';
    echo '</form>';
    echo '</div>';
    echo '</body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

// Ambil data dari JSON body atau dari form POST
$data = json_decode(file_get_contents("php://input"));
$email = $data->email ?? ($_POST['email'] ?? '');
$password = $data->password ?? ($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    if (!empty($_POST)) {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login User</title>' . $css . '</head><body>';
        echo '<div class="container">';
        echo $backBtn;
        echo '<div class="msg msg-error">Email dan password wajib diisi</div>';
        echo '<a href="login_user.php">Kembali</a>';
        echo '</div></body></html>';
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Email dan password wajib diisi"]);
    }
    exit;
}

$stmt = $conn->prepare("SELECT id, nama, email, password FROM users WHERE email = ?");
if (!$stmt) {
    if (!empty($_POST)) {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login User</title>' . $css . '</head><body>';
        echo '<div class="container">';
        echo $backBtn;
        echo '<div class="msg msg-error">Database error: ' . htmlspecialchars($conn->error) . '</div>';
        echo '<a href="login_user.php">Kembali</a>';
        echo '</div></body></html>';
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Database error: " . $conn->error]);
    }
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        unset($user['password']);
        if (!empty($_POST)) {
            // Redirect ke halaman detail user jika login dari form
            header('Location: view_user.php?id=' . $user['id']);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Login berhasil", "user" => $user]);
        }
    } else {
        if (!empty($_POST)) {
            echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login User</title>' . $css . '</head><body>';
            echo '<div class="container">';
            echo $backBtn;
            echo '<div class="msg msg-error">Password salah</div>';
            echo '<a href="login_user.php">Kembali</a>';
            echo '</div></body></html>';
        } else {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Password salah"]);
        }
    }
} else {
    if (!empty($_POST)) {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login User</title>' . $css . '</head><body>';
        echo '<div class="container">';
        echo $backBtn;
        echo '<div class="msg msg-error">Email tidak ditemukan</div>';
        echo '<a href="login_user.php">Kembali</a>';
        echo '</div></body></html>';
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Email tidak ditemukan"]);
    }
}
?>
