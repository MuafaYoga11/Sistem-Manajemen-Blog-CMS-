<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

// Cek apakah penulis masih punya artikel
$cek = $koneksi->prepare("SELECT COUNT(*) as total FROM artikel WHERE id_penulis = ?");
$cek->bind_param("i", $id);
$cek->execute();
$row = $cek->get_result()->fetch_assoc();
$cek->close();

if ($row['total'] > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Penulis tidak bisa dihapus karena masih memiliki ' . $row['total'] . ' artikel']);
    $koneksi->close();
    exit;
}

// Ambil foto untuk dihapus
$getFoto = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$getFoto->bind_param("i", $id);
$getFoto->execute();
$fotoRow = $getFoto->get_result()->fetch_assoc();
$getFoto->close();

$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($fotoRow && $fotoRow['foto'] !== 'default.png' && file_exists('uploads_penulis/' . $fotoRow['foto'])) {
        unlink('uploads_penulis/' . $fotoRow['foto']);
    }
    echo json_encode(['status' => 'success', 'message' => 'Penulis berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
}

$stmt->close();
$koneksi->close();
