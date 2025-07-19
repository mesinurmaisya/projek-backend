<?php
// API endpoint untuk Review (GET, POST, PUT, DELETE)
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
        // Ambil semua review atau satu review (by id)
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM review WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($result);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Review tidak ditemukan']);
            }
        } else {
            $sql = "SELECT * FROM review ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
        }
        break;
    case 'POST':
        // Tambah review baru
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $rating = $input['rating'] ?? 0;
        $pesan = $input['pesan'] ?? '';
        $stmt = $conn->prepare("INSERT INTO review (nama, email, rating, pesan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nama, $email, $rating, $pesan);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review berhasil ditambahkan']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan review']);
        }
        break;
    case 'PUT':
        // Update review
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID review diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $rating = $input['rating'] ?? 0;
        $pesan = $input['pesan'] ?? '';
        $stmt = $conn->prepare("UPDATE review SET nama=?, email=?, rating=?, pesan=? WHERE id=?");
        $stmt->bind_param("ssisi", $nama, $email, $rating, $pesan, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review berhasil diupdate']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update review']);
        }
        break;
    case 'DELETE':
        // Hapus review
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID review diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM review WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review berhasil dihapus']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal hapus review']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
        break;
} 