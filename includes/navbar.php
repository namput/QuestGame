<?php
// ============================================================
// Shared Navbar — include ในทุกหน้า
// ตัวแปร: $activePage = 'home'|'leaderboard'|'dashboard'|'credits'|'admin'
//          $backUrl    = URL สำหรับปุ่มย้อนกลับ (ถ้าไม่ระบุ จะซ่อน)
//          $backLabel  = ข้อความปุ่มย้อนกลับ (default: ← กลับ)
// ============================================================
$activePage = $activePage ?? '';
$backUrl    = $backUrl    ?? '';
$backLabel  = $backLabel  ?? '← กลับ';
?>
<nav class="navbar" id="navbar">
  <div class="navbar-left">
    <?php if ($backUrl): ?>
    <a href="<?= htmlspecialchars($backUrl) ?>" class="nav-back-btn"><?= htmlspecialchars($backLabel) ?></a>
    <?php endif; ?>
    <a href="/index.php" class="nav-logo">
      <span>⚔️</span>
      <span class="logo-text">ผจญภัยแดนโค้ด</span>
    </a>
  </div>

  <ul class="nav-links">
    <li><a href="/index.php"       <?= $activePage==='home'        ? 'class="active"' : '' ?>>🏠 หน้าหลัก</a></li>
    <li><a href="/leaderboard.php" <?= $activePage==='leaderboard' ? 'class="active"' : '' ?>>🏆 Leaderboard</a></li>
    <li><a href="/dashboard.php"   <?= $activePage==='dashboard'   ? 'class="active"' : '' ?>>📊 Dashboard</a></li>
    <li><a href="/credits.php"     <?= $activePage==='credits'     ? 'class="active"' : '' ?>>💳 เติมเครดิต</a></li>
    <li id="admin-nav-item" style="display:none">
      <a href="/admin/credits.php" <?= $activePage==='admin'       ? 'class="active"' : '' ?>>🔑 Admin</a>
    </li>
  </ul>

  <div class="nav-auth">
    <button id="auth-btn" onclick="CodeQuestAuth.openModal()">👤 เข้าสู่ระบบ</button>
    <div id="user-info" style="display:none">
      <span id="user-name"></span>
      <button id="logout-btn" onclick="CodeQuestAuth.signOut()">ออก</button>
    </div>
  </div>
</nav>

