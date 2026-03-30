<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JavaScript Quest - ผจญภัยแดนโค้ด</title>

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
  <div class="loading-logo">⚡ JavaScript Quest</div>
  <div class="loading-bar-container"><div class="loading-bar" id="loading-bar"></div></div>
  <div class="loading-text" id="loading-text">กำลังเตรียม JavaScript Engine...</div>
</div>

<!-- APP -->
<div class="app-container" id="app">
  <div class="top-bar"><a href="/index.php" style="text-decoration:none;color:#94a1b2;font-family:Prompt,sans-serif;font-size:0.8rem;padding:5px 12px;border-radius:20px;border:1px solid rgba(255,255,255,0.1);white-space:nowrap;transition:all 0.2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a1b2'">← กลับ</a>
    <div class="logo">⚡ JavaScript Quest <span>ผจญภัยแดนโค้ด</span></div>
    <div class="player-info">
      <div class="xp-text" id="xp-display">XP: 0 / 200</div>
      <div class="xp-bar-container"><div class="xp-bar" id="xp-bar"></div></div>
      <div class="level-badge" id="player-level">Lv.1 มือใหม่</div>
    </div>
  </div>

  <!-- HOME -->
  <div class="screen active" id="home-screen">
    <div class="home-screen">
      <div class="home-title">JavaScript Quest</div>
      <div class="home-subtitle">ผจญภัยแดนโค้ด — เรียน JavaScript ผ่าน <strong>50 ด่าน</strong> สุดมัน!</div>
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
          <div class="editor-tabs"><div class="editor-tab active">📄 solution.js</div></div>
          <textarea class="code-editor" id="code-editor" spellcheck="false" placeholder="// เขียนโค้ด JavaScript ที่นี่..."></textarea>
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
      <div class="victory-title">สุดยอด! คุณคือ JavaScript Master!</div>
      <div class="victory-text">
        คุณผ่านครบทั้ง 50 ด่าน! ตอนนี้คุณเข้าใจ JavaScript ตั้งแต่พื้นฐานจนถึงขั้นสูง
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
// GAME DATA
// ============================
const LEVELS = {
    1: {
        name: "console.log ตัวแรก",
        icon: "📢",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "console.log - พิมพ์ข้อความ",
        tutorialContent: `
<h4>console.log คืออะไร?</h4>
<p>console.log() เป็นคำสั่งพื้นฐานที่สุดใน JavaScript สำหรับแสดงข้อความหรือค่าในคอนโซล (console)</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>console.log("สวัสดี");
console.log(42);
console.log(3.14);</pre>

<h4>Output:</h4>
<pre>สวัสดี
42
3.14</pre>

<h4>อธิบาย:</h4>
<ul>
<li>console.log() สามารถพิมพ์ข้อความ (string) ได้</li>
<li>สามารถพิมพ์ตัวเลข (number) ได้</li>
<li>แต่ละบรรทัดจะพิมพ์ค่าใหม่</li>
</ul>
`,
        mission: "พิมพ์คำว่า 'ยินดีต้อนรับ' และตัวเลข 100",
        expectedOutput: "ยินดีต้อนรับ\n100",
        hints: [
            "ใช้ console.log สองครั้ง",
            "บรรทัดแรก: console.log('ยินดีต้อนรับ');",
            "บรรทัดที่สอง: console.log(100);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasLog = code.includes('console.log');
    if (!hasLog) return { pass: false, message: 'ต้องใช้ console.log' };
    const logs = (code.match(/console\.log/g) || []).length;
    if (logs < 2) return { pass: false, message: 'ต้องใช้ console.log อย่างน้อย 2 ครั้ง' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    2: {
        name: "let และ const",
        icon: "📍",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "ประกาศตัวแปรด้วย let และ const",
        tutorialContent: `
<h4>let และ const คืออะไร?</h4>
<p>let และ const ใช้ประกาศตัวแปร (variable) ในโปรแกรม</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let name = "Alice";
const age = 20;
console.log(name);
console.log(age);

name = "Bob";
console.log(name);</pre>

<h4>Output:</h4>
<pre>Alice
20
Bob</pre>

<h4>ความแตกต่าง:</h4>
<ul>
<li>let: ตัวแปรที่เปลี่ยนแปลงได้</li>
<li>const: ค่าคงที่ที่ไม่สามารถเปลี่ยนได้ (ส่วนใหญ่)</li>
</ul>
`,
        mission: "ประกาศตัวแปร name เป็น 'JavaScript' และ points เป็น 50 แล้วพิมพ์ทั้งสองตัวแปร",
        expectedOutput: "JavaScript\n50",
        hints: [
            "ประกาศ name ด้วย let หรือ const",
            "ประกาศ points = 50",
            "ใช้ console.log สองครั้งเพื่อพิมพ์"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasName = code.includes('name') && (code.includes('let') || code.includes('const'));
    const hasPoints = code.includes('points') && code.includes('50');
    if (!hasName || !hasPoints) return { pass: false, message: 'ต้องประกาศตัวแปร name และ points' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    3: {
        name: "typeof - ตรวจสอบชนิดข้อมูล",
        icon: "🔍",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "typeof - ตรวจชนิดข้อมูล",
        tutorialContent: `
<h4>typeof คืออะไร?</h4>
<p>typeof เป็นตัวดำเนินการที่ตรวจสอบชนิดของข้อมูล</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>console.log(typeof "hello");
console.log(typeof 42);
console.log(typeof true);
console.log(typeof undefined);</pre>

<h4>Output:</h4>
<pre>string
number
boolean
undefined</pre>

<h4>ชนิดข้อมูลหลัก:</h4>
<ul>
<li>string: ข้อความ "hello"</li>
<li>number: ตัวเลข 42</li>
<li>boolean: true หรือ false</li>
<li>undefined: ยังไม่มีค่า</li>
<li>object: สิ่งของ</li>
</ul>
`,
        mission: "ตรวจสอบชนิดของ 'world' และ 123 แล้วพิมพ์ผลลัพธ์",
        expectedOutput: "string\nnumber",
        hints: [
            "ใช้ typeof สำหรับ 'world'",
            "console.log(typeof 'world');",
            "console.log(typeof 123);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasTypeof = code.includes('typeof');
    if (!hasTypeof) return { pass: false, message: 'ต้องใช้ typeof' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    4: {
        name: "prompt - รับข้อมูลจากผู้ใช้",
        icon: "❓",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "prompt - รับ input",
        tutorialContent: `
<h4>prompt คืออะไร?</h4>
<p>prompt() เป็นฟังก์ชันที่ขอให้ผู้ใช้ป้อนข้อมูลในกล่องโต้ตอบ</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let name = prompt("ชื่อของคุณคือ?");
console.log("สวัสดี " + name);</pre>

<h4>อธิบาย:</h4>
<ul>
<li>prompt("คำถาม") จะแสดงกล่องสำหรับผู้ใช้</li>
<li>ค่าที่ผู้ใช้ป้อนจะบันทึกในตัวแปร</li>
<li>ถ้ากดยกเลิก จะได้ค่า null</li>
</ul>
`,
        mission: "ขอชื่อผู้ใช้ด้วย prompt แล้วพิมพ์คำว่า 'ชื่อของฉันคือ:' ตามด้วยชื่อนั้น",
        expectedOutput: "ชื่อของฉันคือ: John",
        hints: [
            "ใช้ prompt เพื่อรับชื่อ",
            "เก็บค่าใน let username = prompt(...);",
            "ใช้ console.log กับ concatenation หรือ template literal"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasPrompt = code.includes('prompt');
    if (!hasPrompt) return { pass: false, message: 'ต้องใช้ prompt' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    5: {
        name: "Template Literal - backtick",
        icon: "📄",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "Template Literal - ใช้ backtick",
        tutorialContent: `
<h4>Template Literal คืออะไร?</h4>
<p>Template literal ใช้ backtick (\`) เพื่อสร้างสตริง และสามารถใส่ตัวแปรด้วย \${}</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let name = "Alice";
let age = 25;
console.log(\`ชื่อ: \${name}, อายุ: \${age}\`);
console.log(\`บวก: 5 + 3 = \${5 + 3}\`);</pre>

<h4>Output:</h4>
<pre>ชื่อ: Alice, อายุ: 25
บวก: 5 + 3 = 8</pre>

<h4>ข้อดี:</h4>
<ul>
<li>ใช้ \${} เพื่อใส่ตัวแปร</li>
<li>สะอาดกว่า concatenation (+ +)</li>
<li>สามารถ multiline ได้</li>
</ul>
`,
        mission: "ใช้ template literal เพื่อพิมพ์ 'ฉันชื่อ: [name]' เมื่อ name = 'Tom'",
        expectedOutput: "ฉันชื่อ: Tom",
        hints: [
            "ใช้ backtick (`) แทน quote ธรรมดา",
            "let name = 'Tom'; console.log(`ฉันชื่อ: ${name}`);",
            "ตรวจสอบ ${} ข้างในเครื่องหมาย backtick"
        ],
        codeCheck: `
function codeCheck(code) {
  const hasBacktick = code.includes('\`');
  const hasDollarBrace = code.includes('\${');
  if (!hasBacktick || !hasDollarBrace) return { pass: false, message: 'ต้องใช้ backtick กับ \${}' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    6: {
        name: "Arithmetic - บวก ลบ คูณ หาร",
        icon: "🔢",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "Arithmetic - การคำนวณ",
        tutorialContent: `
<h4>Arithmetic Operators (ตัวดำเนินการทางคณิตศาสตร์)</h4>
<p>ใช้สำหรับการคำนวณพื้นฐาน</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>console.log(10 + 5);
console.log(10 - 3);
console.log(4 * 6);
console.log(20 / 4);
console.log(17 % 5);
console.log(2 ** 3);</pre>

<h4>Output:</h4>
<pre>15
7
24
5
2
8</pre>

<h4>ตัวดำเนินการ:</h4>
<ul>
<li>+ บวก</li>
<li>- ลบ</li>
<li>* คูณ</li>
<li>/ หาร</li>
<li>% เศษ (modulo)</li>
<li>** ยกกำลัง</li>
</ul>
`,
        mission: "คำนวณ 100 - 25 และ 8 * 7 แล้วพิมพ์ผลลัพธ์ทั้งสอง",
        expectedOutput: "75\n56",
        hints: [
            "console.log(100 - 25);",
            "console.log(8 * 7);",
            "ใช้ console.log สองครั้ง"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasMinus = code.includes('-');
    const hasMul = code.includes('*');
    if (!hasMinus || !hasMul) return { pass: false, message: 'ต้องใช้ - และ *' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    7: {
        name: "[CHOICE] สรุป Zone 1",
        icon: "🎯",
        zone: 1,
        color: "#FF6B6B",
        tutorialTitle: "ทบทวน Zone พื้นฐาน",
        tutorialContent: `
<h4>Zone 1 ได้เรียนรู้:</h4>
<ul>
<li>console.log - พิมพ์ข้อมูล</li>
<li>let/const - ประกาศตัวแปร</li>
<li>typeof - ตรวจสอบชนิด</li>
<li>prompt - รับ input</li>
<li>Template literal - สตริงแบบใหม่</li>
<li>Arithmetic - การคำนวณ</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_b",
        hints: [
            "ฝึก console.log กับการประกาศตัวแปร",
            "โจทย์นี้เป็นการทดสอบความเข้าใจ",
            "เลือก choice ที่ถูกต้องจากตัวเลือก"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    8: {
        name: "if - เงื่อนไขพื้นฐาน",
        icon: "❓",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "if - เงื่อนไขพื้นฐาน",
        tutorialContent: `
<h4>if statement คืออะไร?</h4>
<p>ใช้ตรวจสอบเงื่อนไข ถ้าเป็นจริง (true) จะรันโค้ด</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let age = 20;
if (age >= 18) {
    console.log("เป็นผู้ใหญ่");
}</pre>

<h4>Output:</h4>
<pre>เป็นผู้ใหญ่</pre>

<h4>เปรียบเทียบ:</h4>
<ul>
<li>== เท่ากับ (ค่า)</li>
<li>=== เท่ากับ (ค่า + ชนิด)</li>
<li>!= ไม่เท่ากับ</li>
<li>> มากกว่า</li>
<li>< น้อยกว่า</li>
<li>>= มากกว่าหรือเท่ากับ</li>
</ul>
`,
        mission: "ตรวจสอบถ้า score > 80 แล้วพิมพ์ 'ผ่าน'",
        expectedOutput: "ผ่าน",
        hints: [
            "ใช้ if (score > 80)",
            "ตั้ง score = 90 เช่น",
            "console.log('ผ่าน'); ข้างใน if"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasIf = code.includes('if');
    const hasComparison = code.includes('>') || code.includes('<') || code.includes('>=') || code.includes('<=') || code.includes('==');
    if (!hasIf || !hasComparison) return { pass: false, message: 'ต้องใช้ if กับตัวเปรียบเทียบ' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    9: {
        name: "if-else - สองทางเลือก",
        icon: "🔀",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "if-else - ทางเลือก A หรือ B",
        tutorialContent: `
<h4>if-else statement</h4>
<p>if ทำอย่างนี้ / else ทำอย่างนั้น</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let num = 5;
if (num > 0) {
    console.log("บวก");
} else {
    console.log("ไม่บวก");
}</pre>

<h4>Output:</h4>
<pre>บวก</pre>

<h4>โครงสร้าง:</h4>
<ul>
<li>if (เงื่อนไข) { โค้ดถ้าจริง }</li>
<li>else { โค้ดถ้าเท็จ }</li>
</ul>
`,
        mission: "ตรวจ grade: ถ้า >= 50 พิมพ์ 'Pass' ไม่งั้น 'Fail'",
        expectedOutput: "Pass",
        hints: [
            "ตั้ง grade = 60",
            "if (grade >= 50) { console.log('Pass'); } else { console.log('Fail'); }",
            "ตรวจเงื่อนไขให้ถูก"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasIf = code.includes('if');
    const hasElse = code.includes('else');
    if (!hasIf || !hasElse) return { pass: false, message: 'ต้องใช้ if-else' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    10: {
        name: "switch - หลายทางเลือก",
        icon: "🎚️",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "switch - เลือกจากหลายตัวเลือก",
        tutorialContent: `
<h4>switch statement</h4>
<p>เลือกตามค่าเฉพาะ</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let day = 1;
switch (day) {
    case 1:
        console.log("จันทร์");
        break;
    case 2:
        console.log("อังคาร");
        break;
    default:
        console.log("อื่นๆ");
}</pre>

<h4>Output:</h4>
<pre>จันทร์</pre>

<h4>ข้อสำคัญ:</h4>
<ul>
<li>case: ตรวจค่า</li>
<li>break: ออกจาก switch</li>
<li>default: ค่าเริ่มต้น</li>
</ul>
`,
        mission: "ใช้ switch สำหรับ color='red' แล้วพิมพ์ '🔴'",
        expectedOutput: "🔴",
        hints: [
            "let color = 'red';",
            "switch (color) { case 'red': ... }",
            "ใช้ break ในแต่ละ case"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasSwitch = code.includes('switch');
    const hasCase = code.includes('case');
    const hasBreak = code.includes('break');
    if (!hasSwitch || !hasCase || !hasBreak) return { pass: false, message: 'ต้องใช้ switch, case, break' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    11: {
        name: "Ternary - condition ? a : b",
        icon: "⚡",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "Ternary - เงื่อนไขแบบสั้น",
        tutorialContent: `
<h4>Ternary Operator</h4>
<p>เขียน if-else แบบสั้นในบรรทัดเดียว</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let age = 20;
let status = age >= 18 ? "ผู้ใหญ่" : "เด็ก";
console.log(status);</pre>

<h4>Output:</h4>
<pre>ผู้ใหญ่</pre>

<h4>โครงสร้าง:</h4>
<p>เงื่อนไข ? ถ้าจริง : ถ้าเท็จ</p>
`,
        mission: "ใช้ ternary เพื่อตรวจ x < 0 ถ้าจริงพิมพ์ 'ลบ' ไม่งั้น 'บวก'",
        expectedOutput: "ลบ",
        hints: [
            "ตั้ง x = -5",
            "ใช้ x < 0 ? 'ลบ' : 'บวก'",
            "console.log() ผลลัพธ์"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasTernary = code.includes('?') && code.includes(':');
    if (!hasTernary) return { pass: false, message: 'ต้องใช้ ternary (? :)' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    12: {
        name: "Logical Operators - && || !",
        icon: "🔗",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "Logical Operators - และ หรือ ไม่",
        tutorialContent: `
<h4>Logical Operators</h4>
<p>เชื่อมหลายเงื่อนไข</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let age = 20;
let hasLicense = true;
if (age >= 18 && hasLicense) {
    console.log("ขับได้");
}

let isMember = false;
if (isMember || age > 50) {
    console.log("ลด");
}</pre>

<h4>Output:</h4>
<pre>ขับได้</pre>

<h4>ตัวดำเนินการ:</h4>
<ul>
<li>&& AND - ทั้งสองต้องจริง</li>
<li>|| OR - อย่างน้อยหนึ่งต้องจริง</li>
<li>! NOT - กลับค่าจริง/เท็จ</li>
</ul>
`,
        mission: "ตรวจ x > 5 && x < 10 เมื่อ x=7 แล้วพิมพ์ 'ใช่'",
        expectedOutput: "ใช่",
        hints: [
            "ตั้ง x = 7",
            "if (x > 5 && x < 10)",
            "console.log('ใช่');"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasLogical = code.includes('&&') || code.includes('||') || code.includes('!');
    if (!hasLogical) return { pass: false, message: 'ต้องใช้ && || หรือ !' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    13: {
        name: "[CHOICE] สรุป Zone 2",
        icon: "🎯",
        zone: 2,
        color: "#4ECDC4",
        tutorialTitle: "ทบทวน Zone เงื่อนไข",
        tutorialContent: `
<h4>Zone 2 ได้เรียนรู้:</h4>
<ul>
<li>if - เงื่อนไขพื้นฐาน</li>
<li>if-else - สองทางเลือก</li>
<li>switch - หลายทางเลือก</li>
<li>Ternary - เงื่อนไขแบบสั้น</li>
<li>Logical Operators - && || !</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_c",
        hints: [
            "ทบทวน if-else switch logical",
            "คิดว่าควรจะตอบไหน",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    14: {
        name: "for - loop พื้นฐาน",
        icon: "🔁",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "for loop - วนซ้ำ",
        tutorialContent: `
<h4>for loop คืออะไร?</h4>
<p>ใช้สำหรับการวนซ้ำ (loop) โค้ดหลายครั้ง</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>for (let i = 1; i <= 3; i++) {
    console.log(i);
}</pre>

<h4>Output:</h4>
<pre>1
2
3</pre>

<h4>โครงสร้าง:</h4>
<ul>
<li>i = 1: เริ่มต้น</li>
<li>i <= 3: เงื่อนไขเข้าวน</li>
<li>i++: เพิ่มทีละ 1</li>
</ul>
`,
        mission: "พิมพ์ตัวเลข 1 ถึง 5",
        expectedOutput: "1\n2\n3\n4\n5",
        hints: [
            "for (let i = 1; i <= 5; i++)",
            "console.log(i);",
            "ใส่ console.log ไว้ในวง for"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasFor = code.includes('for');
    if (!hasFor) return { pass: false, message: 'ต้องใช้ for loop' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    15: {
        name: "while - while loop",
        icon: "⏳",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "while loop - วนจนกว่าเงื่อนไขเท็จ",
        tutorialContent: `
<h4>while loop</h4>
<p>วนซ้ำตราบเท่าที่เงื่อนไขเป็นจริง</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let i = 1;
while (i <= 3) {
    console.log(i);
    i++;
}</pre>

<h4>Output:</h4>
<pre>1
2
3</pre>

<h4>ข้อสำคัญ:</h4>
<ul>
<li>ต้องมี i++ เพื่อเพิ่มค่า</li>
<li>ถ้าลืมอาจเป็น infinite loop</li>
</ul>
`,
        mission: "ใช้ while พิมพ์ 'A' 4 ครั้ง",
        expectedOutput: "A\nA\nA\nA",
        hints: [
            "ตั้ง counter = 0",
            "while (counter < 4)",
            "counter++"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasWhile = code.includes('while');
    if (!hasWhile) return { pass: false, message: 'ต้องใช้ while loop' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    16: {
        name: "do-while - ทำก่อนเช็ค",
        icon: "▶️",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "do-while - อย่างน้อยทำหนึ่งครั้ง",
        tutorialContent: `
<h4>do-while loop</h4>
<p>ทำก่อน แล้วค่อยเช็คเงื่อนไข (อย่างน้อยทำ 1 ครั้ง)</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let i = 1;
do {
    console.log(i);
    i++;
} while (i <= 2);</pre>

<h4>Output:</h4>
<pre>1
2</pre>

<h4>ความแตกต่าง:</h4>
<p>do-while ทำอย่างน้อย 1 ครั้ง แม้เงื่อนไขเท็จตั้งแต่เริ่ม</p>
`,
        mission: "ใช้ do-while เพื่อพิมพ์ 'X' 3 ครั้ง",
        expectedOutput: "X\nX\nX",
        hints: [
            "do { console.log('X'); counter++; } while (...)",
            "เช็คเงื่อนไข counter < 3",
            "ต้องมี counter++"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasDo = code.includes('do');
    const hasWhile = code.includes('while');
    if (!hasDo || !hasWhile) return { pass: false, message: 'ต้องใช้ do-while' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    17: {
        name: "for...of - วน array/string",
        icon: "🔄",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "for...of - วนทีละตัวในคอลเลกชัน",
        tutorialContent: `
<h4>for...of loop</h4>
<p>วนลูปทีละตัวใน array หรือ string</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2, 3];
for (let num of arr) {
    console.log(num);
}

for (let char of "ABC") {
    console.log(char);
}</pre>

<h4>Output:</h4>
<pre>1
2
3
A
B
C</pre>
`,
        mission: "ใช้ for...of เพื่อวน array [5, 10, 15] และพิมพ์แต่ละตัว",
        expectedOutput: "5\n10\n15",
        hints: [
            "for (let item of [5, 10, 15])",
            "console.log(item);",
            "สะอาดกว่า for loop"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasForOf = code.includes('for') && code.includes('of');
    if (!hasForOf) return { pass: false, message: 'ต้องใช้ for...of' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    18: {
        name: "for...in - วน object keys",
        icon: "🗝️",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "for...in - วน property ของ object",
        tutorialContent: `
<h4>for...in loop</h4>
<p>วนลูปผ่าน keys ของ object</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let obj = {name: "Alice", age: 25};
for (let key in obj) {
    console.log(obj[key]);
}</pre>

<h4>Output:</h4>
<pre>Alice
25</pre>

<h4>ข้อสำคัญ:</h4>
<p>for...in วน keys, for...of วน values</p>
`,
        mission: "ใช้ for...in วน object {x: 10, y: 20} และพิมพ์ค่าแต่ละตัว",
        expectedOutput: "10\n20",
        hints: [
            "let obj = {x: 10, y: 20};",
            "for (let key in obj)",
            "console.log(obj[key]);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasForIn = code.includes('for') && code.includes('in');
    if (!hasForIn) return { pass: false, message: 'ต้องใช้ for...in' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    19: {
        name: "break/continue - ควบคุม loop",
        icon: "⏹️",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "break/continue - ควบคุม loop",
        tutorialContent: `
<h4>break และ continue</h4>
<p>ควบคุมการไหลของ loop</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>for (let i = 1; i <= 5; i++) {
    if (i === 3) break;
    console.log(i);
}

for (let i = 1; i <= 5; i++) {
    if (i === 2) continue;
    console.log(i);
}</pre>

<h4>Output:</h4>
<pre>1
2
1
3
4
5</pre>

<h4>ความแตกต่าง:</h4>
<ul>
<li>break: ออกจาก loop</li>
<li>continue: ข้ามการทำงานนี้ไปวนต่อ</li>
</ul>
`,
        mission: "พิมพ์ 1-5 แต่ข้าม 3 ด้วย continue",
        expectedOutput: "1\n2\n4\n5",
        hints: [
            "for (let i = 1; i <= 5; i++)",
            "if (i === 3) continue;",
            "console.log(i);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasBreakOrContinue = code.includes('break') || code.includes('continue');
    if (!hasBreakOrContinue) return { pass: false, message: 'ต้องใช้ break หรือ continue' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    20: {
        name: "[CHOICE] สรุป Zone 3",
        icon: "🎯",
        zone: 3,
        color: "#45B7D1",
        tutorialTitle: "ทบทวน Zone ลูป",
        tutorialContent: `
<h4>Zone 3 ได้เรียนรู้:</h4>
<ul>
<li>for - loop พื้นฐาน</li>
<li>while - while loop</li>
<li>do-while - ทำก่อนเช็ค</li>
<li>for...of - วน array/string</li>
<li>for...in - วน object keys</li>
<li>break/continue - ควบคุม loop</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_a",
        hints: [
            "คิดถึง loop ทั้งหมด",
            "ทบทวน for while for...of",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    21: {
        name: "length, indexOf",
        icon: "📏",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "length - ความยาว, indexOf - หาตำแหน่ง",
        tutorialContent: `
<h4>String Properties และ Methods</h4>
<p>ทำงานกับสตริง</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let str = "JavaScript";
console.log(str.length);
console.log(str.indexOf("S"));
console.log(str.indexOf("x"));</pre>

<h4>Output:</h4>
<pre>10
4
-1</pre>

<h4>อธิบาย:</h4>
<ul>
<li>length: จำนวนอักษร</li>
<li>indexOf(): ตำแหน่งแรก (0-based), -1 ถ้าไม่พบ</li>
</ul>
`,
        mission: "ตรวจ length ของ 'Hello' และหา indexOf 'l'",
        expectedOutput: "5\n2",
        hints: [
            "let str = 'Hello';",
            "console.log(str.length);",
            "console.log(str.indexOf('l'));"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasLength = code.includes('.length');
    const hasIndexOf = code.includes('.indexOf');
    if (!hasLength || !hasIndexOf) return { pass: false, message: 'ต้องใช้ .length และ .indexOf' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    22: {
        name: "slice, substring",
        icon: "✂️",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "slice/substring - ตัดสตริง",
        tutorialContent: `
<h4>slice() และ substring()</h4>
<p>ตัดสตริงให้ส่วนหนึ่ง</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let str = "JavaScript";
console.log(str.slice(0, 4));
console.log(str.slice(4));
console.log(str.substring(0, 4));</pre>

<h4>Output:</h4>
<pre>Java
Script
Java</pre>

<h4>ข้อแตกต่าง:</h4>
<ul>
<li>slice: รับ negative index ได้</li>
<li>substring: ใช้ 0 เมื่อค่าติดลบ</li>
</ul>
`,
        mission: "ตัด 'Python' ให้ได้ 'Py' และ 'thon'",
        expectedOutput: "Py\nthon",
        hints: [
            "str.slice(0, 2) → 'Py'",
            "str.slice(2) → 'thon'",
            "console.log ทั้งสอง"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasSliceOrSubstring = code.includes('.slice') || code.includes('.substring');
    if (!hasSliceOrSubstring) return { pass: false, message: 'ต้องใช้ .slice หรือ .substring' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    23: {
        name: "toUpperCase, toLowerCase, trim, replace",
        icon: "🔤",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "แปลง string - เล็ก ใหญ่ trim replace",
        tutorialContent: `
<h4>String Transformation Methods</h4>
<p>แปลงสตริง</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>console.log("hello".toUpperCase());
console.log("WORLD".toLowerCase());
console.log("  space  ".trim());
console.log("cat".replace("a", "u"));</pre>

<h4>Output:</h4>
<pre>HELLO
world
space
cut</pre>
`,
        mission: "แปลง 'JavaScript' เป็นตัวเล็ก",
        expectedOutput: "javascript",
        hints: [
            "ใช้ .toLowerCase()",
            "console.log('JavaScript'.toLowerCase());",
            "ออกมา javascript"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasTransform = code.includes('.toUpperCase') || code.includes('.toLowerCase') ||
                         code.includes('.trim') || code.includes('.replace');
    if (!hasTransform) return { pass: false, message: 'ต้องใช้ string transformation' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    24: {
        name: "Template literal advanced",
        icon: "📋",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "Template literal - multiline และ expression",
        tutorialContent: `
<h4>Template Literal - ขั้นสูง</h4>
<p>multiline + expression ข้างในวงเล็บปีกกา</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let x = 5;
let y = 10;
console.log(\`x + y = \${x + y}\`);
console.log(\`Line 1
Line 2
Line 3\`);</pre>

<h4>Output:</h4>
<pre>x + y = 15
Line 1
Line 2
Line 3</pre>
`,
        mission: "ใช้ template literal เพื่อพิมพ์ 'ผลลัพธ์: 3 * 4 = 12'",
        expectedOutput: "ผลลัพธ์: 3 * 4 = 12",
        hints: [
            "ใช้ backtick `",
            "`ผลลัพธ์: 3 * 4 = ${3 * 4}`",
            "expression ข้างใน ${}"
        ],
        codeCheck: `
function codeCheck(code) {
  const hasBacktick = code.includes('\`');
  const hasExpression = code.includes('\${');
    if (!hasBacktick || !hasExpression) return { pass: false, message: 'ต้องใช้ backtick + expression' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    25: {
        name: "split, join",
        icon: "🔗",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "split/join - แยก/รวมสตริง",
        tutorialContent: `
<h4>split() และ join()</h4>
<p>split: แยกสตริง, join: รวม array</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let str = "a,b,c";
let arr = str.split(",");
console.log(arr);
console.log(arr.join("-"));</pre>

<h4>Output:</h4>
<pre>a,b,c
a-b-c</pre>

<h4>หมายเหตุ:</h4>
<ul>
<li>split(delimiter) → array</li>
<li>join(glue) → string</li>
</ul>
`,
        mission: "แยก 'x y z' ด้วย space แล้วพิมพ์ array",
        expectedOutput: "x,y,z",
        hints: [
            "str.split(' ') → array",
            "ส่วน output จะเป็น array format (x,y,z)",
            "console.log ผลลัพธ์"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasSplit = code.includes('.split');
    if (!hasSplit) return { pass: false, message: 'ต้องใช้ .split' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    26: {
        name: "[CHOICE] สรุป Zone 4",
        icon: "🎯",
        zone: 4,
        color: "#FFA502",
        tutorialTitle: "ทบทวน Zone สตริง",
        tutorialContent: `
<h4>Zone 4 ได้เรียนรู้:</h4>
<ul>
<li>length, indexOf</li>
<li>slice, substring</li>
<li>toUpperCase, toLowerCase, trim, replace</li>
<li>Template literal advanced</li>
<li>split, join</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_d",
        hints: [
            "ทบทวน string methods",
            "คิดถึง split/join/slice",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    27: {
        name: "array - push, pop",
        icon: "📦",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "Array - สร้าง push pop",
        tutorialContent: `
<h4>Array Basics</h4>
<p>push: เพิ่มจากท้าย, pop: ลบจากท้าย</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2];
arr.push(3);
console.log(arr);
arr.pop();
console.log(arr);</pre>

<h4>Output:</h4>
<pre>1,2,3
1,2</pre>
`,
        mission: "สร้าง array [10, 20] แล้ว push(30) และพิมพ์",
        expectedOutput: "10,20,30",
        hints: [
            "let arr = [10, 20];",
            "arr.push(30);",
            "console.log(arr);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasPush = code.includes('.push');
    if (!hasPush) return { pass: false, message: 'ต้องใช้ .push' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    28: {
        name: "shift, unshift, splice",
        icon: "🔀",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "Array methods - shift unshift splice",
        tutorialContent: `
<h4>Array Manipulation</h4>
<p>shift: ลบหน้า, unshift: เพิ่มหน้า, splice: ตัด/ใส่</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2, 3];
arr.unshift(0);
console.log(arr);
arr.shift();
console.log(arr);</pre>

<h4>Output:</h4>
<pre>0,1,2,3
1,2,3</pre>
`,
        mission: "unshift(5) ที่หน้า [10, 20] และพิมพ์",
        expectedOutput: "5,10,20",
        hints: [
            "let arr = [10, 20];",
            "arr.unshift(5);",
            "console.log(arr);"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasShiftOrUnshift = code.includes('.shift') || code.includes('.unshift');
    if (!hasShiftOrUnshift) return { pass: false, message: 'ต้องใช้ .shift หรือ .unshift' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    29: {
        name: "for loop + forEach",
        icon: "🔄",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "วนลูป array - for / forEach",
        tutorialContent: `
<h4>Array Iteration</h4>
<p>for loop vs forEach method</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [10, 20, 30];
for (let i = 0; i < arr.length; i++) {
    console.log(arr[i]);
}

arr.forEach((num) => {
    console.log(num);
});</pre>

<h4>Output:</h4>
<pre>10
20
30</pre>
`,
        mission: "ใช้ forEach เพื่อพิมพ์แต่ละตัวใน [5, 10, 15]",
        expectedOutput: "5\n10\n15",
        hints: [
            "arr.forEach((x) => { ... })",
            "console.log(x); ข้างใน callback",
            "หรือใช้ for loop ธรรมดา"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasForEach = code.includes('.forEach') || code.includes('for');
    if (!hasForEach) return { pass: false, message: 'ต้องใช้ forEach หรือ for loop' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    30: {
        name: "map - แปลง array",
        icon: "🗺️",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "map - แปลงแต่ละ element",
        tutorialContent: `
<h4>Array.map()</h4>
<p>แปลงแต่ละ element และสร้าง array ใหม่</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2, 3];
let doubled = arr.map(x => x * 2);
console.log(doubled);</pre>

<h4>Output:</h4>
<pre>2,4,6</pre>
`,
        mission: "ใช้ map เพื่อคูณ 2 ของ [2, 3, 4]",
        expectedOutput: "4,6,8",
        hints: [
            "arr.map(x => x * 2)",
            "เก็บผลลัพธ์ในตัวแปรใหม่",
            "console.log"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasMap = code.includes('.map');
    if (!hasMap) return { pass: false, message: 'ต้องใช้ .map' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    31: {
        name: "filter - กรอง array",
        icon: "🔽",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "filter - เลือก element ที่ตรงเงื่อนไข",
        tutorialContent: `
<h4>Array.filter()</h4>
<p>กรอง element ที่ตรงเงื่อนไข</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2, 3, 4, 5];
let even = arr.filter(x => x % 2 === 0);
console.log(even);</pre>

<h4>Output:</h4>
<pre>2,4</pre>
`,
        mission: "กรอง [10, 20, 30, 40] ให้ได้เฉพาะ > 25",
        expectedOutput: "30,40",
        hints: [
            "arr.filter(x => x > 25)",
            "คืน boolean จาก callback",
            "console.log"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasFilter = code.includes('.filter');
    if (!hasFilter) return { pass: false, message: 'ต้องใช้ .filter' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    32: {
        name: "reduce - รวมค่า array",
        icon: "➕",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "reduce - รวมค่าทั้งหมด",
        tutorialContent: `
<h4>Array.reduce()</h4>
<p>รวม array ให้เป็นค่าเดียว</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let arr = [1, 2, 3, 4];
let sum = arr.reduce((a, b) => a + b, 0);
console.log(sum);</pre>

<h4>Output:</h4>
<pre>10</pre>

<h4>หมายเหตุ:</h4>
<p>reduce(callback, initialValue): a คือผลสะสม b คือตัวปัจจุบัน</p>
`,
        mission: "ใช้ reduce เพื่อบวก [5, 10, 15]",
        expectedOutput: "30",
        hints: [
            "arr.reduce((a, b) => a + b, 0)",
            "a = ผลสะสม b = ตัวปัจจุบัน",
            "initialValue = 0"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasReduce = code.includes('.reduce');
    if (!hasReduce) return { pass: false, message: 'ต้องใช้ .reduce' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    33: {
        name: "[CHOICE] สรุป Zone 5",
        icon: "🎯",
        zone: 5,
        color: "#95E1D3",
        tutorialTitle: "ทบทวน Zone Array",
        tutorialContent: `
<h4>Zone 5 ได้เรียนรู้:</h4>
<ul>
<li>array - push, pop</li>
<li>shift, unshift, splice</li>
<li>for loop + forEach</li>
<li>map - แปลง</li>
<li>filter - กรอง</li>
<li>reduce - รวมค่า</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_b",
        hints: [
            "ทบทวน array methods",
            "map filter reduce",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    34: {
        name: "Object literal - สร้าง object",
        icon: "🏢",
        zone: 6,
        color: "#C9B1FF",
        tutorialTitle: "Object - สร้าง + dot/bracket notation",
        tutorialContent: `
<h4>Object Literals</h4>
<p>สร้าง object ด้วย {} และเข้าถึง property</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let person = {name: "Alice", age: 25};
console.log(person.name);
console.log(person["age"]);</pre>

<h4>Output:</h4>
<pre>Alice
25</pre>

<h4>การเข้าถึง:</h4>
<ul>
<li>Dot notation: obj.key</li>
<li>Bracket notation: obj["key"]</li>
</ul>
`,
        mission: "สร้าง {x: 10, y: 20} และพิมพ์ x",
        expectedOutput: "10",
        hints: [
            "let obj = {x: 10, y: 20};",
            "console.log(obj.x);",
            "หรือ obj['x']"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasObject = code.includes('{') && code.includes(':');
    if (!hasObject) return { pass: false, message: 'ต้องสร้าง object' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    35: {
        name: "Object.keys, values, entries",
        icon: "🗝️",
        zone: 6,
        color: "#C9B1FF",
        tutorialTitle: "Object - keys/values/entries",
        tutorialContent: `
<h4>Object Static Methods</h4>
<p>ดึง keys, values หรือทั้งคู่</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let obj = {a: 1, b: 2};
console.log(Object.keys(obj));
console.log(Object.values(obj));
console.log(Object.entries(obj));</pre>

<h4>Output:</h4>
<pre>a,b
1,2
a,1,b,2</pre>
`,
        mission: "ใช้ Object.keys เพื่อดึง keys จาก {name: 'Tom', age: 30}",
        expectedOutput: "name,age",
        hints: [
            "Object.keys(obj)",
            "คืน array ของ keys",
            "console.log"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasObjectMethod = code.includes('Object.keys') || code.includes('Object.values') || code.includes('Object.entries');
    if (!hasObjectMethod) return { pass: false, message: 'ต้องใช้ Object.keys/values/entries' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    36: {
        name: "Map - key-value advanced",
        icon: "🗺️",
        zone: 6,
        color: "#C9B1FF",
        tutorialTitle: "Map - เก็บ key-value",
        tutorialContent: `
<h4>Map vs Object</h4>
<p>Map สามารถใช้ key ของทุกชนิด</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let map = new Map();
map.set("name", "Alice");
map.set(1, "number key");
console.log(map.get("name"));
console.log(map.size);</pre>

<h4>Output:</h4>
<pre>Alice
2</pre>

<h4>Methods:</h4>
<ul>
<li>set(key, value)</li>
<li>get(key)</li>
<li>has(key)</li>
<li>delete(key)</li>
<li>size</li>
</ul>
`,
        mission: "สร้าง Map set('a', 1) set('b', 2) และ get('a')",
        expectedOutput: "1",
        hints: [
            "let map = new Map();",
            "map.set('a', 1); map.set('b', 2);",
            "console.log(map.get('a'));"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasMap = code.includes('new Map') || code.includes('.set') || code.includes('.get');
    if (!hasMap) return { pass: false, message: 'ต้องใช้ Map' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    37: {
        name: "Set - ค่าไม่ซ้ำ",
        icon: "🎯",
        zone: 6,
        color: "#C9B1FF",
        tutorialTitle: "Set - เก็บค่าที่ไม่ซ้ำกัน",
        tutorialContent: `
<h4>Set Collection</h4>
<p>เก็บค่าที่ไม่ซ้ำ</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let set = new Set([1, 2, 2, 3]);
console.log(set.size);
set.add(4);
console.log(set.has(2));</pre>

<h4>Output:</h4>
<pre>3
true</pre>

<h4>Methods:</h4>
<ul>
<li>add(value)</li>
<li>has(value)</li>
<li>delete(value)</li>
<li>size</li>
</ul>
`,
        mission: "สร้าง Set [1, 1, 2, 2, 3] แล้วพิมพ์ size",
        expectedOutput: "3",
        hints: [
            "let set = new Set([1, 1, 2, 2, 3]);",
            "console.log(set.size);",
            "จะลบซ้ำให้"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasSet = code.includes('new Set') || code.includes('.add') || code.includes('.has');
    if (!hasSet) return { pass: false, message: 'ต้องใช้ Set' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    38: {
        name: "[CHOICE] สรุป Zone 6",
        icon: "🎯",
        zone: 6,
        color: "#C9B1FF",
        tutorialTitle: "ทบทวน Zone Object+Map+Set",
        tutorialContent: `
<h4>Zone 6 ได้เรียนรู้:</h4>
<ul>
<li>Object literals</li>
<li>Object.keys/values/entries</li>
<li>Map - advanced key-value</li>
<li>Set - ค่าไม่ซ้ำ</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_a",
        hints: [
            "ทบทวน Object Map Set",
            "คิดเรื่องของแต่ละตัว",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    39: {
        name: "function declaration",
        icon: "⚙️",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "ประกาศฟังก์ชัน",
        tutorialContent: `
<h4>Function Declaration</h4>
<p>ประกาศฟังก์ชันและเรียกใช้</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>function add(a, b) {
    return a + b;
}
console.log(add(3, 5));</pre>

<h4>Output:</h4>
<pre>8</pre>

<h4>โครงสร้าง:</h4>
<ul>
<li>function ชื่อ(parameter) { ... }</li>
<li>return ส่งค่ากลับ</li>
</ul>
`,
        mission: "สร้างฟังก์ชัน multiply(x, y) คืนค่า x * y แล้ว multiply(4, 5)",
        expectedOutput: "20",
        hints: [
            "function multiply(x, y) { return x * y; }",
            "console.log(multiply(4, 5));",
            "return ค่า"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasFunction = code.includes('function');
    const hasReturn = code.includes('return');
    if (!hasFunction || !hasReturn) return { pass: false, message: 'ต้องประกาศ function ที่มี return' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    40: {
        name: "Arrow function - () => {}",
        icon: "➡️",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "Arrow function - เขียนฟังก์ชันแบบสั้น",
        tutorialContent: `
<h4>Arrow Function Syntax</h4>
<p>รูปแบบสั้นของฟังก์ชัน</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>const square = (x) => {
    return x * x;
};
console.log(square(4));

const double = x => x * 2;
console.log(double(5));</pre>

<h4>Output:</h4>
<pre>16
10</pre>
`,
        mission: "สร้าง arrow function subtract = (a, b) => a - b; แล้วเรียก subtract(10, 3)",
        expectedOutput: "7",
        hints: [
            "const subtract = (a, b) => a - b;",
            "console.log(subtract(10, 3));",
            "=> คือ arrow"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasArrow = code.includes('=>');
    if (!hasArrow) return { pass: false, message: 'ต้องใช้ arrow function (=>)' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    41: {
        name: "Default parameters",
        icon: "📌",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "Default parameters - ค่า default",
        tutorialContent: `
<h4>Default Parameters</h4>
<p>ตั้งค่า default สำหรับ parameter</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>function greet(name = "Guest") {
    console.log("สวัสดี " + name);
}
greet();
greet("Alice");</pre>

<h4>Output:</h4>
<pre>สวัสดี Guest
สวัสดี Alice</pre>
`,
        mission: "สร้างฟังก์ชัน power(x, n = 2) แล้วเรียก power(3) และ power(3, 3)",
        expectedOutput: "9\n27",
        hints: [
            "function power(x, n = 2) { ... }",
            "return x ** n;",
            "console.log สองครั้ง"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasDefault = code.includes('=') && code.includes('(');
    if (!hasDefault) return { pass: false, message: 'ต้องใช้ default parameter' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    42: {
        name: "Callback function",
        icon: "📞",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "Callback - ส่ง function เป็น argument",
        tutorialContent: `
<h4>Callback Functions</h4>
<p>ส่ง function ไปเป็น argument</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>function process(x, callback) {
    let result = callback(x);
    return result;
}
console.log(process(5, (n) => n * 2));</pre>

<h4>Output:</h4>
<pre>10</pre>
`,
        mission: "สร้างฟังก์ชัน execute(fn) ที่รับ callback แล้วเรียก fn()",
        expectedOutput: "Hello",
        hints: [
            "function execute(fn) { fn(); }",
            "execute(() => { console.log('Hello'); })",
            "callback"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasFunction = code.includes('function');
    if (!hasFunction) return { pass: false, message: 'ต้องมี function เป็น callback' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    43: {
        name: "Closure - ฟังก์ชันจำค่า",
        icon: "🔒",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "Closure - function จำ scope",
        tutorialContent: `
<h4>Closures</h4>
<p>ฟังก์ชันจำตัวแปรจาก scope ของมัน</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>function outer() {
    let count = 0;
    return function() {
        count++;
        return count;
    };
}
let counter = outer();
console.log(counter());
console.log(counter());</pre>

<h4>Output:</h4>
<pre>1
2</pre>
`,
        mission: "สร้าง closure ที่ return function เพิ่มค่า x",
        expectedOutput: "11",
        hints: [
            "outer function ประกาศ let x = 10",
            "return function() { x++; return x; }",
            "เรียก 1 ครั้ง"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasNestedFunction = code.includes('function') && (code.match(/function/g) || []).length >= 2;
    if (!hasNestedFunction) return { pass: false, message: 'ต้องมี nested function' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    44: {
        name: "[CHOICE] สรุป Zone 7",
        icon: "🎯",
        zone: 7,
        color: "#F38181",
        tutorialTitle: "ทบทวน Zone ฟังก์ชัน",
        tutorialContent: `
<h4>Zone 7 ได้เรียนรู้:</h4>
<ul>
<li>function declaration</li>
<li>Arrow function</li>
<li>Default parameters</li>
<li>Callback function</li>
<li>Closure</li>
</ul>
`,
        mission: "เลือกคำตอบที่ถูกต้อง",
        expectedOutput: "choice_c",
        hints: [
            "ทบทวน function arrow callback closure",
            "คิดถึงความแตกต่าง",
            "CHOICE ด่าน"
        ],
        codeCheck: `
function codeCheck(code) {
    return { pass: true, message: 'CHOICE ด่าน' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    45: {
        name: "try/catch - จัดการ error",
        icon: "🚨",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "try/catch - จัดการข้อผิดพลาด",
        tutorialContent: `
<h4>Error Handling</h4>
<p>ใช้ try/catch เพื่อจัดการข้อผิดพลาด</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>try {
    let x = undefined.length;
} catch (e) {
    console.log("Error: " + e.message);
}</pre>

<h4>Output:</h4>
<pre>Error: Cannot read property 'length' of undefined</pre>

<h4>โครงสร้าง:</h4>
<ul>
<li>try: โค้ดที่อาจเกิด error</li>
<li>catch: จัดการ error</li>
<li>finally: รัน ไม่ว่าจะ error หรือไม่</li>
</ul>
`,
        mission: "ใช้ try/catch แล้วพิมพ์ 'Caught error'",
        expectedOutput: "Caught error",
        hints: [
            "try { throw new Error('test'); }",
            "catch (e) { console.log('Caught error'); }",
            "throw จะโยน error"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasTry = code.includes('try');
    const hasCatch = code.includes('catch');
    if (!hasTry || !hasCatch) return { pass: false, message: 'ต้องใช้ try/catch' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    46: {
        name: "Promise basics",
        icon: "⏳",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "Promise - สิ่งที่จะเกิด",
        tutorialContent: `
<h4>Promises</h4>
<p>ตัวแทนของค่าที่ยังไม่เกิด</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let p = new Promise((resolve, reject) => {
    resolve("Success");
});
p.then((msg) => {
    console.log(msg);
});</pre>

<h4>Output:</h4>
<pre>Success</pre>

<h4>States:</h4>
<ul>
<li>pending: รอ</li>
<li>resolved: เสร็จ</li>
<li>rejected: ล้มเหลว</li>
</ul>
`,
        mission: "สร้าง Promise ที่ resolve('Done') แล้ว .then print ค่า",
        expectedOutput: "Done",
        hints: [
            "new Promise((resolve, reject) => { resolve('Done'); })",
            ".then((msg) => { console.log(msg); })",
            "resolve จะส่งค่า"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasPromise = code.includes('Promise');
    const hasThen = code.includes('.then');
    if (!hasPromise || !hasThen) return { pass: false, message: 'ต้องใช้ Promise + .then' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    47: {
        name: "async/await",
        icon: "⏱️",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "async/await - รอ promise",
        tutorialContent: `
<h4>Async/Await</h4>
<p>เขียน async code ด้วย await</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>async function test() {
    let result = await Promise.resolve("Result");
    console.log(result);
}
test();</pre>

<h4>Output:</h4>
<pre>Result</pre>

<h4>ข้อสำคัญ:</h4>
<ul>
<li>async: ประกาศฟังก์ชัน async</li>
<li>await: รอ promise</li>
</ul>
`,
        mission: "สร้าง async function ที่ await Promise.resolve('Data') แล้ว console.log",
        expectedOutput: "Data",
        hints: [
            "async function test() { ... }",
            "let x = await Promise.resolve('Data');",
            "console.log(x); test();"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasAsync = code.includes('async');
    const hasAwait = code.includes('await');
    if (!hasAsync || !hasAwait) return { pass: false, message: 'ต้องใช้ async/await' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    48: {
        name: "class - OOP พื้นฐาน",
        icon: "🏛️",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "class - Object-Oriented Programming",
        tutorialContent: `
<h4>Classes</h4>
<p>สร้าง blueprint ของ object</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>class Car {
    constructor(name) {
        this.name = name;
    }
    drive() {
        console.log(this.name + " is driving");
    }
}
let car = new Car("Tesla");
car.drive();</pre>

<h4>Output:</h4>
<pre>Tesla is driving</pre>

<h4>โครงสร้าง:</h4>
<ul>
<li>constructor: สร้าง object</li>
<li>methods: ฟังก์ชันใน class</li>
<li>new: สร้าง instance</li>
</ul>
`,
        mission: "สร้าง class Person มี constructor(name) และ method greet() ที่พิมพ์ 'Hello [name]'",
        expectedOutput: "Hello Alice",
        hints: [
            "class Person { constructor(name) { ... } }",
            "greet() { console.log('Hello ' + this.name); }",
            "let p = new Person('Alice'); p.greet();"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasClass = code.includes('class');
    const hasConstructor = code.includes('constructor');
    if (!hasClass || !hasConstructor) return { pass: false, message: 'ต้องใช้ class + constructor' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    49: {
        name: "Destructuring + Spread",
        icon: "💥",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "Destructuring + Spread - แตกออก + รวม",
        tutorialContent: `
<h4>Destructuring และ Spread Operator</h4>
<p>แตกออก array/object และรวม</p>

<h4>ตัวอย่างโค้ด:</h4>
<pre>let [a, b] = [1, 2];
console.log(a, b);

let arr = [1, 2, 3];
let arr2 = [...arr, 4];
console.log(arr2);

let obj = {x: 1};
let obj2 = {...obj, y: 2};
console.log(obj2.x, obj2.y);</pre>

<h4>Output:</h4>
<pre>1 2
1,2,3,4
1 2</pre>
`,
        mission: "Destructure [10, 20] เป็น x, y แล้วพิมพ์ x",
        expectedOutput: "10",
        hints: [
            "let [x, y] = [10, 20];",
            "console.log(x);",
            "destructuring ด้วย []"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasDestructure = code.includes('[') && code.includes(']') && code.includes('=');
    const hasSpread = code.includes('...');
    if (!hasDestructure && !hasSpread) return { pass: false, message: 'ต้องใช้ destructuring หรือ spread' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
    50: {
        name: "[BOSS] Final Boss Battle - รวมทุกทักษะ",
        icon: "🏆",
        zone: 8,
        color: "#AA96DA",
        tutorialTitle: "ด่านเก็บชาติ - รวมทุกทักษะ",
        tutorialContent: `
<h4>Final Challenge</h4>
<p>ใช้ทักษะทั้งหมดที่เรียนมา:</p>
<ul>
<li>Variables, types, string, array</li>
<li>Conditionals, loops, functions</li>
<li>Objects, Map, Set</li>
<li>arrow function, promises, classes</li>
</ul>

<h4>Scenario:</h4>
<p>ดำเนินการกับข้อมูล:</p>
<pre>1. สร้าง array of objects: users = [{name: 'Alice', score: 85}, {name: 'Bob', score: 75}]
2. filter users ที่ score >= 80
3. map ให้เป็น 'Name: X, Score: Y'
4. join ด้วย newline
5. console.log ผลลัพธ์</pre>
`,
        mission: "ประมวลผล array [{name:'Alice',score:85},{name:'Bob',score:75}]: filter score>=80, map เป็นสตริง, join",
        expectedOutput: "Name: Alice, Score: 85",
        hints: [
            "สร้าง array users",
            "filter((u) => u.score >= 80)",
            "map((u) => `Name: ${u.name}, Score: ${u.score}`)"
        ],
        codeCheck: `
function codeCheck(code) {
    const hasArray = code.includes('[') && code.includes(']');
    const hasMethod = code.includes('.filter') || code.includes('.map') || code.includes('.join');
    if (!hasArray || !hasMethod) return { pass: false, message: 'ใช้ array methods' };
    return { pass: true, message: 'ตรวจสอบผ่าน!' };
}
`,
        validate: `
function validate(output, expected) {
    return output.trim() === expected.trim();
}
`
    },
};



// ============================
// GAME STATE
// ============================
let currentLevel = null;
    let currentLevelId = 1;
let gameState = {
  currentLevel: 1,
  xp: 0,
  completed: [],
  selectedChoice: -1,
  hintsUsed: {}
};

function saveGame() {
  try {
    localStorage.setItem('jsquest_50', JSON.stringify(gameState));
  } catch (e) {}
}

function loadGame() {
  try {
    const saved = localStorage.getItem('jsquest_50');
    if (saved) {
      gameState = JSON.parse(saved);
      renderStageMap();
    }
  } catch (e) {}
}

// ============================
// PARTICLES
// ============================
function createParticles() {
  const container = document.getElementById('particles');
  for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.top = -10 + '%';
    p.style.animationDuration = (Math.random() * 15 + 15) + 's';
    p.style.animationDelay = Math.random() * 5 + 's';
    container.appendChild(p);
  }
}

// ============================
// INITIALIZE GAME
// ============================
function startGame() {
  createParticles();
  loadGame();
  renderStageMap();
  document.getElementById('loading-screen').style.display = 'none';
  document.getElementById('app').style.display = 'block';
}

function renderStageMap() {
  const map = document.getElementById('stage-map');
  map.innerHTML = '';
  
  let currentZone = 0;
  for (const levelId in LEVELS) {
    const level = LEVELS[levelId];
    
    if (level.zone && level.zone !== currentZone) {
      currentZone = level.zone;
      const zoneLabel = document.createElement('div');
      zoneLabel.className = `zone-label zone${currentZone}`;
      if (currentZone === 1) zoneLabel.textContent = '⚡ Zone 1: พื้นฐาน';
      else if (currentZone === 2) zoneLabel.textContent = '🔗 Zone 2: เงื่อนไข';
      else if (currentZone === 3) zoneLabel.textContent = '🔄 Zone 3: ลูป';
      else if (currentZone === 4) zoneLabel.textContent = '📝 Zone 4: String/Array';
      else if (currentZone === 5) zoneLabel.textContent = '📦 Zone 5: Object/Function';
      map.appendChild(zoneLabel);
    }
    
    const node = document.createElement('div');
    node.className = 'stage-node';
    
    if (gameState.currentLevel > parseInt(levelId)) {
      node.classList.add('completed');
      node.innerHTML = `<div class="stage-check">✓</div>`;
    } else if (gameState.currentLevel === parseInt(levelId)) {
      node.classList.add('current');
    } else {
      node.classList.add('locked');
    }
    
    if (gameState.currentLevel >= parseInt(levelId)) {
      node.onclick = () => startLevel(parseInt(levelId));
    }
    
    node.innerHTML += `
      <div class="stage-icon">${level.icon}</div>
      <div class="stage-num">Level ${levelId}</div>
      <div class="stage-name">${level.name}</div>
      <div class="stage-tag">${level.skill || 'JavaScript'}</div>
    `;
    
    map.appendChild(node);
  }
  
  updateXpBar();
}

function startLevel(id) {
  gameState.currentLevel = id;
  currentLevelId = id;
  currentLevel = LEVELS[id];
  
  document.getElementById('home-screen').classList.remove('active');
  document.getElementById('game-screen').classList.add('active');
  
  document.getElementById('game-stage-title').textContent = `${currentLevel.icon} ${currentLevel.name}`;
  document.getElementById('game-theme-badge').textContent = currentLevel.themeName || 'JavaScript';
  document.getElementById('game-theme-badge').style.background = currentLevel.color || '#4ecdc4';
  
  document.getElementById('story-box').className = `story-box theme-${currentLevel.theme || 'code'}`;
  document.getElementById('story-char').textContent = currentLevel.character || '⚡';
  document.getElementById('story-text').innerHTML = currentLevel.tutorialContent || currentLevel.story || '';
  
  document.getElementById('mission-text').innerHTML = currentLevel.mission || '';
  
  const expected = currentLevel.expectedOutput || currentLevel.expected;
  if (expected) {
    document.getElementById('expected-box').style.display = 'block';
    document.getElementById('expected-output').textContent = expected;
  } else {
    document.getElementById('expected-box').style.display = 'none';
  }
  
  // Setup hint buttons
  const hintsHtml = (currentLevel.hints || []).map((_, i) => 
    `<button class="btn-hint level${i+1}" onclick="showHint(${i})">${i===0?'💡':i===1?'🔶':'🔴'} Level ${i+1}</button>`
  ).join('');
  document.getElementById('hint-buttons').innerHTML = hintsHtml;
  
  // Clear hints display
  document.querySelectorAll('.hint-box').forEach(h => h.classList.remove('show'));
  
  // Setup code/choice section
  const editorSection = document.getElementById('editor-section');
  const choiceSection = document.getElementById('choice-section');
  
  if (currentLevel.type === 'choice') {
    editorSection.style.display = 'none';
    choiceSection.style.display = 'block';
    
    const choicesHtml = (currentLevel.choices || []).map((c, i) => `
      <div class="choice-btn" onclick="selectChoice(${i})">
        <div class="choice-label">${String.fromCharCode(65+i)}</div>
        <div class="choice-text">${c.text}</div>
      </div>
    `).join('');
    document.getElementById('choices-area').innerHTML = choicesHtml;
  } else {
    editorSection.style.display = 'block';
    choiceSection.style.display = 'none';
    
    document.getElementById('code-editor').value = currentLevel.starter || '';
    document.getElementById('code-editor').focus();
  }
  
  document.getElementById('output-body').textContent = 'รอรันโค้ด...';
  document.getElementById('output-body').className = 'output-body';
}

function goHome() {
  document.getElementById('game-screen').classList.remove('active');
  document.getElementById('home-screen').classList.add('active');
  saveGame();
}

function showHint(level) {
  const hintBox = document.getElementById(`hint-box-${level + 1}`);
  const hintText = document.getElementById(`hint-text-${level + 1}`);
  
  if (currentLevel.hints && currentLevel.hints[level]) {
    hintText.textContent = currentLevel.hints[level];
    hintBox.classList.add('show');
  }
  
  const btn = event.target;
  btn.classList.add('used');
}

function selectChoice(idx) {
  gameState.selectedChoice = idx;
  document.querySelectorAll('.choice-btn').forEach((b, i) => {
    b.classList.toggle('selected', i === idx);
  });
}

function submitChoice() {
  if (gameState.selectedChoice < 0) return;
  
  const correct = currentLevel.choices[gameState.selectedChoice].correct;
  const btns = document.querySelectorAll('.choice-btn');
  
  currentLevel.choices.forEach((c, i) => {
    if (c.correct) btns[i].classList.add('correct');
    else if (i === gameState.selectedChoice) btns[i].classList.add('wrong');
  });
  
  setTimeout(() => {
    showResult(correct);
    btns.forEach(b => b.classList.remove('correct', 'wrong', 'selected'));
    gameState.selectedChoice = -1;
  }, 800);
}

function executeCode(code) {
  let output = [];
  const testInputs = currentLevel.testInputs || [];
  let inputIdx = 0;
  
  try {
    const mockConsole = {
      log: (...args) => output.push(args.map(a => 
        typeof a === 'object' ? JSON.stringify(a) : String(a)
      ).join(' '))
    };
    const mockPrompt = () => testInputs[inputIdx++] || '';
    const mockAlert = (msg) => output.push(String(msg));
    
    const fn = new Function('console', 'prompt', 'alert', code);
    fn(mockConsole, mockPrompt, mockAlert);
  } catch(e) {
    output.push('Error: ' + e.message);
  }
  
  return output.join('\n');
}

function runCode() {
  const code = document.getElementById('code-editor').value;
  
  if (currentLevel.codeCheck) {
    const check = typeof currentLevel.codeCheck === 'function' 
      ? currentLevel.codeCheck(code)
      : eval('(' + currentLevel.codeCheck + ')')(code);
    
    if (!check.pass) {
      showError(check.message);
      return;
    }
  }
  
  const result = executeCode(code);
  const ob = document.getElementById('output-body');
  ob.textContent = result || '(ไม่มี output)';
  ob.className = 'output-body';
}

function submitCode() {
  const code = document.getElementById('code-editor').value;
  
  if (currentLevel.codeCheck) {
    const check = typeof currentLevel.codeCheck === 'function'
      ? currentLevel.codeCheck(code)
      : eval('(' + currentLevel.codeCheck + ')')(code);
    
    if (!check.pass) {
      showError(check.message);
      return;
    }
  }
  
  const output = executeCode(code);
  const expected = currentLevel.expectedOutput || currentLevel.expected || '';
  const valid = typeof currentLevel.validate === 'function'
    ? currentLevel.validate(output, expected)
    : eval('(' + currentLevel.validate + ')')(output, expected);
  
  if (valid) {
    gameState.xp += currentLevel.xp || 50;
    if (!gameState.completed.includes(currentLevelId)) {
      gameState.completed.push(currentLevelId);
    }
    if (gameState.currentLevel === currentLevelId) {
      gameState.currentLevel = Math.min(currentLevelId + 1, 50);
    }
    saveGame();
    
    if (currentLevelId === 50) {
      showVictory();
    } else {
      showResult(true);
    }
  } else {
    showResult(false);
  }
}

function showError(msg) {
  const modal = document.getElementById('result-modal');
  const content = document.getElementById('modal-content');
  content.className = 'modal-content fail';
  document.getElementById('modal-icon').textContent = '🚫';
  document.getElementById('modal-title').textContent = 'ใช้ทักษะไม่ครบ!';
  document.getElementById('modal-text').textContent = msg;
  document.getElementById('modal-xp').textContent = '';
  const btn = document.getElementById('modal-btn');
  btn.textContent = '🔄 กลับไปแก้โค้ด';
  btn.className = 'btn-retry';
  btn.onclick = closeModal;
  modal.classList.add('show');
}

function showResult(success) {
  const modal = document.getElementById('result-modal');
  const content = document.getElementById('modal-content');
  
  if (success) {
    content.className = 'modal-content';
    document.getElementById('modal-icon').textContent = '🎉';
    document.getElementById('modal-title').textContent = 'ผ่านด่าน!';
    document.getElementById('modal-text').textContent = currentLevel.successMsg || 'ยอดเยี่ยม!';
    document.getElementById('modal-xp').textContent = `+${currentLevel.xp || 50} XP`;
    const btn = document.getElementById('modal-btn');
    btn.textContent = '➡️  ต่อไป';
    btn.className = 'btn-next';
    btn.onclick = () => { closeModal(); renderStageMap(); };
  } else {
    content.className = 'modal-content fail';
    document.getElementById('modal-icon').textContent = '❌';
    document.getElementById('modal-title').textContent = 'ผิดนะ!';
    document.getElementById('modal-text').textContent = 'ลองดูให้ดีๆ หรือขอคำใบ้ได้ค่ะ';
    document.getElementById('modal-xp').textContent = '';
    const btn = document.getElementById('modal-btn');
    btn.textContent = '🔄 กลับไปแก้โค้ด';
    btn.className = 'btn-retry';
    btn.onclick = closeModal;
  }
  
  modal.classList.add('show');
}

function closeModal() {
  document.getElementById('result-modal').classList.remove('show');
}

function showVictory() {
  document.getElementById('game-screen').classList.remove('active');
  document.getElementById('victory-screen').classList.add('active');
  saveGame();
}

function updateXpBar() {
  const nextLevel = Math.ceil(gameState.xp / 100) * 100;
  const percent = (gameState.xp % 100) / 100 * 100;
  document.getElementById('xp-bar').style.width = percent + '%';
  document.getElementById('xp-display').textContent = `XP: ${gameState.xp} / ${nextLevel}`;
  document.getElementById('player-level').textContent = `Lv.${Math.floor(gameState.xp / 200) + 1} จิตรกร`;
}

function resetGame() {
  gameState = { currentLevel: 1, xp: 0, completed: [], selectedChoice: -1, hintsUsed: {} };
  localStorage.removeItem('jsquest_50');
  location.reload();
}

startGame();
</script>
<script src="/auth.js"></script>
</body>
</html>
