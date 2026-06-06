<?php
require 'auth.php';

$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

if (isset($_GET['id'])) {
    $id   = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php?success=hapus");
exit();
