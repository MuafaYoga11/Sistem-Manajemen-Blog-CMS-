<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id          = intval($_POST['id'] ?? 0);
$judul       = trim($_POST['judul'] ?? '');
$id_penulis  = intval($_POST['id_penulis'] ?? 0);
$id_kategori = intval($_POST['id_kategori'] ?? 0);
$isi         = trim($_POST['isi'] ?? '');

if ($id <= 0 || $judul === '' || $id_penulis <= 0 || $id_kategori <= 0 || $isi === '') {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Handle gambar opsional
$gambar_baru = null;
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
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
        echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan']);
        exit;
    }

    // Hapus gambar lama
    $old = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
    $old->bind_param("i", $id);
    $old->execute();
    $oldRow = $old->get_result()->fetch_assoc();
    $old->close();
    if ($oldRow && file_exists('uploads_artikel/' . $oldRow['gambar'])) {
        unlink('uploads_artikel/' . $oldRow['gambar']);
    }

    $ext         = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar_baru = 'artikel_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($tmp, 'uploads_artikel/' . $gambar_baru);
}

if ($gambar_baru !== null) {
    $stmt = $koneksi->prepare("UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?");
    $stmt->bind_param("iisssi", $id_penulis, $id_kategori, $judul, $isi, $gambar_baru, $id);
} else {
    $stmt = $koneksi->prepare("UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=? WHERE id=?");
    $stmt->bind_param("iissi", $id_penulis, $id_kategori, $judul, $isi, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui artikel']);
}

$stmt->close();
$koneksi->close();
