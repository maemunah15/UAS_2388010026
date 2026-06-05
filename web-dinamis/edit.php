<?php
$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data lama berdasarkan ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if (!$data) {
        die("Data tidak ditemukan!");
    }
}

// Memproses perubahan data (Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];

    $stmt = $conn->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, jurusan = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nim, $nama, $jurusan, $id);
    
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
    <title>Edit Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 300px; padding: 8px; }
        button { padding: 8px 15px; background-color: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Edit Data Mahasiswa</h2>
    <form method="POST">
        <!-- Hidden input untuk menampung ID -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">

        <div class="form-group">
            <label>NIM</label>
            <input type="text" name="nim" value="<?= htmlspecialchars($data['nim']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="jurusan" value="<?= htmlspecialchars($data['jurusan']) ?>" required>
        </div>
        <button type="submit">Update Data</button>
        <a href="index.php" style="margin-left: 10px;">Batal</a>
    </form>
</body>
</html>
