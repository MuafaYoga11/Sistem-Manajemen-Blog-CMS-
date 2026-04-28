<?php
$host = 'localhost';
$user = 'root';
$pass = 'Pc24Ugfsd@34';
$db   = 'db_blog';

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $koneksi->connect_error]));
}
