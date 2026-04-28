<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$id            = intval($input['id'] ?? 0);
$nama_kategori = trim($input['nama_kategori'] ?? '');
$keterangan    = trim($input['keterangan'] ?? '');

if ($id <= 0 || $nama_kategori === '') {
    echo json_encode(['status' => 'error', 'message' => 'Field wajib tidak boleh kosong']);
    exit;
}

// Cek unik kecuali milik sendiri
$cek = $koneksi->prepare("SELECT id FROM kategori_artikel WHERE nama_kategori = ? AND id != ?");
$cek->bind_param("si", $nama_kategori, $id);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori sudah digunakan']);
    $cek->close();
    $koneksi->close();
    exit;
}
$cek->close();

$stmt = $koneksi->prepare("UPDATE kategori_artikel SET nama_kategori=?, keterangan=? WHERE id=?");
$stmt->bind_param("ssi", $nama_kategori, $keterangan, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data']);
}

$stmt->close();
$koneksi->close();
