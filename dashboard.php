<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🏰 Dashboard - ผจญภัยแดนโค้ด</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700;800&family=Fira+Code:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg-dark: #0f0e17;
  --bg-card: #1a1932;
  --accent: #ff6b6b;
  --accent2: #4ecdc4;
  --accent3: #ffe66d;
  --accent4: #a855f7;
  --text: #fffffe;
  --text-dim: #94a1b2;
  --success: #10b981;
  --error: #ef4444;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Prompt', sans-serif;
  background: var(--bg-dark);
  color: var(--text);
  min-height: 100vh;
}

/* Particles */
.particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; overflow: hidden; }
.particle { position: absolute; width: 4px; height: 4px; background: var(--accent2); border-radius: 50%; opacity: 0.3; animation: float-particle linear infinite; }
@keyframes float-particle { 0% { transform: translateY(100vh) rotate(0deg); opacity: 0; } 10% { opacity: 0.3; } 90% { opacity: 0.3; } 100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; } }

.container { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 30px 20px; }

/* Top Nav */
.top-nav {
  display: flex; align-items: center; justify-content: space-between;
  padding: 15px 25px;
  background: rgba(26,25,50,0.8);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 15px;
  margin-bottom: 30px;
  backdrop-filter: blur(10px);
}
.top-nav .logo {
  font-size: 1.2rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent), var(--accent2), var(--accent3));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  text-decoration: none;
}
.top-nav .nav-links { display: flex; gap: 15px; align-items: center; }
.top-nav .nav-links a {
  color: var(--text-dim); text-decoration: none; font-size: 0.85rem;
  transition: color 0.3s;
}
.top-nav .nav-links a:hover { color: var(--text); }
.logout-btn {
  background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3);
  color: var(--error); padding: 6px 16px; border-radius: 20px;
  font-family: 'Prompt', sans-serif; font-size: 0.8rem; cursor: pointer;
  transition: all 0.3s;
}
.logout-btn:hover { background: var(--error); color: #fff; }

/* Profile Header */
.profile-header {
  display: flex; align-items: center; gap: 25px;
  padding: 30px;
  background: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(168,85,247,0.1));
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 20px;
  margin-bottom: 25px;
}
.avatar {
  width: 80px; height: 80px; border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), var(--accent4));
  display: flex; align-items: center; justify-content: center;
  font-size: 2.5rem; flex-shrink: 0;
}
.profile-info h1 { font-size: 1.6rem; font-weight: 700; }
.profile-info .email { color: var(--text-dim); font-size: 0.85rem; margin-top: 4px; }
.profile-stats {
  display: flex; gap: 25px; margin-top: 12px;
}
.profile-stat {
  text-align: center;
}
.profile-stat .num {
  font-size: 1.3rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent3), var(--accent2));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.profile-stat .label { font-size: 0.7rem; color: var(--text-dim); }

