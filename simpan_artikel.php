<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$judul       = trim($_POST['judul'] ?? '');
$id_penulis  = intval($_POST['id_penulis'] ?? 0);
$id_kategori = intval($_POST['id_kategori'] ?? 0);
$isi         = trim($_POST['isi'] ?? '');

if ($judul === '' || $id_penulis <= 0 || $id_kategori <= 0 || $isi === '') {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Gambar wajib saat tambah
if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'Gambar artikel wajib diupload']);
    exit;
}

$tmp  = $_FILES['gambar']['tmp_name'];
$size = $_FILES['gambar']['size'];

if ($size > 2 * 1024 * 1024) {
    echo json_encode(['status' => 'error', 'message' => 'Ukuran file maksimal 2 MB']);
    exit;
}

$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $tmp);
finfo_close($finfo);

$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mimeType, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, GIF, WEBP']);
    exit;
}

$ext    = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
$gambar = 'artikel_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
move_uploaded_file($tmp, 'uploads_artikel/' . $gambar);

// Generate hari_tanggal
date_default_timezone_set('Asia/Jakarta');
$hari   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan  = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
$sekarang    = new DateTime();
$hari_tanggal = $hari[$sekarang->format('w')].', '.$sekarang->format('j').' '.$bulan[(int)$sekarang->format('n')].' '.$sekarang->format('Y').' | '.$sekarang->format('H:i');

$stmt = $koneksi->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $gambar, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil disimpan']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan artikel']);
}

$stmt->close();
$koneksi->close();
