<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$result = $koneksi->query("SELECT id, nama_depan, nama_belakang, user_name, password, foto FROM penulis ORDER BY id DESC");

if (!$result) {
    echo json_encode(['status' => 'success', 'data' => []]);
    $koneksi->close();
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $data]);
$koneksi->close();
