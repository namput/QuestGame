<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SQL Quest - ผจญภัยแดนฐานข้อมูล</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.8.0/sql-wasm.js"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700;800&family=Fira+Code:wght@400;600&display=swap');

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
  --code-bg: #1e1e3f;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Prompt', sans-serif;
  background: var(--bg-dark);
  color: var(--text);
  min-height: 100vh;
  overflow-x: hidden;
}

/* PARTICLES */
.particles {
  position: fixed; top: 0; left: 0;
  width: 100%; height: 100%;
  pointer-events: none; z-index: 0; overflow: hidden;
}
.particle {
  position: absolute; width: 4px; height: 4px;
  background: var(--accent2); border-radius: 50%; opacity: 0.3;
  animation: float-particle linear infinite;
}
@keyframes float-particle {
  0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
  10% { opacity: 0.3; } 90% { opacity: 0.3; }
  100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
}

/* LOADING */
#loading-screen {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: var(--bg-dark); display: flex; flex-direction: column;
  align-items: center; justify-content: center; z-index: 1000; transition: opacity 0.5s;
}
.loading-logo {
  font-size: 3rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent), var(--accent2), var(--accent3));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  margin-bottom: 30px; animation: pulse-glow 2s ease-in-out infinite;
}
@keyframes pulse-glow { 0%,100% { filter: brightness(1); } 50% { filter: brightness(1.3); } }
.loading-bar-container { width: 300px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; }
.loading-bar { width: 0%; height: 100%; background: linear-gradient(90deg, var(--accent), var(--accent2)); border-radius: 3px; transition: width 0.3s; }
.loading-text { margin-top: 15px; color: var(--text-dim); font-size: 0.9rem; }

.app-container { position: relative; z-index: 1; display: none; }

