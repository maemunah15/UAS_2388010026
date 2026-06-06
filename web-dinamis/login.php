<?php
session_start();

$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Username atau password salah.';
    }
}

if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Akademik</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="brand">
      <div class="logo-big">📚</div>
      <h1>Akademik</h1>
      <p>Masuk untuk melanjutkan ke dashboard</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="admin" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary">Masuk →</button>
    </form>

    <p style="text-align:center;margin-top:24px;font-size:12px;color:var(--muted);">
      <a href="landing.php" style="color:var(--muted);">← Kembali ke beranda</a>
    </p>
  </div>
</div>
</body>
</html>
