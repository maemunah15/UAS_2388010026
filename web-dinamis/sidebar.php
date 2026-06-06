<?php
$current = basename($_SERVER['PHP_SELF']);
function nav($href, $label, $icon, $current) {
    $active = ($current === $href) ? ' active' : '';
    echo "<a href=\"$href\" class=\"nav-item$active\">$icon <span>$label</span></a>";
}
?>
<div class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">📚</div>
    <span>Akademik</span>
  </div>

  <div class="nav-label">Menu</div>
  <?php nav('index.php', 'Dashboard', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>', $current); ?>
  <?php nav('tambah.php', 'Tambah Data', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>', $current); ?>

  <div class="sidebar-bottom">
    <div class="nav-item" style="margin-bottom:0;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      <span style="font-size:13px;"><?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?></span>
    </div>
    <a href="logout.php" class="nav-item" style="color:var(--danger);margin-bottom:0;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      <span>Logout</span>
    </a>
  </div>
</div>
