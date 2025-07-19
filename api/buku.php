<?php
// API endpoint untuk Buku (GET, POST, PUT, DELETE)
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
        // Ambil semua buku atau satu buku (by id)
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM buku WHERE id_buku = $id";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($result);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Buku tidak ditemukan']);
            }
        } else {
            $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
        }
        break;
    case 'POST':
        // Tambah buku baru
        $input = json_decode(file_get_contents('php://input'), true);
        $judul = $input['judul'] ?? '';
        $penulis = $input['penulis'] ?? '';
        $kategori = $input['kategori'] ?? '';
        $tahun_terbit = $input['tahun_terbit'] ?? '';
        $jumlah_halaman = $input['jumlah_halaman'] ?? '';
        $penerbit = $input['penerbit'] ?? '';
        $deskripsi = $input['deskripsi'] ?? '';
        // gambar dan file_pdf bisa ditambah jika upload file
        $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, kategori, tahun_terbit, jumlah_halaman, penerbit, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $judul, $penulis, $kategori, $tahun_terbit, $jumlah_halaman, $penerbit, $deskripsi);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Buku berhasil ditambahkan']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan buku']);
        }
        break;
    case 'PUT':
        // Update buku
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID buku diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $input = json_decode(file_get_contents('php://input'), true);
        $judul = $input['judul'] ?? '';
        $penulis = $input['penulis'] ?? '';
        $kategori = $input['kategori'] ?? '';
        $tahun_terbit = $input['tahun_terbit'] ?? '';
        $jumlah_halaman = $input['jumlah_halaman'] ?? '';
        $penerbit = $input['penerbit'] ?? '';
        $deskripsi = $input['deskripsi'] ?? '';
        $stmt = $conn->prepare("UPDATE buku SET judul=?, penulis=?, kategori=?, tahun_terbit=?, jumlah_halaman=?, penerbit=?, deskripsi=? WHERE id_buku=?");
        $stmt->bind_param("sssssssi", $judul, $penulis, $kategori, $tahun_terbit, $jumlah_halaman, $penerbit, $deskripsi, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Buku berhasil diupdate']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update buku']);
        }
        break;
    case 'DELETE':
        // Hapus buku
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID buku diperlukan']);
            exit();
        }
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM buku WHERE id_buku=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Buku berhasil dihapus']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal hapus buku']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
        break;
} 