<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$sql = "SELECT a.id, a.judul, a.isi, a.gambar, a.hari_tanggal,
               a.id_penulis, a.id_kategori,
               CONCAT(p.nama_depan, ' ', p.nama_belakang) AS nama_penulis,
               k.nama_kategori
        FROM artikel a
        JOIN penulis p ON a.id_penulis = p.id
        JOIN kategori_artikel k ON a.id_kategori = k.id
        ORDER BY a.id DESC";

$result = $koneksi->query($sql);

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
