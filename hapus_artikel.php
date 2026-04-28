<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

// Ambil gambar untuk dihapus
$getGambar = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$getGambar->bind_param("i", $id);
$getGambar->execute();
$gambarRow = $getGambar->get_result()->fetch_assoc();
$getGambar->close();

$stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($gambarRow && file_exists('uploads_artikel/' . $gambarRow['gambar'])) {
        unlink('uploads_artikel/' . $gambarRow['gambar']);
    }
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus artikel']);
}

$stmt->close();
$koneksi->close();