/* Stats Grid */
.stats-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;
  margin-bottom: 30px;
}
.stat-card {
  background: var(--bg-card);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 15px;
  padding: 20px; text-align: center;
}
.stat-card .icon { font-size: 1.8rem; margin-bottom: 8px; }
.stat-card .value {
  font-size: 1.5rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.stat-card .label { font-size: 0.75rem; color: var(--text-dim); margin-top: 4px; }

/* Game Progress Cards */
.section-title {
  font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;
  display: flex; align-items: center; gap: 10px;
}
.games-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;
  margin-bottom: 30px;
}
.game-card {
  background: var(--bg-card);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 15px;
  padding: 25px;
  transition: all 0.3s;
  cursor: pointer;
  text-decoration: none; color: inherit;
  display: block;
}
.game-card:hover { transform: translateY(-3px); border-color: rgba(255,255,255,0.15); }
.game-card .game-header { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
.game-card .game-icon { font-size: 2rem; }
.game-card .game-name { font-size: 1rem; font-weight: 700; }
.game-card .game-lang { font-size: 0.75rem; color: var(--text-dim); }
.progress-bar-bg {
  width: 100%; height: 8px; background: rgba(255,255,255,0.05);
  border-radius: 4px; overflow: hidden; margin-bottom: 8px;
}
.progress-bar-fill {
  height: 100%; border-radius: 4px;
  transition: width 0.5s ease;
}
.python .progress-bar-fill { background: linear-gradient(90deg, #ff6b6b, #ff8e53); }
.javascript .progress-bar-fill { background: linear-gradient(90deg, #ffe66d, #f0c040); }
.htmlcss .progress-bar-fill { background: linear-gradient(90deg, #4ecdc4, #44b09e); }
.sql .progress-bar-fill { background: linear-gradient(90deg, #a855f7, #7c3aed); }
.game-card .game-stats { display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-dim); }
.game-card .xp-earned { color: var(--accent3); font-weight: 600; }

/* Achievements */
.achievements-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px;
  margin-bottom: 30px;
}
.achievement {
  background: var(--bg-card);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 12px;
  padding: 15px; text-align: center;
  opacity: 0.4;
  transition: all 0.3s;
}
.achievement.unlocked {
  opacity: 1;
  border-color: rgba(255,230,109,0.3);
  background: linear-gradient(135deg, rgba(255,230,109,0.05), rgba(168,85,247,0.05));
}
.achievement .badge { font-size: 2rem; margin-bottom: 5px; }
.achievement .name { font-size: 0.75rem; font-weight: 600; }

/* Login prompt */
.login-prompt {
  text-align: center;
  padding: 60px 30px;
  background: var(--bg-card);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 20px;
}
.login-prompt .icon { font-size: 4rem; margin-bottom: 20px; }
.login-prompt h2 { font-size: 1.4rem; margin-bottom: 10px; }
.login-prompt p { color: var(--text-dim); margin-bottom: 25px; font-size: 0.9rem; }
.login-prompt .btn {
  display: inline-block;
  background: linear-gradient(135deg, var(--accent), var(--accent4));
  color: #fff; padding: 12px 30px; border-radius: 25px;
  text-decoration: none; font-weight: 700; font-size: 0.95rem;
  border: none; cursor: pointer; font-family: 'Prompt', sans-serif;
  transition: all 0.3s;
}
.login-prompt .btn:hover { transform: scale(1.05); }
.login-prompt .note { margin-top: 15px; font-size: 0.8rem; color: var(--text-dim); }
.login-prompt .note a { color: var(--accent2); text-decoration: none; }

/* Responsive */
@media (max-width: 768px) {
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .games-grid { grid-template-columns: 1fr; }
  .profile-header { flex-direction: column; text-align: center; }
  .profile-stats { justify-content: center; }
}
@media (max-width: 480px) {
  .stats-grid { grid-template-columns: 1fr 1fr; }
  .achievements-grid { grid-template-columns: repeat(3, 1fr); }
}
</style>
</head>
<body>

<div class="particles" id="particles"></div>

<div class="container">
  <!-- Top Nav -->
  <nav class="top-nav">
    <a href="index.php" class="logo">🏰 ผจญภัยแดนโค้ด</a>
    <div class="nav-links">
      <a href="index.php">หน้าหลัก</a>
      <a href="dashboard.php" style="color:var(--accent2)">Dashboard</a>
      <button class="logout-btn" id="nav-logout" onclick="handleLogout()" style="display:none">ออกจากระบบ</button>
    </div>
  </nav>

  <!-- Content: changes based on login state -->
  <div id="dashboard-content">
    <!-- Login Prompt (shown when not logged in) -->
    <div id="login-section" class="login-prompt">
      <div class="icon">🔐</div>
      <h2>เข้าสู่ระบบเพื่อดู Dashboard</h2>
      <p>Login เพื่อเก็บ progress ข้ามอุปกรณ์ ดูสถิติ และรับใบรับรอง</p>
      <button class="btn" onclick="CodeQuestAuth.openModal()">👤 เข้าสู่ระบบ / สมัครสมาชิก</button>
      <p class="note">หรือ <a href="index.php">เล่นเลยแบบไม่ login</a> (เก็บ progress ใน browser)</p>
    </div>

    <!-- Dashboard (shown when logged in) -->
    <div id="main-dashboard" style="display:none">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="avatar" id="profile-avatar">🧙‍♂️</div>
        <div class="profile-info">
          <h1 id="profile-name">นักผจญภัย</h1>
          <div class="email" id="profile-email"></div>
          <div class="profile-stats">
            <div class="profile-stat">
              <div class="num" id="total-xp">0</div>
              <div class="label">Total XP</div>
            </div>
            <div class="profile-stat">
              <div class="num" id="total-levels">0</div>
              <div class="label">ด่านที่ผ่าน</div>
            </div>
            <div class="profile-stat">
              <div class="num" id="play-streak">0</div>
              <div class="label">วันติดต่อกัน</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="icon">🐍</div>
          <div class="value" id="py-level">0/50</div>
          <div class="label">Python Quest</div>
        </div>
        <div class="stat-card">
          <div class="icon">⚡</div>
          <div class="value" id="js-level">0/50</div>
          <div class="label">JS Quest</div>
        </div>
        <div class="stat-card">
          <div class="icon">🌐</div>
          <div class="value" id="html-level">0/40</div>
          <div class="label">HTML/CSS Quest</div>
        </div>
        <div class="stat-card">
          <div class="icon">💾</div>
          <div class="value" id="sql-level">0/40</div>
          <div class="label">SQL Quest</div>
        </div>
      </div>

      <!-- Game Progress -->
      <div class="section-title">🎮 ความก้าวหน้าแต่ละเกม</div>
      <div class="games-grid">
        <a href="python_quest.php" class="game-card python">
          <div class="game-header">
            <div class="game-icon">🐍</div>
            <div>
              <div class="game-name">Python Quest</div>
              <div class="game-lang">50 ด่าน · 8 Zones</div>
            </div>
          </div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="py-bar" style="width:0%"></div></div>
          <div class="game-stats">
            <span id="py-progress">ด่าน 0/50</span>
            <span class="xp-earned" id="py-xp">0 XP</span>
          </div>
        </a>
        <a href="js_quest.php" class="game-card javascript">
          <div class="game-header">
            <div class="game-icon">⚡</div>
            <div>
              <div class="game-name">JavaScript Quest</div>
              <div class="game-lang">50 ด่าน · 8 Zones</div>
            </div>
          </div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="js-bar" style="width:0%"></div></div>
          <div class="game-stats">
            <span id="js-progress">ด่าน 0/50</span>
            <span class="xp-earned" id="js-xp">0 XP</span>
          </div>
        </a>
        <a href="htmlcss_quest.php" class="game-card htmlcss">
          <div class="game-header">
            <div class="game-icon">🌐</div>
            <div>
              <div class="game-name">HTML/CSS Quest</div>
              <div class="game-lang">40 ด่าน · 7 Zones</div>
            </div>
          </div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="html-bar" style="width:0%"></div></div>
          <div class="game-stats">
            <span id="html-progress">ด่าน 0/40</span>
            <span class="xp-earned" id="html-xp">0 XP</span>
          </div>
        </a>
        <a href="sql_quest.php" class="game-card sql">
          <div class="game-header">
            <div class="game-icon">💾</div>
            <div>
              <div class="game-name">SQL Quest</div>
              <div class="game-lang">40 ด่าน · 8 Zones</div>
            </div>
          </div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="sql-bar" style="width:0%"></div></div>
          <div class="game-stats">
            <span id="sql-progress">ด่าน 0/40</span>
            <span class="xp-earned" id="sql-xp">0 XP</span>
          </div>
        </a>
      </div>

      <!-- Achievements -->
      <div class="section-title">🏆 เหรียญรางวัล</div>
      <div class="achievements-grid" id="achievements-grid">
        <div class="achievement" data-key="first_code"><div class="badge">🎯</div><div class="name">โค้ดแรก</div></div>
        <div class="achievement" data-key="zone_clear"><div class="badge">🗺️</div><div class="name">ผ่าน Zone แรก</div></div>
        <div class="achievement" data-key="no_hints_10"><div class="badge">🧠</div><div class="name">ไม่ใช้ Hint 10 ด่าน</div></div>
        <div class="achievement" data-key="speed_runner"><div class="badge">⚡</div><div class="name">Speed Runner</div></div>
        <div class="achievement" data-key="python_master"><div class="badge">🐍</div><div class="name">Python Master</div></div>
        <div class="achievement" data-key="js_master"><div class="badge">💛</div><div class="name">JS Master</div></div>
        <div class="achievement" data-key="html_master"><div class="badge">🌐</div><div class="name">HTML/CSS Master</div></div>
        <div class="achievement" data-key="sql_master"><div class="badge">💾</div><div class="name">SQL Master</div></div>
        <div class="achievement" data-key="polyglot"><div class="badge">🌟</div><div class="name">Polyglot</div></div>
        <div class="achievement" data-key="xp_1000"><div class="badge">💰</div><div class="name">1,000 XP</div></div>
        <div class="achievement" data-key="xp_5000"><div class="badge">💎</div><div class="name">5,000 XP</div></div>
        <div class="achievement" data-key="completionist"><div class="badge">👑</div><div class="name">ผ่านครบ 180 ด่าน</div></div>
      </div>
    </div>
  </div>
</div>

<script src="auth.js"></script>
<script>
// Particles
(function() {
  const container = document.getElementById('particles');
  for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.animationDuration = (8 + Math.random() * 15) + 's';
    p.style.animationDelay = (Math.random() * 10) + 's';
    p.style.width = p.style.height = (2 + Math.random() * 4) + 'px';
    const colors = ['#ff6b6b','#4ecdc4','#ffe66d','#a855f7'];
    p.style.background = colors[Math.floor(Math.random() * colors.length)];
    container.appendChild(p);
  }
})();

// Game config
const GAMES = [
  { key: 'python', lsKey: 'pythonquest_50', total: 50, prefix: 'py' },
  { key: 'javascript', lsKey: 'jsquest_50', total: 50, prefix: 'js' },
  { key: 'htmlcss', lsKey: 'htmlcssquest_40', total: 40, prefix: 'html' },
  { key: 'sql', lsKey: 'sqlquest_40', total: 40, prefix: 'sql' }
];

// Load progress from localStorage
function loadAllProgress() {
  let totalXP = 0;
  let totalLevels = 0;

  GAMES.forEach(game => {
    const saved = localStorage.getItem(game.lsKey);
    let completed = 0, xp = 0;

    if (saved) {
      try {
        const data = JSON.parse(saved);
        if (data.completedLevels) {
          completed = data.completedLevels.length;
        } else if (data.maxLevel) {
          completed = data.maxLevel - 1;
        } else if (typeof data === 'object') {
          // Handle different save formats
          completed = Object.keys(data).filter(k => data[k]?.completed).length;
        }
        xp = data.xp || data.total_xp || 0;
      } catch(e) {
        console.log('Failed to parse', game.lsKey);
      }
    }

    const pct = Math.round((completed / game.total) * 100);
    totalXP += xp;
    totalLevels += completed;

    // Update UI
    const bar = document.getElementById(game.prefix + '-bar');
    const progress = document.getElementById(game.prefix + '-progress');
    const xpEl = document.getElementById(game.prefix + '-xp');
    const level = document.getElementById(game.prefix + '-level');

    if (bar) bar.style.width = pct + '%';
    if (progress) progress.textContent = `ด่าน ${completed}/${game.total}`;
    if (xpEl) xpEl.textContent = xp.toLocaleString() + ' XP';
    if (level) level.textContent = `${completed}/${game.total}`;
  });

  document.getElementById('total-xp').textContent = totalXP.toLocaleString();
  document.getElementById('total-levels').textContent = totalLevels;

  // Check achievements
  checkAchievements(totalXP, totalLevels);
}

function checkAchievements(totalXP, totalLevels) {
  const unlocked = [];

  if (totalLevels >= 1) unlocked.push('first_code');
  if (totalLevels >= 7) unlocked.push('zone_clear');
  if (totalXP >= 1000) unlocked.push('xp_1000');
  if (totalXP >= 5000) unlocked.push('xp_5000');
  if (totalLevels >= 180) unlocked.push('completionist');

  // Per-game completion
  GAMES.forEach(game => {
    const saved = localStorage.getItem(game.lsKey);
    if (saved) {
      try {
        const data = JSON.parse(saved);
        const completed = data.completedLevels?.length || 0;
        if (completed >= game.total) {
          if (game.key === 'python') unlocked.push('python_master');
          if (game.key === 'javascript') unlocked.push('js_master');
          if (game.key === 'htmlcss') unlocked.push('html_master');
          if (game.key === 'sql') unlocked.push('sql_master');
        }
      } catch(e) {}
    }
  });

  // Check polyglot (played at least 2 games)
  let gamesPlayed = 0;
  GAMES.forEach(game => {
    if (localStorage.getItem(game.lsKey)) gamesPlayed++;
  });
  if (gamesPlayed >= 3) unlocked.push('polyglot');

  // Update UI
  unlocked.forEach(key => {
    const el = document.querySelector(`.achievement[data-key="${key}"]`);
    if (el) el.classList.add('unlocked');
  });
}

// Auth state callback
function onAuthChange(event) {
  if (event === 'SIGNED_IN') {
    showDashboard();
  } else if (event === 'SIGNED_OUT') {
    showLoginPrompt();
  }
}

function showDashboard() {
  document.getElementById('login-section').style.display = 'none';
  document.getElementById('main-dashboard').style.display = 'block';
  document.getElementById('nav-logout').style.display = 'inline-block';

  if (CodeQuestAuth.profile) {
    document.getElementById('profile-name').textContent = CodeQuestAuth.profile.display_name || 'นักผจญภัย';
  }
  if (CodeQuestAuth.currentUser) {
    document.getElementById('profile-email').textContent = CodeQuestAuth.currentUser.email || '';
  }

  loadAllProgress();
}

function showLoginPrompt() {
  document.getElementById('login-section').style.display = 'block';
  document.getElementById('main-dashboard').style.display = 'none';
  document.getElementById('nav-logout').style.display = 'none';
}

async function handleLogout() {
  await CodeQuestAuth.signOut();
  showLoginPrompt();
}

// Init: also show dashboard if localStorage has data (even without login)
document.addEventListener('DOMContentLoaded', () => {
  // Check if any game has been played locally
  let hasLocalData = false;
  GAMES.forEach(game => {
    if (localStorage.getItem(game.lsKey)) hasLocalData = true;
  });

  // If Supabase not configured or user not logged in but has local data → show dashboard anyway
  setTimeout(() => {
    if (CodeQuestAuth.currentUser) {
      showDashboard();
    } else if (hasLocalData) {
      // Show dashboard with local data even without login
      document.getElementById('login-section').style.display = 'none';
      document.getElementById('main-dashboard').style.display = 'block';
      document.getElementById('profile-name').textContent = 'นักผจญภัย (ไม่ได้ Login)';
      document.getElementById('profile-email').textContent = 'Login เพื่อเก็บ progress ข้ามอุปกรณ์';
      loadAllProgress();
    }
  }, 1500); // Wait for auth init
});
</script>
</body>
</html>
