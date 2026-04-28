<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama_depan, nama_belakang, user_name, foto FROM penulis WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data penulis tidak ditemukan']);
} else {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
}

$stmt->close();
$koneksi->close();
