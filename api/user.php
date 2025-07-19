<?php
// API endpoint untuk User (GET, POST, PUT, DELETE)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include '../config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ambil semua user atau satu user (by id)
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT id, nama, email, created_at FROM users WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($result);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            }
        } else {
            $sql = "SELECT id, nama, email, created_at FROM users ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
        }
        break;
    case 'POST':
        // Tambah user baru
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        if (empty($nama) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nama, email, dan password wajib diisi']);
            exit();
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $hashed);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan user']);
        }
        break;
    case 'PUT':
        // Update user
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID user diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $stmt = $conn->prepare("UPDATE users SET nama=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $nama, $email, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User berhasil diupdate']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update user']);
        }
        break;
    case 'DELETE':
        // Hapus user
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID user diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User berhasil dihapus']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal hapus user']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
        break;
} 