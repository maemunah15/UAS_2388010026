<?php
$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $stmt = $conn->prepare("INSERT INTO mahasiswa (nim, nama, jurusan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nim, $nama, $jurusan);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 300px; padding: 8px; }
        button { padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Tambah Data Mahasiswa</h2>
    <form method="POST">
        <div class="form-group">
            <label>NIM</label>
            <input type="text" name="nim" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="jurusan" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="index.php" style="margin-left: 10px;">Kembali</a>
    </form>
</body>
</html>
