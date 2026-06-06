<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akademik — Sistem Informasi Mahasiswa</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="landing">
  <div class="landing-logo" style="margin-bottom:28px;">
    <div style="width:52px;height:52px;background:var(--accent);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:24px;">📚</div>
  </div>

  <h1>Kelola data mahasiswa dengan <em>efisien</em></h1>
  <p>Sistem informasi akademik untuk mencatat, memperbarui, dan mengelola data mahasiswa secara terpusat.</p>

  <div class="cta-group">
    <a href="login.php" class="btn btn-primary">Masuk ke Dashboard →</a>
    <a href="#fitur" class="btn btn-ghost">Lihat Fitur</a>
  </div>

  <div class="landing-features" id="fitur">
    <div class="feature-chip"><span>✦</span> CRUD Mahasiswa</div>
    <div class="feature-chip"><span>✦</span> Pencarian Real-time</div>
    <div class="feature-chip"><span>✦</span> Autentikasi Login</div>
    <div class="feature-chip"><span>✦</span> Berbasis PHP & MySQL</div>
    <div class="feature-chip"><span>✦</span> Deploy via Docker</div>
  </div>

  <p style="color:var(--muted);font-size:12px;margin-top:60px;opacity:0.5;">
    &copy; <?= date('Y') ?> Sistem Akademik — UAS Cloud Computing
  </p>
</div>
</body>
</html>
