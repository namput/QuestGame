<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Python Quest - ผจญภัยแดนโค้ด</title>
<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
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
.stage-map { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; max-width: 900px; width: 100%; margin-bottom: 30px; }
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
.zone-label.zone5 { background: linear-gradient(90deg, rgba(59,130,246,0.15), transparent); color: #3b82f6; }
.zone-label.zone6 { background: linear-gradient(90deg, rgba(34,197,94,0.15), transparent); color: #22c55e; }
.zone-label.zone7 { background: linear-gradient(90deg, rgba(249,115,22,0.15), transparent); color: #f97316; }
.zone-label.zone8 { background: linear-gradient(90deg, rgba(236,72,153,0.15), transparent); color: #ec4899; }


/* TUTORIAL BOX */
.tutorial-box {
  background: linear-gradient(135deg, rgba(78,205,196,0.08), rgba(168,85,247,0.05));
  border: 1px solid rgba(78,205,196,0.2); border-radius: 16px;
  padding: 25px; margin-bottom: 20px; position: relative;
}
.tutorial-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: var(--accent2); }
.tutorial-box h3::before { content: '📖 '; }
.tutorial-content { font-size: 0.95rem; line-height: 1.8; color: var(--text); }
.tutorial-content code {
  background: rgba(168,85,247,0.15); color: var(--accent4);
  padding: 2px 8px; border-radius: 6px; font-family: 'Fira Code', monospace; font-size: 0.85rem;
}
.tutorial-content pre {
  background: var(--code-bg); padding: 15px; border-radius: 10px; margin: 10px 0;
  font-family: 'Fira Code', monospace; font-size: 0.85rem; color: #e0e0ff;
  line-height: 1.6; overflow-x: auto;
}

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

/* EXPECTED OUTPUT BOX */
.expected-box {
  background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15);
  border-radius: 12px; padding: 15px 20px; margin-bottom: 20px;
}
.expected-box .label { color: var(--success); font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; }
.expected-box pre {
  font-family: 'Fira Code', monospace; font-size: 0.9rem; color: #a0ffa0;
  background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; line-height: 1.5;
}

/* HINT SYSTEM */
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

/* MULTIPLE CHOICE */
.choices-area { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.choice-btn {
  background: var(--bg-card); border: 2px solid rgba(255,255,255,0.08);
  border-radius: 12px; padding: 16px 20px; text-align: left; cursor: pointer;
  font-family: 'Prompt'; color: var(--text); font-size: 0.9rem; transition: all 0.2s;
  display: flex; align-items: flex-start; gap: 12px;
}
.choice-btn:hover { border-color: var(--accent2); background: rgba(78,205,196,0.05); }
.choice-btn.selected { border-color: var(--accent2); background: rgba(78,205,196,0.1); }
.choice-btn.correct { border-color: var(--success) !important; background: rgba(16,185,129,0.15) !important; }
.choice-btn.wrong { border-color: var(--error) !important; background: rgba(239,68,68,0.1) !important; }
.choice-label {
  background: rgba(255,255,255,0.08); width: 28px; height: 28px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
}
.choice-text { font-family: 'Fira Code', monospace; font-size: 0.85rem; line-height: 1.5; white-space: pre-wrap; }

/* ACTION */
.action-bar { display: flex; gap: 12px; align-items: center; margin-bottom: 20px; }
.btn-run {
  background: linear-gradient(135deg, var(--accent2), #38b2ac); color: #000; border: none;
  padding: 12px 30px; border-radius: 12px; font-family: 'Prompt'; font-size: 1rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
}
.btn-run:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78,205,196,0.3); }
.btn-run:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-submit {
  background: linear-gradient(135deg, var(--accent4), #7c3aed); color: white; border: none;
  padding: 12px 30px; border-radius: 12px; font-family: 'Prompt'; font-size: 1rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(168,85,247,0.3); }

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
  color: #a0ffa0; min-height: 60px; max-height: 200px; overflow-y: auto;
  white-space: pre-wrap; line-height: 1.6;
}
.output-body.error { color: var(--error); }

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
.modal-content.fail .modal-title { color: var(--error); }
.modal-content.fail .modal-text { color: var(--accent3); font-weight: 500; }
.btn-retry {
  background: linear-gradient(135deg, var(--accent), #dc2626); color: white; border: none;
  padding: 14px 40px; border-radius: 14px; font-family: 'Prompt'; font-size: 1.1rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
}

/* VICTORY */
.victory-screen { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 40px; }
.victory-icon { font-size: 6rem; margin-bottom: 20px; animation: bounce 1s ease infinite; }
@keyframes bounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
.victory-title {
  font-size: 3rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent3), var(--accent), var(--accent4));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 15px;
}
.victory-text { color: var(--text-dim); font-size: 1.1rem; line-height: 1.8; max-width: 600px; }
.btn-restart {
  margin-top: 30px; background: linear-gradient(135deg, var(--accent2), var(--accent4));
  color: white; border: none; padding: 16px 50px; border-radius: 14px;
  font-family: 'Prompt'; font-size: 1.1rem; font-weight: 700; cursor: pointer;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .home-title { font-size: 2.4rem; }
  .stage-map { grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .choices-area { grid-template-columns: 1fr; }
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
  <div class="loading-logo">🐍 Python Quest</div>
  <div class="loading-bar-container"><div class="loading-bar" id="loading-bar"></div></div>
  <div class="loading-text" id="loading-text">กำลังโหลด Python Engine...</div>
</div>

<!-- APP -->
<div class="app-container" id="app">
  <div class="top-bar">
    <div class="logo">🐍 Python Quest <span>ผจญภัยแดนโค้ด</span></div>
    <div class="player-info">
      <div class="xp-text" id="xp-display">XP: 0 / 200</div>
      <div class="xp-bar-container"><div class="xp-bar" id="xp-bar"></div></div>
      <div class="level-badge" id="player-level">Lv.1 มือใหม่</div>
    </div>
  </div>

  <!-- HOME -->
  <div class="screen active" id="home-screen">
    <div class="home-screen">
      <div class="home-title">Python Quest</div>
      <div class="home-subtitle">ผจญภัยแดนโค้ด — เรียน Python ผ่าน <strong>50 ด่าน</strong> สุดมัน!</div>
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
          <div class="theme-badge" id="game-theme-badge"></div>
        </div>
      </div>

      <div class="story-box" id="story-box">
        <div class="story-character" id="story-char"></div>
        <div class="story-text" id="story-text"></div>
      </div>

      <!-- TUTORIAL -->
      <div class="tutorial-box" id="tutorial-box" style="display:none;">
        <h3 id="tutorial-title"></h3>
        <div class="tutorial-content" id="tutorial-content"></div>
      </div>

      <div class="mission-box">
        <h3>ภารกิจ</h3>
        <div class="mission-text" id="mission-text"></div>
      </div>

      <div class="expected-box" id="expected-box" style="display:none;">
        <div class="label">📤 ผลลัพธ์ที่ต้องการ:</div>
        <pre id="expected-output"></pre>
      </div>

      <!-- 3-LEVEL HINTS -->
      <div class="hint-area">
        <div class="hint-buttons" id="hint-buttons"></div>
        <div class="hint-box" id="hint-box-1"><p id="hint-text-1"></p></div>
        <div class="hint-box level2" id="hint-box-2"><p id="hint-text-2"></p></div>
        <div class="hint-box level3" id="hint-box-3"><p id="hint-text-3"></p></div>
      </div>

      <!-- Code Editor -->
      <div id="editor-section" style="display:none;">
        <div class="editor-area">
          <div class="editor-tabs"><div class="editor-tab active">📄 solution.py</div></div>
          <textarea class="code-editor" id="code-editor" spellcheck="false" placeholder="# เขียนโค้ด Python ที่นี่..."></textarea>
        </div>
        <div class="action-bar">
          <button class="btn-run" id="btn-run" onclick="runCode()">▶ รันโค้ด</button>
          <button class="btn-submit" onclick="submitCode()">🚀 ส่งคำตอบ</button>
        </div>
        <div class="output-panel">
          <div class="output-header">
            <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
            <span>Output</span>
          </div>
          <div class="output-body" id="output-body">รอรันโค้ด...</div>
        </div>
      </div>

      <!-- Multiple Choice -->
      <div id="choice-section" style="display:none;">
        <div class="choices-area" id="choices-area"></div>
        <div class="action-bar">
          <button class="btn-submit" onclick="submitChoice()">🚀 ส่งคำตอบ</button>
        </div>
      </div>
    </div>
  </div>

  <!-- VICTORY -->
  <div class="screen" id="victory-screen">
    <div class="victory-screen">
      <div class="victory-icon">🏆</div>
      <div class="victory-title">สุดยอด! คุณคือ Python Master!</div>
      <div class="victory-text">
        คุณผ่านครบทั้ง 50 ด่าน! ตอนนี้คุณเข้าใจ Python ตั้งแต่พื้นฐานจนถึงขั้นสูง
        <br>print, ตัวแปร, เงื่อนไข, ลูป, สตริง, ลิสต์, ดิกชันนารี, ฟังก์ชัน, คลาส, ไฟล์ — ครบหมด!
        <br><br>พร้อมสร้างโปรแกรมจริงแล้ว! 🚀
      </div>
      <button class="btn-restart" onclick="resetGame()">🔄 เล่นใหม่</button>
    </div>
  </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="result-modal">
  <div class="modal-content" id="modal-content">
    <div class="modal-icon" id="modal-icon"></div>
    <div class="modal-title" id="modal-title"></div>
    <div class="modal-text" id="modal-text"></div>
    <div class="modal-xp" id="modal-xp"></div>
    <button class="btn-next" id="modal-btn" onclick="closeModal()"></button>
  </div>
</div>

<script>
// ============================
// GAME DATA — 50 levels
// ============================
const LEVELS = [

  // ===== ZONE 1: 🔥 Zone 1 — พื้นฐาน (print, ตัวแปร, input, data types) =====
  {
    id: 1,
    name: "ส่งสัญญาณแรก",
    icon: "🏰",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 1,
    type: "code",
    xp: 50,
    skill: "print()",
    character: "🧙‍♂️",
    story: `คุณตื่นขึ้นในดันเจี้ยนมืดสนิท... บนผนังมีอักษรเรืองแสงเขียนว่า<br><span class="highlight">"ผู้ที่สามารถส่งเสียงเรียก 3 ครั้งจะเปิดประตูได้"</span><br>ส่งข้อความ 3 บรรทัดตามที่กำหนดเพื่อเปิดประตูดันเจี้ยน!`,
    mission: `ส่งสัญญาณ 3 บรรทัดออกไป ให้ผลลัพธ์ตรงกับที่กำหนด`,
    expected: `สวัสดี\nดันเจี้ยน\nเปิดประตู`,
    starter: `# ส่งสัญญาณ 3 ครั้งเพื่อเปิดประตูดันเจี้ยน\n`,
    successMsg: `ประตูดันเจี้ยนเปิดออก! คุณส่งสัญญาณสำเร็จ!`,
    tutorialTitle: `คำสั่ง print() — แสดงข้อความบนหน้าจอ`,
    tutorialContent: `<code>print()</code> เป็นคำสั่งแรกที่ทุกคนต้องรู้! ใช้แสดงข้อความออกทางหน้าจอ

<b>รูปแบบ:</b>
<pre>print("ข้อความที่ต้องการแสดง")</pre>

<b>ตัวอย่าง:</b>
<pre>print("สวัสดีชาวโลก")
print("ฉันชื่อ Python")
print("วันนี้อากาศดี")</pre>

<b>ผลลัพธ์:</b>
<pre>สวัสดีชาวโลก
ฉันชื่อ Python
วันนี้อากาศดี</pre>

<b>สิ่งสำคัญ:</b> ข้อความต้องอยู่ในเครื่องหมายคำพูด <code>"..."</code> หรือ <code>'...'</code> เสมอ`,
    hints: [
      `💡 ใน Python เราใช้คำสั่งอะไรในการแสดงข้อความบนหน้าจอ?`,
      `🔶 คำสั่ง print() ใช้แสดงข้อความ — แต่ทำยังไงให้แสดง 3 บรรทัด?`,
      `🔴 ต้องใช้ print() 3 ครั้ง แต่ละครั้งใส่ข้อความในเครื่องหมาย \"...\"`,
    ],
    codeCheck: (code) => {
      const printCount = (code.match(/print\s*\(/g) || []).length;
      if (printCount < 3) return "ต้องใช้ print() อย่างน้อย 3 ครั้ง เพื่อแสดง 3 บรรทัด!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l => l.trim());
      return lines.length >= 3 && lines[0] === "สวัสดี" && lines[1] === "ดันเจี้ยน" && lines[2] === "เปิดประตู";
    },
  },
  {
    id: 2,
    name: "คำนวณอายุผู้ต้องสงสัย",
    icon: "🔍",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 1,
    type: "code",
    xp: 70,
    skill: "ตัวแปร + คำนวณ",
    character: "🕵️",
    story: `คุณคือนักสืบดิจิทัล ได้รับข้อมูลว่า<br><span class="highlight">ผู้ต้องสงสัยเกิดปี ค.ศ. 1995 — ต้องคำนวณอายุในปี 2026</span><br>แต่คุณต้องคิดเองว่าจะเขียนโปรแกรมคำนวณอย่างไร`,
    mission: `เขียนโปรแกรมคำนวณอายุจากปีเกิด <code>1995</code> ในปีปัจจุบัน <code>2026</code> แล้วแสดงผลลัพธ์`,
    expected: `31`,
    starter: `# คำนวณอายุผู้ต้องสงสัย\n`,
    successMsg: `ยอดเยี่ยม! ผู้ต้องสงสัยอายุ 31 ปี — คุณเก่งด้านตัวแปรแล้ว!`,
    tutorialTitle: `ตัวแปร (Variables) — เก็บข้อมูลไว้ใช้`,
    tutorialContent: `<b>ตัวแปร</b> คือชื่อที่ใช้เก็บค่าข้อมูลไว้ เพื่อนำไปใช้คำนวณหรือแสดงผลทีหลัง

<b>รูปแบบ:</b>
<pre>ชื่อตัวแปร = ค่า</pre>

<b>ตัวอย่าง:</b>
<pre>price = 150
discount = 30
total = price - discount
print(total)</pre>

<b>ผลลัพธ์:</b>
<pre>120</pre>

<b>สิ่งสำคัญ:</b> ตัวแปรเก็บค่าได้หลายชนิด เช่น ตัวเลข (int, float) หรือข้อความ (str) และสามารถนำมาคำนวณได้`,
    hints: [
      `💡 อายุคำนวณจากอะไรลบอะไร? ลองเก็บค่าที่เกี่ยวข้องไว้ในตัวแปร`,
      `🔶 สร้างตัวแปร 2 ตัวสำหรับปีปัจจุบันและปีเกิด แล้วลบกัน`,
      `🔴 birth_year = 1995 / current_year = 2026 / age = ? / แล้ว print ผลลัพธ์`,
    ],
    codeCheck: (code) => {
      if (!code.includes('=')) return "ต้องใช้ตัวแปรในการเก็บค่า! ลองสร้างตัวแปรเก็บปีเกิดและปีปัจจุบัน";
      if (code.includes('print') && code.includes('31') && !code.includes('-')) return "ห้าม print ค่า 31 ตรงๆ! ต้องคำนวณจากตัวแปร";
      const varCount = (code.match(/\w+\s*=/g) || []).length;
      if (varCount < 2) return "ต้องใช้ตัวแปรอย่างน้อย 2 ตัว (ปีเกิด + ปีปัจจุบัน หรือ ปีเกิด + อายุ)";
      return null;
    },
    validate: (output) => output.trim() === "31" ,
  },
  {
    id: 3,
    name: "แบ่งพิซซ่า",
    icon: "👨‍🍳",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 1,
    type: "code",
    xp: 80,
    skill: "หาร + เศษ",
    character: "👨‍🍳",
    story: `ลูกค้า 7 คนสั่งพิซซ่า 3 ถาด ถาดละ 8 ชิ้น<br><span class="highlight">ต้องคำนวณว่าแต่ละคนได้กี่ชิ้น เหลือเศษกี่ชิ้น</span>`,
    mission: `พิซซ่ามี <code>3 ถาด x 8 ชิ้น = 24 ชิ้น</code> แบ่งให้ <code>7 คน</code><br>แสดงผลว่าแต่ละคนได้กี่ชิ้น (หารเอาจำนวนเต็ม) และเหลือเศษกี่ชิ้น`,
    expected: `คนละ 3 ชิ้น\nเหลือ 3 ชิ้น`,
    starter: `# คำนวณการแบ่งพิซซ่า\n`,
    successMsg: `เชฟเก่งมาก! การหารจำนวนเต็มและหาเศษเป็นทักษะสำคัญ!`,
    tutorialTitle: `ตัวดำเนินการ // และ % — หารเอาจำนวนเต็มและหาเศษ`,
    tutorialContent: `Python มีตัวดำเนินการพิเศษสำหรับการหาร:

<b>ตัวดำเนินการ:</b>
• <code>//</code> = หารเอาจำนวนเต็ม (ตัดทศนิยมทิ้ง)
• <code>%</code> = หาเศษจากการหาร (modulo)

<b>ตัวอย่าง:</b>
<pre>candies = 17
kids = 5
each = candies // kids  # แต่ละคนได้กี่อัน
leftover = candies % kids  # เหลือเศษกี่อัน
print(f"คนละ {each} อัน")
print(f"เหลือ {leftover} อัน")</pre>

<b>ผลลัพธ์:</b>
<pre>คนละ 3 อัน
เหลือ 2 อัน</pre>

<b>สิ่งสำคัญ:</b> <code>//</code> ≠ <code>/</code> → <code>17/5 = 3.4</code> แต่ <code>17//5 = 3</code>`,
    hints: [
      `💡 ใน Python การหารเอาจำนวนเต็มใช้เครื่องหมายอะไร? แล้วหาเศษล่ะ?`,
      `🔶 // คือหารเอาจำนวนเต็ม, % คือหาเศษ — ลองเอาชิ้นทั้งหมดมาหาร`,
      `🔴 total = 3 * 8 → ใช้ total // 7 กับ total % 7 แล้ว print ให้ตรงรูปแบบ`,
    ],
    codeCheck: (code) => {
      if (!code.includes('//') && !code.includes('%')) return "ต้องใช้ตัวดำเนินการหาร (//) หรือหาเศษ (%) ในการคำนวณ!";
      if (!code.includes('//')) return "ต้องใช้ // (หารเอาจำนวนเต็ม) ในการคำนวณว่าแต่ละคนได้กี่ชิ้น";
      if (!code.includes('%')) return "ต้องใช้ % (หาเศษ) ในการคำนวณว่าเหลือเศษกี่ชิ้น";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 2 && lines[0].includes("3") && lines[0].includes("คนละ") && lines[1].includes("3") && lines[1].includes("เหลือ");
    },
  },
  {
    id: 4,
    name: "ถอดรหัส Bug",
    icon: "💻",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 1,
    type: "choice",
    xp: 60,
    skill: "หา Bug",
    character: "🤖",
    story: `Matrix ส่งโค้ดมาให้ แต่มี Bug ซ่อนอยู่!<br><span class="highlight">คุณต้องหาว่าโค้ดนี้ผิดตรงไหน</span>`,
    mission: `โค้ดนี้ต้องการแสดง <code>Hello World</code> แต่รันแล้ว Error — ผิดตรงไหน?<br><br><code>prnt("Hello World")</code>`,
    successMsg: `เก่งมาก! การสังเกต syntax ผิดเป็นทักษะสำคัญของโปรแกรมเมอร์!`,
    tutorialTitle: `Syntax Error — เมื่อเขียนโค้ดผิดรูปแบบ`,
    tutorialContent: `<b>Syntax Error</b> เกิดขึ้นเมื่อเขียนโค้ดผิดรูปแบบที่ Python กำหนด

<b>ตัวอย่าง Error ที่พบบ่อย:</b>
<pre># ❌ สะกดชื่อคำสั่งผิด
pritn("Hello")  # NameError!

# ❌ ลืมวงเล็บปิด
print("Hello"

# ❌ ลืมเครื่องหมายคำพูด
print(Hello)  # NameError!</pre>

<b>วิธีแก้:</b>
<pre># ✅ ถูกต้อง
print("Hello")</pre>

<b>สิ่งสำคัญ:</b> อ่าน Error message ดีๆ Python จะบอกว่าผิดตรงไหน!`,
    hints: [
      `💡 ลองอ่านชื่อคำสั่งดีๆ มีอะไรผิดปกติไหม?`,
      `🔶 เทียบชื่อคำสั่งกับที่ถูกต้อง ตัวอักษรครบไหม?`,
      `🔴 คำสั่งที่ถูกคือ print ไม่ใช่ prnt`,
    ],
    choices: [
      { text: 'ลืมใส่ ; ท้ายบรรทัด', correct: false },
      { text: 'เขียน prnt ผิด ต้องเป็น print', correct: true },
      { text: 'ต้องใช้ \' แทน "', correct: false },
      { text: 'ขาดวงเล็บปิด', correct: false },
    ],
  },
  {
    id: 5,
    name: "เครื่องแลกเงิน",
    icon: "🚀",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 1,
    type: "code",
    xp: 90,
    skill: "input + แปลงชนิด",
    character: "👩‍🚀",
    story: `สถานีอวกาศมีเครื่องแลกเงิน — อัตราแลกเปลี่ยน 1 USD = 34.5 บาท<br><span class="highlight">สร้างโปรแกรมรับจำนวนเงิน USD แล้วคำนวณเป็นบาท</span>`,
    mission: `เขียนโปรแกรมรับจำนวน USD จากผู้ใช้ แล้วคำนวณและแสดงผลเป็นเงินบาท<br><br>ระบบจะทดสอบด้วยค่า <code>100</code> — ผลลัพธ์ต้องเป็น <code>3450.0</code>`,
    expected: `3450.0`,
    starter: `# เครื่องแลกเงินอวกาศ\n`,
    successMsg: `เครื่องแลกเงินทำงานสมบูรณ์! คุณเข้าใจ input + การแปลงชนิดข้อมูลแล้ว!`,
    tutorialTitle: `input() — รับค่าจากผู้ใช้ + แปลงชนิดข้อมูล`,
    tutorialContent: `<code>input()</code> ใช้รับค่าจากผู้ใช้ผ่านคีย์บอร์ด

<b>สิ่งสำคัญ:</b> <code>input()</code> คืนค่าเป็น <b>string เสมอ!</b> ต้องแปลงเป็นตัวเลขก่อนคำนวณ

<b>ตัวอย่าง:</b>
<pre>weight_kg = float(input("น้ำหนัก (kg): "))
height_m = float(input("ส่วนสูง (m): "))
bmi = weight_kg / (height_m ** 2)
print(bmi)</pre>

<b>ถ้าป้อน:</b> น้ำหนัก 70, ส่วนสูง 1.75
<b>ผลลัพธ์:</b>
<pre>22.857142857142858</pre>

<b>การแปลงชนิด:</b>
• <code>int()</code> → จำนวนเต็ม
• <code>float()</code> → ทศนิยม
• <code>str()</code> → ข้อความ`,
    hints: [
      `💡 ต้องรับค่าจากผู้ใช้ด้วยคำสั่งอะไร? แล้วค่าที่รับมาเป็นชนิดข้อมูลอะไร?`,
      `🔶 input() รับมาเป็น string เสมอ — ต้องแปลงเป็นตัวเลขก่อนคำนวณ (ลอง float หรือ int)`,
      `🔴 usd = float(input(...)) → แล้วคูณด้วย 34.5 → print ผลลัพธ์`,
    ],
    testInputs: ["100"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับค่าจากผู้ใช้!";
      if (!code.includes('float') && !code.includes('int')) return "ค่าจาก input() เป็น string เสมอ — ต้องแปลงเป็นตัวเลขก่อนคำนวณ!";
      if (!code.includes('*')) return "ต้องใช้การคูณ (*) เพื่อคำนวณอัตราแลกเปลี่ยน!";
      if (code.includes('3450')) return "ห้าม print ค่า 3450 ตรงๆ! ต้องคำนวณจาก input * อัตราแลกเปลี่ยน";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n');
      const last = lines[lines.length - 1].trim();
      return parseFloat(last) === 3450.0 || last === "3450.0" || last === "3450";
    },
  },
  {
    id: 6,
    name: "ต่อสตริงเวทมนตร์",
    icon: "✨",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 1,
    type: "code",
    xp: 80,
    skill: "string + f-string",
    character: "🧙‍♂️",
    story: `พ่อมดต้องร่ายคาถาโดยรวมชื่อกับพลังเข้าด้วยกัน<br><span class="highlight">ใช้ f-string สร้างคาถาจากตัวแปร</span>`,
    mission: `สร้างตัวแปร <code>name = "Fire"</code> และ <code>power = 99</code><br>แสดงผล: <code>คาถา Fire พลัง 99</code><br>ต้องใช้ f-string (f"...{ตัวแปร}...")`,
    expected: `คาถา Fire พลัง 99`,
    starter: `# ร่ายคาถาด้วย f-string\n`,
    successMsg: `คาถาร่ายสำเร็จ! f-string เป็นเครื่องมือสำคัญในการจัดการข้อความ!`,
    tutorialTitle: `f-string — ใส่ตัวแปรลงในข้อความ`,
    tutorialContent: `<b>f-string</b> (formatted string) ช่วยแทรกค่าตัวแปรลงในข้อความได้สะดวก

<b>รูปแบบ:</b>
<pre>f"ข้อความ {ตัวแปร} ข้อความ"</pre>

<b>ตัวอย่าง:</b>
<pre>item = "กาแฟ"
price = 65
qty = 3
total = price * qty
print(f"สั่ง {item} {qty} แก้ว รวม {total} บาท")</pre>

<b>ผลลัพธ์:</b>
<pre>สั่ง กาแฟ 3 แก้ว รวม 195 บาท</pre>

<b>สิ่งสำคัญ:</b> ต้องมีตัว <code>f</code> นำหน้าเครื่องหมายคำพูด และใส่ตัวแปรใน <code>{}</code>`,
    hints: [
      `💡 f-string คือการใส่ตัวแปรลงในข้อความ — เขียนยังไง?`,
      `🔶 f"ข้อความ {ตัวแปร} ข้อความ" จะแทนค่าตัวแปรลงไป`,
      `🔴 name = "Fire" / power = 99 / print(f"คาถา {name} พลัง {power}")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('=')) return "ต้องสร้างตัวแปร name และ power!";
      if (!code.includes('f"') && !code.includes("f'")) return "ต้องใช้ f-string (f\"...\") ในการสร้างข้อความ!";
      if (code.includes('คาถา Fire พลัง 99') && !code.includes('{')) return "ห้าม print ข้อความตรงๆ! ต้องใช้ f-string กับ {ตัวแปร}";
      return null;
    },
    validate: (output) => output.trim() === "คาถา Fire พลัง 99" ,
  },
  {
    id: 7,
    name: "ปริศนาชนิดข้อมูล",
    icon: "🔮",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 1,
    type: "choice",
    xp: 60,
    skill: "Data Types",
    character: "🕵️",
    story: `นักสืบพบโค้ดลึกลับ ต้องวิเคราะห์ว่าผลลัพธ์จะเป็นอย่างไร<br><span class="highlight">ระวัง! ชนิดข้อมูลต่างกัน ผลลัพธ์ก็ต่างกัน</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>a = "3"<br>b = "7"<br>print(a + b)</code>`,
    successMsg: `ถูกต้อง! string + string = ต่อกัน ไม่ใช่บวกเลข! สำคัญมาก!`,
    tutorialTitle: `ชนิดข้อมูล (Data Types) — string vs int`,
    tutorialContent: `Python แยกข้อมูลเป็นชนิดต่างๆ ที่ทำงานไม่เหมือนกัน

<b>ชนิดหลัก:</b>
• <code>int</code> = จำนวนเต็ม เช่น <code>42</code>
• <code>float</code> = ทศนิยม เช่น <code>3.14</code>
• <code>str</code> = ข้อความ เช่น <code>"hello"</code>
• <code>bool</code> = True / False

<b>ตัวอย่างที่ต้องระวัง:</b>
<pre># int + int = บวกเลข
print(5 + 3)       # 8

# str + str = ต่อข้อความ!
print("5" + "3")   # "53"

# ดู type ของข้อมูล
print(type(42))     # &lt;class 'int'&gt;
print(type("42"))   # &lt;class 'str'&gt;</pre>

<b>สิ่งสำคัญ:</b> <code>"5"</code> ≠ <code>5</code> — ตัวแรกเป็น string ตัวหลังเป็น int!`,
    hints: [
      `💡 a และ b เป็น string หรือ int? เครื่องหมาย + ทำงานต่างกันกับแต่ละชนิด`,
      `🔶 เมื่อ + ใช้กับ string จะเป็นการ 'ต่อข้อความ' ไม่ใช่บวกเลข`,
      `🔴 "3" + "7" = "37" (ต่อ string) ไม่ใช่ 10`,
    ],
    choices: [
      { text: '10', correct: false },
      { text: '37', correct: true },
      { text: 'Error', correct: false },
      { text: '3 7', correct: false },
    ],
  },

  // ===== ZONE 2: ⚡ Zone 2 — เงื่อนไข (if, elif, else, and/or/not) =====
  {
    id: 8,
    name: "ระบบล็อกอิน",
    icon: "🚪",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 2,
    type: "code",
    xp: 110,
    skill: "if-else",
    character: "🧙‍♂️",
    story: `ประตูดันเจี้ยนมีระบบล็อกอิน!<br><span class="highlight">รหัสผ่านคือ "dragon" — ถ้าถูกให้บอกว่าเข้าได้ ถ้าผิดให้บอกว่าเข้าไม่ได้</span>`,
    mission: `เขียนโปรแกรมรับรหัสผ่านจากผู้ใช้<br>- ถ้าตรงกับ <code>"dragon"</code> → แสดง <code>ยินดีต้อนรับ</code><br>- ถ้าไม่ตรง → แสดง <code>รหัสผิด</code><br><br>ทดสอบด้วย: <code>dragon</code>`,
    expected: `ยินดีต้อนรับ`,
    starter: `# ระบบล็อกอินดันเจี้ยน\n`,
    successMsg: `ระบบล็อกอินทำงานเรียบร้อย! คุณเข้าใจ if-else แล้ว!`,
    tutorialTitle: `if-else — ตัดสินใจตามเงื่อนไข`,
    tutorialContent: `<code>if-else</code> ใช้ตัดสินใจว่าจะทำอะไร ขึ้นอยู่กับเงื่อนไข

<b>รูปแบบ:</b>
<pre>if เงื่อนไข:
    ทำสิ่งนี้ (ถ้าเงื่อนไขเป็น True)
else:
    ทำสิ่งนี้แทน (ถ้าเป็น False)</pre>

<b>ตัวอย่าง:</b>
<pre>age = int(input("อายุ: "))
if age >= 18:
    print("เข้าได้")
else:
    print("อายุไม่ถึง")</pre>

<b>ถ้าป้อน:</b> 20
<b>ผลลัพธ์:</b>
<pre>เข้าได้</pre>

<b>เครื่องหมายเปรียบเทียบ:</b> <code>==</code> เท่ากับ, <code>!=</code> ไม่เท่า, <code>></code> มากกว่า, <code><</code> น้อยกว่า, <code>>=</code> มากกว่าหรือเท่า`,
    hints: [
      `💡 ต้องใช้คำสั่งอะไรในการเช็คว่าค่าเท่ากันหรือไม่?`,
      `🔶 ใช้ if เพื่อเทียบค่า — เครื่องหมายเปรียบเทียบ 'เท่ากับ' ใน Python ใช้ ==`,
      `🔴 password = input() → if password == "dragon": → print(...)  else: → print(...)`,
    ],
    testInputs: ["dragon"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับรหัสผ่านจากผู้ใช้!";
      if (!code.includes('if')) return "ต้องใช้ if เพื่อตรวจสอบรหัสผ่าน!";
      if (!code.includes('else')) return "ต้องมี else เพื่อจัดการกรณีรหัสผิดด้วย!";
      if (!code.includes('==')) return "ต้องใช้ == ในการเปรียบเทียบรหัสผ่าน!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n');
      const last = lines[lines.length - 1].trim();
      return last === "ยินดีต้อนรับ";
    },
  },
  {
    id: 9,
    name: "จัดเรตอาหาร",
    icon: "📋",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 2,
    type: "code",
    xp: 130,
    skill: "if-elif-else",
    character: "👨‍🍳",
    story: `ร้านอาหารมีระบบจัดเรตตามคะแนนรีวิว<br><span class="highlight">ต้องเขียนโปรแกรมจัดเรตอัตโนมัติ</span>`,
    mission: `รับคะแนนรีวิว (0-100) จากผู้ใช้ แล้วจัดเรต:<br>- 90 ขึ้นไป → <code>ระดับ 5 ดาว</code><br>- 70 ขึ้นไป → <code>ระดับ 4 ดาว</code><br>- 50 ขึ้นไป → <code>ระดับ 3 ดาว</code><br>- ต่ำกว่า 50 → <code>ต้องปรับปรุง</code><br><br>ทดสอบด้วยค่า <code>75</code>`,
    expected: `ระดับ 4 ดาว`,
    starter: `# ระบบจัดเรตร้านอาหาร\n`,
    successMsg: `ระบบจัดเรตสมบูรณ์แบบ! คุณใช้ if-elif-else ได้คล่องแล้ว!`,
    tutorialTitle: `if-elif-else — เงื่อนไขหลายทาง`,
    tutorialContent: `เมื่อมีมากกว่า 2 ทางเลือก ใช้ <code>elif</code> (else if) เพิ่มเงื่อนไข

<b>รูปแบบ:</b>
<pre>if เงื่อนไข1:
    ...
elif เงื่อนไข2:
    ...
elif เงื่อนไข3:
    ...
else:
    ...</pre>

<b>ตัวอย่าง:</b>
<pre>temp = int(input("อุณหภูมิ: "))
if temp >= 35:
    print("ร้อนมาก")
elif temp >= 25:
    print("อากาศดี")
elif temp >= 15:
    print("เย็นสบาย")
else:
    print("หนาว")</pre>

<b>ถ้าป้อน:</b> 28
<b>ผลลัพธ์:</b> <code>อากาศดี</code>

<b>สิ่งสำคัญ:</b> Python เช็คทีละเงื่อนไขจากบนลงล่าง เจอเงื่อนไขแรกที่เป็น True ก็หยุด!`,
    hints: [
      `💡 เมื่อมีเงื่อนไขหลายทาง ใช้แค่ if-else ได้ไหม? มีคำสั่งอะไรที่เพิ่มทางเลือกได้อีก?`,
      `🔶 elif คือ 'else if' — ใช้สร้างเงื่อนไขหลายชั้น ต้องเรียงจากมากไปน้อย`,
      `🔴 score = int(input()) → if score >= 90: ... elif score >= 70: ... elif ... else: ...`,
    ],
    testInputs: ["75"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับคะแนนจากผู้ใช้!";
      if (!code.includes('if')) return "ต้องใช้ if เพื่อเช็คเงื่อนไข!";
      if (!code.includes('elif')) return "เงื่อนไขมีหลายระดับ — ต้องใช้ elif ด้วย!";
      if (!code.includes('else')) return "ต้องมี else สำหรับกรณีที่ไม่ตรงเงื่อนไขใดเลย!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n');
      const last = lines[lines.length - 1].trim();
      return last === "ระดับ 4 ดาว";
    },
  },
  {
    id: 10,
    name: "เช็คเลขคู่-คี่",
    icon: "🔢",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 2,
    type: "code",
    xp: 100,
    skill: "% + if-else",
    character: "🤖",
    story: `Matrix ต้องจัดหมวดหมู่ตัวเลข<br><span class="highlight">รับตัวเลขมาแล้วบอกว่าเป็นเลขคู่หรือเลขคี่</span>`,
    mission: `รับตัวเลขจากผู้ใช้<br>- ถ้าเป็นเลขคู่ → <code>เลขคู่</code><br>- ถ้าเป็นเลขคี่ → <code>เลขคี่</code><br><br>ทดสอบด้วยค่า <code>42</code>`,
    expected: `เลขคู่`,
    starter: `# เช็คเลขคู่หรือเลขคี่\n`,
    successMsg: `ถูกต้อง! % เป็นตัวดำเนินการที่ใช้บ่อยมากในการเช็คเงื่อนไขตัวเลข!`,
    tutorialTitle: `% (Modulo) กับเงื่อนไข — เช็คคุณสมบัติตัวเลข`,
    tutorialContent: `<code>%</code> (modulo) ใช้หาเศษ เป็นเครื่องมือสำคัญในการเช็คคุณสมบัติของตัวเลข

<b>ตัวอย่างการใช้งาน:</b>
<pre># เช็คว่าหาร 5 ลงตัวไหม
n = 20
if n % 5 == 0:
    print(f"{n} หาร 5 ลงตัว")
else:
    print(f"{n} หาร 5 ไม่ลงตัว")</pre>

<b>ผลลัพธ์:</b>
<pre>20 หาร 5 ลงตัว</pre>

<b>กฎเลขคู่-คี่:</b>
• เลขคู่: <code>n % 2 == 0</code>
• เลขคี่: <code>n % 2 != 0</code> หรือ <code>n % 2 == 1</code>`,
    hints: [
      `💡 เลขคู่หารด้วย 2 แล้วเหลือเศษเท่าไร? ใช้เครื่องหมายอะไรหาเศษ?`,
      `🔶 ถ้า n % 2 == 0 แสดงว่าเป็นเลขคู่ ไม่งั้นเป็นเลขคี่`,
      `🔴 n = int(input()) → if n % 2 == 0: print("เลขคู่") else: print("เลขคี่")`,
    ],
    testInputs: ["42"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับค่า!";
      if (!code.includes('%')) return "ต้องใช้ % (หาเศษ) ในการเช็คเลขคู่-คี่!";
      if (!code.includes('if')) return "ต้องใช้ if ในการเช็คเงื่อนไข!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "เลขคู่" ,
  },
  {
    id: 11,
    name: "ระบบส่วนลด",
    icon: "🛒",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 2,
    type: "code",
    xp: 140,
    skill: "เงื่อนไขซ้อน",
    character: "👨‍🍳",
    story: `ร้านอาหารมีโปรโมชั่น:<br><span class="highlight">ถ้าซื้อ >= 500 บาท และเป็นสมาชิก ลด 20%<br>ถ้าซื้อ >= 500 แต่ไม่เป็นสมาชิก ลด 10%<br>ถ้าซื้อ < 500 ไม่ลด</span>`,
    mission: `รับราคาสินค้า และสถานะสมาชิก (yes/no)<br>คำนวณราคาหลังลดแล้วแสดงผล<br><br>ทดสอบด้วย: <code>600</code> และ <code>yes</code> → ลด 20% → <code>480.0</code>`,
    expected: `480.0`,
    starter: `# ระบบคำนวณส่วนลด\n`,
    successMsg: `ระบบส่วนลดทำงานถูกต้อง! เงื่อนไข and เป็นเครื่องมือทรงพลัง!`,
    tutorialTitle: `เงื่อนไขซ้อน + and/or — เชื่อมหลายเงื่อนไข`,
    tutorialContent: `ใช้ <code>and</code> / <code>or</code> เชื่อมเงื่อนไขหลายตัวเข้าด้วยกัน

<b>กฎ:</b>
• <code>and</code> = ต้องเป็นจริง<b>ทั้งคู่</b>
• <code>or</code> = เป็นจริง<b>อย่างน้อย 1</b> ตัว
• <code>not</code> = กลับค่า True/False

<b>ตัวอย่าง:</b>
<pre>age = 20
has_ticket = True

if age >= 18 and has_ticket:
    print("เข้าชมได้")
elif age >= 18 and not has_ticket:
    print("ซื้อตั๋วก่อน")
else:
    print("อายุไม่ถึง")</pre>

<b>ผลลัพธ์:</b>
<pre>เข้าชมได้</pre>`,
    hints: [
      `💡 ต้องรับค่า 2 ตัว แล้วเช็คเงื่อนไขซ้อนกัน — ลอง and`,
      `🔶 if price >= 500 and member == "yes": ลด 20% / elif price >= 500: ลด 10%`,
      `🔴 price = float(input()) / member = input() / if price >= 500 and member == "yes": price *= 0.8 ...`,
    ],
    testInputs: ["600", "yes"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับค่า!";
      if (!code.includes('if')) return "ต้องใช้ if ในการเช็คเงื่อนไข!";
      if (!code.includes('and') && !code.includes('if') ) return "ต้องใช้ and หรือ เงื่อนไขซ้อน!";
      if (code.includes('480')) return "ห้าม print 480 ตรงๆ! ต้องคำนวณจากราคาและส่วนลด";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return parseFloat(last) === 480.0 || last === "480.0" || last === "480";
    },
  },
  {
    id: 12,
    name: "ตรวจรหัสลับ",
    icon: "🔐",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 2,
    type: "code",
    xp: 120,
    skill: "and, or, not",
    character: "👩‍🚀",
    story: `ยานอวกาศต้องตรวจรหัสปลดล็อก<br><span class="highlight">รหัสต้องมีความยาว >= 4 ตัว และขึ้นต้นด้วย "X"</span>`,
    mission: `รับรหัสจากผู้ใช้ แล้วเช็ค:<br>- ความยาว >= 4 ตัว <b>และ</b> ขึ้นต้นด้วย "X"<br>- ถ้าผ่านทั้ง 2 เงื่อนไข → <code>ปลดล็อกสำเร็จ</code><br>- ถ้าไม่ผ่าน → <code>รหัสไม่ถูกต้อง</code><br><br>ทดสอบด้วย: <code>X999</code>`,
    expected: `ปลดล็อกสำเร็จ`,
    starter: `# ตรวจรหัสปลดล็อกยานอวกาศ\n`,
    successMsg: `ปลดล็อกสำเร็จ! Logical operators (and, or, not) สำคัญมากในการเขียนเงื่อนไข!`,
    tutorialTitle: `len() + startswith() — เช็คคุณสมบัติ string`,
    tutorialContent: `String มี method มากมายสำหรับเช็คคุณสมบัติ

<b>Method สำคัญ:</b>
• <code>len(s)</code> = ความยาว string
• <code>s.startswith("x")</code> = ขึ้นต้นด้วย x?
• <code>s.endswith("x")</code> = ลงท้ายด้วย x?
• <code>s.isdigit()</code> = เป็นตัวเลขทั้งหมด?

<b>ตัวอย่าง:</b>
<pre>phone = "0812345678"
if len(phone) == 10 and phone.startswith("0"):
    print("เบอร์ถูกต้อง")
else:
    print("เบอร์ไม่ถูกรูปแบบ")</pre>

<b>ผลลัพธ์:</b>
<pre>เบอร์ถูกต้อง</pre>`,
    hints: [
      `💡 ใช้ len() หาความยาว string ได้ แล้ว startswith() เช็คตัวอักษรตัวแรก`,
      `🔶 ใช้ and เชื่อม 2 เงื่อนไข: len(code) >= 4 and code.startswith("X")`,
      `🔴 code = input() / if len(code) >= 4 and code.startswith("X"): ...`,
    ],
    testInputs: ["X999"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับรหัส!";
      if (!code.includes('and')) return "ต้องใช้ and ในการเชื่อมเงื่อนไข!";
      if (!code.includes('len')) return "ต้องใช้ len() ในการเช็คความยาว!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "ปลดล็อกสำเร็จ" ,
  },
  {
    id: 13,
    name: "ถอดรหัส: เงื่อนไขซ้อน",
    icon: "🧩",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 2,
    type: "choice",
    xp: 100,
    skill: "วิเคราะห์ if",
    character: "🕵️",
    story: `นักสืบต้องวิเคราะห์โค้ดที่พบในที่เกิดเหตุ<br><span class="highlight">มีเงื่อนไขซ้อนกัน — ต้องไล่ลำดับให้ถูก</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร เมื่อ x = 15?<br><br><code>x = 15<br>if x > 20:<br>&nbsp;&nbsp;&nbsp;&nbsp;print("A")<br>elif x > 10:<br>&nbsp;&nbsp;&nbsp;&nbsp;if x % 2 == 0:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;print("B")<br>&nbsp;&nbsp;&nbsp;&nbsp;else:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;print("C")<br>else:<br>&nbsp;&nbsp;&nbsp;&nbsp;print("D")</code>`,
    successMsg: `เก่งมาก! เข้าใจเงื่อนไขซ้อน (nested if) แล้ว!`,
    tutorialTitle: `Nested if — เงื่อนไขซ้อนในเงื่อนไข`,
    tutorialContent: `สามารถใส่ <code>if</code> ไว้ข้างในอีก <code>if</code> ได้ เรียกว่า nested if

<b>ตัวอย่าง:</b>
<pre>x = 25
if x > 10:
    print("มากกว่า 10")
    if x > 20:
        print("มากกว่า 20 ด้วย")
    else:
        print("แต่ไม่เกิน 20")
else:
    print("ไม่เกิน 10")</pre>

<b>ผลลัพธ์:</b>
<pre>มากกว่า 10
มากกว่า 20 ด้วย</pre>

<b>วิธีอ่าน:</b> ไล่จากนอกเข้าใน เช็คเงื่อนไขทีละชั้น ดู indent ให้ดี!`,
    hints: [
      `💡 x = 15 ผ่านเงื่อนไข x > 20 ไหม? ถ้าไม่ ไปเช็คอะไรต่อ?`,
      `🔶 15 > 20 เป็น False → เข้า elif 15 > 10 เป็น True → แล้วเข้า if ข้างใน: 15 % 2 เท่ากับอะไร?`,
      `🔴 15 % 2 = 1 (ไม่เท่ากับ 0) → เข้า else → แสดง C`,
    ],
    choices: [
      { text: 'A', correct: false },
      { text: 'B', correct: false },
      { text: 'C', correct: true },
      { text: 'D', correct: false },
    ],
  },

  // ===== ZONE 3: 🔁 Zone 3 — ลูป (for, while, break, continue) =====
  {
    id: 14,
    name: "สร้างแถบพลัง",
    icon: "⚔️",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 3,
    type: "code",
    xp: 130,
    skill: "for loop",
    character: "🧙‍♂️",
    story: `นักรบต้องการแถบพลัง (HP bar) แสดงเป็นรูปแบบพิเศษ<br><span class="highlight">ใช้ลูปสร้างแถบพลังจาก 1 ถึง 5</span>`,
    mission: `ใช้ลูปสร้างแถบพลังแบบนี้ (5 บรรทัด):<br>ในแต่ละบรรทัดให้แสดงเครื่องหมาย <code>#</code> ตามจำนวนรอบ`,
    expected: `#\n##\n###\n####\n#####`,
    starter: `# สร้างแถบพลัง HP bar\n`,
    successMsg: `แถบพลังสวยงาม! คุณเข้าใจ for loop + string multiplication แล้ว!`,
    tutorialTitle: `for loop + range() — วนซ้ำตามจำนวนรอบ`,
    tutorialContent: `<code>for</code> ใช้วนซ้ำตามจำนวนที่กำหนด ทำงานร่วมกับ <code>range()</code>

<b>รูปแบบ:</b>
<pre>for ตัวแปร in range(จำนวน):
    ทำสิ่งนี้ซ้ำ</pre>

<b>ตัวอย่าง:</b>
<pre>for i in range(1, 4):
    print("*" * i)</pre>

<b>ผลลัพธ์:</b>
<pre>*
**
***</pre>

<b>range() มี 3 แบบ:</b>
• <code>range(5)</code> → 0, 1, 2, 3, 4
• <code>range(2, 6)</code> → 2, 3, 4, 5
• <code>range(1, 10, 2)</code> → 1, 3, 5, 7, 9

<b>เทคนิค:</b> <code>"#" * 3</code> = <code>"###"</code> — string คูณตัวเลขได้!`,
    hints: [
      `💡 ใน Python ลูปที่ทำซ้ำตามจำนวนรอบใช้คำสั่งอะไร? แล้ว string คูณตัวเลขได้ผลอะไร?`,
      `🔶 for i in range(...) จะวนลูป — ลอง print("#" * ตัวเลข) ดู`,
      `🔴 for i in range(1, 6): แล้วใช้ "#" * i`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop! ห้ามพิมพ์ทีละบรรทัด";
      if (!code.includes('range')) return "ต้องใช้ range() ร่วมกับ for loop!";
      const manualPrints = (code.match(/print\s*\(/g) || []).length;
      if (manualPrints > 2) return "ใช้ print มากเกินไป — ต้องใช้ loop ให้ print อยู่ข้างในลูปแค่ครั้งเดียว!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l => l.trim());
      return lines.length === 5 && lines[0] === "#" && lines[4] === "#####";
    },
  },
  {
    id: 15,
    name: "นับถอยหลังจรวด",
    icon: "🚀",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 3,
    type: "code",
    xp: 120,
    skill: "for + range ย้อน",
    character: "👩‍🚀",
    story: `ยานอวกาศกำลังจะปล่อย! ต้องนับถอยหลังจาก 5 ถึง 1<br><span class="highlight">แล้วบอกว่า "ปล่อยจรวด!"</span>`,
    mission: `ใช้ for loop นับถอยหลังจาก 5 ถึง 1 (ตัวเลขละบรรทัด)<br>แล้วบรรทัดสุดท้ายแสดง <code>ปล่อยจรวด!</code>`,
    expected: `5\n4\n3\n2\n1\nปล่อยจรวด!`,
    starter: `# นับถอยหลังปล่อยจรวด\n`,
    successMsg: `ปล่อยจรวดสำเร็จ! range(start, stop, step) เป็นเครื่องมือที่ยืดหยุ่นมาก!`,
    tutorialTitle: `range() ย้อนกลับ — นับถอยหลัง`,
    tutorialContent: `<code>range()</code> สามารถนับถอยหลังได้ โดยกำหนด step เป็นค่าลบ

<b>รูปแบบ:</b>
<pre>range(เริ่ม, จบ, step)</pre>

<b>ตัวอย่าง:</b>
<pre>for i in range(10, 0, -2):
    print(i)</pre>

<b>ผลลัพธ์:</b>
<pre>10
8
6
4
2</pre>

<b>สิ่งสำคัญ:</b> step = -1 คือลดทีละ 1, step = -2 คือลดทีละ 2
ค่า "จบ" จะ<b>ไม่ถูกรวม</b> (exclusive) เสมอ!`,
    hints: [
      `💡 range() นับถอยหลังได้ไหม? ต้องใส่ argument อะไรบ้าง?`,
      `🔶 range(start, stop, step) — ถ้าจะนับถอยหลัง step ต้องเป็น -1`,
      `🔴 for i in range(5, 0, -1): print(i) แล้ว print("ปล่อยจรวด!")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop!";
      if (!code.includes('range')) return "ต้องใช้ range()!";
      if (!code.includes('-1') && !code.includes('- 1')) return "ต้องใช้ range กับ step -1 เพื่อนับถอยหลัง!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 6 && lines[0]==="5" && lines[4]==="1" && lines[5].includes("ปล่อยจรวด");
    },
  },
  {
    id: 16,
    name: "หาผลรวม 1-100",
    icon: "🔢",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 3,
    type: "code",
    xp: 130,
    skill: "for + สะสมค่า",
    character: "🤖",
    story: `Matrix ท้าให้คำนวณผลรวมตัวเลข 1 ถึง 100<br><span class="highlight">ต้องใช้ลูปในการสะสมค่า ห้ามใช้สูตรลัด!</span>`,
    mission: `ใช้ for loop บวกเลขตั้งแต่ 1 ถึง 100 แล้วแสดงผลรวม`,
    expected: `5050`,
    starter: `# หาผลรวม 1 ถึง 100\n`,
    successMsg: `ถูกต้อง! 5050 — เกาส์ก็ใช้สูตรนี้! แต่คุณใช้ลูปได้เก่งมาก!`,
    tutorialTitle: `การสะสมค่าในลูป (Accumulator Pattern)`,
    tutorialContent: `เทคนิคสำคัญ: สร้างตัวแปรเริ่มต้น แล้วเพิ่มค่าทีละรอบในลูป

<b>รูปแบบ:</b>
<pre>total = 0  # เริ่มที่ 0
for i in range(...):
    total += i  # สะสมค่าทุกรอบ</pre>

<b>ตัวอย่าง:</b>
<pre>total = 0
for i in range(1, 6):  # 1 ถึง 5
    total += i
    print(f"รอบ {i}: total = {total}")
print(f"ผลรวม = {total}")</pre>

<b>ผลลัพธ์:</b>
<pre>รอบ 1: total = 1
รอบ 2: total = 3
รอบ 3: total = 6
รอบ 4: total = 10
รอบ 5: total = 15
ผลรวม = 15</pre>`,
    hints: [
      `💡 สร้างตัวแปรเก็บผลรวม (เริ่มที่ 0) แล้ว += ทุกรอบ`,
      `🔶 total = 0 แล้ว for i in range(1, 101): total += i`,
      `🔴 total = 0 / for i in range(1, 101): total += i / print(total)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop!";
      if (!code.includes('range')) return "ต้องใช้ range()!";
      if (code.includes('5050')) return "ห้ามใส่ 5050 ตรงๆ! ต้องคำนวณจากลูป";
      if (!code.includes('+=')) return "ต้องใช้ += ในการสะสมค่าผลรวม!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "5050" ,
  },
  {
    id: 17,
    name: "เกมทายตัวเลข",
    icon: "🎲",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 3,
    type: "code",
    xp: 150,
    skill: "while + break",
    character: "👩‍🚀",
    story: `ยานอวกาศติด — ต้องใส่รหัสปลดล็อก!<br><span class="highlight">ระบบจะให้ทายไปเรื่อยๆ จนกว่าจะถูก</span><br>เลขที่ถูกคือ 7 — ระบบจะทดสอบด้วยลำดับ: 3, 5, 7`,
    mission: `เขียนโปรแกรมให้ผู้ใช้ทายตัวเลขซ้ำไปเรื่อยๆ จนกว่าจะทายถูก<br>- ถ้าทายผิด → แสดง <code>ลองอีกครั้ง</code><br>- ถ้าทายถูก (เท่ากับ 7) → แสดง <code>ถูกต้อง!</code> แล้วหยุดลูป<br><br>ระบบทดสอบจะป้อน: <code>3</code>, <code>5</code>, <code>7</code> ตามลำดับ`,
    expected: `ลองอีกครั้ง\nลองอีกครั้ง\nถูกต้อง!`,
    starter: `# เกมทายตัวเลขปลดล็อกยาน\n`,
    successMsg: `ยานปลดล็อกสำเร็จ! คุณใช้ while loop + break ได้เยี่ยม!`,
    tutorialTitle: `while loop + break — วนจนกว่าจะหยุด`,
    tutorialContent: `<code>while</code> วนซ้ำตราบที่เงื่อนไขยังเป็น True / <code>break</code> หยุดลูปทันที

<b>รูปแบบ:</b>
<pre>while เงื่อนไข:
    ทำสิ่งนี้
    if ต้องการหยุด:
        break</pre>

<b>ตัวอย่าง:</b>
<pre>password = "abc123"
while True:
    guess = input("รหัส: ")
    if guess == password:
        print("ถูกต้อง!")
        break
    print("ผิด ลองใหม่")</pre>

<b>ถ้าป้อน:</b> "xyz" → "abc123"
<b>ผลลัพธ์:</b>
<pre>ผิด ลองใหม่
ถูกต้อง!</pre>

<b>สิ่งสำคัญ:</b> <code>while True</code> จะวนไม่มีที่สิ้นสุด → ต้องมี <code>break</code> เสมอ!`,
    hints: [
      `💡 ต้องใช้ลูปชนิดไหนที่วนซ้ำไปจนกว่าเงื่อนไขจะเป็นจริง?`,
      `🔶 while loop จะวนซ้ำตราบที่เงื่อนไขยังเป็น True — เมื่อทายถูกให้ break ออกจากลูป`,
      `🔴 while True: → guess = int(input()) → if guess == 7: print + break → else: print ลองอีกครั้ง`,
    ],
    testInputs: ["3", "5", "7"],
    codeCheck: (code) => {
      if (!code.includes('while')) return "ต้องใช้ while loop เพื่อให้ทายซ้ำได้เรื่อยๆ!";
      if (!code.includes('input')) return "ต้องใช้ input() เพื่อรับค่าจากผู้ใช้!";
      if (!code.includes('break')) return "ต้องใช้ break เพื่อหยุดลูปเมื่อทายถูก!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').filter(l => l.includes("ลอง") || l.includes("ถูก"));
      return lines.length >= 3 && lines[0].includes("ลองอีกครั้ง") && lines[lines.length-1].includes("ถูกต้อง");
    },
  },
  {
    id: 18,
    name: "ข้ามเลข 3",
    icon: "⚡",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 3,
    type: "code",
    xp: 120,
    skill: "continue",
    character: "🤖",
    story: `Matrix ต้องการแสดงเลข 1-10 แต่ข้ามเลขที่หาร 3 ลงตัว<br><span class="highlight">ใช้ continue เพื่อข้ามรอบที่ไม่ต้องการ</span>`,
    mission: `แสดงเลข 1-10 ยกเว้นเลขที่หาร 3 ลงตัว (3, 6, 9)<br>แสดงเลขละบรรทัด`,
    expected: `1\n2\n4\n5\n7\n8\n10`,
    starter: `# แสดงเลข 1-10 ยกเว้นที่หาร 3 ลงตัว\n`,
    successMsg: `เยี่ยม! continue ช่วยข้ามรอบที่ไม่ต้องการได้อย่างสง่างาม!`,
    tutorialTitle: `continue — ข้ามรอบปัจจุบัน`,
    tutorialContent: `<code>continue</code> สั่งให้ข้ามไปทำรอบถัดไปเลย ไม่ทำโค้ดที่เหลือในรอบนั้น

<b>ตัวอย่าง:</b>
<pre>for i in range(1, 8):
    if i % 2 == 0:  # ถ้าเป็นเลขคู่
        continue     # ข้ามไปเลย
    print(i)</pre>

<b>ผลลัพธ์:</b> (แสดงเฉพาะเลขคี่)
<pre>1
3
5
7</pre>

<b>เปรียบเทียบ:</b>
• <code>break</code> = หยุดลูปทั้งหมด
• <code>continue</code> = ข้ามรอบนี้ ไปทำรอบถัดไป`,
    hints: [
      `💡 continue คือคำสั่งที่ข้ามไปทำรอบถัดไปเลย ไม่ทำโค้ดที่เหลือในรอบนั้น`,
      `🔶 for i in range(1, 11): if i % 3 == 0: continue แล้ว print(i)`,
      `🔴 for i in range(1, 11):\n    if i % 3 == 0:\n        continue\n    print(i)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop!";
      if (!code.includes('continue')) return "ต้องใช้ continue เพื่อข้ามรอบ!";
      if (!code.includes('%')) return "ต้องใช้ % ในการเช็คว่าหาร 3 ลงตัว!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.join(',') === "1,2,4,5,7,8,10";
    },
  },
  {
    id: 19,
    name: "สูตรคูณ",
    icon: "📐",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 3,
    type: "code",
    xp: 150,
    skill: "nested loop",
    character: "👨‍🍳",
    story: `เชฟต้องสร้างตารางสูตรคูณสำหรับลูกมือในครัว<br><span class="highlight">สร้างสูตรคูณแม่ 3 ตั้งแต่ 3x1 ถึง 3x5</span>`,
    mission: `แสดงสูตรคูณแม่ 3 (3x1 ถึง 3x5) ในรูปแบบ:<br><code>3 x 1 = 3</code><br><code>3 x 2 = 6</code><br>... ไปจนถึง <code>3 x 5 = 15</code>`,
    expected: `3 x 1 = 3\n3 x 2 = 6\n3 x 3 = 9\n3 x 4 = 12\n3 x 5 = 15`,
    starter: `# สูตรคูณแม่ 3\n`,
    successMsg: `สูตรคูณเสร็จสมบูรณ์! loop + f-string ทำให้สร้างตารางได้ง่าย!`,
    tutorialTitle: `for loop + f-string — สร้างตารางข้อมูล`,
    tutorialContent: `การรวม for loop กับ f-string ทำให้สร้างข้อมูลเป็นรูปแบบได้ง่าย

<b>ตัวอย่าง:</b>
<pre>for i in range(1, 4):
    for j in range(1, 4):
        print(f"{i} x {j} = {i*j}", end="\t")
    print()  # ขึ้นบรรทัดใหม่</pre>

<b>ผลลัพธ์:</b>
<pre>1 x 1 = 1	1 x 2 = 2	1 x 3 = 3
2 x 1 = 2	2 x 2 = 4	2 x 3 = 6
3 x 1 = 3	3 x 2 = 6	3 x 3 = 9	</pre>

<b>เทคนิค:</b> <code>end="\t"</code> ทำให้ print ไม่ขึ้นบรรทัดใหม่ แต่ใส่ tab แทน`,
    hints: [
      `💡 ต้องวนลูป i จาก 1 ถึง 5 แล้วคำนวณ 3 * i`,
      `🔶 for i in range(1, 6): print(f"3 x {i} = {3*i}")`,
      `🔴 for i in range(1, 6):\n    print(f"3 x {i} = {3*i}")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop!";
      if (!code.includes('range')) return "ต้องใช้ range()!";
      if (!code.includes('*')) return "ต้องใช้การคูณ (*) ในการคำนวณ!";
      const printCount = (code.match(/print\s*\(/g) || []).length;
      if (printCount > 2) return "ใช้ print มากเกินไป — ต้องใช้ loop!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length === 5 && lines[0].includes("3 x 1 = 3") && lines[4].includes("3 x 5 = 15");
    },
  },
  {
    id: 20,
    name: "ถอดรหัส: วิเคราะห์ลูป",
    icon: "🐛",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 3,
    type: "choice",
    xp: 100,
    skill: "วิเคราะห์ลูป",
    character: "🕵️",
    story: `นักสืบพบโค้ดที่มีลูปซ้อนกัน<br><span class="highlight">ต้องวิเคราะห์ว่าผลลัพธ์เป็นอะไร</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>total = 0<br>for i in range(1, 4):<br>&nbsp;&nbsp;&nbsp;&nbsp;for j in range(1, 3):<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;total += 1<br>print(total)</code>`,
    successMsg: `ถูกต้อง! nested loop = ลูปนอก x ลูปใน = จำนวนรอบทั้งหมด!`,
    tutorialTitle: `Nested Loop — ลูปซ้อนลูป`,
    tutorialContent: `Nested loop คือลูปที่อยู่ข้างในอีกลูป — ลูปในจะวนครบก่อน แล้วลูปนอกถึงวนรอบถัดไป

<b>ตัวอย่าง:</b>
<pre>for i in range(1, 4):      # ลูปนอก: 3 รอบ
    for j in range(1, 3):  # ลูปใน: 2 รอบ
        print(f"({i},{j})", end=" ")
    print()</pre>

<b>ผลลัพธ์:</b>
<pre>(1,1) (1,2)
(2,1) (2,2)
(3,1) (3,2) </pre>

<b>จำนวนรอบทั้งหมด:</b> ลูปนอก x ลูปใน = 3 x 2 = 6 รอบ`,
    hints: [
      `💡 ลูปนอกวน i = 1,2,3 / ลูปในวน j = 1,2 — แต่ละรอบ total += 1`,
      `🔶 ลูปนอก 3 รอบ x ลูปใน 2 รอบ = กี่ครั้งรวมทั้งหมด?`,
      `🔴 3 x 2 = 6 ครั้ง → total = 6`,
    ],
    choices: [
      { text: '3', correct: false },
      { text: '5', correct: false },
      { text: '6', correct: true },
      { text: '9', correct: false },
    ],
  },

  // ===== ZONE 4: 📝 Zone 4 — สตริง (slicing, methods, format) =====
  {
    id: 21,
    name: "กลับคำ",
    icon: "🔄",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 4,
    type: "code",
    xp: 130,
    skill: "string slicing",
    character: "🤖",
    story: `Matrix ต้องการถอดรหัสข้อความโดยกลับตัวอักษร<br><span class="highlight">ใช้ string slicing เพื่อกลับคำ</span>`,
    mission: `รับข้อความจากผู้ใช้ แล้วแสดงข้อความย้อนกลับ<br><br>ทดสอบด้วย: <code>Python</code> → ผลลัพธ์: <code>nohtyP</code>`,
    expected: `nohtyP`,
    starter: `# กลับคำด้วย slicing\n`,
    successMsg: `กลับคำสำเร็จ! [::-1] เป็นเทคนิค slicing ที่ใช้บ่อยมาก!`,
    tutorialTitle: `String Slicing — ตัดส่วนของข้อความ`,
    tutorialContent: `Slicing ใช้ตัดส่วนของ string ออกมา ด้วยรูปแบบ <code>[start:end:step]</code>

<b>ตัวอย่าง:</b>
<pre>text = "ABCDEFGH"
print(text[0:3])   # ABC (ตำแหน่ง 0 ถึง 2)
print(text[3:])    # DEFGH (ตำแหน่ง 3 จนจบ)
print(text[:4])    # ABCD (เริ่มต้นถึง 3)
print(text[::2])   # ACEG (ข้ามทีละ 2)
print(text[::-1])  # HGFEDCBA (กลับหลัง!)</pre>

<b>สิ่งสำคัญ:</b>
• Index เริ่มจาก 0 (ตัวแรก = 0)
• <code>[::-1]</code> เป็นเทคนิคกลับ string ที่ใช้บ่อยมาก!`,
    hints: [
      `💡 Python มีวิธีพิเศษในการกลับ string — เกี่ยวกับ slicing [::]`,
      `🔶 string[::-1] คือการกลับ string — step เป็น -1 ทำให้อ่านจากหลังไปหน้า`,
      `🔴 text = input() / print(text[::-1])`,
    ],
    testInputs: ["Python"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input()!";
      if (!code.includes('[::-1]') && !code.includes('[:: -1]')) return "ต้องใช้ [::-1] ในการกลับ string!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "nohtyP" ,
  },
  {
    id: 22,
    name: "นับตัวอักษร",
    icon: "🔍",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 4,
    type: "code",
    xp: 130,
    skill: "string methods",
    character: "🕵️",
    story: `นักสืบต้องวิเคราะห์ข้อความหลักฐาน<br><span class="highlight">นับจำนวนตัวอักษร "a" ในข้อความ</span>`,
    mission: `รับข้อความจากผู้ใช้ แล้วนับจำนวนตัว "a" (ตัวพิมพ์เล็ก) ที่มีในข้อความ<br><br>ทดสอบด้วย: <code>banana attack</code> → มี a กี่ตัว? → <code>5</code>`,
    expected: `5`,
    starter: `# นับตัวอักษรในข้อความ\n`,
    successMsg: `นับถูกต้อง! .count() เป็น string method ที่ใช้ง่ายและมีประโยชน์มาก!`,
    tutorialTitle: `.count() — นับจำนวน substring`,
    tutorialContent: `<code>.count()</code> นับว่า substring ปรากฏใน string กี่ครั้ง

<b>ตัวอย่าง:</b>
<pre>text = "programming is fun"
print(text.count("g"))     # 2
print(text.count("mm"))    # 1
print(text.count("z"))     # 0
print(text.count(" "))     # 2 (มีช่องว่าง 2 ตัว)</pre>

<b>String methods อื่นที่ควรรู้:</b>
• <code>.find("x")</code> → ตำแหน่งแรกที่พบ (-1 ถ้าไม่พบ)
• <code>.index("x")</code> → เหมือน find แต่ error ถ้าไม่พบ
• <code>len(text)</code> → ความยาวทั้งหมด`,
    hints: [
      `💡 string มี method สำเร็จรูปสำหรับนับตัวอักษร`,
      `🔶 .count() ใช้นับจำนวนครั้งที่ substring ปรากฏใน string`,
      `🔴 text = input() / print(text.count("a"))`,
    ],
    testInputs: ["banana attack"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input()!";
      if (!code.includes('.count')) return "ต้องใช้ .count() ในการนับตัวอักษร!";
      if (code.includes('5') && !code.includes('count')) return "ห้ามใส่ค่าตรงๆ! ต้องใช้ .count()";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "5" ,
  },
  {
    id: 23,
    name: "แปลงรหัสลับ",
    icon: "🔐",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 4,
    type: "code",
    xp: 140,
    skill: "upper/lower/replace",
    character: "🧙‍♂️",
    story: `คาถาลับต้องแปลงข้อความ<br><span class="highlight">เปลี่ยนเป็นตัวพิมพ์ใหญ่ทั้งหมด แล้วแทนที่ช่องว่างด้วย "-"</span>`,
    mission: `รับข้อความจากผู้ใช้ → แปลงเป็นตัวพิมพ์ใหญ่ → แทนที่ช่องว่างด้วย "-"<br><br>ทดสอบด้วย: <code>hello world</code> → <code>HELLO-WORLD</code>`,
    expected: `HELLO-WORLD`,
    starter: `# แปลงรหัสลับ\n`,
    successMsg: `แปลงรหัสสำเร็จ! method chaining (.upper().replace()) เป็นเทคนิคที่ใช้บ่อยมาก!`,
    tutorialTitle: `.upper() .lower() .replace() — แปลงข้อความ`,
    tutorialContent: `String มี method สำหรับแปลงข้อความหลายแบบ

<b>ตัวอย่าง:</b>
<pre>msg = "Hello World"
print(msg.upper())       # HELLO WORLD
print(msg.lower())       # hello world
print(msg.title())       # Hello World
print(msg.replace("World", "Python"))  # Hello Python
print(msg.replace(" ", "_"))           # Hello_World</pre>

<b>Method Chaining:</b> ใช้หลาย method ต่อกันได้!
<pre>text = "  Hello  "
print(text.strip().lower().replace("hello", "hi"))
# ผลลัพธ์: hi</pre>`,
    hints: [
      `💡 string มี method .upper() สำหรับทำตัวพิมพ์ใหญ่ แล้ว .replace() ล่ะ?`,
      `🔶 .upper() แปลงเป็นตัวใหญ่ แล้ว .replace(" ", "-") แทนที่ช่องว่าง`,
      `🔴 text = input() / print(text.upper().replace(" ", "-"))`,
    ],
    testInputs: ["hello world"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input()!";
      if (!code.includes('.upper()')) return "ต้องใช้ .upper() ในการแปลงตัวพิมพ์ใหญ่!";
      if (!code.includes('.replace')) return "ต้องใช้ .replace() ในการแทนที่ตัวอักษร!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "HELLO-WORLD" ,
  },
  {
    id: 24,
    name: "แยกคำ",
    icon: "✂️",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 4,
    type: "code",
    xp: 140,
    skill: "split + join",
    character: "👨‍🍳",
    story: `เชฟได้รับรายการวัตถุดิบเป็นข้อความยาว คั่นด้วยจุลภาค<br><span class="highlight">ต้องแยกออกมาแล้วแสดงทีละบรรทัด</span>`,
    mission: `รับรายการวัตถุดิบ (คั่นด้วย ,) แล้วแสดงทีละบรรทัด<br><br>ทดสอบด้วย: <code>ไข่,นม,แป้ง</code>`,
    expected: `ไข่\nนม\nแป้ง`,
    starter: `# แยกรายการวัตถุดิบ\n`,
    successMsg: `แยกวัตถุดิบสำเร็จ! .split() เป็น method ที่ใช้บ่อยมากในการจัดการข้อมูล!`,
    tutorialTitle: `.split() + .join() — แยกและรวมข้อความ`,
    tutorialContent: `<code>.split()</code> แยก string เป็น list / <code>.join()</code> รวม list เป็น string

<b>ตัวอย่าง split:</b>
<pre>csv = "แดง,เขียว,น้ำเงิน"
colors = csv.split(",")
print(colors)  # ['แดง', 'เขียว', 'น้ำเงิน']
print(colors[1])  # เขียว</pre>

<b>ตัวอย่าง join:</b>
<pre>words = ["Python", "is", "fun"]
sentence = " ".join(words)
print(sentence)  # Python is fun

path = "/".join(["home", "user", "docs"])
print(path)  # home/user/docs</pre>`,
    hints: [
      `💡 .split() แยก string เป็น list ตามตัวคั่น`,
      `🔶 text.split(",") จะได้ list แล้ววนลูป print ทีละตัว`,
      `🔴 items = input().split(",") / for item in items: print(item)`,
    ],
    testInputs: ["\u0e44\u0e02\u0e48,\u0e19\u0e21,\u0e41\u0e1b\u0e49\u0e07"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input()!";
      if (!code.includes('.split')) return "ต้องใช้ .split() ในการแยกข้อความ!";
      if (!code.includes('for')) return "ต้องใช้ for loop แสดงทีละบรรทัด!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 3 && lines[0] === "ไข่" && lines[1] === "นม" && lines[2] === "แป้ง";
    },
  },
  {
    id: 25,
    name: "เช็คอีเมล",
    icon: "📧",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 4,
    type: "code",
    xp: 140,
    skill: "in + string check",
    character: "👩‍🚀",
    story: `ระบบลงทะเบียนยานอวกาศต้องเช็คว่าอีเมลถูกรูปแบบ<br><span class="highlight">ต้องมี @ และ . อยู่ในอีเมล</span>`,
    mission: `รับอีเมลจากผู้ใช้ เช็คว่ามีทั้ง "@" และ "." หรือไม่<br>- ถ้ามีทั้ง 2 → <code>อีเมลถูกต้อง</code><br>- ถ้าไม่ → <code>อีเมลไม่ถูกต้อง</code><br><br>ทดสอบด้วย: <code>test@mail.com</code>`,
    expected: `อีเมลถูกต้อง`,
    starter: `# เช็คอีเมล\n`,
    successMsg: `เช็คอีเมลสำเร็จ! in เป็นวิธีง่ายๆ ในการเช็ค substring!`,
    tutorialTitle: `in — เช็คว่ามีอยู่ใน string/list ไหม`,
    tutorialContent: `<code>in</code> ใช้เช็คว่าค่าหนึ่งมีอยู่ใน string หรือ list หรือไม่

<b>ตัวอย่าง:</b>
<pre># เช็คใน string
msg = "Hello World"
print("World" in msg)    # True
print("world" in msg)    # False (case sensitive!)

# เช็คใน list
fruits = ["apple", "banana", "mango"]
print("banana" in fruits)  # True
print("grape" in fruits)   # False

# ใช้ในเงื่อนไข
url = "https://example.com"
if "https" in url:
    print("เป็น HTTPS")</pre>`,
    hints: [
      `💡 ใน Python ใช้คำว่า "in" เช็คว่า substring อยู่ใน string ได้`,
      `🔶 if "@" in email and "." in email: → ถูกต้อง`,
      `🔴 email = input() / if "@" in email and "." in email: print("อีเมลถูกต้อง") else: ...`,
    ],
    testInputs: ["test@mail.com"],
    codeCheck: (code) => {
      if (!code.includes('input')) return "ต้องใช้ input()!";
      if (!code.includes('in ') && !code.includes('in\t')) return "ต้องใช้ in ในการเช็ค substring!";
      if (!code.includes('and')) return "ต้องใช้ and เชื่อม 2 เงื่อนไข!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "อีเมลถูกต้อง" ,
  },
  {
    id: 26,
    name: "ปริศนา string",
    icon: "🧩",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 4,
    type: "choice",
    xp: 100,
    skill: "วิเคราะห์ string",
    character: "🕵️",
    story: `นักสืบพบโค้ดจัดการ string<br><span class="highlight">ต้องวิเคราะห์ว่าผลลัพธ์เป็นอะไร</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>s = "Hello Python"<br>print(s[6:].upper())</code>`,
    successMsg: `ถูกต้อง! slicing + method เป็นคอมโบที่ทรงพลัง!`,
    tutorialTitle: `String Slicing + Methods — รวมเทคนิค`,
    tutorialContent: `สามารถใช้ slicing ร่วมกับ methods ได้ เพื่อจัดการ string อย่างยืดหยุ่น

<b>ตัวอย่าง:</b>
<pre>text = "Hello Python World"
# Slice แล้วแปลง
print(text[:5])              # Hello
print(text[6:12])            # Python
print(text[6:12].lower())    # python
print(text[::-1].upper())    # DLROW NOHTYP OLLEH

# Index เริ่มจาก 0
# H=0, e=1, l=2, l=3, o=4, ' '=5, P=6...</pre>

<b>สิ่งสำคัญ:</b> Slicing ไม่เปลี่ยน string ต้นฉบับ (string เป็น immutable)`,
    hints: [
      `💡 s[6:] จะ slice ตั้งแต่ตำแหน่งที่ 6 เป็นต้นไป (index เริ่มจาก 0)`,
      `🔶 H=0, e=1, l=2, l=3, o=4, =5, P=6 → s[6:] = "Python"`,
      `🔴 "Python".upper() = "PYTHON"`,
    ],
    choices: [
      { text: 'HELLO PYTHON', correct: false },
      { text: 'PYTHON', correct: true },
      { text: 'Python', correct: false },
      { text: 'hello python', correct: false },
    ],
  },

  // ===== ZONE 5: 📦 Zone 5 — ลิสต์ + ทูเพิล (list, tuple, comprehension) =====
  {
    id: 27,
    name: "วิเคราะห์คะแนนสอบ",
    icon: "📊",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 5,
    type: "code",
    xp: 150,
    skill: "list + loop",
    character: "🕵️",
    story: `คุณได้รับคะแนนสอบ 6 คน: <code>[45, 82, 67, 91, 53, 78]</code><br><span class="highlight">ต้องวิเคราะห์หาว่ามีกี่คนที่สอบผ่าน (60 ขึ้นไป)</span>`,
    mission: `จาก list คะแนน <code>[45, 82, 67, 91, 53, 78]</code><br>นับจำนวนคนที่สอบผ่าน (คะแนน >= 60) แล้วแสดงผลเป็น <code>สอบผ่าน 4 คน</code>`,
    expected: `สอบผ่าน 4 คน`,
    starter: `# วิเคราะห์คะแนนสอบ\nscores = [45, 82, 67, 91, 53, 78]\n`,
    successMsg: `วิเคราะห์ได้ถูกต้อง! loop + list + เงื่อนไข = พลังมหาศาล!`,
    tutorialTitle: `List + Loop — วนลูปกับลิสต์`,
    tutorialContent: `<code>list</code> เก็บข้อมูลหลายตัวไว้ในตัวแปรเดียว ใช้ for loop วนดูได้

<b>ตัวอย่าง:</b>
<pre>temps = [30, 28, 35, 32, 27]
hot_days = 0
for t in temps:
    if t >= 30:
        hot_days += 1
print(f"วันร้อน {hot_days} วัน")</pre>

<b>ผลลัพธ์:</b>
<pre>วันร้อน 3 วัน</pre>

<b>วิธีสร้าง list:</b>
<pre>nums = [1, 2, 3, 4, 5]
names = ["A", "B", "C"]
mixed = [1, "hello", True, 3.14]  # ผสมชนิดได้</pre>`,
    hints: [
      `💡 ต้องวนลูปดูแต่ละค่าใน list — จะนับจำนวนที่ตรงเงื่อนไขทำยังไง?`,
      `🔶 สร้างตัวแปรนับ count = 0 แล้ว +1 ทุกครั้งที่เจอคนสอบผ่าน`,
      `🔴 for score in scores: → if score >= 60: count += 1 → print("สอบผ่าน", count, "คน")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop เพื่อวนดูแต่ละค่าใน list!";
      if (!code.includes('if')) return "ต้องใช้ if เพื่อเช็คว่าสอบผ่านหรือไม่!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("4") && last.includes("สอบผ่าน");
    },
  },
  {
    id: 28,
    name: "เรียงลำดับเมนู",
    icon: "🍜",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 5,
    type: "code",
    xp: 160,
    skill: "list methods",
    character: "👨‍🍳",
    story: `เมนูร้านอาหารกระจัดกระจาย!<br><span class="highlight">ต้องจัดเรียงราคาจากน้อยไปมาก แล้วบอกว่าเมนูที่แพงสุดและถูกสุดราคาเท่าไร</span>`,
    mission: `จาก list ราคา <code>[120, 45, 200, 89, 35, 150]</code><br>แสดงผล 3 บรรทัด:<br>1. ราคาถูกสุด<br>2. ราคาแพงสุด<br>3. list เรียงจากน้อยไปมาก`,
    expected: `ถูกสุด 35\nแพงสุด 200\n[35, 45, 89, 120, 150, 200]`,
    starter: `# จัดเรียงเมนูร้านอาหาร\nprices = [120, 45, 200, 89, 35, 150]\n`,
    successMsg: `เมนูเรียบร้อย! คุณใช้ list methods ได้คล่องแล้ว!`,
    tutorialTitle: `List Methods — min, max, sort, sum`,
    tutorialContent: `Python มีฟังก์ชันสำเร็จรูปสำหรับจัดการ list

<b>ฟังก์ชันสำคัญ:</b>
<pre>nums = [4, 2, 8, 1, 6]
print(min(nums))     # 1 (ค่าน้อยสุด)
print(max(nums))     # 8 (ค่ามากสุด)
print(sum(nums))     # 21 (ผลรวม)
print(len(nums))     # 5 (จำนวนสมาชิก)
print(sorted(nums))  # [1, 2, 4, 6, 8] (เรียง)

# .sort() เรียงใน list เดิม
nums.sort()
print(nums)  # [1, 2, 4, 6, 8]

# .sort(reverse=True) เรียงจากมากไปน้อย
nums.sort(reverse=True)
print(nums)  # [8, 6, 4, 2, 1]</pre>`,
    hints: [
      `💡 Python มีฟังก์ชันสำเร็จรูปสำหรับหาค่าน้อยสุด มากสุด และเรียงลำดับใน list`,
      `🔶 min(), max() หาค่าน้อย/มากสุด ส่วนการเรียงลำดับลอง sorted() หรือ .sort()`,
      `🔴 print("ถูกสุด", min(prices)) / print("แพงสุด", max(prices)) / prices.sort() แล้ว print(prices)`,
    ],
    codeCheck: (code) => {
      const usesBuiltin = code.includes('min') || code.includes('max') || code.includes('sort');
      if (!usesBuiltin) return "ต้องใช้ฟังก์ชัน/เมธอดของ list เช่น min(), max(), sort()!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l => l.trim());
      return lines.length >= 3 && lines[0].includes("35") && lines[1].includes("200") && lines[2].includes("[35");
    },
  },
  {
    id: 29,
    name: "เพิ่ม-ลบสมาชิก",
    icon: "👥",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 5,
    type: "code",
    xp: 140,
    skill: "append/remove/insert",
    character: "🧙‍♂️",
    story: `ทีมนักรบต้องจัดการรายชื่อสมาชิก<br><span class="highlight">เพิ่มสมาชิกใหม่ ลบคนที่ออก แล้วแทรกคนใหม่</span>`,
    mission: `เริ่มจาก <code>team = ["Alice", "Bob", "Charlie"]</code><br>1. เพิ่ม "Dave" ต่อท้าย<br>2. ลบ "Bob" ออก<br>3. แทรก "Eve" ที่ตำแหน่ง 1<br>แสดง list สุดท้าย`,
    expected: `["Alice", "Eve", "Charlie", "Dave"]`,
    starter: `# จัดการรายชื่อทีมนักรบ\nteam = ["Alice", "Bob", "Charlie"]\n`,
    successMsg: `จัดการทีมสำเร็จ! append, remove, insert เป็น list methods พื้นฐานที่สำคัญ!`,
    tutorialTitle: `append, remove, insert — จัดการสมาชิก list`,
    tutorialContent: `Method สำหรับเพิ่ม/ลบ/แทรกสมาชิกใน list

<b>ตัวอย่าง:</b>
<pre>fruits = ["apple", "banana"]

# เพิ่มท้าย
fruits.append("cherry")
print(fruits)  # ['apple', 'banana', 'cherry']

# แทรกที่ตำแหน่ง 1
fruits.insert(1, "mango")
print(fruits)  # ['apple', 'mango', 'banana', 'cherry']

# ลบตามค่า
fruits.remove("banana")
print(fruits)  # ['apple', 'mango', 'cherry']

# ลบตาม index
fruits.pop(0)
print(fruits)  # ['mango', 'cherry']</pre>`,
    hints: [
      `💡 .append() เพิ่มท้าย, .remove() ลบ, .insert(ตำแหน่ง, ค่า) แทรก`,
      `🔶 team.append("Dave") → team.remove("Bob") → team.insert(1, "Eve")`,
      `🔴 team.append("Dave")\nteam.remove("Bob")\nteam.insert(1, "Eve")\nprint(team)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('.append')) return "ต้องใช้ .append() เพื่อเพิ่มสมาชิก!";
      if (!code.includes('.remove')) return "ต้องใช้ .remove() เพื่อลบสมาชิก!";
      if (!code.includes('.insert')) return "ต้องใช้ .insert() เพื่อแทรกสมาชิก!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("Alice") && last.includes("Eve") && last.includes("Charlie") && last.includes("Dave") && !last.includes("Bob");
    },
  },
  {
    id: 30,
    name: "List Comprehension",
    icon: "⚡",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 5,
    type: "code",
    xp: 170,
    skill: "list comprehension",
    character: "🤖",
    story: `Matrix ต้องการ list ใหม่ที่มีแต่เลขคู่จาก list เดิม คูณ 2<br><span class="highlight">ใช้ List Comprehension สร้างในบรรทัดเดียว</span>`,
    mission: `จาก <code>nums = [1, 2, 3, 4, 5, 6, 7, 8]</code><br>สร้าง list ใหม่ที่เอาเฉพาะเลขคู่ แล้วคูณ 2<br>ต้องใช้ list comprehension<br>แสดงผลลัพธ์`,
    expected: `[4, 8, 12, 16]`,
    starter: `# List Comprehension\nnums = [1, 2, 3, 4, 5, 6, 7, 8]\n`,
    successMsg: `List Comprehension สำเร็จ! นี่คือเครื่องมือทรงพลังที่ Pythonista ใช้ประจำ!`,
    tutorialTitle: `List Comprehension — สร้าง list แบบกระชับ`,
    tutorialContent: `List Comprehension สร้าง list ใหม่ในบรรทัดเดียว

<b>รูปแบบ:</b>
<pre>[expression for item in iterable if condition]</pre>

<b>ตัวอย่าง:</b>
<pre># สร้าง list กำลังสอง
squares = [x**2 for x in range(1, 6)]
print(squares)  # [1, 4, 9, 16, 25]

# เอาเฉพาะเลขคู่
evens = [x for x in range(10) if x % 2 == 0]
print(evens)  # [0, 2, 4, 6, 8]

# แปลงข้อมูล
words = ["hello", "world"]
upper = [w.upper() for w in words]
print(upper)  # ['HELLO', 'WORLD']</pre>`,
    hints: [
      `💡 List comprehension รูปแบบ: [expression for x in list if condition]`,
      `🔶 [x*2 for x in nums if x % 2 == 0] จะเอาเฉพาะเลขคู่มาคูณ 2`,
      `🔴 nums = [1,2,3,4,5,6,7,8]\nresult = [x*2 for x in nums if x % 2 == 0]\nprint(result)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('[') || !code.includes('for') || !code.includes('if') || !code.includes(']')) {
        if (!code.match(/\[.*for.*in.*\]/)) return "ต้องใช้ List Comprehension! (รูปแบบ [x for x in list if ...])";
      }
      const forCount = (code.match(/\bfor\b/g) || []).length;
      if (forCount > 1 && !code.match(/\[.*for.*in.*\]/)) return "ต้องใช้ List Comprehension ไม่ใช่ for loop ธรรมดา!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last === "[4, 8, 12, 16]";
    },
  },
  {
    id: 31,
    name: "Tuple ข้อมูลนักเรียน",
    icon: "📦",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 5,
    type: "code",
    xp: 130,
    skill: "tuple + unpacking",
    character: "🕵️",
    story: `ข้อมูลนักเรียนถูกเก็บเป็น tuple (ชื่อ, อายุ, เกรด)<br><span class="highlight">ต้อง unpack แล้วแสดงผล</span>`,
    mission: `สร้าง tuple: <code>student = ("สมชาย", 17, 3.75)</code><br>Unpack ค่าจาก tuple เป็นตัวแปร 3 ตัว<br>แสดงผล: <code>ชื่อ: สมชาย อายุ: 17 เกรด: 3.75</code>`,
    expected: `ชื่อ: สมชาย อายุ: 17 เกรด: 3.75`,
    starter: `# Tuple ข้อมูลนักเรียน\nstudent = ("สมชาย", 17, 3.75)\n`,
    successMsg: `Unpack สำเร็จ! Tuple เหมาะสำหรับเก็บข้อมูลที่ไม่ต้องเปลี่ยนแปลง!`,
    tutorialTitle: `Tuple — ข้อมูลที่เปลี่ยนแปลงไม่ได้ + Unpacking`,
    tutorialContent: `<code>tuple</code> คล้าย list แต่<b>เปลี่ยนแปลงค่าไม่ได้</b> (immutable)

<b>สร้าง tuple:</b>
<pre>point = (3, 7)
rgb = (255, 128, 0)
single = (42,)  # tuple ตัวเดียวต้องมี , ตามหลัง</pre>

<b>Unpacking — แยกค่าเป็นตัวแปร:</b>
<pre>person = ("สมศรี", 25, "กรุงเทพ")
name, age, city = person  # unpack!
print(f"{name} อายุ {age} จาก {city}")</pre>

<b>ผลลัพธ์:</b>
<pre>สมศรี อายุ 25 จาก กรุงเทพ</pre>

<b>ใช้เมื่อไร:</b> เก็บข้อมูลที่ไม่ควรเปลี่ยน เช่น พิกัด, สี RGB, ข้อมูลคงที่`,
    hints: [
      `💡 Tuple unpacking: name, age, grade = student จะแยกค่าจาก tuple ไปเก็บในตัวแปร`,
      `🔶 สร้าง tuple แล้ว unpack: name, age, grade = student → ใช้ f-string แสดงผล`,
      `🔴 student = ("สมชาย", 17, 3.75)\nname, age, grade = student\nprint(f"ชื่อ: {name} อายุ: {age} เกรด: {grade}")`,
    ],
    codeCheck: (code) => {
      if (!code.includes(',') || !code.includes('=')) return "ต้อง unpack tuple เป็นตัวแปรหลายตัว!";
      if (code.match(/\bprint\b.*สมชาย.*17.*3\.75/) && !code.includes('{')) return "ห้าม print ค่าตรงๆ! ต้อง unpack จาก tuple";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("สมชาย") && last.includes("17") && last.includes("3.75");
    },
  },
  {
    id: 32,
    name: "หาตัวซ้ำ",
    icon: "🔎",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 5,
    type: "code",
    xp: 160,
    skill: "list + loop + logic",
    character: "👩‍🚀",
    story: `ฐานข้อมูลยานอวกาศมีข้อมูลซ้ำ<br><span class="highlight">ต้องหาว่ามีค่าไหนซ้ำกันบ้าง</span>`,
    mission: `จาก <code>data = [3, 5, 2, 3, 8, 5, 1]</code><br>หาตัวเลขที่ซ้ำแล้วแสดงเป็น list เรียงจากน้อยไปมาก`,
    expected: `[3, 5]`,
    starter: `# หาตัวเลขซ้ำ\ndata = [3, 5, 2, 3, 8, 5, 1]\n`,
    successMsg: `หาตัวซ้ำสำเร็จ! การรวม list + set + comprehension เป็นเทคนิคที่ทรงพลังมาก!`,
    tutorialTitle: `หาข้อมูลซ้ำด้วย Loop + Logic`,
    tutorialContent: `เทคนิคหาข้อมูลซ้ำใน list มีหลายวิธี

<b>วิธีที่ 1: ใช้ .count()</b>
<pre>nums = [1, 2, 3, 2, 4, 3]
dups = []
for n in nums:
    if nums.count(n) > 1 and n not in dups:
        dups.append(n)
print(sorted(dups))  # [2, 3]</pre>

<b>วิธีที่ 2: ใช้ set (เร็วกว่า)</b>
<pre>nums = [1, 2, 3, 2, 4, 3]
seen = set()
dups = set()
for n in nums:
    if n in seen:
        dups.add(n)
    seen.add(n)
print(sorted(dups))  # [2, 3]</pre>`,
    hints: [
      `💡 วนลูปนับจำนวนแต่ละตัว — ถ้ามีมากกว่า 1 ครั้งแสดงว่าซ้ำ`,
      `🔶 ใช้ .count() หรือ set ช่วยหาตัวซ้ำ แล้ว sorted() เรียงลำดับ`,
      `🔴 dups = sorted(set([x for x in data if data.count(x) > 1])) แล้ว print(dups)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for') && !code.includes('count') && !code.includes('set')) return "ต้องใช้ loop หรือ built-in functions ในการหาตัวซ้ำ!";
      if (code.includes('[3, 5]') && !code.includes('for') && !code.includes('count')) return "ห้ามใส่คำตอบตรงๆ! ต้องคำนวณจาก data";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last === "[3, 5]";
    },
  },
  {
    id: 33,
    name: "ปริศนา list",
    icon: "🧩",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 5,
    type: "choice",
    xp: 100,
    skill: "วิเคราะห์ list",
    character: "🤖",
    story: `Matrix ส่งโค้ด list มาให้ถอดรหัส<br><span class="highlight">ต้องวิเคราะห์ว่าผลลัพธ์เป็นอะไร</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>a = [1, 2, 3]<br>b = a<br>b.append(4)<br>print(a)</code>`,
    successMsg: `ถูกต้อง! list assignment เป็น reference — เปลี่ยนตัวหนึ่งมีผลต่ออีกตัว!`,
    tutorialTitle: `List Reference — ระวังการ copy list`,
    tutorialContent: `ใน Python การกำหนด <code>b = a</code> กับ list เป็นแค่การชี้ไปที่ list เดียวกัน!

<b>ตัวอย่างปัญหา:</b>
<pre>a = [1, 2, 3]
b = a          # b ชี้ไปที่ list เดียวกับ a!
b.append(4)
print(a)  # [1, 2, 3, 4] — a เปลี่ยนด้วย!</pre>

<b>วิธี copy ถูกต้อง:</b>
<pre>a = [1, 2, 3]
b = a.copy()   # หรือ b = a[:] หรือ b = list(a)
b.append(4)
print(a)  # [1, 2, 3] — a ไม่เปลี่ยน
print(b)  # [1, 2, 3, 4]</pre>`,
    hints: [
      `💡 เมื่อ b = a จะเป็นการ copy reference หรือ copy ค่า?`,
      `🔶 b = a ทำให้ b ชี้ไปที่ list เดียวกับ a (reference) → เปลี่ยน b = เปลี่ยน a ด้วย`,
      `🔴 b.append(4) เพิ่ม 4 ใน list ที่ a และ b ชี้อยู่ → a = [1, 2, 3, 4]`,
    ],
    choices: [
      { text: '[1, 2, 3]', correct: false },
      { text: '[1, 2, 3, 4]', correct: true },
      { text: 'Error', correct: false },
      { text: '[4, 1, 2, 3]', correct: false },
    ],
  },

  // ===== ZONE 6: 🗂️ Zone 6 — ดิกชันนารี + เซ็ต (dict, set) =====
  {
    id: 34,
    name: "สมุดโทรศัพท์",
    icon: "📱",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 6,
    type: "code",
    xp: 150,
    skill: "dictionary",
    character: "🕵️",
    story: `นักสืบต้องสร้างสมุดโทรศัพท์ผู้ต้องสงสัย<br><span class="highlight">เก็บข้อมูลเป็น key-value แล้วค้นหา</span>`,
    mission: `สร้าง dictionary: <code>phone = {"Alice": "0812345678", "Bob": "0898765432", "Charlie": "0856781234"}</code><br>แสดงเบอร์ของ Bob<br>แสดงจำนวนรายชื่อทั้งหมด`,
    expected: `0898765432\n3`,
    starter: `# สมุดโทรศัพท์\nphone = {"Alice": "0812345678", "Bob": "0898765432", "Charlie": "0856781234"}\n`,
    successMsg: `สมุดโทรศัพท์ทำงานสมบูรณ์! Dictionary เป็นโครงสร้างข้อมูลสำคัญมาก!`,
    tutorialTitle: `Dictionary — เก็บข้อมูลแบบ key-value`,
    tutorialContent: `<code>dict</code> เก็บข้อมูลเป็นคู่ key: value ค้นหาเร็วมาก

<b>สร้างและใช้งาน:</b>
<pre>student = {"name": "สมชาย", "age": 17, "grade": 3.5}

# เข้าถึงค่า
print(student["name"])   # สมชาย
print(student.get("gpa", "N/A"))  # N/A (ไม่มี key นี้)

# เพิ่ม/แก้ไข
student["school"] = "XYZ"
student["age"] = 18

# จำนวน key
print(len(student))  # 4</pre>

<b>สิ่งสำคัญ:</b> key ต้องไม่ซ้ำกัน! ถ้าซ้ำจะทับค่าเดิม`,
    hints: [
      `💡 dictionary เข้าถึงค่าผ่าน key: dict["key"]`,
      `🔶 phone["Bob"] จะได้เบอร์ของ Bob / len(phone) นับจำนวน`,
      `🔴 print(phone["Bob"])\nprint(len(phone))`,
    ],
    codeCheck: (code) => {
      if (!code.includes('[') && !code.includes('.get')) return "ต้องเข้าถึงค่าใน dict ผ่าน key!";
      if (!code.includes('len')) return "ต้องใช้ len() นับจำนวนรายชื่อ!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 2 && lines[0] === "0898765432" && lines[1] === "3";
    },
  },
  {
    id: 35,
    name: "นับคำ",
    icon: "📝",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 6,
    type: "code",
    xp: 170,
    skill: "dict + loop",
    character: "👨‍🍳",
    story: `เชฟต้องนับจำนวนคำสั่งอาหารแต่ละชนิด<br><span class="highlight">ใช้ dict เก็บจำนวนนับ</span>`,
    mission: `จาก <code>orders = ["ข้าวผัด", "ต้มยำ", "ข้าวผัด", "ส้มตำ", "ต้มยำ", "ข้าวผัด"]</code><br>นับจำนวนแต่ละเมนู แสดงเมนูที่สั่งมากสุดพร้อมจำนวน`,
    expected: `ข้าวผัด 3`,
    starter: `# นับคำสั่งอาหาร\norders = ["ข้าวผัด", "ต้มยำ", "ข้าวผัด", "ส้มตำ", "ต้มยำ", "ข้าวผัด"]\n`,
    successMsg: `นับคำสั่งสำเร็จ! dict + loop เป็นเทคนิคนับจำนวนที่ใช้บ่อยมาก!`,
    tutorialTitle: `Dict + Loop — นับจำนวนด้วย Dictionary`,
    tutorialContent: `Dict เหมาะมากสำหรับนับจำนวนของแต่ละรายการ

<b>เทคนิค .get():</b>
<pre>votes = ["A", "B", "A", "C", "B", "A"]
count = {}
for v in votes:
    count[v] = count.get(v, 0) + 1
print(count)  # {'A': 3, 'B': 2, 'C': 1}

# หาตัวที่มากสุด
winner = max(count, key=count.get)
print(f"ชนะ: {winner} ({count[winner]} โหวต)")</pre>

<b>ผลลัพธ์:</b>
<pre>{'A': 3, 'B': 2, 'C': 1}
ชนะ: A (3 โหวต)</pre>

<b>.get(key, default)</b> คืน default ถ้าไม่มี key (ไม่ error)`,
    hints: [
      `💡 สร้าง dict เปล่า แล้ววนลูปนับ — หรือใช้ .get() ช่วย`,
      `🔶 count = {} / for o in orders: count[o] = count.get(o, 0) + 1 → แล้วหาค่ามากสุด`,
      `🔴 ใช้ max(count, key=count.get) หาเมนูที่สั่งมากสุด`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop ในการนับ!";
      if (!code.includes('{') && !code.includes('dict')) return "ต้องใช้ dictionary ในการเก็บจำนวนนับ!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("ข้าวผัด") && last.includes("3");
    },
  },
  {
    id: 36,
    name: "วนลูป Dict",
    icon: "🔁",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 6,
    type: "code",
    xp: 150,
    skill: "dict.items()",
    character: "🧙‍♂️",
    story: `คลังอาวุธดันเจี้ยนเก็บข้อมูลเป็น dict<br><span class="highlight">ต้องวนลูปแสดงรายการอาวุธทั้งหมด</span>`,
    mission: `จาก <code>weapons = {"ดาบ": 50, "ธนู": 35, "ไม้เท้า": 70}</code><br>วนลูปแสดงผลแต่ละอาวุธ: <code>ดาบ - พลัง 50</code><br>แล้วแสดงพลังรวมทั้งหมด`,
    expected: `ดาบ - พลัง 50\nธนู - พลัง 35\nไม้เท้า - พลัง 70\nพลังรวม 155`,
    starter: `# คลังอาวุธดันเจี้ยน\nweapons = {"ดาบ": 50, "ธนู": 35, "ไม้เท้า": 70}\n`,
    successMsg: `วนลูป dict สำเร็จ! .items() เป็นวิธีที่ดีที่สุดในการเข้าถึง key-value พร้อมกัน!`,
    tutorialTitle: `dict.items() — วนลูป key-value พร้อมกัน`,
    tutorialContent: `<code>.items()</code> ให้ทั้ง key และ value สำหรับวนลูป

<b>ตัวอย่าง:</b>
<pre>scores = {"คณิต": 85, "วิทย์": 92, "อังกฤษ": 78}

for subject, score in scores.items():
    if score >= 80:
        print(f"{subject}: {score} ✓")
    else:
        print(f"{subject}: {score} ✗")</pre>

<b>ผลลัพธ์:</b>
<pre>คณิต: 85 ✓
วิทย์: 92 ✓
อังกฤษ: 78 ✗</pre>

<b>Method อื่น:</b>
• <code>.keys()</code> → เฉพาะ key
• <code>.values()</code> → เฉพาะ value
• <code>sum(scores.values())</code> → ผลรวมทุก value`,
    hints: [
      `💡 .items() ให้ key-value pairs ที่วนลูปได้ / for k, v in dict.items():`,
      `🔶 for name, power in weapons.items(): print(f"{name} - พลัง {power}")`,
      `🔴 แล้วรวมค่าด้วย sum(weapons.values()) หรือสะสมจากลูป`,
    ],
    codeCheck: (code) => {
      if (!code.includes('for')) return "ต้องใช้ for loop!";
      if (!code.includes('.items()') && !code.includes('.values()') && !code.includes('.keys()')) return "ต้องใช้ .items(), .values() หรือ .keys() ในการวนลูป dict!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 4 && lines[0].includes("ดาบ") && lines[0].includes("50") && lines[3].includes("155");
    },
  },
  {
    id: 37,
    name: "หาของซ้ำด้วย Set",
    icon: "🎯",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 6,
    type: "code",
    xp: 150,
    skill: "set operations",
    character: "👩‍🚀",
    story: `ฐานอวกาศ A และ B มีรายชื่ออะไหล่ต่างกัน<br><span class="highlight">ต้องหาว่ามีอะไหล่อะไรที่ทั้ง 2 ฐานมีร่วมกัน</span>`,
    mission: `สร้าง set A = <code>{"bolt", "nut", "gear", "wire"}</code><br>สร้าง set B = <code>{"gear", "tube", "bolt", "lens"}</code><br>แสดง set ที่เป็นส่วนร่วม (intersection) เรียงตัวอักษร`,
    expected: `['bolt', 'gear']`,
    starter: `# หาอะไหล่ร่วม\nA = {"bolt", "nut", "gear", "wire"}\nB = {"gear", "tube", "bolt", "lens"}\n`,
    successMsg: `หาส่วนร่วมสำเร็จ! Set operations เป็นเครื่องมือที่มีประโยชน์มากในการจัดการข้อมูล!`,
    tutorialTitle: `Set — ข้อมูลไม่ซ้ำ + การดำเนินการเซ็ต`,
    tutorialContent: `<code>set</code> เก็บข้อมูลที่<b>ไม่ซ้ำกัน</b> และทำ set operations ได้

<b>ตัวอย่าง:</b>
<pre>a = {1, 2, 3, 4, 5}
b = {4, 5, 6, 7, 8}

print(a & b)    # {4, 5} — ส่วนร่วม (intersection)
print(a | b)    # {1,2,3,4,5,6,7,8} — รวม (union)
print(a - b)    # {1, 2, 3} — ต่าง (difference)

# กำจัดซ้ำ
nums = [1, 2, 2, 3, 3, 3]
unique = set(nums)
print(unique)  # {1, 2, 3}</pre>`,
    hints: [
      `💡 set มี operation สำหรับหาส่วนร่วม — ใช้เครื่องหมายอะไร?`,
      `🔶 & หรือ .intersection() หาส่วนร่วม → แล้วใช้ sorted() เรียง`,
      `🔴 common = A & B → print(sorted(common))`,
    ],
    codeCheck: (code) => {
      if (!code.includes('&') && !code.includes('intersection')) return "ต้องใช้ & หรือ .intersection() ในการหาส่วนร่วม!";
      if (!code.includes('sorted')) return "ต้องใช้ sorted() ในการเรียงลำดับ!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("bolt") && last.includes("gear") && last.startsWith("[");
    },
  },
  {
    id: 38,
    name: "ปริศนา Dict",
    icon: "🧩",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 6,
    type: "choice",
    xp: 100,
    skill: "วิเคราะห์ dict",
    character: "🕵️",
    story: `นักสืบพบโค้ดที่ใช้ dictionary<br><span class="highlight">ต้องวิเคราะห์ว่าผลลัพธ์เป็นอะไร</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>d = {"a": 1, "b": 2}<br>d["c"] = d["a"] + d["b"]<br>d["a"] = 10<br>print(d["c"])</code>`,
    successMsg: `ถูกต้อง! ค่าใน dict คำนวณตอนกำหนด ไม่ใช่ตอนเรียกใช้!`,
    tutorialTitle: `Dict — ค่าคำนวณ vs สูตร`,
    tutorialContent: `เมื่อกำหนดค่าให้ dict จะคำนวณ<b>ณ ตอนที่กำหนด</b> ไม่ใช่ตอนเรียกใช้

<b>ตัวอย่าง:</b>
<pre>x = 10
y = 20
data = {"sum": x + y, "diff": x - y}
print(data["sum"])   # 30

x = 100  # เปลี่ยน x
print(data["sum"])   # ยังเป็น 30! (ไม่เปลี่ยนตาม)</pre>

<b>สิ่งสำคัญ:</b> ค่าใน dict ถูกเก็บเป็น<b>ผลลัพธ์</b> ไม่ใช่<b>สูตร</b>
ถ้าอยากให้คำนวณใหม่ทุกครั้ง ต้องใช้ function`,
    hints: [
      `💡 d["c"] ถูกกำหนดค่าตอนไหน? ค่าของ d["a"] เปลี่ยนทีหลังมีผลไหม?`,
      `🔶 d["c"] = 1 + 2 = 3 → หลังจากนั้น d["a"] เปลี่ยนเป็น 10 แต่ d["c"] ยังเป็น 3`,
      `🔴 d["c"] = 3 (คำนวณแล้วเก็บค่า ไม่ใช่สูตร) → print(d["c"]) = 3`,
    ],
    choices: [
      { text: '3', correct: true },
      { text: '12', correct: false },
      { text: '13', correct: false },
      { text: 'Error', correct: false },
    ],
  },

  // ===== ZONE 7: ⚙️ Zone 7 — ฟังก์ชัน (def, return, lambda, recursion) =====
  {
    id: 39,
    name: "สร้างเครื่องคิดเลข",
    icon: "🔧",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 7,
    type: "code",
    xp: 170,
    skill: "def + return",
    character: "🤖",
    story: `Matrix ต้องการเครื่องคิดเลขอัจฉริยะ<br><span class="highlight">สร้าง function ที่รับเลข 2 ตัว แล้วคืนค่าผลรวม ผลต่าง และผลคูณ</span>`,
    mission: `สร้าง function ชื่อ <code>calculate(a, b)</code> ที่ print 3 บรรทัด:<br>- ผลบวก<br>- ผลลบ<br>- ผลคูณ<br><br>แล้วเรียกใช้ด้วย <code>calculate(10, 3)</code>`,
    expected: `13\n7\n30`,
    starter: `# สร้างเครื่องคิดเลขอัจฉริยะ\n`,
    successMsg: `เครื่องคิดเลขทำงานสมบูรณ์! คุณสร้าง function เป็นแล้ว!`,
    tutorialTitle: `def + return — สร้างฟังก์ชัน`,
    tutorialContent: `<code>def</code> สร้าง function ของเราเอง — ใช้ซ้ำได้ไม่จำกัด

<b>รูปแบบ:</b>
<pre>def ชื่อfunction(parameter):
    ทำงาน
    return ผลลัพธ์</pre>

<b>ตัวอย่าง:</b>
<pre>def area(width, height):
    return width * height

def perimeter(width, height):
    return 2 * (width + height)

a = area(5, 3)
p = perimeter(5, 3)
print(f"พื้นที่: {a}")
print(f"เส้นรอบรูป: {p}")</pre>

<b>ผลลัพธ์:</b>
<pre>พื้นที่: 15
เส้นรอบรูป: 16</pre>`,
    hints: [
      `💡 ใน Python สร้าง function ด้วยคำสั่งอะไร? แล้วรับค่าเข้ามาทำยังไง?`,
      `🔶 def ชื่อfunction(ตัวแปร): → ข้างในใช้ print แสดงผลลัพธ์แต่ละแบบ`,
      `🔴 def calculate(a, b): → print(a+b) → print(a-b) → print(a*b) → เรียกใช้ calculate(10, 3)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('def')) return "ต้องสร้าง function ด้วยคำสั่ง def!";
      if (!code.includes('calculate')) return "function ต้องชื่อ calculate!";
      if (!code.match(/def\s+calculate\s*\(/)) return "ต้องสร้าง def calculate(a, b): ให้ถูกรูปแบบ!";
      if (!code.includes('calculate(10')) return "ต้องเรียกใช้ function ด้วย calculate(10, 3)!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l => l.trim());
      return lines.length >= 3 && lines[0] === "13" && lines[1] === "7" && lines[2] === "30";
    },
  },
  {
    id: 40,
    name: "ค่า Default",
    icon: "⚙️",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 7,
    type: "code",
    xp: 150,
    skill: "default parameter",
    character: "👩‍🚀",
    story: `ระบบทักทายยานอวกาศมีค่า default<br><span class="highlight">ถ้าไม่ใส่ชื่อ ให้ทักทาย "นักบินอวกาศ"</span>`,
    mission: `สร้าง function <code>greet(name="นักบินอวกาศ")</code><br>ที่แสดง <code>สวัสดี [name]!</code><br><br>เรียก 2 ครั้ง:<br>1. <code>greet("สมชาย")</code><br>2. <code>greet()</code> (ไม่ใส่ค่า)`,
    expected: `สวัสดี สมชาย!\nสวัสดี นักบินอวกาศ!`,
    starter: `# ระบบทักทายด้วย default parameter\n`,
    successMsg: `Default parameter ทำงานสมบูรณ์! ทำให้ function ยืดหยุ่นขึ้นมาก!`,
    tutorialTitle: `Default Parameter — ค่าเริ่มต้นของ parameter`,
    tutorialContent: `กำหนดค่า default ให้ parameter เผื่อเวลาเรียกใช้ไม่ใส่ค่า

<b>ตัวอย่าง:</b>
<pre>def power(base, exp=2):
    return base ** exp

print(power(3))     # 9  (ใช้ exp=2 default)
print(power(3, 3))  # 27 (กำหนด exp=3)
print(power(2, 10)) # 1024</pre>

<b>สิ่งสำคัญ:</b>
• Parameter ที่มี default ต้องอยู่<b>หลัง</b> parameter ที่ไม่มี default
• <code>def f(a, b=10)</code> ✅ / <code>def f(a=10, b)</code> ❌`,
    hints: [
      `💡 default parameter คือค่าที่กำหนดไว้ให้ เผื่อเวลาเรียกใช้ไม่ใส่ค่า`,
      `🔶 def greet(name="นักบินอวกาศ"): → print(f"สวัสดี {name}!")`,
      `🔴 def greet(name="นักบินอวกาศ"):\n    print(f"สวัสดี {name}!")\ngreet("สมชาย")\ngreet()`,
    ],
    codeCheck: (code) => {
      if (!code.includes('def')) return "ต้องสร้าง function ด้วย def!";
      if (!code.includes('greet')) return "function ต้องชื่อ greet!";
      if (!code.includes('=') || !code.match(/def.*=.*:/)) return "ต้องกำหนดค่า default ให้ parameter!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 2 && lines[0].includes("สมชาย") && lines[1].includes("นักบินอวกาศ");
    },
  },
  {
    id: 41,
    name: "Return หลายค่า",
    icon: "📦",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "Chef",
    zone: 7,
    type: "code",
    xp: 160,
    skill: "return multiple",
    character: "👨‍🍳",
    story: `เชฟต้องสร้าง function วิเคราะห์ราคาอาหาร<br><span class="highlight">return ค่าหลายตัวพร้อมกัน</span>`,
    mission: `สร้าง function <code>analyze(prices)</code> ที่รับ list ราคา<br>แล้ว return 3 ค่า: ค่าต่ำสุด, ค่าสูงสุด, ค่าเฉลี่ย<br><br>ทดสอบด้วย <code>[100, 200, 150, 300, 50]</code><br>แสดง: <code>ต่ำสุด: 50 สูงสุด: 300 เฉลี่ย: 160.0</code>`,
    expected: `ต่ำสุด: 50 สูงสุด: 300 เฉลี่ย: 160.0`,
    starter: `# วิเคราะห์ราคาอาหาร\n`,
    successMsg: `วิเคราะห์สำเร็จ! return หลายค่าเป็นจุดแข็งของ Python!`,
    tutorialTitle: `Return หลายค่า — ใช้ Tuple`,
    tutorialContent: `Python สามารถ return หลายค่าจาก function ได้ (เป็น tuple)

<b>ตัวอย่าง:</b>
<pre>def stats(numbers):
    return min(numbers), max(numbers), sum(numbers) / len(numbers)

data = [10, 20, 30, 40, 50]
lo, hi, avg = stats(data)
print(f"ต่ำสุด: {lo}")
print(f"สูงสุด: {hi}")
print(f"เฉลี่ย: {avg}")</pre>

<b>ผลลัพธ์:</b>
<pre>ต่ำสุด: 10
สูงสุด: 50
เฉลี่ย: 30.0</pre>`,
    hints: [
      `💡 function สามารถ return หลายค่าพร้อมกันได้ — return a, b, c`,
      `🔶 return min(prices), max(prices), sum(prices)/len(prices)`,
      `🔴 def analyze(prices):\n    return min(prices), max(prices), sum(prices)/len(prices)\nlo, hi, avg = analyze([100,200,150,300,50])\nprint(f"ต่ำสุด: {lo} สูงสุด: {hi} เฉลี่ย: {avg}")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('def')) return "ต้องสร้าง function ด้วย def!";
      if (!code.includes('return')) return "function ต้อง return ค่า!";
      if (!code.includes('analyze')) return "function ต้องชื่อ analyze!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("50") && last.includes("300") && last.includes("160.0");
    },
  },
  {
    id: 42,
    name: "Lambda Express",
    icon: "⚡",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 7,
    type: "code",
    xp: 160,
    skill: "lambda",
    character: "🤖",
    story: `Matrix ต้องการ function สั้นๆ แบบบรรทัดเดียว<br><span class="highlight">ใช้ lambda สร้าง function ที่กระชับ</span>`,
    mission: `สร้าง lambda function ชื่อ <code>square</code> ที่ยกกำลัง 2<br>แล้วใช้มันกับ list <code>[1, 2, 3, 4, 5]</code> ผ่าน <code>list(map(...))</code><br>แสดงผล`,
    expected: `[1, 4, 9, 16, 25]`,
    starter: `# Lambda function\n`,
    successMsg: `Lambda + map สำเร็จ! นี่คือ functional programming แบบ Python!`,
    tutorialTitle: `Lambda — ฟังก์ชันแบบย่อ + map/filter`,
    tutorialContent: `<code>lambda</code> สร้าง function สั้นๆ ในบรรทัดเดียว ใช้ร่วมกับ <code>map()</code> และ <code>filter()</code>

<b>รูปแบบ:</b>
<pre>lambda parameters: expression</pre>

<b>ตัวอย่าง:</b>
<pre># lambda function
double = lambda x: x * 2
print(double(5))  # 10

# map — ใช้ function กับทุกตัวใน list
nums = [1, 2, 3, 4]
doubled = list(map(lambda x: x*2, nums))
print(doubled)  # [2, 4, 6, 8]

# filter — เอาเฉพาะที่ผ่านเงื่อนไข
evens = list(filter(lambda x: x%2==0, nums))
print(evens)  # [2, 4]</pre>`,
    hints: [
      `💡 lambda x: x**2 คือ function ที่รับ x แล้ว return x ยกกำลัง 2`,
      `🔶 square = lambda x: x**2 → list(map(square, [1,2,3,4,5]))`,
      `🔴 square = lambda x: x**2\nprint(list(map(square, [1, 2, 3, 4, 5])))`,
    ],
    codeCheck: (code) => {
      if (!code.includes('lambda')) return "ต้องใช้ lambda ในการสร้าง function!";
      if (!code.includes('map')) return "ต้องใช้ map() ในการ apply function กับ list!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim() === "[1, 4, 9, 16, 25]" ,
  },
  {
    id: 43,
    name: "Scope ตัวแปร",
    icon: "🔍",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 7,
    type: "choice",
    xp: 120,
    skill: "local vs global",
    character: "🕵️",
    story: `นักสืบพบโค้ดที่มีตัวแปรชื่อซ้ำกัน<br><span class="highlight">ต้องวิเคราะห์ว่า scope ของตัวแปรเป็นอย่างไร</span>`,
    mission: `โค้ดนี้จะแสดงผลอะไร?<br><br><code>x = 10<br>def change():<br>&nbsp;&nbsp;&nbsp;&nbsp;x = 20<br>&nbsp;&nbsp;&nbsp;&nbsp;print(x)<br>change()<br>print(x)</code>`,
    successMsg: `ถูกต้อง! ตัวแปรใน function เป็น local — ไม่กระทบตัวแปร global!`,
    tutorialTitle: `Scope — ขอบเขตตัวแปร (Local vs Global)`,
    tutorialContent: `ตัวแปรที่สร้างใน function เป็น <b>local</b> — มีผลแค่ภายใน function

<b>ตัวอย่าง:</b>
<pre>name = "Global"  # ตัวแปร global

def greet():
    name = "Local"   # ตัวแปร local (คนละตัวกับข้างนอก!)
    print(f"ใน function: {name}")

greet()          # ใน function: Local
print(f"นอก function: {name}")  # นอก function: Global</pre>

<b>กฎ:</b>
• ตัวแปรใน function = <b>local</b> (เห็นแค่ใน function)
• ตัวแปรนอก function = <b>global</b> (เห็นได้ทุกที่)
• <code>global x</code> ใช้ประกาศว่าจะใช้ตัวแปร global ใน function (แต่ไม่แนะนำ)`,
    hints: [
      `💡 ตัวแปรใน function เป็น local scope — มีผลแค่ภายใน function`,
      `🔶 x = 20 ข้างใน function เป็นตัวแปร local ไม่ใช่ตัวเดียวกับ x = 10 ข้างนอก`,
      `🔴 change() → print(20) / print(x) ข้างนอก → print(10)`,
    ],
    choices: [
      { text: '20\n20', correct: false },
      { text: '20\n10', correct: true },
      { text: '10\n10', correct: false },
      { text: 'Error', correct: false },
    ],
  },
  {
    id: 44,
    name: "Recursive นับถอยหลัง",
    icon: "🔄",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 7,
    type: "code",
    xp: 180,
    skill: "recursion",
    character: "🧙‍♂️",
    story: `ดันเจี้ยนมีกลไกที่ต้องใช้ recursive function<br><span class="highlight">function เรียกตัวเองจนกว่าจะถึง base case</span>`,
    mission: `สร้าง function <code>countdown(n)</code> ที่:<br>- ถ้า n <= 0 → print "เริ่ม!" แล้วหยุด<br>- ไม่งั้น → print n แล้วเรียก countdown(n-1)<br><br>เรียก <code>countdown(3)</code>`,
    expected: `3\n2\n1\nเริ่ม!`,
    starter: `# Recursive countdown\n`,
    successMsg: `Recursion สำเร็จ! เป็นแนวคิดสำคัญใน Computer Science!`,
    tutorialTitle: `Recursion — ฟังก์ชันเรียกตัวเอง`,
    tutorialContent: `Recursion คือ function ที่เรียกตัวเองซ้ำ จนกว่าจะถึง <b>base case</b>

<b>ตัวอย่าง — Factorial:</b>
<pre>def factorial(n):
    if n <= 1:        # base case (จุดหยุด)
        return 1
    return n * factorial(n - 1)  # เรียกตัวเอง

print(factorial(5))  # 120</pre>

<b>การทำงาน:</b>
<pre>factorial(5)
= 5 * factorial(4)
= 5 * 4 * factorial(3)
= 5 * 4 * 3 * factorial(2)
= 5 * 4 * 3 * 2 * factorial(1)
= 5 * 4 * 3 * 2 * 1 = 120</pre>

<b>สิ่งสำคัญ:</b> ต้องมี base case เสมอ ไม่งั้นจะวนไม่สิ้นสุด!`,
    hints: [
      `💡 Recursion = function เรียกตัวเอง / ต้องมี base case เพื่อหยุด`,
      `🔶 def countdown(n): if n <= 0: print("เริ่ม!") / else: print(n) → countdown(n-1)`,
      `🔴 def countdown(n):\n    if n <= 0:\n        print("เริ่ม!")\n    else:\n        print(n)\n        countdown(n-1)\ncountdown(3)`,
    ],
    codeCheck: (code) => {
      if (!code.includes('def')) return "ต้องสร้าง function ด้วย def!";
      if (!code.includes('countdown')) return "function ต้องชื่อ countdown!";
      const callCount = (code.match(/countdown\s*\(/g) || []).length;
      if (callCount < 2) return "ต้องเรียก countdown() ภายใน function ด้วย (recursion)!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 4 && lines[0]==="3" && lines[1]==="2" && lines[2]==="1" && lines[3].includes("เริ่ม");
    },
  },

  // ===== ZONE 8: 🏆 Zone 8 — ขั้นสูง + Boss (class, file, try-except, Boss!) =====
  {
    id: 45,
    name: "จัดการ Error",
    icon: "🛡️",
    theme: "space",
    themeColor: "#a855f7",
    themeName: "Space",
    zone: 8,
    type: "code",
    xp: 170,
    skill: "try-except",
    character: "👩‍🚀",
    story: `ระบบยานอวกาศต้องรับมือกับ error ได้<br><span class="highlight">ใช้ try-except จัดการเมื่อผู้ใช้ใส่ค่าผิดประเภท</span>`,
    mission: `เขียนโปรแกรมรับตัวเลขจากผู้ใช้ แล้วหาร 100 ด้วยตัวเลขนั้น<br>- ถ้าป้อนค่าไม่ใช่ตัวเลข → <code>กรุณาป้อนตัวเลข</code><br>- ถ้าป้อน 0 → <code>หารด้วย 0 ไม่ได้</code><br>- ถ้าถูกต้อง → แสดงผลลัพธ์<br><br>ทดสอบด้วย: <code>4</code> → <code>25.0</code>`,
    expected: `25.0`,
    starter: `# จัดการ error ด้วย try-except\n`,
    successMsg: `try-except ทำงานสมบูรณ์! การจัดการ error ทำให้โปรแกรมมั่นคง!`,
    tutorialTitle: `try-except — จัดการ Error`,
    tutorialContent: `<code>try-except</code> ใช้ดักจับ error ไม่ให้โปรแกรมหยุดทำงาน

<b>รูปแบบ:</b>
<pre>try:
    โค้ดที่อาจ error
except ชนิดError:
    จัดการเมื่อเกิด error</pre>

<b>ตัวอย่าง:</b>
<pre>try:
    num = int(input("ป้อนเลข: "))
    result = 10 / num
    print(f"ผลลัพธ์: {result}")
except ValueError:
    print("ต้องป้อนตัวเลข!")
except ZeroDivisionError:
    print("หารด้วย 0 ไม่ได้!")</pre>

<b>Error ที่พบบ่อย:</b>
• <code>ValueError</code> — แปลงชนิดข้อมูลไม่ได้
• <code>ZeroDivisionError</code> — หารด้วย 0
• <code>KeyError</code> — ไม่มี key ใน dict
• <code>IndexError</code> — index เกินขอบเขต list`,
    hints: [
      `💡 try-except ใช้ดักจับ error — except ValueError ดัก input ผิดประเภท`,
      `🔶 try: n = float(input()) ... except ValueError: ... except ZeroDivisionError: ...`,
      `🔴 try:\n    n = float(input())\n    print(100/n)\nexcept ValueError:\n    print("กรุณาป้อนตัวเลข")\nexcept ZeroDivisionError:\n    print("หารด้วย 0 ไม่ได้")`,
    ],
    testInputs: ["4"],
    codeCheck: (code) => {
      if (!code.includes('try')) return "ต้องใช้ try ในการดักจับ error!";
      if (!code.includes('except')) return "ต้องใช้ except ในการจัดการ error!";
      if (!code.includes('input')) return "ต้องใช้ input() รับค่าจากผู้ใช้!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return parseFloat(last) === 25.0 || last === "25.0";
    },
  },
  {
    id: 46,
    name: "สร้าง Class นักรบ",
    icon: "⚔️",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "Dungeon",
    zone: 8,
    type: "code",
    xp: 200,
    skill: "class + __init__",
    character: "🧙‍♂️",
    story: `ดันเจี้ยนต้องการระบบสร้างนักรบ<br><span class="highlight">ใช้ class สร้าง blueprint สำหรับนักรบ</span>`,
    mission: `สร้าง class <code>Warrior</code> ที่มี:<br>- <code>__init__(self, name, hp)</code><br>- method <code>status(self)</code> ที่ print <code>[name] HP:[hp]</code><br><br>สร้างนักรบ <code>Warrior("อาทิตย์", 100)</code> แล้วเรียก <code>.status()</code>`,
    expected: `อาทิตย์ HP:100`,
    starter: `# สร้าง class นักรบ\n`,
    successMsg: `สร้าง class สำเร็จ! OOP เป็นแนวคิดสำคัญในการเขียนโปรแกรมขนาดใหญ่!`,
    tutorialTitle: `Class — สร้าง blueprint ของวัตถุ`,
    tutorialContent: `<code>class</code> เป็น blueprint สำหรับสร้างวัตถุ (object) ที่มีทั้งข้อมูลและฟังก์ชัน

<b>ตัวอย่าง:</b>
<pre>class Dog:
    def __init__(self, name, breed):
        self.name = name     # attribute
        self.breed = breed

    def bark(self):          # method
        print(f"{self.name}: โฮ่งๆ!")

    def info(self):
        print(f"{self.name} สายพันธุ์ {self.breed}")

# สร้าง object
dog1 = Dog("บัดดี้", "Golden")
dog1.bark()   # บัดดี้: โฮ่งๆ!
dog1.info()   # บัดดี้ สายพันธุ์ Golden</pre>

<b>คำสำคัญ:</b>
• <code>__init__</code> = constructor (ทำงานตอนสร้าง object)
• <code>self</code> = อ้างถึงตัว object เอง`,
    hints: [
      `💡 class สร้างด้วย class ชื่อ: แล้ว def __init__(self, ...): เป็น constructor`,
      `🔶 class Warrior:\n    def __init__(self, name, hp): → self.name = name ...`,
      `🔴 class Warrior:\n    def __init__(self, name, hp):\n        self.name = name\n        self.hp = hp\n    def status(self):\n        print(f"{self.name} HP:{self.hp}")\nw = Warrior("อาทิตย์", 100)\nw.status()`,
    ],
    codeCheck: (code) => {
      if (!code.includes('class')) return "ต้องใช้ class ในการสร้าง blueprint!";
      if (!code.includes('Warrior')) return "class ต้องชื่อ Warrior!";
      if (!code.includes('__init__')) return "ต้องมี __init__ เป็น constructor!";
      if (!code.includes('self')) return "ต้องใช้ self อ้างอิงถึง instance!";
      if (!code.includes('def status')) return "ต้องสร้าง method status!";
      return null;
    },
    validate: (output) => {
      const last = output.trim().split('\n').pop().trim();
      return last.includes("อาทิตย์") && last.includes("HP:100");
    },
  },
  {
    id: 47,
    name: "อ่าน-เขียนไฟล์",
    icon: "📄",
    theme: "detective",
    themeColor: "#3b82f6",
    themeName: "Detective",
    zone: 8,
    type: "code",
    xp: 180,
    skill: "file I/O",
    character: "🕵️",
    story: `นักสืบต้องบันทึกหลักฐานลงไฟล์<br><span class="highlight">ใช้ open() เขียนและอ่านไฟล์</span>`,
    mission: `เขียนข้อความ 3 บรรทัดลงไฟล์ "evidence.txt":<br><code>หลักฐาน 1</code><br><code>หลักฐาน 2</code><br><code>หลักฐาน 3</code><br>แล้วอ่านกลับมาแสดง`,
    expected: `หลักฐาน 1\nหลักฐาน 2\nหลักฐาน 3`,
    starter: `# บันทึกและอ่านหลักฐาน\n`,
    successMsg: `อ่าน-เขียนไฟล์สำเร็จ! File I/O เป็นทักษะพื้นฐานที่ขาดไม่ได้!`,
    tutorialTitle: `File I/O — อ่านและเขียนไฟล์`,
    tutorialContent: `ใช้ <code>open()</code> เปิดไฟล์ แนะนำใช้ <code>with</code> เพื่อปิดไฟล์อัตโนมัติ

<b>เขียนไฟล์:</b>
<pre>with open("note.txt", "w") as f:
    f.write("บรรทัด 1\n")
    f.write("บรรทัด 2\n")</pre>

<b>อ่านไฟล์:</b>
<pre>with open("note.txt", "r") as f:
    content = f.read()
    print(content)</pre>

<b>Mode:</b>
• <code>"w"</code> = เขียน (ทับของเดิม)
• <code>"r"</code> = อ่าน
• <code>"a"</code> = เขียนต่อท้าย
• <code>"r+"</code> = อ่าน+เขียน

<b>สิ่งสำคัญ:</b> ใช้ <code>with</code> ดีกว่า <code>open/close</code> เพราะปิดไฟล์อัตโนมัติ!`,
    hints: [
      `💡 open("file", "w") เปิดเขียน / open("file", "r") เปิดอ่าน / ใช้ with ดีกว่า`,
      `🔶 with open("evidence.txt", "w") as f: f.write(...) แล้ว with open("evidence.txt", "r") as f: print(f.read())`,
      `🔴 with open("evidence.txt", "w") as f:\n    f.write("หลักฐาน 1\nหลักฐาน 2\nหลักฐาน 3")\nwith open("evidence.txt", "r") as f:\n    print(f.read())`,
    ],
    codeCheck: (code) => {
      if (!code.includes('open')) return "ต้องใช้ open() ในการเปิดไฟล์!";
      if (!code.includes('w') && !code.includes('write')) return "ต้องเขียนลงไฟล์ด้วย mode 'w'!";
      if (!code.includes('r') && !code.includes('read')) return "ต้องอ่านไฟล์ด้วย mode 'r'!";
      return null;
    },
    validate: (output) => {
      const text = output.trim();
      return text.includes("หลักฐาน 1") && text.includes("หลักฐาน 2") && text.includes("หลักฐาน 3");
    },
  },
  {
    id: 48,
    name: "Import Module",
    icon: "📚",
    theme: "matrix",
    themeColor: "#22c55e",
    themeName: "Matrix",
    zone: 8,
    type: "code",
    xp: 150,
    skill: "import + math",
    character: "🤖",
    story: `Matrix ต้องการคำนวณทางคณิตศาสตร์ขั้นสูง<br><span class="highlight">ใช้ module math ช่วยคำนวณ</span>`,
    mission: `ใช้ module <code>math</code> คำนวณ:<br>1. รากที่สองของ 144<br>2. ค่า pi ปัดทศนิยม 2 ตำแหน่ง<br>3. 2 ยกกำลัง 10`,
    expected: `12.0\n3.14\n1024`,
    starter: `# ใช้ module math\n`,
    successMsg: `Import สำเร็จ! Modules ขยายความสามารถของ Python ได้ไม่จำกัด!`,
    tutorialTitle: `import — นำเข้า Module`,
    tutorialContent: `<code>import</code> นำเข้า module ที่มีฟังก์ชันเพิ่มเติม

<b>ตัวอย่าง module math:</b>
<pre>import math

print(math.pi)        # 3.141592653589793
print(math.sqrt(16))  # 4.0
print(math.pow(2, 8)) # 256.0
print(math.ceil(3.2)) # 4 (ปัดขึ้น)
print(math.floor(3.8)) # 3 (ปัดลง)</pre>

<b>วิธี import:</b>
<pre>import math              # ใช้ math.sqrt()
from math import sqrt    # ใช้ sqrt() ได้เลย
from math import *       # import ทุกอย่าง (ไม่แนะนำ)
import math as m         # ใช้ m.sqrt()</pre>

<b>Module อื่นที่น่าสนใจ:</b> random, datetime, os, json`,
    hints: [
      `💡 import math แล้วใช้ math.sqrt(), math.pi, math.pow()`,
      `🔶 math.sqrt(144) = 12.0 / round(math.pi, 2) = 3.14 / int(math.pow(2, 10)) = 1024`,
      `🔴 import math\nprint(math.sqrt(144))\nprint(round(math.pi, 2))\nprint(int(math.pow(2, 10)))`,
    ],
    codeCheck: (code) => {
      if (!code.includes('import')) return "ต้องใช้ import ในการนำเข้า module!";
      if (!code.includes('math')) return "ต้อง import math module!";
      if (!code.includes('sqrt') && !code.includes('pi') && !code.includes('pow')) return "ต้องใช้ function จาก math เช่น sqrt, pi, pow!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 3 && lines[0] === "12.0" && lines[1] === "3.14" && lines[2] === "1024";
    },
  },
  {
    id: 49,
    name: "⚔️ Mini Boss: ระบบจัดการร้าน",
    icon: "🏪",
    theme: "chef",
    themeColor: "#f59e0b",
    themeName: "⚔️ Mini Boss!",
    zone: 8,
    type: "code",
    xp: 220,
    skill: "dict + function + loop",
    character: "👨‍🍳",
    story: `Mini Boss! ต้องสร้างระบบจัดการร้านอาหาร<br><span class="highlight">รวม dict, function และ loop เข้าด้วยกัน</span>`,
    mission: `สร้าง function <code>total_order(items)</code> ที่:<br>- รับ dict เช่น <code>{"ข้าวผัด": 2, "ต้มยำ": 1, "ส้มตำ": 3}</code><br>- ราคา: ข้ามผัด=60, ต้มยำ=80, ส้มตำ=45<br>- คำนวณราคารวม = จำนวน x ราคา ของแต่ละเมนู<br>- แสดงรายการแล้วแสดงราคารวม<br><br>ผลลัพธ์ 4 บรรทัด: รายการแต่ละเมนู + ราคารวม`,
    expected: `ข้าวผัด x2 = 120\nต้มยำ x1 = 80\nส้มตำ x3 = 135\nรวม 335`,
    starter: `# ระบบจัดการร้านอาหาร\n`,
    successMsg: `Mini Boss ถูกปราบ! คุณรวม dict + function + loop ได้อย่างเชี่ยวชาญ!`,
    tutorialTitle: `รวมทักษะ: Dict + Function + Loop`,
    tutorialContent: `ในโปรแกรมจริง เราต้องรวมหลายทักษะเข้าด้วยกัน

<b>ตัวอย่าง — ระบบคำนวณเกรด:</b>
<pre>def calc_grade(score):
    if score >= 80: return "A"
    elif score >= 70: return "B"
    elif score >= 60: return "C"
    else: return "F"

students = {"สมชาย": 85, "สมหญิง": 72, "สมศรี": 55}

for name, score in students.items():
    grade = calc_grade(score)
    print(f"{name}: {score} คะแนน → เกรด {grade}")</pre>

<b>ผลลัพธ์:</b>
<pre>สมชาย: 85 คะแนน → เกรด A
สมหญิง: 72 คะแนน → เกรด B
สมศรี: 55 คะแนน → เกรด F</pre>`,
    hints: [
      `💡 สร้าง dict ราคา แล้ววนลูป items.items() คำนวณแต่ละเมนู`,
      `🔶 prices = {"ข้าวผัด": 60, "ต้มยำ": 80, "ส้มตำ": 45} → for name, qty in items.items(): ...`,
      `🔴 def total_order(items):\n    prices = {"ข้าวผัด": 60, "ต้มยำ": 80, "ส้มตำ": 45}\n    total = 0\n    for name, qty in items.items():\n        cost = prices[name] * qty\n        print(f"{name} x{qty} = {cost}")\n        total += cost\n    print(f"รวม {total}")`,
    ],
    codeCheck: (code) => {
      if (!code.includes('def')) return "ต้องสร้าง function!";
      if (!code.includes('total_order')) return "function ต้องชื่อ total_order!";
      if (!code.includes('for')) return "ต้องใช้ for loop วนรายการ!";
      if (!code.includes('.items()')) return "ต้องใช้ .items() ในการวน dict!";
      return null;
    },
    validate: (output) => {
      const lines = output.trim().split('\n').map(l=>l.trim());
      return lines.length >= 4 && lines[0].includes("120") && lines[3].includes("335");
    },
  },
  {
    id: 50,
    name: "🐉 FINAL BOSS: ปราบมังกร",
    icon: "🐉",
    theme: "dungeon",
    themeColor: "#ff6b6b",
    themeName: "🐉 Final Boss!",
    zone: 8,
    type: "code",
    xp: 300,
    skill: "รวมทุกทักษะ",
    character: "🐉",
    story: `ด่านสุดท้าย! มังกรดิจิทัลยืนขวางทาง!<br><span class="highlight">มังกรมี HP = 200 — นักรบ 5 คนมีพลังต่างกัน</span><br>ใช้ class, function, loop, list, dict, try-except — ทุกอย่างที่เรียนมา!`,
    mission: `สร้าง class <code>Hero</code> ที่มี name, power<br>สร้าง method <code>attack(self)</code>:<br>- ถ้า power > 30 → return power * 2 (คริติคอล!)<br>- ไม่งั้น return power<br><br>นักรบ: <code>[("อาทิตย์",40), ("มานี",25), ("วีระ",50), ("สมหญิง",15), ("ธนา",35)]</code><br>มังกร HP = 200<br><br>วนลูปโจมตี — ถ้า HP <= 0 แสดง <code>มังกรพ่ายแพ้!</code> แล้ว break<br>ถ้าหมดนักรบแล้ว HP > 0 แสดง <code>มังกรยังอยู่! HP: [จำนวน]</code>`,
    expected: `มังกรพ่ายแพ้!`,
    starter: `# 🐉 FINAL BOSS: ปราบมังกรดิจิทัล!\n# ใช้ทุกสิ่งที่เรียนมา!\n`,
    successMsg: `🎉🎉🎉 มังกรพ่ายแพ้! คุณคือ Python Master ตัวจริง! คุณผ่านครบ 50 ด่าน!`,
    tutorialTitle: `รวมทุกทักษะ: Class + Loop + List + Dict`,
    tutorialContent: `ด่านสุดท้าย! ต้องรวม class, function, loop, list, dict เข้าด้วยกัน

<b>ตัวอย่าง — ระบบ RPG:</b>
<pre>class Character:
    def __init__(self, name, power):
        self.name = name
        self.power = power

    def attack(self):
        if self.power > 30:
            return self.power * 2  # คริติคอล!
        return self.power

heroes = [Character("A", 40), Character("B", 20)]
enemy_hp = 100

for h in heroes:
    dmg = h.attack()
    enemy_hp -= dmg
    print(f"{h.name} โจมตี {dmg} HP เหลือ {enemy_hp}")
    if enemy_hp <= 0:
        print("ชนะ!")
        break</pre>

<b>ผลลัพธ์:</b>
<pre>A โจมตี 80 HP เหลือ 20
B โจมตี 20 HP เหลือ 0
ชนะ!</pre>`,
    hints: [
      `💡 รวม class + method + loop + list + if — เริ่มจากสร้าง class Hero ก่อน`,
      `🔶 class Hero: __init__(name, power) + attack(self) → สร้าง list ของ Hero → วนลูปโจมตี`,
      `🔴 class Hero:\n    def __init__(self, name, power):\n        self.name = name\n        self.power = power\n    def attack(self):\n        if self.power > 30:\n            return self.power * 2\n        return self.power\n\nheroes = [Hero(n,p) for n,p in [("อาทิตย์",40),("มานี",25),("วีระ",50),("สมหญิง",15),("ธนา",35)]]\nhp = 200\nfor h in heroes:\n    hp -= h.attack()\n    if hp <= 0:\n        print("มังกรพ่ายแพ้!")\n        break`,
    ],
    codeCheck: (code) => {
      if (!code.includes('class')) return "ต้องใช้ class สร้าง Hero!";
      if (!code.includes('Hero')) return "class ต้องชื่อ Hero!";
      if (!code.includes('def attack')) return "ต้องสร้าง method attack!";
      if (!code.includes('for')) return "ต้องใช้ for loop วนโจมตี!";
      if (!code.includes('if')) return "ต้องใช้ if เช็คเงื่อนไข!";
      if (!code.includes('return')) return "method attack ต้อง return ค่า!";
      return null;
    },
    validate: (output) => output.trim().split('\n').pop().trim().includes("มังกรพ่ายแพ้") ,
  },
];

// ============================
// GAME STATE
// ============================
let gameState = { currentLevel: 0, xp: 0, completed: [], selectedChoice: -1, hintsUsed: {} };

function saveState() { try { localStorage.setItem('pythonquest_50', JSON.stringify(gameState)); } catch(e){} }
function loadState() { try { const s = localStorage.getItem('pythonquest_50'); if (s) gameState = JSON.parse(s); } catch(e){} }

// ============================
// PYODIDE
// ============================
let pyodide = null;

async function initPyodide() {
  const bar = document.getElementById('loading-bar');
  const text = document.getElementById('loading-text');
  text.textContent = 'กำลังโหลด Python Engine...'; bar.style.width = '20%';
  try {
    pyodide = await loadPyodide({ indexURL: "https://cdn.jsdelivr.net/pyodide/v0.24.1/full/" });
    bar.style.width = '80%'; text.textContent = 'เตรียมระบบ...';
    await new Promise(r => setTimeout(r, 300));
    bar.style.width = '100%'; text.textContent = 'พร้อมแล้ว!';
    await new Promise(r => setTimeout(r, 500));
    document.getElementById('loading-screen').style.opacity = '0';
    setTimeout(() => {
      document.getElementById('loading-screen').style.display = 'none';
      document.getElementById('app').style.display = 'block';
      loadState(); renderStageMap(); updatePlayerUI();
    }, 500);
  } catch(e) {
    text.textContent = 'โหลดไม่สำเร็จ — ลองรีเฟรชหน้าเว็บ';
    bar.style.background = '#ef4444';
  }
}

async function runPythonCode(code, testInputs = []) {
  if (!pyodide) return { output: "Error: Python ยังไม่พร้อม", error: true };

  pyodide.runPython(`
import sys
from io import StringIO
__stdout_capture = StringIO()
sys.stdout = __stdout_capture
  `);

  if (testInputs.length > 0) {
    const inputsJson = JSON.stringify(testInputs);
    pyodide.runPython(`
__input_queue = ${inputsJson}
__input_index = 0
def __mock_input(prompt=""):
    global __input_index
    sys.stdout.write(str(prompt))
    if __input_index < len(__input_queue):
        val = __input_queue[__input_index]
        __input_index += 1
        return str(val)
    return ""
input = __mock_input
    `);
  }

  try {
    pyodide.runPython(code);
    const output = pyodide.runPython("__stdout_capture.getvalue()");
    pyodide.runPython("sys.stdout = sys.__stdout__");
    return { output, error: false };
  } catch(e) {
    pyodide.runPython("sys.stdout = sys.__stdout__");
    let errMsg = e.message || String(e);
    const lines = errMsg.split('\n');
    const relevant = lines.filter(l => !l.includes('pyodide') && !l.includes('JsProxy'));
    return { output: relevant.join('\n') || errMsg, error: true };
  }
}

// ============================
// PARTICLES
// ============================
function createParticles() {
  const c = document.getElementById('particles');
  for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random()*100 + '%';
    p.style.animationDuration = (8+Math.random()*12)+'s';
    p.style.animationDelay = Math.random()*10+'s';
    p.style.width = p.style.height = (2+Math.random()*4)+'px';
    p.style.background = ['#ff6b6b','#4ecdc4','#ffe66d','#a855f7'][Math.floor(Math.random()*4)];
    c.appendChild(p);
  }
}

// ============================
// UI
// ============================
function renderStageMap() {
  const map = document.getElementById('stage-map');
  map.innerHTML = '';
  const zones = [
    { num: 1, label: "🔥 Zone 1 — พื้นฐาน (print, ตัวแปร, input, data types)", cls: "zone1" },
    { num: 2, label: "⚡ Zone 2 — เงื่อนไข (if, elif, else, and/or/not)", cls: "zone2" },
    { num: 3, label: "🔁 Zone 3 — ลูป (for, while, break, continue)", cls: "zone3" },
    { num: 4, label: "📝 Zone 4 — สตริง (slicing, methods, format)", cls: "zone4" },
    { num: 5, label: "📦 Zone 5 — ลิสต์ + ทูเพิล (list, tuple, comprehension)", cls: "zone5" },
    { num: 6, label: "🗂️ Zone 6 — ดิกชันนารี + เซ็ต (dict, set)", cls: "zone6" },
    { num: 7, label: "⚙️ Zone 7 — ฟังก์ชัน (def, return, lambda, recursion)", cls: "zone7" },
    { num: 8, label: "🏆 Zone 8 — ขั้นสูง + Boss (class, file, try-except, Boss!)", cls: "zone8" },
  ];
  zones.forEach(zone => {
    const label = document.createElement('div');
    label.className = `zone-label ${zone.cls}`;
    label.textContent = zone.label;
    map.appendChild(label);
    LEVELS.filter(l => l.zone === zone.num).forEach(level => {
      const isCompleted = gameState.completed.includes(level.id);
      const isUnlocked = level.id === 1 || gameState.completed.includes(level.id - 1);
      const isCurrent = isUnlocked && !isCompleted;
      const node = document.createElement('div');
      node.className = `stage-node ${isCompleted?'completed':''} ${isCurrent?'current':''} ${!isUnlocked?'locked':''}`;
      node.innerHTML = `
        ${isCompleted ? '<div class="stage-check">✅</div>' : ''}
        <div class="stage-icon">${level.icon}</div>
        <div class="stage-num">ด่าน ${level.id}</div>
        <div class="stage-name">${level.name}</div>
        <div class="stage-tag">${level.skill}</div>
      `;
      if (isUnlocked) node.onclick = () => startLevel(level.id);
      map.appendChild(node);
    });
  });
}

function updatePlayerUI() {
  const totalXP = gameState.xp;
  const level = Math.floor(totalXP / 200) + 1;
  const xpInLevel = totalXP % 200;
  const titles = ["มือใหม่","นักเรียน","นักผจญภัย","นักรบ","จอมเวท","ปรมาจารย์","ผู้พิชิต","ราชามังกร","จอมคาถา","Python Master"];
  document.getElementById('xp-display').textContent = `XP: ${xpInLevel} / 200`;
  document.getElementById('xp-bar').style.width = `${(xpInLevel/200)*100}%`;
  document.getElementById('player-level').textContent = `Lv.${level} ${titles[Math.min(level-1, titles.length-1)]}`;
}

// ============================
// GAME FLOW
// ============================
function showScreen(id) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}
function goHome() { showScreen('home-screen'); renderStageMap(); }

function startLevel(id) {
  const level = LEVELS.find(l => l.id === id);
  if (!level) return;
  gameState.currentLevel = id;
  gameState.selectedChoice = -1;

  document.getElementById('game-stage-title').textContent = `ด่าน ${level.id}: ${level.name}`;
  const badge = document.getElementById('game-theme-badge');
  badge.textContent = `${level.icon} ${level.themeName} — ${level.skill}`;
  badge.style.background = `${level.themeColor}22`;
  badge.style.color = level.themeColor;

  const storyBox = document.getElementById('story-box');
  storyBox.className = `story-box theme-${level.theme}`;
  document.getElementById('story-char').textContent = level.character;
  document.getElementById('story-text').innerHTML = level.story;

  // Tutorial
  const tutBox = document.getElementById('tutorial-box');
  if (level.tutorialTitle) {
    tutBox.style.display = 'block';
    document.getElementById('tutorial-title').textContent = level.tutorialTitle;
    document.getElementById('tutorial-content').innerHTML = level.tutorialContent;
  } else {
    tutBox.style.display = 'none';
  }

  document.getElementById('mission-text').innerHTML = level.mission;

  // Expected output
  const expectedBox = document.getElementById('expected-box');
  if (level.expected) {
    expectedBox.style.display = 'block';
    document.getElementById('expected-output').textContent = level.expected;
  } else {
    expectedBox.style.display = 'none';
  }

  // 3-Level Hints
  renderHints(level);

  if (level.type === 'code') {
    document.getElementById('editor-section').style.display = 'block';
    document.getElementById('choice-section').style.display = 'none';
    document.getElementById('code-editor').value = level.starter || '';
    document.getElementById('output-body').textContent = 'รอรันโค้ด...';
    document.getElementById('output-body').className = 'output-body';
  } else {
    document.getElementById('editor-section').style.display = 'none';
    document.getElementById('choice-section').style.display = 'block';
    renderChoices(level.choices);
  }
  showScreen('game-screen');
}

function renderHints(level) {
  const container = document.getElementById('hint-buttons');
  container.innerHTML = '';
  const hintLevels = [
    { label: "💡 คำใบ้เบาๆ", penalty: "ฟรี!", cls: "", boxId: "hint-box-1", textId: "hint-text-1" },
    { label: "🔶 คำใบ้ปานกลาง", penalty: "-10 XP", cls: "level2", boxId: "hint-box-2", textId: "hint-text-2" },
    { label: "🔴 เฉลยแนวทาง", penalty: "-25 XP", cls: "level3", boxId: "hint-box-3", textId: "hint-text-3" },
  ];

  hintLevels.forEach(h => {
    document.getElementById(h.boxId).classList.remove('show');
  });

  if (!level.hints) return;

  level.hints.forEach((hintText, i) => {
    if (i >= hintLevels.length) return;
    const h = hintLevels[i];
    document.getElementById(h.textId).textContent = hintText;

    const btn = document.createElement('button');
    btn.className = `btn-hint ${h.cls}`;
    const used = gameState.hintsUsed[level.id] && gameState.hintsUsed[level.id].includes(i);
    if (used) btn.classList.add('used');
    btn.innerHTML = `${h.label} <span class="xp-penalty">${h.penalty}</span>`;
    btn.onclick = () => toggleHintLevel(level.id, i, h.boxId);
    container.appendChild(btn);
  });
}

function toggleHintLevel(levelId, hintIdx, boxId) {
  const box = document.getElementById(boxId);
  const isShowing = box.classList.contains('show');

  ['hint-box-1','hint-box-2','hint-box-3'].forEach(id => document.getElementById(id).classList.remove('show'));

  if (!isShowing) {
    box.classList.add('show');
    if (!gameState.hintsUsed[levelId]) gameState.hintsUsed[levelId] = [];
    if (!gameState.hintsUsed[levelId].includes(hintIdx)) {
      gameState.hintsUsed[levelId].push(hintIdx);
      const penalties = [0, 10, 25];
      gameState.xp = Math.max(0, gameState.xp - penalties[hintIdx]);
      updatePlayerUI();
      saveState();
    }
  }
}

function renderChoices(choices) {
  const area = document.getElementById('choices-area');
  area.innerHTML = '';
  const labels = ['A','B','C','D'];
  choices.forEach((choice, i) => {
    const btn = document.createElement('div');
    btn.className = 'choice-btn';
    btn.innerHTML = `<div class="choice-label">${labels[i]}</div><div class="choice-text">${choice.text}</div>`;
    btn.onclick = () => selectChoice(i);
    area.appendChild(btn);
  });
}

function selectChoice(idx) {
  gameState.selectedChoice = idx;
  document.querySelectorAll('.choice-btn').forEach((b,i) => b.classList.toggle('selected', i===idx));
}

// ============================
// RUN & SUBMIT
// ============================
async function runCode() {
  const code = document.getElementById('code-editor').value;
  const level = LEVELS.find(l => l.id === gameState.currentLevel);
  const btn = document.getElementById('btn-run');
  btn.disabled = true; btn.textContent = '⏳ กำลังรัน...';
  const result = await runPythonCode(code, level.testInputs || []);
  const ob = document.getElementById('output-body');
  ob.textContent = result.output || '(ไม่มี output)';
  ob.className = `output-body ${result.error ? 'error' : ''}`;
  btn.disabled = false; btn.innerHTML = '▶ รันโค้ด';
}

async function submitCode() {
  const code = document.getElementById('code-editor').value;
  const level = LEVELS.find(l => l.id === gameState.currentLevel);

  if (level.codeCheck) {
    const codeError = level.codeCheck(code);
    if (codeError) {
      showCodeCheckFail(codeError);
      return;
    }
  }

  const result = await runPythonCode(code, level.testInputs || []);
  const ob = document.getElementById('output-body');
  ob.textContent = result.output || '(ไม่มี output)';
  ob.className = `output-body ${result.error ? 'error' : ''}`;
  showResult(!result.error && level.validate(result.output), level);
}

function showCodeCheckFail(reason) {
  const modal = document.getElementById('result-modal');
  const content = document.getElementById('modal-content');
  content.className = 'modal-content fail';
  document.getElementById('modal-icon').textContent = '🚫';
  document.getElementById('modal-title').textContent = 'ใช้ทักษะไม่ครบ!';
  document.getElementById('modal-text').textContent = reason;
  document.getElementById('modal-xp').textContent = 'ต้องใช้ทักษะตามที่กำหนด ไม่ใช่แค่ได้ผลลัพธ์ถูก';
  const btn = document.getElementById('modal-btn');
  btn.textContent = '🔄 กลับไปแก้โค้ด'; btn.className = 'btn-retry'; btn.onclick = closeModal;
  modal.classList.add('show');
}

function submitChoice() {
  const level = LEVELS.find(l => l.id === gameState.currentLevel);
  if (gameState.selectedChoice < 0) return;
  const correct = level.choices[gameState.selectedChoice].correct;
  const btns = document.querySelectorAll('.choice-btn');
  level.choices.forEach((c,i) => {
    if (c.correct) btns[i].classList.add('correct');
    else if (i === gameState.selectedChoice) btns[i].classList.add('wrong');
  });
  setTimeout(() => {
    showResult(correct, level);
    btns.forEach(b => b.classList.remove('correct','wrong','selected'));
  }, 800);
}

function showResult(success, level) {
  const modal = document.getElementById('result-modal');
  const content = document.getElementById('modal-content');
  const LAST_LEVEL = LEVELS[LEVELS.length - 1].id;
  if (success) {
    content.className = 'modal-content';
    document.getElementById('modal-icon').textContent = level.id === LAST_LEVEL ? '🏆' : '🎉';
    document.getElementById('modal-title').textContent = 'ผ่านด่าน!';
    document.getElementById('modal-text').textContent = level.successMsg;
    let earnedXp = level.xp;
    document.getElementById('modal-xp').textContent = `+${earnedXp} XP`;
    if (!gameState.completed.includes(level.id)) {
      gameState.completed.push(level.id);
      gameState.xp += earnedXp;
      saveState(); updatePlayerUI();
    }
    const btn = document.getElementById('modal-btn');
    if (level.id === LAST_LEVEL) {
      btn.textContent = '🏆 ดูหน้าชัยชนะ'; btn.className = 'btn-next';
      btn.onclick = () => { closeModal(); showScreen('victory-screen'); };
    } else {
      btn.textContent = 'ด่านถัดไป →'; btn.className = 'btn-next';
      btn.onclick = () => { closeModal(); startLevel(level.id + 1); };
    }
  } else {
    content.className = 'modal-content fail';
    document.getElementById('modal-icon').textContent = '💥';
    document.getElementById('modal-title').textContent = 'ยังไม่ถูก!';
    document.getElementById('modal-text').textContent = 'ลองรันโค้ดดูผลลัพธ์ก่อน เทียบกับผลที่ต้องการ แล้วแก้ไข — ใช้คำใบ้ได้นะ!';
    document.getElementById('modal-xp').textContent = '';
    const btn = document.getElementById('modal-btn');
    btn.textContent = '🔄 ลองอีกครั้ง'; btn.className = 'btn-retry'; btn.onclick = closeModal;
  }
  modal.classList.add('show');
}

function closeModal() { document.getElementById('result-modal').classList.remove('show'); }

function resetGame() {
  gameState = { currentLevel: 0, xp: 0, completed: [], selectedChoice: -1, hintsUsed: {} };
  saveState(); updatePlayerUI(); goHome();
}

// TAB KEY
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('code-editor').addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
      e.preventDefault();
      const el = e.target;
      const s = el.selectionStart, end = el.selectionEnd;
      el.value = el.value.substring(0,s) + '    ' + el.value.substring(end);
      el.selectionStart = el.selectionEnd = s + 4;
    }
  });
});

createParticles();
initPyodide();
</script>
<script src="auth.js"></script>
</body>
</html>