<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id            = intval($_POST['id'] ?? 0);
$nama_depan    = trim($_POST['nama_depan'] ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name'] ?? '');
$password      = trim($_POST['password'] ?? '');

if ($id <= 0 || $nama_depan === '' || $nama_belakang === '' || $user_name === '') {
    echo json_encode(['status' => 'error', 'message' => 'Field wajib tidak boleh kosong']);
    exit;
}

// Cek username unik (kecuali milik sendiri)
$cek = $koneksi->prepare("SELECT id FROM penulis WHERE user_name = ? AND id != ?");
$cek->bind_param("si", $user_name, $id);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan oleh penulis lain']);
    $cek->close();
    $koneksi->close();
    exit;
}
$cek->close();

// Handle foto upload
$foto_baru = null;
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
        echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan']);
        exit;
    }

    // Hapus foto lama
    $old = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
    $old->bind_param("i", $id);
    $old->execute();
    $oldRow = $old->get_result()->fetch_assoc();
    $old->close();
    if ($oldRow && $oldRow['foto'] !== 'default.png' && file_exists('uploads_penulis/' . $oldRow['foto'])) {
        unlink('uploads_penulis/' . $oldRow['foto']);
    }

    $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_baru = 'penulis_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($tmp, 'uploads_penulis/' . $foto_baru);
}

// Build query
if ($password !== '' && $foto_baru !== null) {
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama_depan, $nama_belakang, $user_name, $hashed, $foto_baru, $id);
} elseif ($password !== '') {
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama_depan, $nama_belakang, $user_name, $hashed, $id);
} elseif ($foto_baru !== null) {
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama_depan, $nama_belakang, $user_name, $foto_baru, $id);
} else {
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=? WHERE id=?");
    $stmt->bind_param("sssi", $nama_depan, $nama_belakang, $user_name, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Penulis berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data']);
}

$stmt->close();
$koneksi->close();
