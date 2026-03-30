<?php
// ============================================================
// Shared Navbar — include ในทุกหน้า
// ตัวแปร: $activePage = 'home'|'leaderboard'|'dashboard'|'credits'
// ============================================================
$activePage = $activePage ?? '';
?>
<nav class="navbar" id="navbar">
  <a href="/index.php" class="nav-logo">
    <span>⚔️</span>
    <span class="logo-text">ผจญภัยแดนโค้ด</span>
  </a>
  <ul class="nav-links">
    <li><a href="/leaderboard.php" <?= $activePage==='leaderboard' ? 'class="active"' : '' ?>>🏆 Leaderboard</a></li>
    <li><a href="/dashboard.php"   <?= $activePage==='dashboard'   ? 'class="active"' : '' ?>>📊 Dashboard</a></li>
    <li><a href="/credits.php"     <?= $activePage==='credits'     ? 'class="active"' : '' ?>>💳 เติมเครดิต</a></li>
  </ul>
  <div class="nav-auth">
    <button id="auth-btn" onclick="CodeQuestAuth.openModal()">👤 เข้าสู่ระบบ</button>
    <div id="user-info" style="display:none">
      <span id="user-name"></span>
      <button id="logout-btn" onclick="CodeQuestAuth.signOut()">ออก</button>
    </div>
  </div>
</nav>