/* TOP BAR */
.top-bar {
  background: rgba(26,25,50,0.9); backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255,255,255,0.05);
  padding: 12px 30px; display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 100;
}
.logo { font-size: 1.3rem; font-weight: 700; background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.logo span { font-size: 0.8rem; opacity: 0.7; -webkit-text-fill-color: var(--text-dim); margin-left: 8px; }
.player-info { display: flex; align-items: center; gap: 20px; }
.xp-bar-container { width: 200px; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; }
.xp-bar { height: 100%; background: linear-gradient(90deg, var(--accent3), var(--accent)); border-radius: 4px; transition: width 0.5s ease; }
.xp-text { font-size: 0.8rem; color: var(--accent3); font-weight: 600; }
.level-badge { background: linear-gradient(135deg, var(--accent4), var(--accent)); padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }

.screen { display: none; }
.screen.active { display: block; }

/* HOME */
.home-screen { min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 60px 20px; }
.home-title {
  font-size: 4rem; font-weight: 800; text-align: center; margin-bottom: 10px;
  background: linear-gradient(135deg, var(--accent), var(--accent2), var(--accent3), var(--accent4));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  animation: title-shimmer 3s ease-in-out infinite;
}
@keyframes title-shimmer { 0%,100% { filter: hue-rotate(0deg); } 50% { filter: hue-rotate(30deg); } }
.home-subtitle { font-size: 1.2rem; color: var(--text-dim); text-align: center; margin-bottom: 50px; }
.home-subtitle strong { color: var(--accent2); }

/* STAGE MAP */
.stage-map { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; max-width: 800px; width: 100%; margin-bottom: 30px; }
.stage-node {
  background: var(--bg-card); border: 2px solid rgba(255,255,255,0.08);
  border-radius: 16px; padding: 20px 14px; text-align: center;
  cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;
}
.stage-node:hover { transform: translateY(-4px); border-color: var(--accent2); box-shadow: 0 8px 30px rgba(78,205,196,0.15); }
.stage-node.locked { opacity: 0.4; cursor: not-allowed; filter: grayscale(0.5); }
.stage-node.locked:hover { transform: none; border-color: rgba(255,255,255,0.08); box-shadow: none; }
.stage-node.completed { border-color: var(--success); background: linear-gradient(135deg, rgba(16,185,129,0.1), var(--bg-card)); }
.stage-node.current { border-color: var(--accent3); animation: current-pulse 2s ease-in-out infinite; }
@keyframes current-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(255,230,109,0.3); } 50% { box-shadow: 0 0 20px 5px rgba(255,230,109,0.15); } }
.stage-icon { font-size: 2rem; margin-bottom: 8px; }
.stage-num { font-size: 0.75rem; color: var(--text-dim); margin-bottom: 4px; }
.stage-name { font-size: 0.85rem; font-weight: 600; line-height: 1.3; }
.stage-tag { display: inline-block; margin-top: 8px; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; background: rgba(168,85,247,0.2); color: var(--accent4); }
.stage-check { position: absolute; top: 8px; right: 8px; font-size: 1.2rem; }
.zone-label { grid-column: 1 / -1; text-align: center; padding: 10px; margin-top: 10px; font-size: 1rem; font-weight: 700; border-radius: 10px; }
.zone-label.zone1 { background: linear-gradient(90deg, rgba(255,107,107,0.15), transparent); color: var(--accent); }
.zone-label.zone2 { background: linear-gradient(90deg, rgba(78,205,196,0.15), transparent); color: var(--accent2); }
.zone-label.zone3 { background: linear-gradient(90deg, rgba(168,85,247,0.15), transparent); color: var(--accent4); }
.zone-label.zone4 { background: linear-gradient(90deg, rgba(255,230,109,0.15), transparent); color: var(--accent3); }
.zone-label.zone5 { background: linear-gradient(90deg, rgba(16,185,129,0.15), transparent); color: var(--success); }
.zone-label.zone6 { background: linear-gradient(90deg, rgba(239,68,68,0.15), transparent); color: var(--error); }
.zone-label.zone7 { background: linear-gradient(90deg, rgba(59,130,246,0.15), transparent); color: #3b82f6; }
.zone-label.zone8 { background: linear-gradient(90deg, rgba(249,115,22,0.15), transparent); color: #f97316; }

/* GAME SCREEN */
.game-screen { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }
.game-header { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; }
.btn-back {
  background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
  color: var(--text); padding: 8px 16px; border-radius: 10px; cursor: pointer;
  font-family: 'Prompt'; font-size: 0.9rem; transition: all 0.2s;
}
.btn-back:hover { background: rgba(255,255,255,0.12); }
.game-title-area { flex: 1; }
.game-title-area h2 { font-size: 1.4rem; font-weight: 700; }
.game-title-area .theme-badge { display: inline-block; font-size: 0.75rem; padding: 3px 10px; border-radius: 12px; margin-top: 4px; }

/* STORY BOX */
.story-box {
  background: linear-gradient(135deg, rgba(26,25,50,0.9), rgba(15,14,23,0.9));
  border: 1px solid rgba(255,255,255,0.08); border-radius: 16px;
  padding: 25px; margin-bottom: 20px; position: relative; overflow: hidden;
}
.story-box::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
.story-box.theme-dungeon::before { background: linear-gradient(180deg, var(--accent), var(--accent3)); }
.story-box.theme-detective::before { background: linear-gradient(180deg, #3b82f6, var(--accent2)); }
.story-box.theme-chef::before { background: linear-gradient(180deg, var(--accent3), #f97316); }
.story-box.theme-matrix::before { background: linear-gradient(180deg, #22c55e, var(--accent2)); }
.story-box.theme-space::before { background: linear-gradient(180deg, var(--accent4), #ec4899); }
.story-character { font-size: 2.5rem; margin-bottom: 10px; }
.story-text { font-size: 1rem; line-height: 1.8; color: var(--text); }
.story-text .highlight { color: var(--accent3); font-weight: 600; }

/* MISSION BOX */
.mission-box {
  background: var(--bg-card); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 16px; padding: 25px; margin-bottom: 20px;
}
.mission-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: var(--accent3); }
.mission-box h3::before { content: '🎯 '; }
.mission-text { font-size: 0.95rem; line-height: 1.7; color: var(--text); }
.mission-text code {
  background: rgba(168,85,247,0.15); color: var(--accent4);
  padding: 2px 8px; border-radius: 6px; font-family: 'Fira Code', monospace; font-size: 0.85rem;
}

/* HINT SYSTEM - 3 LEVELS */
.hint-area { margin-bottom: 20px; }
.hint-buttons { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.btn-hint {
  background: rgba(255,230,109,0.1); border: 1px solid rgba(255,230,109,0.2);
  color: var(--accent3); padding: 6px 16px; border-radius: 8px; cursor: pointer;
  font-family: 'Prompt'; font-size: 0.85rem; transition: all 0.2s;
}
.btn-hint:hover { background: rgba(255,230,109,0.2); }
.btn-hint.used { opacity: 0.5; }
.btn-hint.level2 { background: rgba(255,165,0,0.1); border-color: rgba(255,165,0,0.2); color: #ffa500; }
.btn-hint.level3 { background: rgba(255,107,107,0.1); border-color: rgba(255,107,107,0.2); color: var(--accent); }
.hint-box {
  background: rgba(255,230,109,0.05); border: 1px solid rgba(255,230,109,0.15);
  border-radius: 12px; padding: 15px 20px; display: none;
}
.hint-box.show { display: block; }
.hint-box p { color: var(--accent3); font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; }
.hint-box.level2 { border-color: rgba(255,165,0,0.15); }
.hint-box.level2 p { color: #ffa500; }
.hint-box.level3 { border-color: rgba(255,107,107,0.15); }
.hint-box.level3 p { color: var(--accent); }

.xp-penalty { font-size: 0.7rem; opacity: 0.6; display: block; margin-top: 2px; }

/* CODE EDITOR */
.editor-area { margin-bottom: 20px; }
.editor-tabs { display: flex; gap: 0; }
.editor-tab {
  background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
  border-bottom: none; padding: 8px 20px; font-family: 'Fira Code', monospace;
  font-size: 0.8rem; color: var(--text-dim); border-radius: 10px 10px 0 0;
}
.editor-tab.active { background: var(--code-bg); color: var(--accent2); }
.code-editor {
  width: 100%; min-height: 220px; background: var(--code-bg);
  border: 1px solid rgba(255,255,255,0.1); border-radius: 0 12px 12px 12px;
  padding: 20px; font-family: 'Fira Code', monospace; font-size: 0.95rem;
  color: #e0e0ff; line-height: 1.7; resize: vertical; outline: none; tab-size: 4;
}
.code-editor:focus { border-color: var(--accent2); box-shadow: 0 0 15px rgba(78,205,196,0.1); }
.code-editor::placeholder { color: rgba(255,255,255,0.2); }

/* ACTION */
.action-bar { display: flex; gap: 12px; align-items: center; margin-bottom: 20px; }
.btn-run {
  background: linear-gradient(135deg, var(--accent2), #38b2ac); color: #000; border: none;
  padding: 12px 30px; border-radius: 12px; font-family: 'Prompt'; font-size: 1rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
}
.btn-run:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78,205,196,0.3); }
.btn-run:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

/* OUTPUT */
.output-panel { background: #0d0d1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
.output-header {
  background: rgba(255,255,255,0.03); padding: 10px 20px; font-size: 0.8rem;
  color: var(--text-dim); border-bottom: 1px solid rgba(255,255,255,0.05);
  display: flex; align-items: center; gap: 8px;
}
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot.red { background: #ef4444; } .dot.yellow { background: #eab308; } .dot.green { background: #22c55e; }
.output-body {
  padding: 16px 20px; font-family: 'Fira Code', monospace; font-size: 0.85rem;
  color: #a0ffa0; min-height: 60px; max-height: 300px; overflow-y: auto;
  line-height: 1.6;
}
.output-body.error { color: var(--error); }

/* SQL RESULT TABLE */
.sql-result-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Fira Code', monospace;
  font-size: 0.85rem;
  margin-top: 10px;
}
.sql-result-table th {
  background: rgba(78,205,196,0.15);
  color: var(--accent2);
  padding: 8px 12px;
  text-align: left;
  border-bottom: 2px solid rgba(78,205,196,0.3);
  font-weight: 600;
}
.sql-result-table td {
  padding: 6px 12px;
  border-bottom: 1px solid rgba(255,255,255,0.05);
  color: #a0ffa0;
}
.sql-result-table tr:nth-child(even) td {
  background: rgba(255,255,255,0.02);
}
.sql-result-table tr:hover td {
  background: rgba(78,205,196,0.05);
}

/* MODAL */
.modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.7); backdrop-filter: blur(5px);
  display: none; align-items: center; justify-content: center; z-index: 200;
}
.modal-overlay.show { display: flex; }
.modal-content {
  background: var(--bg-card); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 24px; padding: 40px; text-align: center;
  max-width: 480px; width: 90%; animation: modal-pop 0.4s ease;
}
@keyframes modal-pop { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
.modal-icon { font-size: 4rem; margin-bottom: 16px; }
.modal-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 10px; }
.modal-text { color: var(--text-dim); margin-bottom: 8px; line-height: 1.6; }
.modal-xp { color: var(--accent3); font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; }
.btn-next {
  background: linear-gradient(135deg, var(--accent3), #f59e0b); color: #000; border: none;
  padding: 14px 40px; border-radius: 14px; font-family: 'Prompt'; font-size: 1.1rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
}
.btn-next:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,230,109,0.3); }

/* RESPONSIVE */
@media (max-width: 768px) {
  .home-title { font-size: 2.4rem; }
  .stage-map { grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .game-screen { padding: 15px; }
  .top-bar { padding: 10px 15px; }
  .xp-bar-container { width: 120px; }
  .action-bar { flex-wrap: wrap; }
}
</style>
</head>
<body>

<div class="particles" id="particles"></div>

<!-- LOADING -->
<div id="loading-screen">
  <div class="loading-logo">💾 SQL Quest</div>
  <div class="loading-bar-container"><div class="loading-bar" id="loading-bar"></div></div>
  <div class="loading-text" id="loading-text">กำลังโหลด SQL Engine (WASM)...</div>
</div>

<!-- APP -->
<div class="app-container" id="app">
  <div class="top-bar">
    <div class="logo">💾 SQL Quest <span>ผจญภัยแดนฐานข้อมูล</span></div>
    <div class="player-info">
      <div class="xp-text" id="xp-display">XP: 0 / 100</div>
      <div class="xp-bar-container"><div class="xp-bar" id="xp-bar"></div></div>
      <div class="level-badge" id="player-level">Lv.1 มือใหม่</div>
    </div>
  </div>

  <!-- HOME -->
  <div class="screen active" id="home-screen">
    <div class="home-screen">
      <div class="home-title">SQL Quest</div>
      <div class="home-subtitle">ผจญภัยแดนฐานข้อมูล — เรียน SQL ผ่าน <strong>40 ด่าน</strong> สุดมัน!</div>
      <div class="stage-map" id="stage-map"></div>
    </div>
  </div>

  <!-- GAME -->
  <div class="screen" id="game-screen">
    <div class="game-screen">
      <div class="game-header">
        <button class="btn-back" onclick="goHome()">← กลับ</button>
        <div class="game-title-area">
          <h2 id="game-stage-title"></h2>
        </div>
      </div>

      <div class="story-box" id="story-box">
        <div class="story-character" id="story-char">💻</div>
        <div class="story-text" id="story-text"></div>
      </div>

      <div class="mission-box">
        <h3>ภารกิจ</h3>
        <div class="mission-text" id="mission-text"></div>
      </div>

      <!-- 3-LEVEL HINTS -->
      <div class="hint-area">
        <div class="hint-buttons" id="hint-buttons"></div>
        <div class="hint-box" id="hint-box-1"><p id="hint-text-1"></p></div>
        <div class="hint-box level2" id="hint-box-2"><p id="hint-text-2"></p></div>
        <div class="hint-box level3" id="hint-box-3"><p id="hint-text-3"></p></div>
      </div>

      <!-- SQL Editor -->
      <div id="editor-section" style="display:none;">
        <div class="editor-area">
          <div class="editor-tabs"><div class="editor-tab active">📄 query.sql</div></div>
          <textarea class="code-editor" id="code-editor" spellcheck="false" placeholder="-- เขียน SQL ที่นี่...&#10;SELECT * FROM students;"></textarea>
        </div>
        <div class="action-bar">
          <button class="btn-run" id="btn-run" onclick="runSQL()">▶ รัน SQL</button>
          <button class="btn-hint" id="btn-hint-1" onclick="showHint(1)">💡 Hint 1 <span class="xp-penalty">(-10 XP)</span></button>
          <button class="btn-hint level2" id="btn-hint-2" onclick="showHint(2)">🔶 Hint 2 <span class="xp-penalty">(-25 XP)</span></button>
          <button class="btn-hint level3" id="btn-hint-3" onclick="showHint(3)">🔴 Hint 3 <span class="xp-penalty">(-50 XP)</span></button>
        </div>
      </div>

      <!-- OUTPUT -->
      <div class="output-panel" id="output-panel" style="display:none;">
        <div class="output-header">
          <div class="dot yellow"></div>
          <div class="dot yellow"></div>
          <div class="dot yellow"></div>
          <span id="output-label">OUTPUT</span>
        </div>
        <div class="output-body" id="output"></div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modal">
  <div class="modal-content" id="modal-content">
    <div class="modal-icon" id="modal-icon">✨</div>
    <div class="modal-title" id="modal-title">สุดยอด!</div>
    <div class="modal-text" id="modal-text">คำตอบถูกต้อง!</div>
    <div class="modal-xp" id="modal-xp">+100 XP</div>
    <button class="btn-next" id="btn-next-level" onclick="nextLevel()">ด่านต่อไป →</button>
  </div>
</div>

<script>
let SQL, db, currentLevel = 1, playerXP = 0, playerLevel = 1;
const completedLevels = new Set(), hintUsed = {};

const LEVELS = [{"id": 1, "zone": 1, "title": "SELECT ทั้งหมด", "tutorialTitle": "ดึงข้อมูลทั้งหมด", "description": "ใช้ SELECT * เพื่อดึงข้อมูลทั้งหมดจากตาราง students", "tutorial": "SELECT * ใช้สำหรับดึงข้อมูลทั้งหมดจากตาราง\n\nตัวอย่าง:\nSELECT * FROM students;\n\nผลลัพธ์: แสดงทุกคอลัมน์ของทุกแถว", "task": "เขียน SQL ดึงข้อมูลทั้งหมดจากตาราง students", "expectedSQL": "SELECT * FROM students", "expectedRowCount": 8, "codeCheck": ["SELECT", "*", "FROM", "students"], "hints": ["ใช้ SELECT * FROM [ชื่อตาราง]", "students คือชื่อตาราง", "* หมายถึงทุกคอลัมน์"]}, {"id": 2, "zone": 1, "title": "SELECT เฉพาะคอลัมน์", "tutorialTitle": "เลือกคอลัมน์เฉพาะ", "description": "เลือกเฉพาะชื่อและคะแนนของนักเรียน", "tutorial": "เมื่อต้องการคอลัมน์บางคอลัมน์ ใช้ชื่อคอลัมน์แทน *\n\nตัวอย่าง:\nSELECT name, score FROM students;\n\nผลลัพธ์: แสดงเฉพาะชื่อและคะแนน", "task": "เขียน SQL ดึง name และ score จากตาราง students", "expectedSQL": "SELECT name, score FROM students", "expectedRowCount": 8, "codeCheck": ["SELECT", "name", "score", "FROM"], "hints": ["ระบุชื่อคอลัมน์ที่ต้องการ: name, score", "คั่นด้วยเครื่องหมายจุลภาค (,)", "ใช้ SELECT name, score FROM students"]}, {"id": 3, "zone": 1, "title": "WHERE เงื่อนไข", "tutorialTitle": "เงื่อนไขในการค้นหา", "description": "ดึงนักเรียนที่อายุเท่ากับ 17 ปี", "tutorial": "WHERE ใช้เพื่อกำหนดเงื่อนไข\n\nตัวอย่าง:\nSELECT * FROM students WHERE age = 17;\n\nผลลัพธ์: แสดงเฉพาะนักเรียนที่อายุ 17", "task": "ดึงนักเรียนทั้งหมดที่มีอายุ 17 ปี", "expectedSQL": "SELECT * FROM students WHERE age = 17", "expectedRowCount": 3, "codeCheck": ["SELECT", "WHERE", "age", "17"], "hints": ["ใช้ WHERE age = 17", "age คือคอลัมน์อายุ", "SELECT * FROM students WHERE age = 17"]}, {"id": 4, "zone": 1, "title": "ORDER BY เรียงลำดับ", "tutorialTitle": "เรียงลำดับข้อมูล", "description": "ดึงข้อมูลนักเรียนเรียงตามคะแนนจากน้อยไปมาก", "tutorial": "ORDER BY เรียงลำดับข้อมูล\n\nตัวอย่าง:\nSELECT * FROM students ORDER BY score ASC;\n\nASC = จากน้อยไปมาก (default)\nDESC = จากมากไปน้อย", "task": "ดึงข้อมูลนักเรียนเรียงตามคะแนนจากน้อยไปมาก", "expectedSQL": "SELECT * FROM students ORDER BY score ASC", "expectedRowCount": 8, "codeCheck": ["SELECT", "ORDER", "BY", "score"], "hints": ["ใช้ ORDER BY score ASC", "ASC = จากน้อยไปมาก", "SELECT * FROM students ORDER BY score ASC"]}, {"id": 5, "zone": 1, "title": "LIMIT ข้อมูลจำนวน", "tutorialTitle": "จำกัดจำนวนแถว", "description": "ดึง 3 นักเรียนแรก", "tutorial": "LIMIT ใช้เพื่อจำกัดจำนวนแถว\n\nตัวอย่าง:\nSELECT * FROM students LIMIT 3;\n\nผลลัพธ์: แสดงเฉพาะ 3 แถวแรก", "task": "ดึง 3 นักเรียนแรกเท่านั้น", "expectedSQL": "SELECT * FROM students LIMIT 3", "expectedRowCount": 3, "codeCheck": ["SELECT", "LIMIT", "3"], "hints": ["ใช้ LIMIT 3", "LIMIT กำหนดจำนวนแถวที่ต้องการ", "SELECT * FROM students LIMIT 3"]}, {"id": 6, "zone": 1, "title": "COUNT นับจำนวน", "tutorialTitle": "นับจำนวนแถว", "description": "นับว่ามีนักเรียนกี่คน", "tutorial": "COUNT(*) นับจำนวนแถวทั้งหมด\n\nตัวอย่าง:\nSELECT COUNT(*) FROM students;\n\nผลลัพธ์: แสดงตัวเลขจำนวนแถว", "task": "นับจำนวนนักเรียนทั้งหมด", "expectedSQL": "SELECT COUNT(*) FROM students", "expectedRowCount": 1, "codeCheck": ["SELECT", "COUNT"], "hints": ["ใช้ COUNT(*) เพื่อนับทั้งหมด", "COUNT() จะคืนค่าตัวเลขเพียงตัวเดียว", "SELECT COUNT(*) FROM students"]}, {"id": 7, "zone": 1, "title": "MIN/MAX", "tutorialTitle": "ค่าต่ำสุดและสูงสุด", "description": "หาคะแนนต่ำสุดและสูงสุด", "tutorial": "MIN() หาค่าต่ำสุด, MAX() หาค่าสูงสุด\n\nตัวอย่าง:\nSELECT MIN(score) FROM students;\nSELECT MAX(score) FROM students;", "task": "หาคะแนนสูงสุดของนักเรียนทั้งหมด", "expectedSQL": "SELECT MAX(score) FROM students", "expectedRowCount": 1, "codeCheck": ["SELECT", "MAX", "score"], "hints": ["ใช้ MAX(score) หาคะแนนสูงสุด", "MAX() คืนค่าจำนวนเดียว", "SELECT MAX(score) FROM students"]}, {"id": 8, "zone": 1, "title": "AVG เฉลี่ยค่า", "tutorialTitle": "ค่าเฉลี่ย", "description": "หาคะแนนเฉลี่ยของนักเรียน", "tutorial": "AVG() คำนวณค่าเฉลี่ย\n\nตัวอย่าง:\nSELECT AVG(score) FROM students;\n\nผลลัพธ์: แสดงค่าเฉลี่ยของคะแนน", "task": "หาคะแนนเฉลี่ยของนักเรียนทั้งหมด", "expectedSQL": "SELECT AVG(score) FROM students", "expectedRowCount": 1, "codeCheck": ["SELECT", "AVG", "score"], "hints": ["ใช้ AVG(score) หาค่าเฉลี่ย", "AVG() คำนวณผลรวมหารด้วยจำนวน", "SELECT AVG(score) FROM students"]}, {"id": 9, "zone": 2, "title": "DISTINCT ค่าที่ไม่ซ้ำ", "tutorialTitle": "ลบค่าที่ซ้ำกัน", "description": "หากเกรดที่แตกต่างกัน", "tutorial": "DISTINCT ใช้ลบค่าที่ซ้ำกัน\n\nตัวอย่าง:\nSELECT DISTINCT grade FROM students;", "task": "หาเกรดที่ไม่ซ้ำกัน", "expectedSQL": "SELECT DISTINCT grade FROM students", "expectedRowCount": 3, "codeCheck": ["SELECT", "DISTINCT", "grade"], "hints": ["ใช้ DISTINCT เพื่อลบค่าซ้ำ", "DISTINCT grade จะแสดงเกรดไม่ซ้ำ", "SELECT DISTINCT grade FROM students"]}, {"id": 10, "zone": 2, "title": "JOIN รวมตาราง", "tutorialTitle": "รวมข้อมูลจากตารางสองตาราง", "description": "แสดงชื่อและวิชาที่ลงทะเบียน", "tutorial": "JOIN รวมข้อมูลจากสองตาราง\n\nตัวอย่าง:\nSELECT students.name, courses.course_name FROM students JOIN enrollments ON students.id = enrollments.student_id JOIN courses ON enrollments.course_id = courses.id;", "task": "ดึงข้อมูลเก่า: ชื่อนักเรียนและวิชาที่ลงทะเบียน", "expectedSQL": "SELECT students.name, courses.course_name FROM students JOIN enrollments ON students.id = enrollments.student_id JOIN courses ON enrollments.course_id = courses.id", "expectedRowCount": 16, "codeCheck": ["SELECT", "JOIN", "enrollments", "courses"], "hints": ["ใช้ JOIN เพื่อรวมตาราง", "ต้อง JOIN enrollments และ courses กับ students", "คำสั่ง: SELECT students.name, courses.course_name FROM students JOIN enrollments ON students.id = enrollments.student_id JOIN courses ON enrollments.course_id = courses.id"]}, {"id": 11, "zone": 2, "title": "WHERE + JOIN", "tutorialTitle": "กรองข้อมูล JOIN", "description": "ดึงชื่อและวิชาของสมชายเท่านั้น", "tutorial": "รวม WHERE กับ JOIN เพื่อกรองข้อมูล\n\nตัวอย่าง:\nSELECT s.name, c.course_name FROM students s JOIN enrollments e ON s.id = e.student_id JOIN courses c ON e.course_id = c.id WHERE s.name = 'สมชาย';", "task": "หาวิชาที่สมชายลงทะเบียน", "expectedSQL": "SELECT students.name, courses.course_name FROM students JOIN enrollments ON students.id = enrollments.student_id JOIN courses ON enrollments.course_id = courses.id WHERE students.name = 'สมชาย'", "expectedRowCount": 2, "codeCheck": ["SELECT", "WHERE", "students.name", "สมชาย"], "hints": ["ใช้ WHERE students.name = 'สมชาย'", "ต้อง JOIN enrollments และ courses", "ต้องเพิ่ม WHERE students.name = 'สมชาย' ที่ท้าย"]}, {"id": 12, "zone": 2, "title": "GROUP BY จัดกลุ่ม", "tutorialTitle": "จัดกลุ่มข้อมูล", "description": "นับจำนวนนักเรียนในแต่ละเกรด", "tutorial": "GROUP BY จัดกลุ่มข้อมูลตามคอลัมน์\n\nตัวอย่าง:\nSELECT grade, COUNT(*) FROM students GROUP BY grade;", "task": "นับจำนวนนักเรียนแต่ละเกรด", "expectedSQL": "SELECT grade, COUNT(*) FROM students GROUP BY grade", "expectedRowCount": 3, "codeCheck": ["SELECT", "GROUP", "BY", "grade"], "hints": ["ใช้ GROUP BY grade", "ต้อง COUNT(*) กับ GROUP BY", "SELECT grade, COUNT(*) FROM students GROUP BY grade"]}, {"id": 13, "zone": 2, "title": "HAVING กรองกลุ่ม", "tutorialTitle": "กรองข้อมูลหลังจากจัดกลุ่ม", "description": "หากเกรดที่มีนักเรียนมากกว่า 2 คน", "tutorial": "HAVING กรองกลุ่มที่ COUNT > บางค่า\n\nตัวอย่าง:\nSELECT grade, COUNT(*) FROM students GROUP BY grade HAVING COUNT(*) > 2;", "task": "หาเกรดที่มีนักเรียนมากกว่า 2 คน", "expectedSQL": "SELECT grade, COUNT(*) FROM students GROUP BY grade HAVING COUNT(*) > 2", "expectedRowCount": 2, "codeCheck": ["SELECT", "HAVING", "COUNT"], "hints": ["ใช้ HAVING COUNT(*) > 2", "HAVING ใช้กับ GROUP BY", "SELECT grade, COUNT(*) FROM students GROUP BY grade HAVING COUNT(*) > 2"]}, {"id": 14, "zone": 2, "title": "SUM รวมค่า", "tutorialTitle": "รวมค่าตัวเลข", "description": "รวมจำนวนสินค้าทั้งหมด", "tutorial": "SUM() รวมค่าของคอลัมน์ตัวเลข\n\nตัวอย่าง:\nSELECT SUM(quantity) FROM orders;", "task": "รวมจำนวนสินค้าทั้งหมดในออเดอร์", "expectedSQL": "SELECT SUM(quantity) FROM orders", "expectedRowCount": 1, "codeCheck": ["SELECT", "SUM", "quantity"], "hints": ["ใช้ SUM(quantity)", "SUM() รวมค่าทั้งหมด", "SELECT SUM(quantity) FROM orders"]}, {"id": 15, "zone": 2, "title": "LIKE ค้นหาแบบ", "tutorialTitle": "ค้นหาโดยใช้รูปแบบ", "description": "ค้นหาสินค้าที่มีคำว่า กาแฟ", "tutorial": "LIKE ใช้ค้นหาแบบรูปแบบ\n% = ตัวอักษรหลายตัว\n_ = ตัวอักษรตัวเดียว\n\nตัวอย่าง:\nSELECT * FROM products WHERE name LIKE '%กาแฟ%';", "task": "ค้นหาสินค้าที่ชื่อมีคำว่า กาแฟ", "expectedSQL": "SELECT * FROM products WHERE name LIKE '%กาแฟ%'", "expectedRowCount": 1, "codeCheck": ["SELECT", "LIKE", "กาแฟ"], "hints": ["ใช้ LIKE '%กาแฟ%'", "% ใช้แทนตัวอักษรหลายตัว", "SELECT * FROM products WHERE name LIKE '%กาแฟ%'"]}, {"id": 16, "zone": 3, "title": "IN รายการหลายค่า", "tutorialTitle": "ค้นหาจากรายการ", "description": "ดึงสินค้าในหมวด เครื่องดื่ม หรือ ขนม", "tutorial": "IN ใช้เพื่อค้นหาหลายค่า\n\nตัวอย่าง:\nSELECT * FROM products WHERE category IN ('เครื่องดื่ม', 'ขนม');", "task": "ค้นหาสินค้าในหมวด เครื่องดื่ม หรือ ขนม", "expectedSQL": "SELECT * FROM products WHERE category IN ('เครื่องดื่ม', 'ขนม')", "expectedRowCount": 6, "codeCheck": ["SELECT", "IN", "เครื่องดื่ม"], "hints": ["ใช้ IN ('เครื่องดื่ม', 'ขนม')", "IN ใช้สำหรับหลายค่า", "SELECT * FROM products WHERE category IN ('เครื่องดื่ม', 'ขนม')"]}, {"id": 17, "zone": 3, "title": "BETWEEN ช่วงค่า", "tutorialTitle": "ค้นหาในช่วงค่า", "description": "ค้นหาสินค้าที่ราคาระหว่าง 50-80 บาท", "tutorial": "BETWEEN ใช้ค้นหาช่วงค่า\n\nตัวอย่าง:\nSELECT * FROM products WHERE price BETWEEN 50 AND 80;", "task": "หาสินค้าที่ราคาระหว่าง 50-80 บาท", "expectedSQL": "SELECT * FROM products WHERE price BETWEEN 50 AND 80", "expectedRowCount": 4, "codeCheck": ["SELECT", "BETWEEN", "50"], "hints": ["ใช้ BETWEEN 50 AND 80", "BETWEEN ใช้สำหรับช่วงค่า", "SELECT * FROM products WHERE price BETWEEN 50 AND 80"]}, {"id": 18, "zone": 3, "title": "NULL ค่าว่าง", "tutorialTitle": "ค้นหาค่าว่าง", "description": "ค้นหาสินค้าที่ไม่มีข้อมูล", "tutorial": "IS NULL / IS NOT NULL ใช้เพื่อค้นหาค่าว่าง\n\nตัวอย่าง:\nSELECT * FROM products WHERE stock IS NULL;", "task": "ค้นหาสินค้าที่ stock ไม่เป็น NULL", "expectedSQL": "SELECT * FROM products WHERE stock IS NOT NULL", "expectedRowCount": 8, "codeCheck": ["SELECT", "IS NOT NULL"], "hints": ["ใช้ IS NOT NULL", "ต้อง stock IS NOT NULL", "SELECT * FROM products WHERE stock IS NOT NULL"]}, {"id": 19, "zone": 3, "title": "UNION รวมผล", "tutorialTitle": "รวมผลลัพธ์สองชุด", "description": "รวมชื่อนักเรียนและชื่อสินค้า", "tutorial": "UNION รวมผลลัพธ์จากสองคำสั่ง\n\nตัวอย่าง:\nSELECT name FROM students UNION SELECT name FROM products;", "task": "รวมชื่อจากนักเรียนและชื่อสินค้า", "expectedSQL": "SELECT name FROM students UNION SELECT name FROM products", "expectedRowCount": 16, "codeCheck": ["SELECT", "UNION"], "hints": ["ใช้ UNION", "UNION รวมผลลัพธ์", "SELECT name FROM students UNION SELECT name FROM products"]}, {"id": 20, "zone": 3, "title": "AS ตั้งชื่อ", "tutorialTitle": "ตั้งชื่อคอลัมน์ใหม่", "description": "แสดงชื่อสินค้า และราคาเป็นจำนวนเงิน", "tutorial": "AS ตั้งชื่อสำหรับคอลัมน์ใหม่\n\nตัวอย่าง:\nSELECT name AS ชื่อ, price AS ราคา FROM products;", "task": "ดึงชื่อสินค้ากับราคา ตั้งชื่อเป็น ชื่อ และ ราคา", "expectedSQL": "SELECT name AS ชื่อ, price AS ราคา FROM products", "expectedRowCount": 8, "codeCheck": ["SELECT", "AS", "ชื่อ"], "hints": ["ใช้ AS ชื่อ", "AS ตั้งชื่อคอลัมน์ใหม่", "SELECT name AS ชื่อ, price AS ราคา FROM products"]}, {"id": 21, "zone": 4, "title": "CASE คำสั่งเงื่อนไข", "tutorialTitle": "เงื่อนไข CASE", "description": "แสดงระดับคะแนน: เก่ง-ปกติ-อ่อน", "tutorial": "CASE ใช้เพื่อเงื่อนไขหลายกรณี\n\nตัวอย่าง:\nSELECT name, CASE WHEN score >= 90 THEN 'เก่ง' ELSE 'ปกติ' END FROM students;", "task": "แสดงชื่อและระดับคะแนน (>= 90 = เก่ง, < 90 = ปกติ)", "expectedSQL": "SELECT name, CASE WHEN score >= 90 THEN 'เก่ง' ELSE 'ปกติ' END FROM students", "expectedRowCount": 8, "codeCheck": ["SELECT", "CASE", "WHEN"], "hints": ["ใช้ CASE WHEN score >= 90 THEN 'เก่ง' ELSE 'ปกติ' END", "CASE ใช้สำหรับเงื่อนไข", "SELECT name, CASE WHEN score >= 90 THEN 'เก่ง' ELSE 'ปกติ' END FROM students"]}, {"id": 22, "zone": 4, "title": "UPDATE เปลี่ยนข้อมูล", "tutorialTitle": "อัปเดตข้อมูล", "description": "เปลี่ยนคะแนนของสมชาย เป็น 100", "tutorial": "UPDATE เปลี่ยนข้อมูลในตาราง\n\nตัวอย่าง:\nUPDATE students SET score = 100 WHERE name = 'สมชาย';", "task": "เปลี่ยนคะแนนของสมชาย เป็น 100", "expectedSQL": "UPDATE students SET score = 100 WHERE name = 'สมชาย'", "expectedRowCount": 0, "codeCheck": ["UPDATE", "SET", "WHERE"], "hints": ["ใช้ UPDATE students", "SET score = 100 WHERE name = 'สมชาย'", "UPDATE students SET score = 100 WHERE name = 'สมชาย'"]}, {"id": 23, "zone": 4, "title": "DELETE ลบข้อมูล", "tutorialTitle": "ลบข้อมูล", "description": "ลบออเดอร์ของคุณแก้ว", "tutorial": "DELETE ลบข้อมูลจากตาราง\n\nตัวอย่าง:\nDELETE FROM orders WHERE customer_name = 'คุณแก้ว';", "task": "ลบออเดอร์ทั้งหมดของคุณแก้ว", "expectedSQL": "DELETE FROM orders WHERE customer_name = 'คุณแก้ว'", "expectedRowCount": 0, "codeCheck": ["DELETE", "WHERE"], "hints": ["ใช้ DELETE FROM orders", "WHERE customer_name = 'คุณแก้ว'", "DELETE FROM orders WHERE customer_name = 'คุณแก้ว'"]}, {"id": 24, "zone": 4, "title": "INSERT เพิ่มข้อมูล", "tutorialTitle": "เพิ่มข้อมูลใหม่", "description": "เพิ่มนักเรียนใหม่ชื่อ วีรพล อายุ 17", "tutorial": "INSERT เพิ่มข้อมูลลงตาราง\n\nตัวอย่าง:\nINSERT INTO students (name, age) VALUES ('วีรพล', 17);", "task": "เพิ่มนักเรียนใหม่: ชื่อ วีรพล, อายุ 17", "expectedSQL": "INSERT INTO students (name, age, grade, score) VALUES ('วีรพล', 17, 'ม.5', 85)", "expectedRowCount": 0, "codeCheck": ["INSERT", "INTO", "students"], "hints": ["ใช้ INSERT INTO students", "VALUES ('วีรพล', 17, 'ม.5', 85)", "INSERT INTO students (name, age, grade, score) VALUES ('วีรพล', 17, 'ม.5', 85)"]}, {"id": 25, "zone": 4, "title": "CREATE TABLE สร้างตาราง", "tutorialTitle": "สร้างตารางใหม่", "description": "สร้างตาราง teachers", "tutorial": "CREATE TABLE สร้างตารางใหม่\n\nตัวอย่าง:\nCREATE TABLE teachers (id INTEGER, name TEXT);", "task": "สร้างตาราง teachers ด้วยคอลัมน์ id และ name", "expectedSQL": "CREATE TABLE teachers (id INTEGER, name TEXT)", "expectedRowCount": 0, "codeCheck": ["CREATE", "TABLE", "teachers"], "hints": ["ใช้ CREATE TABLE teachers", "(id INTEGER, name TEXT)", "CREATE TABLE teachers (id INTEGER, name TEXT)"]}, {"id": 26, "zone": 5, "title": "LEFT JOIN ด้านซ้าย", "tutorialTitle": "JOIN ด้านซ้าย", "description": "แสดงชื่อนักเรียนและวิชา (แม้ไม่ลงทะเบียน)", "tutorial": "LEFT JOIN รักษาทุกแถวจากตารางด้านซ้าย\n\nตัวอย่าง:\nSELECT s.name, c.course_name FROM students s LEFT JOIN enrollments e ON s.id = e.student_id LEFT JOIN courses c ON e.course_id = c.id;", "task": "แสดงชื่อนักเรียนและวิชา ใช้ LEFT JOIN", "expectedSQL": "SELECT students.name, courses.course_name FROM students LEFT JOIN enrollments ON students.id = enrollments.student_id LEFT JOIN courses ON enrollments.course_id = courses.id", "expectedRowCount": 16, "codeCheck": ["SELECT", "LEFT JOIN"], "hints": ["ใช้ LEFT JOIN", "LEFT JOIN รักษาแถวด้านซ้าย", "SELECT students.name, courses.course_name FROM students LEFT JOIN enrollments ON students.id = enrollments.student_id LEFT JOIN courses ON enrollments.course_id = courses.id"]}, {"id": 27, "zone": 5, "title": "RIGHT JOIN ด้านขวา", "tutorialTitle": "JOIN ด้านขวา", "description": "แสดงวิชาทั้งหมดแม้ไม่มีนักเรียน", "tutorial": "RIGHT JOIN รักษาทุกแถวจากตารางด้านขวา\n\nตัวอย่าง:\nSELECT c.course_name, s.name FROM students s RIGHT JOIN courses c ON ...;", "task": "แสดงวิชาทั้งหมดกับชื่อนักเรียน ใช้ RIGHT JOIN", "expectedSQL": "SELECT courses.course_name, students.name FROM students RIGHT JOIN enrollments ON students.id = enrollments.student_id RIGHT JOIN courses ON enrollments.course_id = courses.id", "expectedRowCount": 16, "codeCheck": ["SELECT", "RIGHT JOIN"], "hints": ["ใช้ RIGHT JOIN", "RIGHT JOIN รักษาแถวด้านขวา", "SELECT courses.course_name, students.name FROM students RIGHT JOIN enrollments ... RIGHT JOIN courses ..."]}, {"id": 28, "zone": 5, "title": "INNER JOIN (DEFAULT)", "tutorialTitle": "JOIN ค่ากลาง", "description": "แสดงเฉพาะที่มีความสัมพันธ์", "tutorial": "INNER JOIN (หรือ JOIN) แสดงเฉพาะแถวที่ตรงกัน\n\nตัวอย่าง:\nSELECT s.name, c.course_name FROM students s INNER JOIN enrollments e ON s.id = e.student_id;", "task": "แสดงชื่อและวิชา เฉพาะที่มีความสัมพันธ์", "expectedSQL": "SELECT students.name, courses.course_name FROM students INNER JOIN enrollments ON students.id = enrollments.student_id INNER JOIN courses ON enrollments.course_id = courses.id", "expectedRowCount": 16, "codeCheck": ["SELECT", "INNER JOIN"], "hints": ["ใช้ INNER JOIN", "INNER JOIN แสดงเฉพาะที่ตรงกัน", "SELECT students.name, courses.course_name FROM students INNER JOIN enrollments ON students.id = enrollments.student_id INNER JOIN courses ON enrollments.course_id = courses.id"]}, {"id": 29, "zone": 5, "title": "FULL OUTER JOIN", "tutorialTitle": "JOIN ทั้งหมด", "description": "แสดงทั้งสองตาราง", "tutorial": "FULL OUTER JOIN แสดงเฉพาะเมื่อ SQLite รองรับ (ใช้ UNION แทน)\n\nตัวอย่าง:\nSELECT s.name FROM students s LEFT JOIN enrollments e ON s.id = e.student_id UNION SELECT s.name FROM students s RIGHT JOIN enrollments e ON s.id = e.student_id;", "task": "แสดงนักเรียนทั้งหมด (ด้วย LEFT JOIN + UNION + RIGHT JOIN)", "expectedSQL": "SELECT students.name FROM students LEFT JOIN enrollments ON students.id = enrollments.student_id UNION SELECT students.name FROM students RIGHT JOIN enrollments ON students.id = enrollments.student_id", "expectedRowCount": 8, "codeCheck": ["SELECT", "UNION"], "hints": ["SQLite ไม่รองรับ FULL OUTER JOIN ใช้ UNION แทน", "SELECT ... LEFT JOIN ... UNION SELECT ... RIGHT JOIN ...", "SELECT students.name FROM students LEFT JOIN enrollments ON students.id = enrollments.student_id UNION SELECT students.name FROM students RIGHT JOIN enrollments ON students.id = enrollments.student_id"]}, {"id": 30, "zone": 5, "title": "CROSS JOIN ผลคูณ", "tutorialTitle": "ผลคูณคาร์ทีเซียน", "description": "รวมนักเรียนกับสินค้า", "tutorial": "CROSS JOIN รวมทุกแถวของสองตาราง\n\nตัวอย่าง:\nSELECT s.name, p.name FROM students s CROSS JOIN products p;", "task": "รวมนักเรียนกับสินค้า ใช้ CROSS JOIN", "expectedSQL": "SELECT students.name, products.name FROM students CROSS JOIN products", "expectedRowCount": 64, "codeCheck": ["SELECT", "CROSS JOIN"], "hints": ["ใช้ CROSS JOIN", "CROSS JOIN รวมทั้งหมด (8 x 8 = 64)", "SELECT students.name, products.name FROM students CROSS JOIN products"]}, {"id": 31, "zone": 6, "title": "SUBQUERY คำสั่งซ้อน", "tutorialTitle": "คำสั่ง SQL ซ้อนกัน", "description": "หานักเรียนที่มีคะแนนเท่ากับคะแนนเฉลี่ย", "tutorial": "SUBQUERY คำสั่ง SELECT ซ้อนในคำสั่ง SELECT\n\nตัวอย่าง:\nSELECT * FROM students WHERE score = (SELECT AVG(score) FROM students);", "task": "หานักเรียนที่มีคะแนนเท่ากับคะแนนเฉลี่ย", "expectedSQL": "SELECT * FROM students WHERE score = (SELECT AVG(score) FROM students)", "expectedRowCount": 1, "codeCheck": ["SELECT", "WHERE", "score"], "hints": ["ใช้ SUBQUERY", "ซ้อน SELECT AVG(score) ในวงเล็บ", "SELECT * FROM students WHERE score = (SELECT AVG(score) FROM students)"]}, {"id": 32, "zone": 6, "title": "EXISTS ตรวจสอบจำนวน", "tutorialTitle": "ตรวจสอบว่ามีข้อมูล", "description": "หาอเดอร์ที่มีสินค้าราคามากกว่า 80 บาท", "tutorial": "EXISTS ตรวจสอบว่ามีแถวหรือไม่\n\nตัวอย่าง:\nSELECT * FROM orders o WHERE EXISTS (SELECT 1 FROM products p WHERE p.id = o.product_id AND p.price > 80);", "task": "หาออเดอร์ที่สินค้าราคามากกว่า 80 บาท", "expectedSQL": "SELECT * FROM orders WHERE EXISTS (SELECT 1 FROM products WHERE products.id = orders.product_id AND products.price > 80)", "expectedRowCount": 5, "codeCheck": ["SELECT", "EXISTS"], "hints": ["ใช้ EXISTS", "EXISTS ตรวจสอบว่ามีข้อมูล", "SELECT * FROM orders WHERE EXISTS (SELECT 1 FROM products WHERE products.id = orders.product_id AND products.price > 80)"]}, {"id": 33, "zone": 6, "title": "IN + SUBQUERY", "tutorialTitle": "ค้นหาด้วย SUBQUERY", "description": "หาสินค้าที่มีในออเดอร์", "tutorial": "IN + SUBQUERY ค้นหาหลายค่า\n\nตัวอย่าง:\nSELECT * FROM products WHERE id IN (SELECT product_id FROM orders);", "task": "หาสินค้าที่มีในออเดอร์อย่างน้อยหนึ่งครั้ง", "expectedSQL": "SELECT * FROM products WHERE id IN (SELECT product_id FROM orders)", "expectedRowCount": 8, "codeCheck": ["SELECT", "IN", "SUBQUERY"], "hints": ["ใช้ IN (SELECT ...)", "SUBQUERY คืน product_id จาก orders", "SELECT * FROM products WHERE id IN (SELECT product_id FROM orders)"]}, {"id": 34, "zone": 6, "title": "NOT IN", "tutorialTitle": "ค้นหาเฉพาะที่ไม่อยู่", "description": "หาสินค้าที่ไม่มีในออเดอร์", "tutorial": "NOT IN ค้นหาค่าที่ไม่อยู่ในรายการ\n\nตัวอย่าง:\nSELECT * FROM products WHERE id NOT IN (SELECT product_id FROM orders);", "task": "หาสินค้าที่ไม่มีในออเดอร์เลย", "expectedSQL": "SELECT * FROM products WHERE id NOT IN (SELECT product_id FROM orders)", "expectedRowCount": 0, "codeCheck": ["SELECT", "NOT IN"], "hints": ["ใช้ NOT IN", "NOT IN แสดงค่าที่ไม่อยู่", "SELECT * FROM products WHERE id NOT IN (SELECT product_id FROM orders)"]}, {"id": 35, "zone": 7, "title": "ROUND ปัดเศษ", "tutorialTitle": "ปัดเศษตัวเลข", "description": "ปัดเศษคะแนนเฉลี่ยเป็น 2 ตำแหน่ง", "tutorial": "ROUND ปัดเศษตัวเลข\n\nตัวอย่าง:\nSELECT ROUND(AVG(score), 2) FROM students;", "task": "ปัดเศษคะแนนเฉลี่ยเป็น 2 ตำแหน่งทศนิยม", "expectedSQL": "SELECT ROUND(AVG(score), 2) FROM students", "expectedRowCount": 1, "codeCheck": ["SELECT", "ROUND", "AVG"], "hints": ["ใช้ ROUND(AVG(score), 2)", "ROUND ปัดเศษ 2 ตำแหน่ง", "SELECT ROUND(AVG(score), 2) FROM students"]}, {"id": 36, "zone": 7, "title": "LENGTH ความยาวข้อความ", "tutorialTitle": "นับความยาวข้อความ", "description": "หาชื่อที่มีความยาวมากกว่า 3", "tutorial": "LENGTH นับความยาวของข้อความ\n\nตัวอย่าง:\nSELECT name FROM students WHERE LENGTH(name) > 3;", "task": "หาชื่อที่มีความยาวมากกว่า 3 ตัวอักษร", "expectedSQL": "SELECT name FROM students WHERE LENGTH(name) > 3", "expectedRowCount": 3, "codeCheck": ["SELECT", "LENGTH"], "hints": ["ใช้ LENGTH(name) > 3", "LENGTH นับตัวอักษร", "SELECT name FROM students WHERE LENGTH(name) > 3"]}, {"id": 37, "zone": 7, "title": "UPPER/LOWER", "tutorialTitle": "เปลี่ยนตัวอักษร", "description": "แสดงชื่อเป็นตัวพิมพ์ใหญ่", "tutorial": "UPPER ตัวพิมพ์ใหญ่ / LOWER ตัวพิมพ์เล็ก\n\nตัวอย่าง:\nSELECT UPPER(name) FROM students;", "task": "แสดงชื่อนักเรียนเป็นตัวพิมพ์ใหญ่ทั้งหมด", "expectedSQL": "SELECT UPPER(name) FROM students", "expectedRowCount": 8, "codeCheck": ["SELECT", "UPPER"], "hints": ["ใช้ UPPER(name)", "UPPER เปลี่ยนเป็นตัวพิมพ์ใหญ่", "SELECT UPPER(name) FROM students"]}, {"id": 38, "zone": 7, "title": "SUBSTR ตัดข้อความ", "tutorialTitle": "ตัดข้อความ", "description": "ตัดตัวอักษร 3 ตัวแรก", "tutorial": "SUBSTR ตัดข้อความ\n\nตัวอย่าง:\nSELECT SUBSTR(name, 1, 3) FROM students;", "task": "ตัดตัวอักษร 3 ตัวแรกของชื่อ", "expectedSQL": "SELECT SUBSTR(name, 1, 3) FROM students", "expectedRowCount": 8, "codeCheck": ["SELECT", "SUBSTR"], "hints": ["ใช้ SUBSTR(name, 1, 3)", "SUBSTR(คอลัมน์, ตำแหน่ง, ยาว)", "SELECT SUBSTR(name, 1, 3) FROM students"]}, {"id": 39, "zone": 8, "title": "DATE ฟังก์ชันวันที่", "tutorialTitle": "ใช้งานวันที่", "description": "นับจำนวนออเดอร์ในแต่ละวัน", "tutorial": "DATE ฟังก์ชันสำหรับวันที่\n\nตัวอย่าง:\nSELECT DATE(order_date), COUNT(*) FROM orders GROUP BY DATE(order_date);", "task": "นับจำนวนออเดอร์แต่ละวัน", "expectedSQL": "SELECT DATE(order_date), COUNT(*) FROM orders GROUP BY DATE(order_date)", "expectedRowCount": 8, "codeCheck": ["SELECT", "DATE", "GROUP BY"], "hints": ["ใช้ DATE(order_date)", "GROUP BY DATE(order_date)", "SELECT DATE(order_date), COUNT(*) FROM orders GROUP BY DATE(order_date)"]}, {"id": 40, "zone": 8, "title": "COMPLEX QUERY", "tutorialTitle": "คำสั่งซับซ้อน", "description": "หาชื่อสินค้า ปริมาณ และยอดรวม", "tutorial": "รวม JOIN, GROUP BY, COUNT, SUM\n\nตัวอย่าง:\nSELECT p.name, SUM(o.quantity) AS total_qty, SUM(o.quantity * p.price) AS total_amount FROM products p LEFT JOIN orders o ON p.id = o.product_id GROUP BY p.id, p.name, p.price ORDER BY total_amount DESC;", "task": "แสดงชื่อสินค้า รวมปริมาณ รวมยอดเงิน เรียงจากสูงสุด", "expectedSQL": "SELECT products.name, SUM(orders.quantity) AS total_qty, SUM(orders.quantity * products.price) AS total_amount FROM products LEFT JOIN orders ON products.id = orders.product_id GROUP BY products.id ORDER BY total_amount DESC", "expectedRowCount": 8, "codeCheck": ["SELECT", "JOIN", "SUM", "GROUP BY"], "hints": ["ใช้ LEFT JOIN orders", "GROUP BY products.id", "SELECT products.name, SUM(orders.quantity) AS total_qty, SUM(orders.quantity * products.price) AS total_amount FROM products LEFT JOIN orders ON products.id = orders.product_id GROUP BY products.id ORDER BY total_amount DESC"]}];

async function initSQL() {
  updateLoading(10, 'กำลังโหลด SQL Engine (WASM)...');
  SQL = await initSqlJs({locateFile: (file) => `https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.8.0/${file}`});
  updateLoading(60, 'กำลังเตรียมฐานข้อมูล...');
  setupDatabase();
  updateLoading(100, 'พร้อมแล้ว!');
  setTimeout(startApp, 500);
}

function setupDatabase() {
  db = new SQL.Database();
  db.run(`CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, grade TEXT, score REAL);
    INSERT INTO students VALUES (1,'สมชาย',16,'ม.4',85.5),(2,'สมหญิง',17,'ม.5',92.0),(3,'วิชัย',16,'ม.4',78.0),(4,'สุดา',18,'ม.6',95.5),(5,'ประเสริฐ',17,'ม.5',88.0),(6,'นภา',16,'ม.4',91.0),(7,'ธนา',18,'ม.6',72.5),(8,'พิมพ์',17,'ม.5',96.0);
    CREATE TABLE courses (id INTEGER PRIMARY KEY, course_name TEXT, teacher TEXT, credits INTEGER);
    INSERT INTO courses VALUES (1,'คณิตศาสตร์','อ.สมศรี',3),(2,'วิทยาศาสตร์','อ.ประยุทธ์',3),(3,'ภาษาอังกฤษ','อ.แมรี่',2),(4,'ภาษาไทย','อ.สุภาพ',2),(5,'คอมพิวเตอร์','อ.ธีระ',2);
    CREATE TABLE enrollments (id INTEGER PRIMARY KEY, student_id INTEGER, course_id INTEGER, semester TEXT, grade_letter TEXT);
    INSERT INTO enrollments VALUES (1,1,1,'1/2568','B+'),(2,1,3,'1/2568','A'),(3,2,1,'1/2568','A'),(4,2,2,'1/2568','A'),(5,3,1,'1/2568','C+'),(6,3,5,'1/2568','B'),(7,4,2,'1/2568','A'),(8,4,4,'1/2568','A'),(9,5,3,'1/2568','B+'),(10,5,5,'1/2568','A'),(11,6,1,'1/2568','A'),(12,6,2,'1/2568','B+'),(13,7,4,'1/2568','C'),(14,7,5,'1/2568','B'),(15,8,1,'1/2568','A'),(16,8,2,'1/2568','A');
    CREATE TABLE products (id INTEGER PRIMARY KEY, name TEXT, category TEXT, price REAL, stock INTEGER);
    INSERT INTO products VALUES (1,'กาแฟลาเต้','เครื่องดื่ม',65.0,100),(2,'ชาเขียว','เครื่องดื่ม',45.0,150),(3,'เค้กช็อคโกแลต','ขนม',85.0,30),(4,'ครัวซองต์','ขนม',55.0,50),(5,'สลัดผลไม้','อาหาร',75.0,20),(6,'แซนด์วิช','อาหาร',60.0,40),(7,'น้ำส้ม','เครื่องดื่ม',35.0,200),(8,'บราวนี่','ขนม',70.0,25);
    CREATE TABLE orders (id INTEGER PRIMARY KEY, customer_name TEXT, product_id INTEGER, quantity INTEGER, order_date TEXT);
    INSERT INTO orders VALUES (1,'คุณแก้ว',1,2,'2568-01-15'),(2,'คุณต้น',3,1,'2568-01-15'),(3,'คุณแก้ว',2,3,'2568-01-16'),(4,'คุณมด',1,1,'2568-01-16'),(5,'คุณต้น',5,2,'2568-01-17'),(6,'คุณฝน',4,4,'2568-01-17'),(7,'คุณแก้ว',6,1,'2568-01-18'),(8,'คุณมด',7,5,'2568-01-18'),(9,'คุณฝน',1,2,'2568-01-19'),(10,'คุณต้น',8,1,'2568-01-19');`);
}

function updateLoading(percent, text) {
  document.getElementById('loading-bar').style.width = percent + '%';
  document.getElementById('loading-text').textContent = text;
}

function startApp() {
  document.getElementById('loading-screen').style.display = 'none';
  document.getElementById('app').style.display = 'block';
  createParticles();
  loadGame();
  renderHome();
}

function createParticles() {
  const container = document.getElementById('particles');
  for (let i = 0; i < 20; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.top = Math.random() * 100 + '%';
    particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
    particle.style.animationDelay = Math.random() * 2 + 's';
    container.appendChild(particle);
  }
}

function loadGame() {
  const saved = localStorage.getItem('sqlquest_40');
  if (saved) {
    const data = JSON.parse(saved);
    playerXP = data.xp || 0;
    playerLevel = data.level || 1;
    completedLevels.clear();
    (data.completed || []).forEach(id => completedLevels.add(id));
  }
  updateXPDisplay();
}

function saveGame() {
  localStorage.setItem('sqlquest_40', JSON.stringify({xp: playerXP, level: playerLevel, completed: Array.from(completedLevels)}));
}

function updateXPDisplay() {
  const xpInLevel = playerXP % 100;
  document.getElementById('xp-display').textContent = `XP: ${xpInLevel} / 100`;
  document.getElementById('xp-bar').style.width = (xpInLevel / 100 * 100) + '%';
  const levels = ['มือใหม่', 'หัดเขียน', 'เข้ากำลัง', 'ช่วงกึ่ง', 'ขึ้นชั้น'];
  const levelName = levels[Math.min(playerLevel - 1, levels.length - 1)];
  document.getElementById('player-level').textContent = `Lv.${playerLevel} ${levelName}`;
}

function addXP(amount) {
  playerXP += amount;
  if (playerXP < 0) playerXP = 0;
  while (playerXP >= 100) { playerXP -= 100; playerLevel++; }
  updateXPDisplay();
  saveGame();
}

function renderHome() {
  document.getElementById('home-screen').style.display = 'block';
  document.getElementById('game-screen').style.display = 'none';
  const mapContainer = document.getElementById('stage-map');
  mapContainer.innerHTML = '';
  const zones = {};
  LEVELS.forEach(level => {
    if (!zones[level.zone]) zones[level.zone] = [];
    zones[level.zone].push(level);
  });
  Object.keys(zones).sort((a, b) => parseInt(a) - parseInt(b)).forEach(zone => {
    const zoneDiv = document.createElement('div');
    zoneDiv.className = `zone-label zone${zone}`;
    zoneDiv.textContent = `⚔️ ZONE ${zone}`;
    mapContainer.appendChild(zoneDiv);
    zones[zone].forEach(level => {
      const node = document.createElement('div');
      node.className = 'stage-node';
      if (completedLevels.has(level.id)) node.classList.add('completed');
      else if (level.id > 1 && !completedLevels.has(level.id - 1)) node.classList.add('locked');
      else if (currentLevel === level.id) node.classList.add('current');
      node.innerHTML = `<div class="stage-icon">💾</div><div class="stage-num">D ${level.id}</div><div class="stage-name">${level.title}</div>${completedLevels.has(level.id) ? '<div class="stage-check">✓</div>' : ''}`;
      if (!node.classList.contains('locked')) {
        node.style.cursor = 'pointer';
        node.onclick = () => loadLevel(level.id);
      }
      mapContainer.appendChild(node);
    });
  });
}

function loadLevel(levelId) {
  if (levelId > 1 && !completedLevels.has(levelId - 1)) return;
  currentLevel = levelId;
  const level = LEVELS[levelId - 1];
  document.getElementById('home-screen').style.display = 'none';
  document.getElementById('game-screen').style.display = 'block';
  document.getElementById('game-stage-title').textContent = `ด่าน ${level.id}: ${level.title}`;
  document.getElementById('story-text').textContent = level.description;
  document.getElementById('mission-text').textContent = level.task;
  document.getElementById('editor-section').style.display = 'block';
  document.getElementById('code-editor').value = '';
  document.getElementById('output-panel').style.display = 'none';
  hintUsed[levelId] = [false, false, false];
  updateHints(level);
  saveGame();
}

function updateHints(level) {
  const container = document.getElementById('hint-buttons');
  container.innerHTML = '';
  for (let i = 1; i <= 3; i++) {
    const btn = document.createElement('button');
    btn.className = `btn-hint${i > 1 ? ' level' + i : ''}`;
    if (hintUsed[currentLevel][i-1]) btn.classList.add('used');
    btn.innerHTML = `${i === 1 ? '💡' : i === 2 ? '🔶' : '🔴'} Hint ${i} <span class="xp-penalty">${i === 1 ? '(-10 XP)' : i === 2 ? '(-25 XP)' : '(-50 XP)'}</span>`;
    btn.onclick = () => showHint(i);
    container.appendChild(btn);
  }
  for (let i = 1; i <= 3; i++) document.getElementById(`hint-text-${i}`).textContent = level.hints[i-1];
}

function showHint(level) {
  if (hintUsed[currentLevel][level-1]) return;
  hintUsed[currentLevel][level-1] = true;
  const penalties = [10, 25, 50];
  addXP(-penalties[level-1]);
  document.getElementById(`hint-box-${level}`).classList.add('show');
  document.getElementById(`btn-hint-${level}`).classList.add('used');
  saveGame();
}

function runSQL() {
  const code = document.getElementById('code-editor').value.trim();
  const level = LEVELS[currentLevel - 1];
  if (!code) { showError('เขียน SQL ก่อน!'); return; }
  try {
    const result = db.exec(code);
    if (result.length === 0) { showSuccess('คำสั่ง SQL ทำงานแล้ว (ไม่มีผลลัพธ์)'); return; }
    const { columns, values } = result[0];
    if (values.length === level.expectedRowCount) {
      showSuccess(`ถูกต้อง! ✨ ได้ ${values.length} แถว`);
      addXP(100);
      completedLevels.add(currentLevel);
      saveGame();
      setTimeout(() => showVictory(level), 500);
    } else showError(`จำนวนแถวไม่ถูก (ได้ ${values.length}, ต้องการ ${level.expectedRowCount})`);
    displayTable(columns, values);
  } catch (err) { showError('SQL Error: ' + err.message); }
}

function displayTable(columns, values) {
  let html = '<table class="sql-result-table"><thead><tr>';
  columns.forEach(col => html += `<th>${col}</th>`);
  html += '</tr></thead><tbody>';
  values.forEach(row => {
    html += '<tr>';
    row.forEach(cell => html += `<td>${cell === null ? 'NULL' : cell}</td>`);
    html += '</tr>';
  });
  html += '</tbody></table>';
  document.getElementById('output').innerHTML = html;
  document.getElementById('output-panel').style.display = 'block';
}

function showError(msg) {
  document.getElementById('output').innerHTML = '<div style="color: #ef4444; padding: 12px; background: rgba(239,68,68,0.1); border-left: 4px solid #ef4444; border-radius: 6px;">❌ ' + msg + '</div>';
  document.getElementById('output-panel').style.display = 'block';
}

function showSuccess(msg) {
  document.getElementById('output').innerHTML = '<div style="color: #10b981; padding: 12px; background: rgba(16,185,129,0.1); border-left: 4px solid #10b981; border-radius: 6px;">✅ ' + msg + '</div>';
  document.getElementById('output-panel').style.display = 'block';
}

function showVictory(level) {
  const modal = document.getElementById('modal');
  const content = document.getElementById('modal-content');
  content.className = 'modal-content';
  content.innerHTML = '<div class="modal-icon">✨</div><div class="modal-title">สุดยอด!</div><div class="modal-text">คำตอบถูกต้อง!</div><div class="modal-xp">+100 XP</div><button class="btn-next" onclick="nextLevel()">ด่านต่อไป →</button>';
  modal.classList.add('show');
}

function nextLevel() {
  document.getElementById('modal').classList.remove('show');
  if (currentLevel < LEVELS.length) loadLevel(currentLevel + 1);
  else renderHome();
}

function goHome() {
  document.getElementById('modal').classList.remove('show');
  renderHome();
}

window.onload = initSQL;
</script>
<script src="/auth.js"></script>
</body>
</html>