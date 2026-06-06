<?php
require 'auth.php';

$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$pass = getenv('DATABASE_PASSWORD') ?: '';
$db   = getenv('DATABASE_NAME') ?: 'uas_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$search = trim($_GET['q'] ?? '');
$total  = $conn->query("SELECT COUNT(*) AS n FROM mahasiswa")->fetch_assoc()['n'];

$jurusan_counts = [];
$res_j = $conn->query("SELECT jurusan, COUNT(*) as n FROM mahasiswa GROUP BY jurusan ORDER BY n DESC LIMIT 1");
$top_jurusan = $res_j->fetch_assoc();

if ($search) {
    $s = $conn->real_escape_string($search);
    $result = $conn->query("SELECT * FROM mahasiswa WHERE nim LIKE '%$s%' OR nama LIKE '%$s%' OR jurusan LIKE '%$s%' ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM mahasiswa ORDER BY id DESC");
}
$rows = $result->fetch_all(MYSQLI_ASSOC);

$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Akademik</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
  <?php require 'sidebar.php'; ?>

  <main class="main">
    <div class="page-header">
      <div class="page-title">
        <h1>Dashboard</h1>
        <p>Kelola data mahasiswa terdaftar</p>
      </div>
      <a href="tambah.php" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Mahasiswa
      </a>
    </div>

    <?php if ($success === '1'): ?>
      <div class="alert alert-success">Data berhasil disimpan.</div>
    <?php elseif ($success === 'edit'): ?>
      <div class="alert alert-success">Data berhasil diperbarui.</div>
    <?php elseif ($success === 'hapus'): ?>
      <div class="alert alert-danger" style="background:rgba(78,203,138,0.08);color:var(--success);border-color:rgba(78,203,138,0.2);">Data berhasil dihapus.</div>
    <?php endif; ?>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="label">Total Mahasiswa</div>
        <div class="value"><?= $total ?></div>
        <div class="sub">terdaftar dalam sistem</div>
      </div>
      <div class="stat-card">
        <div class="label">Jurusan Terbanyak</div>
        <div class="value" style="font-size:18px;margin-top:4px;"><?= htmlspecialchars($top_jurusan['jurusan'] ?? '—') ?></div>
        <div class="sub"><?= $top_jurusan['n'] ?? 0 ?> mahasiswa</div>
      </div>
      <div class="stat-card">
        <div class="label">Hasil Pencarian</div>
        <div class="value"><?= count($rows) ?></div>
        <div class="sub"><?= $search ? 'untuk "'.htmlspecialchars($search).'"' : 'semua data ditampilkan' ?></div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <h2>Data Mahasiswa</h2>
        <form method="GET" style="display:flex;gap:8px;">
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIM, nama, jurusan…"
            style="background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius-sm);color:var(--text);padding:7px 14px;font-size:13px;outline:none;width:220px;">
          <button type="submit" class="btn btn-ghost btn-sm">Cari</button>
          <?php if ($search): ?><a href="index.php" class="btn btn-ghost btn-sm">Reset</a><?php endif; ?>
        </form>
      </div>

      <?php if (empty($rows)): ?>
        <div style="padding:48px;text-align:center;color:var(--muted);">
          <div style="font-size:32px;margin-bottom:12px;">🔍</div>
          <p>Tidak ada data ditemukan<?= $search ? ' untuk "'.htmlspecialchars($search).'"' : '' ?>.</p>
        </div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th style="text-align:right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row):
            $initials = strtoupper(substr($row['nama'], 0, 1));
            if (strpos($row['nama'], ' ') !== false) {
                $parts = explode(' ', $row['nama']);
                $initials = strtoupper($parts[0][0] . end($parts)[0]);
            }
          ?>
          <tr>
            <td>
              <span class="avatar"><?= $initials ?></span>
              <?= htmlspecialchars($row['nama']) ?>
            </td>
            <td><span style="font-family:monospace;font-size:13px;color:var(--muted);"><?= htmlspecialchars($row['nim']) ?></span></td>
            <td><span class="badge"><?= htmlspecialchars($row['jurusan']) ?></span></td>
            <td style="text-align:right;">
              <div class="action-btns" style="justify-content:flex-end;">
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-ghost btn-sm">Edit</a>
                <button class="btn btn-danger-ghost btn-sm" onclick="confirmDelete(<?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['nama'])) ?>')">Hapus</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </main>
</div>

<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div style="font-size:36px;margin-bottom:16px;">⚠️</div>
    <h3>Hapus Mahasiswa?</h3>
    <p id="deleteMsg">Data yang dihapus tidak dapat dikembalikan.</p>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal()">Batal</button>
      <a id="deleteConfirmBtn" href="#" class="btn btn-danger-ghost">Ya, Hapus</a>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, nama) {
  document.getElementById('deleteMsg').textContent = 'Yakin ingin menghapus data "' + nama + '"?';
  document.getElementById('deleteConfirmBtn').href = 'hapus.php?id=' + id;
  document.getElementById('deleteModal').classList.add('active');
}
function closeModal() {
  document.getElementById('deleteModal').classList.remove('active');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
</script>
</body>
</html>
