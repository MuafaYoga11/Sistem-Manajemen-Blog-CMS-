<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$nama_kategori = trim($input['nama_kategori'] ?? '');
$keterangan    = trim($input['keterangan'] ?? '');

if ($nama_kategori === '') {
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori wajib diisi']);
    exit;
}

// Cek unik
$cek = $koneksi->prepare("SELECT id FROM kategori_artikel WHERE nama_kategori = ?");
$cek->bind_param("s", $nama_kategori);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori sudah ada']);
    $cek->close();
    $koneksi->close();
    exit;
}
$cek->close();

$stmt = $koneksi->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param("ss", $nama_kategori, $keterangan);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil disimpan']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
}

$stmt->close();
$koneksi->close();
