<?php
require 'auth.php';

$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nim && $nama && $jurusan) {
        $stmt = $conn->prepare("INSERT INTO mahasiswa (nim, nama, jurusan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nim, $nama, $jurusan);
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit();
        } else {
            $error = "Gagal menyimpan: " . $stmt->error;
        }
    } else {
        $error = "Semua field wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Mahasiswa — Akademik</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
  <?php require 'sidebar.php'; ?>
  <main class="main">
    <div class="page-header">
      <div class="page-title">
        <h1>Tambah Mahasiswa</h1>
        <p>Daftarkan mahasiswa baru ke sistem</p>
      </div>
    </div>

    <div class="form-card">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label>NIM</label>
          <input type="text" name="nim" placeholder="Contoh: 2388010020" required value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" placeholder="Nama sesuai KTP" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Jurusan</label>
          <input type="text" name="jurusan" placeholder="Contoh: Teknik Informatika" required value="<?= htmlspecialchars($_POST['jurusan'] ?? '') ?>">
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Simpan Data</button>
          <a href="index.php" class="btn btn-ghost">Batal</a>
        </div>
      </form>
    </div>
  </main>
</div>
</body>
</html>
