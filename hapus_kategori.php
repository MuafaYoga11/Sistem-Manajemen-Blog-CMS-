<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

// Cek apakah kategori masih punya artikel
$cek = $koneksi->prepare("SELECT COUNT(*) as total FROM artikel WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
$row = $cek->get_result()->fetch_assoc();
$cek->close();

if ($row['total'] > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Kategori tidak bisa dihapus karena masih memiliki ' . $row['total'] . ' artikel']);
    $koneksi->close();
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
}

$stmt->close();
$koneksi->close();
