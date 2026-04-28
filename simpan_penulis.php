<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$nama_depan    = trim($_POST['nama_depan'] ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name'] ?? '');
$password      = trim($_POST['password'] ?? '');

if ($nama_depan === '' || $nama_belakang === '' || $user_name === '' || $password === '') {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Cek username unik
$cek = $koneksi->prepare("SELECT id FROM penulis WHERE user_name = ?");
$cek->bind_param("s", $user_name);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan']);
    $cek->close();
    $koneksi->close();
    exit;
}
$cek->close();

// Handle foto upload
$foto = 'default.png';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];

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

    $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = 'penulis_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($tmp, 'uploads_penulis/' . $foto);
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $koneksi->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $hashed, $foto);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Penulis berhasil disimpan']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
}

$stmt->close();
$koneksi->close();
