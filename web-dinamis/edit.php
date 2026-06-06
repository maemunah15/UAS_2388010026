<?php
require 'auth.php';

$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$error = '';
$data  = [];

if (isset($_GET['id'])) {
    $id   = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if (!$data) { header("Location: index.php"); exit(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)$_POST['id'];
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    $stmt = $conn->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, jurusan = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nim, $nama, $jurusan, $id);
    if ($stmt->execute()) {
        header("Location: index.php?success=edit");
        exit();
    } else {
        $error = "Gagal memperbarui: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Mahasiswa — Akademik</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
  <?php require 'sidebar.php'; ?>
  <main class="main">
    <div class="page-header">
      <div class="page-title">
        <h1>Edit Mahasiswa</h1>
        <p>Perbarui data <?= htmlspecialchars($data['nama'] ?? '') ?></p>
      </div>
    </div>

    <div class="form-card">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
        <div class="form-group">
          <label>NIM</label>
          <input type="text" name="nim" required value="<?= htmlspecialchars($data['nim']) ?>">
        </div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" required value="<?= htmlspecialchars($data['nama']) ?>">
        </div>
        <div class="form-group">
          <label>Jurusan</label>
          <input type="text" name="jurusan" required value="<?= htmlspecialchars($data['jurusan']) ?>">
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Perbarui Data</button>
          <a href="index.php" class="btn btn-ghost">Batal</a>
        </div>
      </form>
    </div>
  </main>
</div>
</body>
</html>
