<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🤖 AI Quest — ผจญภัยแดนโค้ด</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700;800&family=Fira+Code:wght@400;600&display=swap" rel="stylesheet">
<script src="/auth.js"></script>
<script src="codequest-engine.js"></script>
<style>
:root {
  --bg: #0f0e17;
  --card: #1a1932;
  --accent: #a855f7;
  --accent2: #4ECDC4;
  --accent3: #FFE66D;
  --text: #fffffe;
  --text-dim: #94a1b2;
  --success: #10b981;
  --error: #ef4444;
  --border: rgba(255,255,255,0.07);
  --code-bg: #1e1e3f;
  --zone1: #a855f7;
  --zone2: #3b82f6;
  --zone3: #10b981;
  --zone4: #FF6B6B;
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Prompt', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; overflow-x: hidden; }

/* LOADING SCREEN */
#loading-screen {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: var(--bg); display: flex; flex-direction: column;
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
.loading-text-msg { margin-top: 15px; color: var(--text-dim); font-size: 0.9rem; }

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
.xp-bar-container { width: 160px; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; }
.xp-bar { height: 100%; background: linear-gradient(90deg, var(--accent3), var(--accent)); border-radius: 4px; transition: width 0.5s ease; width: 0%; }
.xp-text { font-size: 0.8rem; color: var(--accent3); font-weight: 600; }
.level-badge { background: linear-gradient(135deg, var(--accent), var(--accent2)); padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }

/* SCREENS */
.screen { display: none; }
.screen.active { display: block; }

/* HOME SCREEN */
.home-screen { min-height: calc(100vh - 57px); display: flex; flex-direction: column; align-items: center; padding: 60px 20px; }
.home-title {
  font-size: 4rem; font-weight: 800; text-align: center; margin-bottom: 10px;
  background: linear-gradient(135deg, var(--accent), var(--accent2), var(--accent3));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  animation: title-shimmer 3s ease-in-out infinite;
}
@keyframes title-shimmer { 0%,100% { filter: hue-rotate(0deg); } 50% { filter: hue-rotate(30deg); } }
.home-subtitle { font-size: 1.2rem; color: var(--text-dim); text-align: center; margin-bottom: 50px; }
.home-subtitle strong { color: var(--accent2); }

/* STAGE MAP */
.stage-map { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; max-width: 900px; width: 100%; margin-bottom: 30px; }
.stage-node {
  background: var(--card); border: 2px solid rgba(255,255,255,0.08);
  border-radius: 16px; padding: 20px 14px; text-align: center;
  cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;
}
.stage-node:hover { transform: translateY(-4px); border-color: var(--accent2); box-shadow: 0 8px 30px rgba(78,205,196,0.15); }
.stage-node.completed { border-color: var(--success); background: linear-gradient(135deg, rgba(16,185,129,0.1), var(--card)); }
.stage-node.current { border-color: var(--accent3); animation: current-pulse 2s ease-in-out infinite; }
@keyframes current-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(255,230,109,0.3); } 50% { box-shadow: 0 0 20px 5px rgba(255,230,109,0.15); } }
.stage-icon { font-size: 2rem; margin-bottom: 8px; }
.stage-num { font-size: 0.75rem; color: var(--text-dim); margin-bottom: 4px; }
.stage-name { font-size: 0.85rem; font-weight: 600; line-height: 1.3; }
.stage-tag { display: inline-block; margin-top: 8px; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; background: rgba(168,85,247,0.2); color: var(--accent); }
.stage-check { position: absolute; top: 8px; right: 8px; font-size: 1.2rem; }
.zone-label { grid-column: 1 / -1; text-align: center; padding: 10px; margin-top: 10px; font-size: 1rem; font-weight: 700; border-radius: 10px; }
.zone-label.zone1 { background: linear-gradient(90deg, rgba(168,85,247,0.15), transparent); color: var(--zone1); }
.zone-label.zone2 { background: linear-gradient(90deg, rgba(59,130,246,0.15), transparent); color: var(--zone2); }
.zone-label.zone3 { background: linear-gradient(90deg, rgba(16,185,129,0.15), transparent); color: var(--zone3); }
.zone-label.zone4 { background: linear-gradient(90deg, rgba(255,107,107,0.15), transparent); color: var(--zone4); }

/* GAME SCREEN */
.game-screen { max-width: 900px; margin: 0 auto; padding: 30px 20px; }
.game-header { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; }
.btn-back {
  background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
  color: var(--text); padding: 8px 16px; border-radius: 10px; cursor: pointer;
  font-family: 'Prompt', sans-serif; font-size: 0.9rem; transition: all 0.2s; white-space: nowrap;
}
.btn-back:hover { background: rgba(255,255,255,0.12); }
.game-title-area { flex: 1; }
.game-title-area h2 { font-size: 1.3rem; font-weight: 700; }
.game-title-area .theme-badge { display: inline-block; font-size: 0.75rem; padding: 3px 10px; border-radius: 12px; margin-top: 4px; }

/* ── LEVEL HEADER ── */
.level-header {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px 24px;
}
.level-header-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.level-badge-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.zone-chip { font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.05em; }
.level-num-chip { font-size: 0.7rem; color: var(--text-dim); font-family: 'Fira Code', monospace; }
.level-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 6px; }
.level-desc { color: var(--text-dim); font-size: 0.9rem; line-height: 1.6; }
.level-meta { display: flex; align-items: center; gap: 12px; margin-top: 12px; flex-wrap: wrap; }
.meta-chip { font-size: 0.75rem; padding: 4px 12px; border-radius: 8px; font-weight: 600; }
.chip-xp { background: rgba(255,230,109,0.1); color: var(--accent3); }
.chip-tests { background: rgba(78,205,196,0.1); color: var(--accent2); }
.chip-diff { background: rgba(168,85,247,0.1); color: var(--accent); }

/* ── CONCEPT BOX ── */
.concept-box {
  background: rgba(168,85,247,0.06);
  border: 1px solid rgba(168,85,247,0.2);
  border-radius: 14px;
  padding: 18px 20px;
}
.concept-box h3 { font-size: 0.85rem; font-weight: 700; color: var(--accent); margin-bottom: 10px; letter-spacing: 0.03em; }
.concept-box p { font-size: 0.88rem; color: var(--text-dim); line-height: 1.7; }
.concept-box pre {
  background: rgba(0,0,0,0.3);
  border-radius: 8px; padding: 12px 14px; margin-top: 10px;
  font-family: 'Fira Code', monospace; font-size: 0.8rem;
  color: #c9d1d9; overflow-x: auto; line-height: 1.6;
  border: 1px solid rgba(255,255,255,0.06);
}

/* ── EDITOR AREA ── */
.editor-section { display: flex; flex-direction: column; gap: 12px; }
.editor-label { font-size: 0.8rem; font-weight: 700; color: var(--text-dim); letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
.editor-wrap {
  background: #12111e;
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
}
.editor-toolbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 8px 14px;
  background: rgba(255,255,255,0.03);
  border-bottom: 1px solid var(--border);
}
.editor-lang { font-size: 0.75rem; color: var(--accent); font-weight: 700; }
.editor-actions { display: flex; gap: 8px; }
.btn-icon { background: none; border: none; cursor: pointer; font-size: 0.8rem; color: var(--text-dim); padding: 4px 8px; border-radius: 6px; transition: all 0.15s; }
.btn-icon:hover { background: rgba(255,255,255,0.07); color: var(--text); }
textarea#code-editor {
  display: block; width: 100%;
  background: transparent; border: none; outline: none; resize: vertical;
  font-family: 'Fira Code', monospace; font-size: 0.88rem;
  color: #e2e8f0; padding: 16px;
  min-height: 220px; line-height: 1.6;
  tab-size: 4;
}

/* ── BUTTONS ── */
.action-row { display: flex; gap: 10px; flex-wrap: wrap; }
.btn-run {
  display: flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, #a855f7, #7c3aed);
  border: none; color: #fff; font-family: 'Prompt', sans-serif;
  font-size: 0.9rem; font-weight: 700;
  padding: 11px 24px; border-radius: 12px;
  cursor: pointer; transition: all 0.2s;
  box-shadow: 0 4px 14px rgba(168,85,247,0.3);
}
.btn-run:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(168,85,247,0.4); }
.btn-run:active { transform: translateY(0); }
.btn-run:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
.btn-hint {
  display: flex; align-items: center; gap: 6px;
  background: rgba(255,230,109,0.1); border: 1px solid rgba(255,230,109,0.2);
  color: var(--accent3); font-family: 'Prompt', sans-serif;
  font-size: 0.85rem; font-weight: 600;
  padding: 11px 18px; border-radius: 12px; cursor: pointer; transition: all 0.2s;
}
.btn-hint:hover { background: rgba(255,230,109,0.16); }
.btn-reset {
  display: flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,0.05); border: 1px solid var(--border);
  color: var(--text-dim); font-family: 'Prompt', sans-serif;
  font-size: 0.85rem; font-weight: 600;
  padding: 11px 16px; border-radius: 12px; cursor: pointer; transition: all 0.2s;
}
.btn-reset:hover { background: rgba(255,255,255,0.08); color: var(--text); }

/* ── RESULTS ── */
.results-section { display: none; }
.results-summary {
  display: flex; align-items: center; gap: 16px;
  padding: 14px 18px; border-radius: 12px; margin-bottom: 12px;
}
.results-summary.all-pass { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); }
.results-summary.partial { background: rgba(255,230,109,0.08); border: 1px solid rgba(255,230,109,0.2); }
.results-summary.fail { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); }
.results-icon { font-size: 1.6rem; }
.results-text { flex: 1; }
.results-title { font-weight: 700; font-size: 0.95rem; }
.results-sub { font-size: 0.8rem; color: var(--text-dim); margin-top: 2px; }
.score-badge { font-size: 0.85rem; font-weight: 700; color: var(--accent3); font-family: 'Fira Code', monospace; }

/* ── HINT BOX ── */
.hint-box {
  display: none;
  background: rgba(255,230,109,0.06);
  border: 1px solid rgba(255,230,109,0.2);
  border-radius: 12px; padding: 14px 18px;
}
.hint-box.visible { display: block; }
.hint-box h4 { font-size: 0.8rem; color: var(--accent3); font-weight: 700; margin-bottom: 6px; }
.hint-box p { font-size: 0.85rem; color: var(--text-dim); line-height: 1.6; }

/* ── COMPLETE BANNER ── */
.complete-banner {
  display: none;
  background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(168,85,247,0.08));
  border: 1px solid rgba(16,185,129,0.3);
  border-radius: 16px; padding: 20px 24px;
  text-align: center;
}
.complete-banner.visible { display: block; }
.complete-banner h2 { font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; }
.complete-banner p { color: var(--text-dim); font-size: 0.9rem; }
.btn-next {
  display: inline-flex; align-items: center; gap: 8px; margin-top: 14px;
  background: linear-gradient(135deg, #10b981, #059669);
  border: none; color: #fff; font-family: 'Prompt', sans-serif;
  font-size: 0.9rem; font-weight: 700;
  padding: 11px 24px; border-radius: 12px;
  cursor: pointer; transition: all 0.2s;
  box-shadow: 0 4px 14px rgba(16,185,129,0.3);
}
.btn-next:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,0.4); }



/* ── LOADING ── */
.loading-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(15,14,23,0.7); backdrop-filter: blur(4px);
  z-index: 900; align-items: center; justify-content: center;
  flex-direction: column; gap: 16px;
}
.loading-overlay.visible { display: flex; }
.spinner {
  width: 44px; height: 44px; border-radius: 50%;
  border: 3px solid rgba(168,85,247,0.2);
  border-top-color: #a855f7;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.loading-text { color: var(--text-dim); font-size: 0.9rem; }

/* ── STORY BOX ── */
.story-box {
  background: linear-gradient(135deg, rgba(26,25,50,0.9), rgba(15,14,23,0.9));
  border: 1px solid rgba(255,255,255,0.08); border-radius: 16px;
  padding: 25px; margin-bottom: 20px; position: relative; overflow: hidden;
}
.story-box::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
.story-box.theme-space::before    { background: linear-gradient(180deg, var(--accent), var(--accent2)); }
.story-box.theme-matrix::before   { background: linear-gradient(180deg, var(--zone2), var(--accent2)); }
.story-box.theme-detective::before{ background: linear-gradient(180deg, var(--zone3), var(--accent2)); }
.story-box.theme-dungeon::before  { background: linear-gradient(180deg, var(--zone4), var(--accent3)); }
.story-character { font-size: 2.5rem; margin-bottom: 10px; }
.story-text { font-size: 1rem; line-height: 1.8; color: var(--text); }
.story-text .highlight { color: var(--accent3); font-weight: 600; }

/* ── TUTORIAL BOX ── */
.tutorial-box {
  background: linear-gradient(135deg, rgba(168,85,247,0.08), rgba(78,205,196,0.05));
  border: 1px solid rgba(168,85,247,0.2); border-radius: 16px;
  padding: 25px; margin-bottom: 20px;
}
.tutorial-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: var(--accent); }
.tutorial-box h3::before { content: '📖 '; }
.tutorial-content { font-size: 0.92rem; line-height: 1.8; color: var(--text); }
.tutorial-content code {
  background: rgba(168,85,247,0.15); color: var(--accent);
  padding: 2px 8px; border-radius: 6px; font-family: 'Fira Code', monospace; font-size: 0.83rem;
}
.tutorial-content pre {
  background: var(--code-bg); padding: 14px; border-radius: 10px; margin: 10px 0;
  font-family: 'Fira Code', monospace; font-size: 0.83rem; color: #e0e0ff;
  line-height: 1.6; overflow-x: auto; border: 1px solid rgba(255,255,255,0.06);
}
.tutorial-content b { color: var(--accent2); }

/* ── MISSION BOX ── */
.mission-box {
  background: var(--card); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 16px; padding: 22px 24px; margin-bottom: 20px;
}
.mission-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 10px; color: var(--accent3); }
.mission-box h3::before { content: '🎯 '; }
.mission-text { font-size: 0.93rem; line-height: 1.7; color: var(--text); }
.mission-text code {
  background: rgba(168,85,247,0.15); color: var(--accent);
  padding: 2px 8px; border-radius: 6px; font-family: 'Fira Code', monospace; font-size: 0.83rem;
}
.mission-text ul { padding-left: 20px; margin-top: 6px; }
.mission-text li { margin-bottom: 4px; color: var(--text-dim); }

/* ── EXPECTED BOX ── */
.expected-box {
  background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15);
  border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;
}
.expected-box .label { color: var(--success); font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; }
.expected-box pre {
  font-family: 'Fira Code', monospace; font-size: 0.88rem; color: #a0ffa0;
  background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; line-height: 1.5; overflow-x: auto;
}

/* ── 3-LEVEL HINTS ── */
.hint-area { margin-bottom: 20px; }
.hint-buttons { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.btn-hint-level {
  background: rgba(255,230,109,0.1); border: 1px solid rgba(255,230,109,0.2);
  color: var(--accent3); padding: 6px 16px; border-radius: 8px; cursor: pointer;
  font-family: 'Prompt', sans-serif; font-size: 0.85rem; transition: all 0.2s;
}
.btn-hint-level:hover { background: rgba(255,230,109,0.2); }
.btn-hint-level.used { opacity: 0.5; }
.btn-hint-level.level2 { background: rgba(255,165,0,0.1); border-color: rgba(255,165,0,0.2); color: #ffa500; }
.btn-hint-level.level3 { background: rgba(255,107,107,0.1); border-color: rgba(255,107,107,0.2); color: var(--zone4); }
.hint-box-level {
  background: rgba(255,230,109,0.05); border: 1px solid rgba(255,230,109,0.15);
  border-radius: 12px; padding: 14px 18px; display: none;
}
.hint-box-level.show { display: block; }
.hint-box-level p { color: var(--accent3); font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; }
.hint-box-level.level2 { border-color: rgba(255,165,0,0.15); }
.hint-box-level.level2 p { color: #ffa500; }
.hint-box-level.level3 { border-color: rgba(255,107,107,0.15); }
.hint-box-level.level3 p { color: var(--zone4); }
.xp-penalty { font-size: 0.7rem; opacity: 0.6; display: block; margin-top: 2px; }

/* ── CHOICES ── */
.choices-area { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.choice-btn {
  background: var(--card); border: 2px solid rgba(255,255,255,0.08);
  border-radius: 12px; padding: 16px 20px; text-align: left; cursor: pointer;
  font-family: 'Prompt', sans-serif; color: var(--text); font-size: 0.9rem; transition: all 0.2s;
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

/* ── ACTION BAR ── */
.action-bar { display: flex; gap: 10px; align-items: center; margin-bottom: 14px; flex-wrap: wrap; }
.btn-submit {
  background: linear-gradient(135deg, var(--accent), #7c3aed); color: white; border: none;
  padding: 11px 26px; border-radius: 12px; font-family: 'Prompt', sans-serif; font-size: 0.9rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
  box-shadow: 0 4px 14px rgba(168,85,247,0.25);
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(168,85,247,0.35); }

/* ── OUTPUT PANEL ── */
.output-panel { background: #0d0d1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
.output-header {
  background: rgba(255,255,255,0.03); padding: 8px 14px; font-size: 0.8rem;
  color: var(--text-dim); border-bottom: 1px solid rgba(255,255,255,0.05);
  display: flex; align-items: center; gap: 7px;
}
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot.red { background: #ef4444; } .dot.yellow { background: #eab308; } .dot.green { background: #22c55e; }
.output-body {
  padding: 14px 16px; font-family: 'Fira Code', monospace; font-size: 0.85rem;
  color: #a0ffa0; min-height: 60px; max-height: 220px; overflow-y: auto;
  white-space: pre-wrap; line-height: 1.6;
}
.output-body.error { color: var(--error); }

/* ── RESULT MODAL ── */
.modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.7); backdrop-filter: blur(5px);
  display: none; align-items: center; justify-content: center; z-index: 300;
}
.modal-overlay.show { display: flex; }
.modal-content {
  background: var(--card); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 24px; padding: 40px; text-align: center;
  max-width: 480px; width: 90%; animation: modal-pop 0.4s ease;
}
@keyframes modal-pop { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
.modal-icon { font-size: 4rem; margin-bottom: 16px; }
.modal-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 10px; }
.modal-text { color: var(--text-dim); margin-bottom: 8px; line-height: 1.6; }
.modal-xp { color: var(--accent3); font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; }
.btn-next-modal {
  background: linear-gradient(135deg, var(--accent3), #f59e0b); color: #000; border: none;
  padding: 14px 40px; border-radius: 14px; font-family: 'Prompt', sans-serif; font-size: 1.05rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
}
.btn-next-modal:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,230,109,0.3); }
.modal-content.fail .modal-title { color: var(--error); }
.btn-retry {
  background: linear-gradient(135deg, var(--accent), #dc2626); color: white; border: none;
  padding: 14px 40px; border-radius: 14px; font-family: 'Prompt', sans-serif; font-size: 1.05rem;
  font-weight: 700; cursor: pointer; transition: all 0.2s;
}

/* ── VICTORY ── */
.victory-screen { min-height: calc(100vh - 57px); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 40px; }
.victory-icon { font-size: 6rem; margin-bottom: 20px; animation: bounce-vi 1s ease infinite; }
@keyframes bounce-vi { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
.victory-title {
  font-size: 3rem; font-weight: 800;
  background: linear-gradient(135deg, var(--accent3), var(--accent), var(--accent2));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 15px;
}
.victory-text { color: var(--text-dim); font-size: 1.1rem; line-height: 1.8; max-width: 600px; }
.btn-restart {
  margin-top: 30px; background: linear-gradient(135deg, var(--accent2), var(--accent));
  color: white; border: none; padding: 16px 50px; border-radius: 14px;
  font-family: 'Prompt', sans-serif; font-size: 1.05rem; font-weight: 700; cursor: pointer;
}

@media (max-width: 768px) {
  .stage-map { grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .home-title { font-size: 2.5rem; }
  .game-screen { padding: 15px; }
  .top-bar { padding: 10px 16px; }
  .player-info .xp-bar-container { display: none; }
  .choices-area { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- LOADING SCREEN -->
<div id="loading-screen">
  <div class="loading-logo">🤖 AI Quest</div>
  <div class="loading-bar-container"><div class="loading-bar" id="loading-bar"></div></div>
  <div class="loading-text-msg" id="loading-msg">กำลังโหลด...</div>
</div>

<!-- APP -->
<div class="app-container" id="app">
  <!-- TOP BAR -->
  <div class="top-bar">
    <a href="/index.php" style="text-decoration:none;color:#94a1b2;font-family:Prompt,sans-serif;font-size:0.8rem;padding:5px 12px;border-radius:20px;border:1px solid rgba(255,255,255,0.1);white-space:nowrap;transition:all 0.2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a1b2'">← กลับ</a>
    <div class="logo">🤖 AI Quest <span>ผจญภัยแดนโค้ด</span></div>
    <div class="player-info">
      <div class="xp-text" id="nav-xp">0 XP</div>
      <div class="xp-bar-container"><div class="xp-bar" id="xp-bar-fill"></div></div>
      <div class="level-badge" id="nav-progress">ด่าน 1/28</div>
    </div>
  </div>

  <!-- HOME SCREEN -->
  <div class="screen active" id="home-screen">
    <div class="home-screen">
      <div class="home-title">AI Quest</div>
      <div class="home-subtitle">เรียน Python, NumPy, pandas &amp; Machine Learning ผ่าน <strong>28 ด่าน</strong>!</div>
      <div class="stage-map" id="stage-map"></div>
    </div>
  </div>

  <!-- GAME SCREEN -->
  <div class="screen" id="game-screen">
    <div class="game-screen">
      <div class="game-header">
        <button class="btn-back" onclick="goHome()">← กลับ</button>
        <div class="game-title-area">
          <h2 id="game-stage-title"></h2>
          <div class="theme-badge" id="game-theme-badge"></div>
        </div>
      </div>

      <!-- STORY BOX -->
      <div class="story-box theme-space" id="story-box">
        <div class="story-character" id="story-char">🧠</div>
        <div class="story-text" id="story-text"></div>
      </div>

      <!-- TUTORIAL BOX -->
      <div class="tutorial-box" id="tutorial-box" style="display:none;">
        <h3 id="tutorial-title"></h3>
        <div class="tutorial-content" id="tutorial-content"></div>
      </div>

      <!-- MISSION BOX -->
      <div class="mission-box">
        <h3>ภารกิจ</h3>
        <div class="mission-text" id="mission-text"></div>
      </div>

      <!-- EXPECTED OUTPUT -->
      <div class="expected-box" id="expected-box" style="display:none;">
        <div class="label">📤 ผลลัพธ์ที่ต้องการ:</div>
        <pre id="expected-output"></pre>
      </div>

      <!-- 3-LEVEL HINTS -->
      <div class="hint-area">
        <div class="hint-buttons" id="hint-buttons"></div>
        <div class="hint-box-level" id="hint-box-level-1"><p id="hint-text-1"></p></div>
        <div class="hint-box-level level2" id="hint-box-level-2"><p id="hint-text-2"></p></div>
        <div class="hint-box-level level3" id="hint-box-level-3"><p id="hint-text-3"></p></div>
      </div>

      <!-- CODE EDITOR -->
      <div id="editor-section" style="display:none;">
        <div style="margin-bottom:0;">
          <div style="display:flex;">
            <div class="editor-tab active">📄 solution.py</div>
          </div>
          <textarea class="code-editor" id="code-editor" spellcheck="false" placeholder="# เขียนโค้ด Python ที่นี่..."></textarea>
        </div>
        <div class="action-bar">
          <button class="btn-run" id="run-btn" onclick="runCode()">▶ รันโค้ด</button>
          <button class="btn-submit" onclick="submitCode()">🚀 ส่งคำตอบ</button>
          <button class="btn-reset" onclick="resetCode()" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--text-dim);font-family:'Prompt',sans-serif;font-size:0.85rem;padding:11px 16px;border-radius:12px;cursor:pointer;">↩ Reset</button>
        </div>
        <div class="output-panel">
          <div class="output-header">
            <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
            <span>Output</span>
          </div>
          <div class="output-body" id="output-body">รอรันโค้ด...</div>
        </div>
      </div>

      <!-- MULTIPLE CHOICE -->
      <div id="choice-section" style="display:none;">
        <div class="choices-area" id="choices-area"></div>
        <div class="action-bar">
          <button class="btn-submit" onclick="submitChoice()">🚀 ส่งคำตอบ</button>
        </div>
      </div>
    </div>
  </div>

  <!-- VICTORY SCREEN -->
  <div class="screen" id="victory-screen">
    <div class="victory-screen">
      <div class="victory-icon">🏆</div>
      <div class="victory-title">AI Quest สำเร็จแล้ว!</div>
      <div class="victory-text">
        คุณผ่านครบ 28 ด่าน!<br>
        Python, NumPy, pandas และ Machine Learning — คุณมีพื้นฐานแน่นแล้ว!<br><br>
        พร้อมสร้างโปรเจค AI ของตัวเองได้เลย! 🚀
      </div>
      <button class="btn-restart" onclick="goHome()">← กลับหน้าเลือกด่าน</button>
    </div>
  </div>
</div>

<!-- RESULT MODAL -->
<div class="modal-overlay" id="result-modal">
  <div class="modal-content" id="modal-content">
    <div class="modal-icon" id="modal-icon"></div>
    <div class="modal-title" id="modal-title"></div>
    <div class="modal-text" id="modal-text"></div>
    <div class="modal-xp" id="modal-xp"></div>
    <button id="modal-btn" onclick="closeModal()"></button>
  </div>
</div>

<!-- AI TUTOR FAB -->
<div id="tutor-fab" style="display:none;position:fixed;bottom:24px;right:24px;z-index:500;flex-direction:column;align-items:flex-end;gap:8px;">
  <a href="/credits.php" id="credits-link" style="font-size:0.72rem;color:var(--accent3);background:rgba(255,230,109,0.1);border:1px solid rgba(255,230,109,0.2);padding:4px 10px;border-radius:20px;text-decoration:none;display:none;">
    💳 <span id="fab-credits">0</span> เครดิต
  </a>
  <button id="tutor-btn" onclick="openTutor()"
    style="background:linear-gradient(135deg,#a855f7,#7c3aed);border:none;border-radius:50px;cursor:pointer;display:flex;align-items:center;gap:8px;padding:12px 20px;color:#fff;font-family:'Prompt',sans-serif;font-size:0.85rem;font-weight:700;box-shadow:0 6px 24px rgba(168,85,247,0.4);transition:all 0.2s;">
    🤖 ถาม AI Tutor
  </button>
</div>

<!-- TUTOR MODAL -->
<div id="tutor-modal" style="display:none;position:fixed;inset:0;z-index:600;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:flex-end;justify-content:flex-end;padding:80px 24px 24px;">
  <div style="width:360px;max-height:70vh;background:#1a1932;border:1px solid rgba(168,85,247,0.25);border-radius:20px;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    <div style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
      <div style="font-weight:700;font-size:0.9rem;">🤖 AI Tutor <span style="font-size:0.72rem;color:var(--text-dim);font-weight:400;" id="modal-credits-info"></span></div>
      <button onclick="closeTutor()" style="background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:1.1rem;">✕</button>
    </div>
    <div id="tutor-messages" style="flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;"></div>
    <div style="padding:12px;border-top:1px solid rgba(255,255,255,0.06);display:flex;gap:8px;">
      <textarea id="tutor-input" placeholder="ถามเกี่ยวกับด่านนี้..." rows="2"
        style="flex:1;background:rgba(255,255,255,0.05);border:1px solid rgba(168,85,247,0.2);color:#fff;font-family:'Prompt',sans-serif;font-size:0.82rem;padding:8px 12px;border-radius:10px;outline:none;resize:none;"
        onkeydown="if(event.ctrlKey&&event.key==='Enter')sendTutorMsg()"></textarea>
      <button onclick="sendTutorMsg()" id="tutor-send"
        style="background:var(--accent);border:none;color:#fff;padding:0 14px;border-radius:10px;cursor:pointer;font-family:'Prompt',sans-serif;font-weight:700;font-size:0.85rem;">ส่ง</button>
    </div>
    <div style="padding:6px 14px 10px;font-size:0.7rem;color:var(--text-dim);text-align:center;">
      1 คำถาม = 1 เครดิต · <a href="/credits.php" style="color:var(--accent);">เติมเครดิต</a>
    </div>
  </div>
</div>

<!-- PYODIDE LOADING OVERLAY -->
<div class="loading-overlay" id="loading">
  <div class="spinner"></div>
  <div class="loading-text" id="loading-text">กำลังโหลด Python...</div>
</div>

<script>
// ═══════════════════════════════════════════════════════════════
// LEVEL DATA — 28 ด่าน แบ่ง 4 Zone
// ═══════════════════════════════════════════════════════════════
const ZONES = [
  { id: 1, name: 'Zone 1: AI Concepts', color: 'var(--zone1)', emoji: '🧠', levels: [1,2,3,4,5,6,7] },
  { id: 2, name: 'Zone 2: NumPy',       color: 'var(--zone2)', emoji: '🔢', levels: [8,9,10,11,12,13,14] },
  { id: 3, name: 'Zone 3: pandas',      color: 'var(--zone3)', emoji: '🐼', levels: [15,16,17,18,19,20,21] },
  { id: 4, name: 'Zone 4: ML จากศูนย์', color: 'var(--zone4)', emoji: '🤖', levels: [22,23,24,25,26,27,28] },
];

const LEVELS = {
  // ─────────────────────────── ZONE 1 ───────────────────────────
  1: {
    title: 'ตัวแปรและชนิดข้อมูลสำหรับ AI',
    zone: 1, diff: 'เริ่มต้น', xp: 80,
    desc: 'AI ทำงานกับข้อมูลตัวเลขเป็นหลัก เรามาฝึกสร้างตัวแปรและตรวจสอบชนิดข้อมูลก่อนเลย',
    concept: `ใน Python ตัวแปร AI มักเป็น int (จำนวนเต็ม), float (ทศนิยม), หรือ list (รายการ)

<pre>age = 20          # int
score = 0.95      # float
labels = [0, 1, 1, 0]  # list of int
name = "model"    # str

print(type(age))   # <class 'int'>
print(type(score)) # <class 'float'></pre>`,
    starter: `# สร้างตัวแปรสำหรับโมเดล AI
accuracy = 0.92      # ความแม่นยำ (float)
epochs = 100         # จำนวนรอบเทรน (int)
labels = [0, 1, 1, 0, 1]  # ป้ายกำกับ (list)

# พิมพ์ชนิดข้อมูลของแต่ละตัวแปร
print(type(accuracy))
print(type(epochs))
print(type(labels))`,
    tests: [
      { type: 'stdout', input: '', expected: "<class 'float'>\n<class 'int'>\n<class 'list'>", label: 'ชนิดข้อมูลถูกต้อง', points: 3 }
    ],
    hints: ['ใช้ type() เพื่อตรวจชนิดข้อมูล', 'float คือเลขทศนิยม, int คือจำนวนเต็ม']
  },
  2: {
    title: 'Lists & Loops — Dataset เบื้องต้น',
    zone: 1, diff: 'เริ่มต้น', xp: 90,
    desc: 'Dataset คือ list ของข้อมูล ฝึกวนลูปและคำนวณค่าเฉลี่ยด้วยมือก่อนใช้ library',
    concept: `<pre>scores = [85, 92, 78, 95, 88]

total = 0
for s in scores:
    total += s

avg = total / len(scores)
print(avg)  # 87.6</pre>`,
    starter: `scores = [85, 92, 78, 95, 88, 76, 90]

# คำนวณผลรวมด้วย loop
total = 0
for s in scores:
    total += s

# คำนวณค่าเฉลี่ย
avg = total / len(scores)
print(round(avg, 2))`,
    tests: [
      { type: 'stdout', input: '', expected: '86.29', label: 'ค่าเฉลี่ยถูกต้อง', points: 3 }
    ],
    hints: ['sum = ผลรวมทั้งหมด หาร len() ของ list', 'ใช้ round(value, 2) ปัดเป็น 2 ตำแหน่ง']
  },
  3: {
    title: 'Functions — Building Blocks ของ AI',
    zone: 1, diff: 'เริ่มต้น', xp: 100,
    desc: 'ฟังก์ชันคือหัวใจของ AI pipeline เขียน activate() ที่ใช้ ReLU activation function',
    concept: `ReLU (Rectified Linear Unit) คือ activation function ยอดนิยม: f(x) = max(0, x)

<pre>def relu(x):
    return max(0, x)

print(relu(3))   # 3
print(relu(-2))  # 0
print(relu(0))   # 0</pre>`,
    starter: `def relu(x):
    # คืนค่า x ถ้า x > 0, ไม่งั้นคืน 0
    return max(0, x)

def relu_list(lst):
    # apply relu ทุก element ใน list
    return [relu(x) for x in lst]

# ทดสอบ
values = [-3, -1, 0, 2, 5]
result = relu_list(values)
print(result)`,
    tests: [
      { type: 'stdout', input: '', expected: '[0, 0, 0, 2, 5]', label: 'ReLU list ถูกต้อง', points: 3 },
      { type: 'function', fn: 'relu', args: [-5], expected: 0, label: 'relu(-5) = 0', points: 1 },
      { type: 'function', fn: 'relu', args: [3.5], expected: 3.5, label: 'relu(3.5) = 3.5', points: 1 }
    ],
    hints: ['ReLU คือ f(x) = max(0, x)', 'list comprehension: [f(x) for x in lst]']
  },
  4: {
    title: 'NumPy Hello World',
    zone: 1, diff: 'เริ่มต้น', xp: 100,
    desc: 'NumPy คือ library สำหรับคำนวณตัวเลขอย่างเร็ว สร้าง array แรกของคุณ',
    concept: `<pre>import numpy as np

# สร้าง array
a = np.array([1, 2, 3, 4, 5])
print(a)        # [1 2 3 4 5]
print(a.shape)  # (5,)
print(a.dtype)  # int64

# คณิตศาสตร์ vectorized
print(a * 2)    # [ 2  4  6  8 10]
print(a ** 2)   # [ 1  4  9 16 25]</pre>`,
    starter: `import numpy as np

# สร้าง array จากคะแนน
scores = np.array([85, 92, 78, 95, 88])

# พิมพ์ขนาดและชนิด
print(scores.shape)
print(scores.dtype)
print(scores * 2)`,
    tests: [
      { type: 'stdout', input: '', expected: '(5,)\nint64\n[170 184 156 190 176]', label: 'NumPy array ถูกต้อง', points: 3 }
    ],
    hints: ['np.array() สร้าง array จาก list', '.shape คือ (จำนวนแถว,) สำหรับ 1D array']
  },
  5: {
    title: 'NumPy Statistics',
    zone: 1, diff: 'พื้นฐาน', xp: 110,
    desc: 'NumPy มี functions คำนวณสถิติเร็วมาก — mean, std, min, max ใน 1 บรรทัด',
    concept: `<pre>import numpy as np
data = np.array([10, 20, 30, 40, 50])

print(np.mean(data))   # 30.0
print(np.std(data))    # 14.14...
print(np.min(data))    # 10
print(np.max(data))    # 50
print(np.sum(data))    # 150</pre>`,
    starter: `import numpy as np

heights = np.array([165, 172, 158, 180, 163, 175, 169])

# คำนวณสถิติ
print(round(float(np.mean(heights)), 2))
print(round(float(np.std(heights)), 2))
print(int(np.min(heights)))
print(int(np.max(heights)))`,
    tests: [
      { type: 'stdout', input: '', expected: '168.86\n7.02\n158\n180', label: 'สถิติถูกต้อง', points: 4 }
    ],
    hints: ['np.mean(), np.std(), np.min(), np.max()', 'float() แปลงเป็น float ก่อน round()']
  },
  6: {
    title: 'Data Normalization',
    zone: 1, diff: 'พื้นฐาน', xp: 120,
    desc: 'Normalization ทำให้ข้อมูลอยู่ในช่วง [0,1] ก่อนเทรน ML model',
    concept: `Min-Max Normalization: x_norm = (x - min) / (max - min)

<pre>import numpy as np
x = np.array([10, 20, 30, 40, 50])
x_norm = (x - x.min()) / (x.max() - x.min())
# [0.   0.25 0.5  0.75 1.  ]</pre>`,
    starter: `import numpy as np

data = np.array([5, 10, 15, 20, 25, 30])

# Min-Max Normalization
x_min = data.min()
x_max = data.max()
normalized = (data - x_min) / (x_max - x_min)

print(normalized)`,
    tests: [
      { type: 'stdout', input: '', expected: '[0.   0.2  0.4  0.6  0.8  1. ]', label: 'Normalization ถูกต้อง', points: 4 }
    ],
    hints: ['x_norm = (x - min) / (max - min)', 'ใช้ .min() และ .max() กับ NumPy array']
  },
  7: {
    title: 'One-Hot Encoding',
    zone: 1, diff: 'พื้นฐาน', xp: 130,
    desc: 'แปลง label หมวดหมู่เป็น vector 0/1 ก่อนใช้กับ Neural Network',
    concept: `<pre>label "cat" → [1, 0, 0]
label "dog" → [0, 1, 0]
label "bird"→ [0, 0, 1]</pre>

<pre>def one_hot(label, classes):
    vec = [0] * len(classes)
    vec[classes.index(label)] = 1
    return vec</pre>`,
    starter: `def one_hot(label, classes):
    # สร้าง zero vector ขนาด len(classes)
    vec = [0] * len(classes)
    # ตั้งค่า 1 ที่ตำแหน่ง index ของ label
    idx = classes.index(label)
    vec[idx] = 1
    return vec

classes = ['cat', 'dog', 'bird', 'fish']
print(one_hot('cat',  classes))
print(one_hot('bird', classes))
print(one_hot('fish', classes))`,
    tests: [
      { type: 'stdout', input: '', expected: '[1, 0, 0, 0]\n[0, 0, 1, 0]\n[0, 0, 0, 1]', label: 'One-hot ถูกต้อง', points: 3 },
      { type: 'function', fn: 'one_hot', args: ['dog', ['cat','dog','bird','fish']], expected: [0,1,0,0], label: 'dog encoding', points: 2 }
    ],
    hints: ['classes.index(label) หา index', 'สร้าง list 0 ก่อน แล้วตั้ง 1 ที่ index']
  },

  // ─────────────────────────── ZONE 2 ───────────────────────────
  8: {
    title: 'NumPy Array Creation',
    zone: 2, diff: 'พื้นฐาน', xp: 120,
    desc: 'NumPy มีหลายวิธีสร้าง array — zeros, ones, arange, linspace',
    concept: `<pre>import numpy as np

np.zeros(5)           # [0. 0. 0. 0. 0.]
np.ones((2, 3))       # matrix 2x3 ของ 1
np.arange(0, 10, 2)   # [0 2 4 6 8]
np.linspace(0, 1, 5)  # [0.   0.25 0.5  0.75 1.  ]
np.eye(3)             # Identity matrix 3x3</pre>`,
    starter: `import numpy as np

# สร้าง arrays ต่างๆ
a = np.arange(0, 20, 3)
b = np.linspace(0, 1, 6)
c = np.zeros((2, 4))

print(a)
print(b.round(2))
print(c.shape)`,
    tests: [
      { type: 'stdout', input: '', expected: '[ 0  3  6  9 12 15 18]\n[0.   0.2  0.4  0.6  0.8  1.  ]\n(2, 4)', label: 'Array creation ถูกต้อง', points: 4 }
    ],
    hints: ['arange(start, stop, step)', 'linspace(start, stop, num) — รวม stop ด้วย']
  },
  9: {
    title: 'Array Indexing & Slicing',
    zone: 2, diff: 'พื้นฐาน', xp: 120,
    desc: 'เข้าถึงส่วนต่างๆ ของ array — การ slice ข้อมูล dataset',
    concept: `<pre>import numpy as np
a = np.array([10, 20, 30, 40, 50, 60])

a[0]     # 10 (element แรก)
a[-1]    # 60 (element สุดท้าย)
a[1:4]   # [20 30 40]
a[::2]   # [10 30 50] (ทุก 2)

m = np.array([[1,2,3],[4,5,6]])
m[0, 1]  # 2 (แถว 0, คอลัมน์ 1)
m[:, 0]  # [1 4] (คอลัมน์ทั้งหมด)</pre>`,
    starter: `import numpy as np

data = np.array([3, 7, 1, 9, 4, 6, 8, 2, 5, 0])

# เอา 5 ตัวแรก
first5 = data[:5]
# เอา 3 ตัวสุดท้าย
last3 = data[-3:]
# เอาตัว index คู่ (0,2,4,...)
even_idx = data[::2]

print(first5)
print(last3)
print(even_idx)`,
    tests: [
      { type: 'stdout', input: '', expected: '[3 7 1 9 4]\n[8 2 5 0]\n[3 1 4 8 5]', label: 'Slicing ถูกต้อง', points: 4 }
    ],
    hints: ['a[:5] = 5 ตัวแรก', 'a[-3:] = 3 ตัวท้าย', 'a[::2] = ข้ามทีละ 2']
  },
  10: {
    title: 'Array Operations & Broadcasting',
    zone: 2, diff: 'กลาง', xp: 130,
    desc: 'Broadcasting ทำให้คำนวณ array ต่างขนาดได้ — พลังหลักของ NumPy',
    concept: `<pre>import numpy as np
a = np.array([[1,2,3],[4,5,6]])  # (2,3)
b = np.array([10, 20, 30])        # (3,)

# Broadcasting: b ถูก expand เป็น (2,3)
print(a + b)
# [[11 22 33]
#  [14 25 36]]</pre>`,
    starter: `import numpy as np

# Matrix คะแนนนักเรียน (3 คน, 4 วิชา)
scores = np.array([
    [80, 75, 90, 85],
    [70, 85, 78, 92],
    [95, 88, 82, 79]
])

# bonus คะแนนพิเศษแต่ละวิชา
bonus = np.array([5, 3, 2, 4])

# ใช้ broadcasting บวก bonus
final = scores + bonus

print(final)
print(final.mean(axis=1).round(2))`,
    tests: [
      { type: 'stdout', input: '', expected: '[[ 85  78  92  89]\n [ 75  88  80  96]\n [100  91  84  83]]\n[86.   84.75 89.5 ]', label: 'Broadcasting ถูกต้อง', points: 4 }
    ],
    hints: ['+ กับ array ต่างขนาดใช้ broadcasting', 'mean(axis=1) = ค่าเฉลี่ยรายแถว']
  },
  11: {
    title: 'Linear Algebra — Dot Product',
    zone: 2, diff: 'กลาง', xp: 140,
    desc: 'Dot product คือหัวใจของ Neural Network — คูณ weight กับ input',
    concept: `<pre>import numpy as np

# Dot product: weighted sum
w = np.array([0.5, 0.3, 0.2])  # weights
x = np.array([4.0, 2.0, 1.0])  # inputs

output = np.dot(w, x)
# = 0.5*4 + 0.3*2 + 0.2*1 = 2.0 + 0.6 + 0.2 = 2.8
print(output)  # 2.8</pre>`,
    starter: `import numpy as np

# Neuron เดียว: output = dot(weights, inputs) + bias
def neuron(inputs, weights, bias):
    return np.dot(weights, inputs) + bias

# ทดสอบ
inputs  = np.array([1.0, 2.0, 3.0])
weights = np.array([0.4, 0.3, 0.3])
bias    = 0.5

out = neuron(inputs, weights, bias)
print(round(float(out), 2))`,
    tests: [
      { type: 'stdout', input: '', expected: '2.1', label: 'Neuron output ถูกต้อง', points: 3 },
      { type: 'function', fn: 'neuron',
        args: [[[1.0,0.0,0.0],[0.5,0.3,0.2],[0.0]], 0],
        setup: 'import numpy as np\ninputs=np.array([1.0,0.0,0.0])\nweights=np.array([0.5,0.3,0.2])\nbias=0.0',
        expected: 0.5,
        label: 'dot([1,0,0],[0.5,0.3,0.2])+0 = 0.5', points: 2 }
    ],
    hints: ['np.dot(w, x) คือผลรวม w[i]*x[i]', 'bias คือค่าคงที่บวกท้าย']
  },
  12: {
    title: 'Random Numbers & Distributions',
    zone: 2, diff: 'กลาง', xp: 130,
    desc: 'สุ่มข้อมูล dataset จำลอง — ใช้ seed เพื่อได้ผลเหมือนกันทุกครั้ง',
    concept: `<pre>import numpy as np

np.random.seed(42)  # กำหนด seed ให้ผลลัพธ์เหมือนกัน

# Uniform distribution [0, 1)
u = np.random.rand(4)

# Normal distribution (mean=0, std=1)
n = np.random.randn(4)

# สุ่มจำนวนเต็ม [low, high)
i = np.random.randint(0, 10, size=4)</pre>`,
    starter: `import numpy as np

np.random.seed(0)

# สร้าง dataset จำลอง 5 ตัวอย่าง
# features จาก normal distribution
X = np.random.randn(5, 2).round(3)

# labels จาก 0 หรือ 1
y = np.random.randint(0, 2, size=5)

print("X shape:", X.shape)
print("y:", y)
print("y mean:", y.mean())`,
    tests: [
      { type: 'stdout', input: '', expected: 'X shape: (5, 2)\ny: [0 1 1 0 1]\ny mean: 0.6', label: 'Random seed ถูกต้อง', points: 4 }
    ],
    hints: ['np.random.seed(0) ต้องอยู่ก่อนการสุ่ม', 'randn สุ่ม normal, randint สุ่ม int']
  },
  13: {
    title: 'Reshape & Flatten',
    zone: 2, diff: 'กลาง', xp: 130,
    desc: 'เปลี่ยนรูปร่าง array — จำเป็นมากในการ preprocess รูปภาพสำหรับ CNN',
    concept: `<pre>import numpy as np

a = np.arange(12)  # [0,1,...,11]

b = a.reshape(3, 4)  # matrix 3x4
c = a.reshape(2, 2, 3)  # 3D: 2x2x3

# flatten กลับเป็น 1D
d = b.flatten()  # [0,1,...,11]

# -1 ให้ numpy คิดเอง
e = a.reshape(-1, 3)  # (4, 3)</pre>`,
    starter: `import numpy as np

# จำลองภาพ 4x4 pixels (grayscale)
image = np.arange(16).reshape(4, 4)
print("Image shape:", image.shape)

# Flatten สำหรับส่งเข้า network
flat = image.flatten()
print("Flat shape:", flat.shape)
print("Flat:", flat)`,
    tests: [
      { type: 'stdout', input: '', expected: 'Image shape: (4, 4)\nFlat shape: (16,)\nFlat: [ 0  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15]', label: 'Reshape ถูกต้อง', points: 4 }
    ],
    hints: ['reshape(rows, cols) เปลี่ยนรูปร่าง', 'flatten() ทำให้เป็น 1D array']
  },
  14: {
    title: 'Boolean Masking',
    zone: 2, diff: 'กลาง', xp: 140,
    desc: 'กรองข้อมูล array ด้วย condition — เหมือน WHERE ใน SQL แต่เร็วกว่า',
    concept: `<pre>import numpy as np

scores = np.array([55, 78, 42, 91, 65, 83])

# Boolean mask
mask = scores >= 70
# [False  True False  True False  True]

# กรองด้วย mask
passing = scores[mask]
# [78 91 83]

# นับ
print(mask.sum())  # 3</pre>`,
    starter: `import numpy as np

temps = np.array([22, 35, 18, 41, 28, 33, 15, 38, 25, 30])

# กรองเฉพาะวันที่อุณหภูมิ >= 30
hot_days = temps[temps >= 30]

# วันที่อุณหภูมิต่ำกว่า 20
cold_days = temps[temps < 20]

print("Hot:", hot_days)
print("Cold:", cold_days)
print("Hot count:", len(hot_days))`,
    tests: [
      { type: 'stdout', input: '', expected: 'Hot: [35 41 33 38 30]\nCold: [18 15]\nHot count: 5', label: 'Boolean masking ถูกต้อง', points: 4 }
    ],
    hints: ['condition สร้าง boolean array', 'array[mask] กรองเอาแค่ที่เป็น True']
  },

  // ─────────────────────────── ZONE 3 ───────────────────────────
  15: {
    title: 'pandas DataFrame เบื้องต้น',
    zone: 3, diff: 'พื้นฐาน', xp: 120,
    desc: 'pandas DataFrame คือตารางข้อมูล — แกนหลักของ Data Science',
    concept: `<pre>import pandas as pd

# สร้างจาก dict
df = pd.DataFrame({
    'name':  ['Alice', 'Bob', 'Carol'],
    'score': [90, 85, 92],
    'grade': ['A', 'B', 'A']
})

print(df.shape)    # (3, 3)
print(df.columns)  # Index(['name','score','grade'])
print(df.head(2))  # 2 แถวแรก</pre>`,
    starter: `import pandas as pd

students = pd.DataFrame({
    'name':   ['Alice', 'Bob', 'Carol', 'Dave'],
    'math':   [92, 85, 78, 95],
    'science':[88, 79, 90, 82],
    'grade':  ['A', 'B', 'B', 'A']
})

print(students.shape)
print(students.dtypes)
print(students['math'].mean())`,
    tests: [
      { type: 'stdout', input: '', expected: '(4, 4)\nname      object\nmath       int64\nscience    int64\ngrade     object\ndtype: object\n87.5', label: 'DataFrame ถูกต้อง', points: 4 }
    ],
    hints: ['pd.DataFrame(dict) สร้าง DataFrame', '.shape คือ (rows, cols)', '.dtypes แสดงชนิดแต่ละคอลัมน์']
  },
  16: {
    title: 'pandas Filtering',
    zone: 3, diff: 'พื้นฐาน', xp: 120,
    desc: 'กรองแถวข้อมูลตาม condition — เหมือน SQL WHERE',
    concept: `<pre>import pandas as pd

df = pd.DataFrame({'score':[55,78,92,65,88],'pass':[0,1,1,0,1]})

# กรองแถว
high = df[df['score'] >= 80]  # score >= 80
passed = df[df['pass'] == 1]

# หลาย condition
both = df[(df['score'] >= 70) & (df['pass'] == 1)]</pre>`,
    starter: `import pandas as pd

products = pd.DataFrame({
    'name':  ['A','B','C','D','E','F'],
    'price': [120, 45, 200, 80, 150, 60],
    'stock': [10, 0, 5, 20, 3, 0]
})

# สินค้าราคา >= 100
expensive = products[products['price'] >= 100]

# สินค้ามี stock > 0 และราคา < 100
cheap_avail = products[(products['price'] < 100) & (products['stock'] > 0)]

print(len(expensive))
print(list(expensive['name']))
print(len(cheap_avail))`,
    tests: [
      { type: 'stdout', input: '', expected: "3\n['A', 'C', 'E']\n2", label: 'Filtering ถูกต้อง', points: 4 }
    ],
    hints: ['df[df["col"] >= val] กรองแถว', '& สำหรับ AND, | สำหรับ OR', 'len() นับจำนวนแถว']
  },
  17: {
    title: 'GroupBy & Aggregation',
    zone: 3, diff: 'กลาง', xp: 140,
    desc: 'groupby() รวมและสรุปข้อมูลตามกลุ่ม — ใช้บ่อยมากใน EDA',
    concept: `<pre>import pandas as pd

df = pd.DataFrame({
    'dept':   ['IT','HR','IT','HR','IT'],
    'salary': [50000, 40000, 60000, 45000, 55000]
})

# ค่าเฉลี่ยเงินเดือนแต่ละแผนก
result = df.groupby('dept')['salary'].mean()
print(result)
# dept
# HR    42500.0
# IT    55000.0</pre>`,
    starter: `import pandas as pd

sales = pd.DataFrame({
    'region':  ['North','South','North','East','South','East','North'],
    'product': ['A','B','A','C','A','B','C'],
    'revenue': [1200, 800, 1500, 900, 700, 1100, 850]
})

# รายได้รวมแต่ละ region
by_region = sales.groupby('region')['revenue'].sum().sort_index()

# จำนวน transaction แต่ละ product
by_product = sales.groupby('product')['revenue'].count().sort_index()

print(by_region)
print(by_product)`,
    tests: [
      { type: 'stdout', input: '', expected: 'region\nEast     2000\nNorth    3550\nSouth    1500\nName: revenue, dtype: int64\nproduct\nA    3\nB    2\nC    2\nName: revenue, dtype: int64', label: 'GroupBy ถูกต้อง', points: 4 }
    ],
    hints: ['groupby("col")["val"].sum()', '.sort_index() เรียงตาม index']
  },
  18: {
    title: 'Data Cleaning — Missing Values',
    zone: 3, diff: 'กลาง', xp: 130,
    desc: 'ข้อมูลจริงมัก missing ต้องจัดการก่อน train model',
    concept: `<pre>import pandas as pd
import numpy as np

df = pd.DataFrame({'a':[1,None,3],'b':[4,5,None]})

df.isnull().sum()       # นับ missing แต่ละคอลัมน์
df.dropna()             # ลบแถวที่มี None
df.fillna(0)            # แทน None ด้วย 0
df['a'].fillna(df['a'].mean())  # แทนด้วยค่าเฉลี่ย</pre>`,
    starter: `import pandas as pd
import numpy as np

df = pd.DataFrame({
    'age':    [25, None, 30, 22, None, 28],
    'salary': [45000, 50000, None, 40000, 55000, None],
    'dept':   ['IT','HR','IT',None,'IT','HR']
})

# นับ missing values
print(df.isnull().sum())
print()

# เติม age ด้วยค่าเฉลี่ย, salary ด้วย 0, dept ด้วย "Unknown"
df['age']    = df['age'].fillna(df['age'].mean())
df['salary'] = df['salary'].fillna(0)
df['dept']   = df['dept'].fillna('Unknown')

print(df.isnull().sum())`,
    tests: [
      { type: 'stdout', input: '', expected: 'age       2\nsalary    2\ndept      1\ndtype: int64\n\nage       0\nsalary    0\ndept      0\ndtype: int64', label: 'Missing values จัดการถูกต้อง', points: 4 }
    ],
    hints: ['isnull().sum() นับ NaN', 'fillna(value) แทนค่า', 'fillna(df["col"].mean()) แทนด้วย mean']
  },
  19: {
    title: 'Apply & Lambda',
    zone: 3, diff: 'กลาง', xp: 130,
    desc: 'apply() รัน function กับทุกแถว/คอลัมน์ — feature engineering ขั้นเบื้องต้น',
    concept: `<pre>import pandas as pd

df = pd.DataFrame({'score': [55, 78, 42, 91, 65]})

# apply function
df['grade'] = df['score'].apply(lambda s:
    'A' if s >= 80 else ('B' if s >= 65 else 'C'))
print(df)</pre>`,
    starter: `import pandas as pd

df = pd.DataFrame({
    'name':  ['Alice','Bob','Carol','Dave','Eve'],
    'score': [92, 58, 74, 85, 61]
})

# สร้าง grade column
def score_to_grade(s):
    if s >= 80: return 'A'
    elif s >= 70: return 'B'
    elif s >= 60: return 'C'
    else: return 'F'

df['grade'] = df['score'].apply(score_to_grade)

# นับแต่ละเกรด
grade_counts = df['grade'].value_counts().sort_index()
print(grade_counts)`,
    tests: [
      { type: 'stdout', input: '', expected: 'grade\nA    2\nB    1\nC    1\nF    1\ndtype: int64', label: 'Apply ถูกต้อง', points: 4 }
    ],
    hints: ['apply(func) รัน func กับทุกแถว', 'value_counts() นับความถี่']
  },
  20: {
    title: 'Merge DataFrames',
    zone: 3, diff: 'ยาก', xp: 150,
    desc: 'รวม DataFrame 2 ตัวเข้าด้วยกัน — เหมือน SQL JOIN',
    concept: `<pre>import pandas as pd

left  = pd.DataFrame({'id':[1,2,3],'name':['A','B','C']})
right = pd.DataFrame({'id':[1,2,4],'score':[90,85,70]})

# inner join
pd.merge(left, right, on='id')
# id  name  score
#  1     A     90
#  2     B     85

# left join — เก็บทุกแถวของ left
pd.merge(left, right, on='id', how='left')</pre>`,
    starter: `import pandas as pd

students = pd.DataFrame({
    'id':   [1, 2, 3, 4, 5],
    'name': ['Alice','Bob','Carol','Dave','Eve']
})

scores = pd.DataFrame({
    'id':    [1, 2, 3, 5],
    'score': [88, 72, 95, 80]
})

# inner join
inner = pd.merge(students, scores, on='id')

# left join
left = pd.merge(students, scores, on='id', how='left')

print("Inner rows:", len(inner))
print("Left rows:", len(left))
print("Missing scores:", left['score'].isnull().sum())`,
    tests: [
      { type: 'stdout', input: '', expected: 'Inner rows: 4\nLeft rows: 5\nMissing scores: 1', label: 'Merge ถูกต้อง', points: 4 }
    ],
    hints: ['merge(df1, df2, on="key")', 'how="inner" default, how="left" เก็บทุกแถว left']
  },
  21: {
    title: 'Exploratory Data Analysis (EDA)',
    zone: 3, diff: 'ยาก', xp: 160,
    desc: 'EDA คือขั้นตอนสำรวจข้อมูลก่อน train model — correlation, distribution',
    concept: `<pre>import pandas as pd

df.describe()   # สถิติพื้นฐาน
df.corr()       # correlation matrix
df.nunique()    # จำนวนค่าไม่ซ้ำแต่ละคอลัมน์
df.value_counts() # ความถี่แต่ละค่า</pre>`,
    starter: `import pandas as pd
import numpy as np

np.random.seed(1)
df = pd.DataFrame({
    'age':    np.random.randint(18, 60, 100),
    'income': np.random.randint(15000, 80000, 100),
    'score':  np.random.randint(0, 100, 100),
})

# 1. ขนาด dataset
print("Shape:", df.shape)

# 2. ค่าเฉลี่ยแต่ละคอลัมน์
print("Means:", df.mean().round(1).to_dict())

# 3. correlation ระหว่าง income กับ score
corr = df[['income','score']].corr().iloc[0,1]
print("Corr:", round(corr, 4))`,
    tests: [
      { type: 'stdout', input: '', expected: 'Shape: (100, 3)\nMeans: {\'age\': 37.7, \'income\': 47508.8, \'score\': 49.4}\nCorr: -0.0148', label: 'EDA ถูกต้อง', points: 4 }
    ],
    hints: ['df.mean() คำนวณค่าเฉลี่ยทุกคอลัมน์', 'df.corr() correlation matrix', '.iloc[0,1] เข้าถึง element']
  },

  // ─────────────────────────── ZONE 4 ───────────────────────────
  22: {
    title: 'Distance Functions',
    zone: 4, diff: 'กลาง', xp: 150,
    desc: 'Euclidean distance วัดความใกล้เคียงของจุดข้อมูล — พื้นฐานของ kNN และ Clustering',
    concept: `<pre>d = sqrt(sum((a[i] - b[i])^2))

import numpy as np
a = np.array([1, 2])
b = np.array([4, 6])
d = np.sqrt(np.sum((a - b)**2))
# = sqrt((1-4)^2 + (2-6)^2)
# = sqrt(9 + 16) = sqrt(25) = 5.0</pre>`,
    starter: `import numpy as np

def euclidean(a, b):
    return float(np.sqrt(np.sum((np.array(a) - np.array(b))**2)))

def manhattan(a, b):
    return float(np.sum(np.abs(np.array(a) - np.array(b))))

a = [1, 2]
b = [4, 6]
print(euclidean(a, b))
print(manhattan(a, b))`,
    tests: [
      { type: 'stdout', input: '', expected: '5.0\n7.0', label: 'Distance ถูกต้อง', points: 3 },
      { type: 'function', fn: 'euclidean', args: [[0,0],[3,4]], expected: 5.0, label: 'euclidean([0,0],[3,4])=5', points: 2 }
    ],
    hints: ['euclidean = sqrt(sum((a-b)^2))', 'manhattan = sum(|a-b|)']
  },
  23: {
    title: 'k-Nearest Neighbors (kNN)',
    zone: 4, diff: 'ยาก', xp: 160,
    desc: 'kNN ทำนาย class โดยดู k เพื่อนบ้านที่ใกล้ที่สุด — เข้าใจ concept ผ่านโค้ด',
    concept: `<pre>1. คำนวณ distance จาก point ทดสอบ ถึงทุก point ใน training set
2. เรียงตาม distance (น้อย → มาก)
3. เลือก k อันดับแรก
4. ทำนาย class ที่มีคะแนนเสียงมากที่สุด</pre>`,
    starter: `import numpy as np

def knn_predict(X_train, y_train, x_test, k=3):
    # คำนวณ distance จาก x_test ไปยังทุก point ใน X_train
    dists = [np.sqrt(np.sum((x_test - xi)**2)) for xi in X_train]

    # เรียงและเอา index k ตัวใกล้สุด
    sorted_idx = np.argsort(dists)[:k]
    k_labels = [y_train[i] for i in sorted_idx]

    # majority vote
    return max(set(k_labels), key=k_labels.count)

X_train = np.array([[1,1],[1,2],[2,1],[5,5],[5,6],[6,5]])
y_train = [0, 0, 0, 1, 1, 1]

print(knn_predict(X_train, y_train, np.array([1.5, 1.5]), k=3))
print(knn_predict(X_train, y_train, np.array([5.5, 5.5]), k=3))`,
    tests: [
      { type: 'stdout', input: '', expected: '0\n1', label: 'kNN prediction ถูกต้อง', points: 4 }
    ],
    hints: ['np.argsort() คืน index ที่เรียงจากน้อยไปมาก', 'max(set(list), key=list.count) หา majority vote']
  },
  24: {
    title: 'Linear Regression จากศูนย์',
    zone: 4, diff: 'ยาก', xp: 170,
    desc: 'Linear Regression หา y = mx + b ที่ fit ข้อมูลดีที่สุด ด้วย closed-form solution',
    concept: `<pre>y = mx + b

# Closed-form (Least Squares):
# m = (n*Σxy - Σx*Σy) / (n*Σx² - (Σx)²)
# b = (Σy - m*Σx) / n</pre>`,
    starter: `import numpy as np

def linear_regression(x, y):
    x = np.array(x, dtype=float)
    y = np.array(y, dtype=float)
    n = len(x)
    m = (n * np.sum(x*y) - np.sum(x)*np.sum(y)) / (n * np.sum(x**2) - np.sum(x)**2)
    b = (np.sum(y) - m * np.sum(x)) / n
    return round(float(m), 4), round(float(b), 4)

x = [1, 2, 3, 4, 5]
y = [2, 4, 5, 4, 5]

m, b = linear_regression(x, y)
print(f"m = {m}")
print(f"b = {b}")
print(f"predict x=6: {round(m*6 + b, 4)}")`,
    tests: [
      { type: 'stdout', input: '', expected: 'm = 0.7\nb = 2.1\npredict x=6: 6.3', label: 'Linear Regression ถูกต้อง', points: 4 }
    ],
    hints: ['m คือ slope, b คือ intercept', 'ใช้สูตร closed-form หรือ numpy linalg']
  },
  25: {
    title: 'Gradient Descent',
    zone: 4, diff: 'ยาก', xp: 180,
    desc: 'Gradient Descent คือวิธี optimize โมเดล — หัวใจของ Deep Learning',
    concept: `<pre># อัพเดท weight ทีละ step
# w = w - lr * gradient

# สำหรับ linear regression:
# loss = mean((y_pred - y)^2)
# grad_m = mean(2*(y_pred-y)*x)
# grad_b = mean(2*(y_pred-y))</pre>`,
    starter: `import numpy as np

def gradient_descent(x, y, lr=0.01, epochs=1000):
    x = np.array(x, dtype=float)
    y = np.array(y, dtype=float)
    m, b = 0.0, 0.0

    for _ in range(epochs):
        y_pred = m * x + b
        grad_m = np.mean(2 * (y_pred - y) * x)
        grad_b = np.mean(2 * (y_pred - y))
        m -= lr * grad_m
        b -= lr * grad_b

    return round(m, 3), round(b, 3)

x = [1, 2, 3, 4, 5]
y = [2, 4, 6, 8, 10]

m, b = gradient_descent(x, y)
print(f"m = {m}")
print(f"b = {b}")`,
    tests: [
      { type: 'stdout', input: '', expected: 'm = 2.0\nb = 0.0', label: 'Gradient Descent converged', points: 5 }
    ],
    hints: ['gradient = อนุพันธ์ของ loss function', 'w = w - learning_rate * gradient', 'y = 2x+0 ควร converge ได้']
  },
  26: {
    title: 'Logistic Regression — Sigmoid',
    zone: 4, diff: 'ยาก', xp: 180,
    desc: 'Logistic Regression ทำนาย probability สำหรับ binary classification ด้วย sigmoid',
    concept: `<pre>sigmoid(z) = 1 / (1 + e^(-z))

z = dot(w, x) + b
p = sigmoid(z)  # [0, 1]
predict: 1 if p >= 0.5 else 0</pre>`,
    starter: `import numpy as np

def sigmoid(z):
    return 1 / (1 + np.exp(-np.array(z, dtype=float)))

def logistic_predict(X, w, b):
    z = np.dot(X, w) + b
    prob = sigmoid(z)
    return (prob >= 0.5).astype(int)

# ทดสอบ
X = np.array([[2, 1], [1, 3], [5, 4], [6, 2]])
w = np.array([0.8, -0.5])
b = -1.0

preds = logistic_predict(X, w, b)
print(preds)

# ตรวจ sigmoid
print(round(float(sigmoid(0)), 4))
print(round(float(sigmoid(100)), 4))`,
    tests: [
      { type: 'stdout', input: '', expected: '[0 0 1 1]\n0.5\n1.0', label: 'Logistic Regression ถูกต้อง', points: 4 }
    ],
    hints: ['sigmoid(0) = 0.5 เสมอ', 'np.dot(X, w) = matrix-vector multiply', '(prob >= 0.5).astype(int) แปลงเป็น 0/1']
  },
  27: {
    title: 'Decision Tree — Gini Index',
    zone: 4, diff: 'ยาก', xp: 190,
    desc: 'Decision Tree แบ่งข้อมูลด้วย Gini Impurity — วัดความไม่บริสุทธิ์ของกลุ่ม',
    concept: `<pre>Gini = 1 - Σ(p_i^2)
p_i = สัดส่วน class i ในกลุ่ม

ตัวอย่าง: [3 ใบ class A, 1 ใบ class B]
p_A = 3/4, p_B = 1/4
Gini = 1 - (0.75^2 + 0.25^2) = 0.375</pre>`,
    starter: `def gini_impurity(labels):
    n = len(labels)
    if n == 0: return 0.0
    classes = set(labels)
    return 1.0 - sum((labels.count(c) / n) ** 2 for c in classes)

def gini_split(left, right):
    n = len(left) + len(right)
    return (len(left)/n) * gini_impurity(left) + (len(right)/n) * gini_impurity(right)

# Pure split — gini = 0
print(round(gini_impurity([1,1,1,1]), 4))

# Mixed — gini สูงสุดที่ 0.5 (binary)
print(round(gini_impurity([0,0,1,1]), 4))

# Weighted split
left  = [0, 0, 0]
right = [1, 1, 0]
print(round(gini_split(left, right), 4))`,
    tests: [
      { type: 'stdout', input: '', expected: '0.0\n0.5\n0.1667', label: 'Gini Index ถูกต้อง', points: 4 }
    ],
    hints: ['Gini = 1 - Σ(p²)', 'p = count(class) / total', 'weighted = (n_left/n)*gini_left + (n_right/n)*gini_right']
  },
  28: {
    title: 'Neural Network Forward Pass',
    zone: 4, diff: 'Boss', xp: 250,
    desc: 'ด่านสุดท้าย! สร้าง 2-layer neural network forward pass ด้วย NumPy',
    concept: `<pre>Layer 1: Z1 = X @ W1 + b1,  A1 = relu(Z1)
Layer 2: Z2 = A1 @ W2 + b2, A2 = sigmoid(Z2)

Input: (samples, features)
W1:    (features, hidden_size)
W2:    (hidden_size, 1)</pre>`,
    starter: `import numpy as np

def relu(x):    return np.maximum(0, x)
def sigmoid(x): return 1 / (1 + np.exp(-x))

def forward_pass(X, W1, b1, W2, b2):
    Z1 = X @ W1 + b1          # Hidden layer linear
    A1 = relu(Z1)              # Hidden layer activation
    Z2 = A1 @ W2 + b2          # Output layer linear
    A2 = sigmoid(Z2)           # Output probability
    return A2

np.random.seed(42)
X  = np.random.randn(5, 3)     # 5 samples, 3 features
W1 = np.random.randn(3, 4)     # 3→4 hidden
b1 = np.zeros((1, 4))
W2 = np.random.randn(4, 1)     # 4→1 output
b2 = np.zeros((1, 1))

output = forward_pass(X, W1, b1, W2, b2)
print("Output shape:", output.shape)
print("Predictions:", (output >= 0.5).astype(int).flatten())
print("Mean prob:", round(float(output.mean()), 4))`,
    tests: [
      { type: 'stdout', input: '', expected: 'Output shape: (5, 1)\nPredictions: [1 0 1 1 0]\nMean prob: 0.5403', label: 'Neural Network forward pass ถูกต้อง', points: 5 }
    ],
    hints: [
      'X @ W = matrix multiply (numpy @)',
      'Z1 = X @ W1 + b1, A1 = relu(Z1)',
      'Z2 = A1 @ W2 + b2, A2 = sigmoid(Z2)',
      'output.shape ควรเป็น (5, 1)'
    ]
  }
};

// ─── Enrich LEVELS with defaults ───────────────────────────────
const ZONE_META = {
  1: { theme: 'space',      character: '🧠' },
  2: { theme: 'matrix',     character: '🔢' },
  3: { theme: 'detective',  character: '🐼' },
  4: { theme: 'dungeon',    character: '🤖' },
};
Object.keys(LEVELS).forEach(n => {
  const lv = LEVELS[n];
  const zm = ZONE_META[lv.zone] || ZONE_META[1];
  if (!lv.theme)         lv.theme = zm.theme;
  if (!lv.character)     lv.character = zm.character;
  if (!lv.story)         lv.story = lv.desc || lv.title;
  if (!lv.mission) {
    const testDesc = (lv.tests || []).map(t => `<li>${t.label}</li>`).join('');
    lv.mission = `${lv.desc}${testDesc ? `<br><br><strong>ต้องผ่าน:</strong><ul>${testDesc}</ul>` : ''}`;
  }
  if (!lv.successMsg)    lv.successMsg = `เก่งมาก! ผ่านด่าน <strong>${lv.title}</strong> แล้ว!`;
  if (!lv.tutorialTitle) lv.tutorialTitle = lv.title;
  if (!lv.tutorialContent) lv.tutorialContent = lv.concept || '';
  if (!lv.type)          lv.type = 'code';
  // Upgrade hints to 3-level with emoji prefixes
  if (lv.hints && lv.hints.length > 0) {
    const pre = ['💡', '🔶', '🔴'];
    lv.hints = lv.hints.slice(0, 3).map((h, i) => {
      if (/^[💡🔶🔴]/.test(h)) return h;
      return `${pre[i] || '💡'} ${h}`;
    });
    while (lv.hints.length < 3) {
      lv.hints.push('🔴 อ่านแนวคิดด้านบนอีกครั้ง และดู starter code เป็นแนวทาง');
    }
  }
});

// ═══════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════
const GAME_KEY = 'ai';
let currentLevel = 1;
let hintsUsed    = {};
let selectedChoice = -1;
let xpPenalty    = 0;
let attempts     = {};
let completedLocal = new Set();
let xpLocal      = {};
let pyodideReady = false;
let userCredits  = 0;
let tutorMessages = [];  // [{role, content}]

// ═══════════════════════════════════════════════════════════════
// BOOT
// ═══════════════════════════════════════════════════════════════
async function boot() {
  // Load progress
  const saved = JSON.parse(localStorage.getItem(`cq_progress_${GAME_KEY}`) || '{}');
  completedLocal = new Set(saved.completed || []);
  xpLocal = saved.xpMap || {};

  // Build home screen stage map
  buildStageMap();
  updateNavXP();

  // Show app, hide loading screen
  const ls = document.getElementById('loading-screen');
  ls.style.opacity = '0';
  setTimeout(() => {
    ls.style.display = 'none';
    document.getElementById('app').style.display = 'block';
  }, 500);

  // Auth init
  if (window.CodeQuestAuth) await CodeQuestAuth.init();
  if (window.CodeQuestAuth?.currentUser) await loadCredits();

  // Preload Pyodide silently in background
  try {
    await CodeQuestEngine.preloadPyodide(['numpy', 'pandas']);
    pyodideReady = true;
  } catch(e) {
    console.warn('Pyodide preload failed:', e);
  }
}

// ═══════════════════════════════════════════════════════════════
// CREDITS
// ═══════════════════════════════════════════════════════════════
async function loadCredits() {
  try {
    const token = localStorage.getItem('cq_token') || '';
    const res = await fetch('/api/credits.php?action=balance', {
      headers: { 'Authorization': 'Bearer ' + token }
    });
    const json = await res.json();
    userCredits = json.credits || 0;
  } catch(e) { userCredits = 0; }
  updateCreditsUI();
}

function updateCreditsUI() {
  const fab = document.getElementById('tutor-fab');
  const credLink = document.getElementById('credits-link');
  const fabCredits = document.getElementById('fab-credits');
  const modalInfo = document.getElementById('modal-credits-info');

  if (!fab) return;
  if (!window.CodeQuestAuth?.currentUser) { fab.style.display = 'none'; return; }

  fab.style.display = 'flex';
  if (credLink) { credLink.style.display = 'inline'; }
  if (fabCredits) fabCredits.textContent = userCredits;
  if (modalInfo) modalInfo.textContent = `(${userCredits} เครดิตเหลือ)`;
}

// ═══════════════════════════════════════════════════════════════
// AI TUTOR MODAL
// ═══════════════════════════════════════════════════════════════
function openTutor() {
  if (!window.CodeQuestAuth?.currentUser) {
    alert('กรุณาเข้าสู่ระบบก่อนใช้ AI Tutor');
    return;
  }
  if (userCredits <= 0) {
    if (confirm('เครดิตหมดแล้ว ต้องการเติมเครดิตไหม?')) {
      window.open('/credits.php', '_blank');
    }
    return;
  }
  const modal = document.getElementById('tutor-modal');
  modal.style.display = 'flex';
  updateCreditsUI();

  if (tutorMessages.length === 0) {
    appendTutorMsg('assistant', `สวัสดี! ฉันคือ AI Tutor 🤖\nตอนนี้คุณอยู่ด่านที่ ${currentLevel}: "${LEVELS[currentLevel]?.title}"\nถามอะไรเกี่ยวกับด่านนี้ได้เลยนะ (1 คำถาม = 1 เครดิต)`);
  }
  setTimeout(() => document.getElementById('tutor-input')?.focus(), 100);
}

function closeTutor() {
  document.getElementById('tutor-modal').style.display = 'none';
}

function appendTutorMsg(role, text) {
  const box = document.getElementById('tutor-messages');
  if (!box) return;
  const isUser = role === 'user';
  const div = document.createElement('div');
  div.style.cssText = `display:flex;justify-content:${isUser?'flex-end':'flex-start'};`;
  div.innerHTML = `<div style="max-width:85%;padding:10px 14px;border-radius:${isUser?'14px 14px 4px 14px':'14px 14px 14px 4px'};background:${isUser?'rgba(168,85,247,0.2)':'rgba(255,255,255,0.06)'};font-size:0.83rem;line-height:1.6;white-space:pre-wrap;">${escHtml(text)}</div>`;
  box.appendChild(div);
  box.scrollTop = box.scrollHeight;
  return div.querySelector('div');
}

async function sendTutorMsg() {
  const input = document.getElementById('tutor-input');
  const sendBtn = document.getElementById('tutor-send');
  const text = input?.value.trim();
  if (!text) return;
  if (userCredits <= 0) {
    if (confirm('เครดิตหมดแล้ว ต้องการเติมเครดิตไหม?')) window.open('/credits.php', '_blank');
    return;
  }

  input.value = '';
  sendBtn.disabled = true;
  appendTutorMsg('user', text);
  tutorMessages.push({ role: 'user', content: text });

  // Streaming response bubble
  const box = document.getElementById('tutor-messages');
  const bubble = document.createElement('div');
  bubble.style.cssText = 'display:flex;justify-content:flex-start;';
  const inner = document.createElement('div');
  inner.style.cssText = 'max-width:85%;padding:10px 14px;border-radius:14px 14px 14px 4px;background:rgba(255,255,255,0.06);font-size:0.83rem;line-height:1.6;white-space:pre-wrap;';
  inner.textContent = '...';
  bubble.appendChild(inner);
  box.appendChild(bubble);
  box.scrollTop = box.scrollHeight;

  const lv = LEVELS[currentLevel];
  const code = document.getElementById('code-editor')?.value || '';

  try {
    const token = localStorage.getItem('cq_token') || '';
    const resp = await fetch('/api/ai-tutor.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
      body: JSON.stringify({
        questType:   'ai',
        levelNum:    currentLevel,
        levelTitle:  lv?.title || '',
        challenge:   lv?.desc || '',
        studentCode: code,
        testResults: [],
        messages:    tutorMessages.slice(-6)  // ส่ง 6 ข้อความล่าสุด
      })
    });

    if (resp.status === 402) {
      inner.textContent = 'เครดิตหมดแล้ว กรุณาเติมเครดิตก่อน';
      userCredits = 0; updateCreditsUI();
      sendBtn.disabled = false; return;
    }
    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

    const reader = resp.body.getReader();
    const decoder = new TextDecoder();
    let fullText = '';
    inner.textContent = '';

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;
      const lines = decoder.decode(value).split('\n');
      for (const line of lines) {
        if (!line.startsWith('data:')) continue;
        try {
          const data = JSON.parse(line.slice(5).trim());
          if (data.type === 'delta' && data.text) {
            fullText += data.text;
            inner.textContent = fullText;
            box.scrollTop = box.scrollHeight;
          }
        } catch {}
      }
    }

    tutorMessages.push({ role: 'assistant', content: fullText });
    userCredits = Math.max(0, userCredits - 1);
    updateCreditsUI();

  } catch(e) {
    inner.textContent = 'เกิดข้อผิดพลาด: ' + e.message;
  }

  sendBtn.disabled = false;
  input.focus();
}

// ═══════════════════════════════════════════════════════════════
// STAGE MAP
// ═══════════════════════════════════════════════════════════════
function buildStageMap() {
  const map = document.getElementById('stage-map');
  if (!map) return;
  let html = '';
  for (const zone of ZONES) {
    html += `<div class="zone-label zone${zone.id}">${zone.emoji} ${zone.name}</div>`;
    for (const n of zone.levels) {
      const lv = LEVELS[n];
      const done = completedLocal.has(n);
      html += `<div class="stage-node${done ? ' completed' : ''}" onclick="navigateTo(${n})">
        ${done ? '<div class="stage-check">✅</div>' : ''}
        <div class="stage-icon">${zone.emoji}</div>
        <div class="stage-num">ด่าน ${n}</div>
        <div class="stage-name">${lv.title}</div>
        <div class="stage-tag">${lv.diff} · ${lv.xp}XP</div>
      </div>`;
    }
  }
  map.innerHTML = html;
}

function showScreen(id) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  window.scrollTo(0, 0);
}

function goHome() {
  showScreen('home-screen');
  buildStageMap();
}

// ═══════════════════════════════════════════════════════════════
// NAVIGATE — populate static game screen elements (like python_quest.php)
// ═══════════════════════════════════════════════════════════════
function navigateTo(n) {
  currentLevel   = n;
  xpPenalty      = 0;
  selectedChoice = -1;

  const lv   = LEVELS[n];
  const zone = ZONES.find(z => z.id === lv.zone);

  // Top bar
  document.getElementById('game-stage-title').textContent = `ด่าน ${n}: ${lv.title}`;
  const badge = document.getElementById('game-theme-badge');
  badge.textContent = `${zone.emoji} ${zone.name} · ⭐${lv.xp} XP · ${lv.diff}`;
  badge.style.cssText = `background:${zone.color}22;color:${zone.color};border:1px solid ${zone.color}44;padding:3px 10px;border-radius:12px;font-size:0.75rem;`;
  document.getElementById('nav-progress').textContent = `ด่าน ${n}/28`;

  // Story box
  const storyBox = document.getElementById('story-box');
  storyBox.className = `story-box theme-${lv.theme}`;
  document.getElementById('story-char').textContent = lv.character;
  document.getElementById('story-text').innerHTML = lv.story;

  // Tutorial box
  const tutBox = document.getElementById('tutorial-box');
  if (lv.tutorialContent) {
    tutBox.style.display = 'block';
    document.getElementById('tutorial-title').textContent = lv.tutorialTitle || lv.title;
    document.getElementById('tutorial-content').innerHTML = lv.tutorialContent;
  } else {
    tutBox.style.display = 'none';
  }

  // Mission
  document.getElementById('mission-text').innerHTML = lv.mission;

  // Expected output (first stdout test)
  const expectedBox = document.getElementById('expected-box');
  const firstStdout = (lv.tests || []).find(t => t.type === 'stdout');
  if (firstStdout && firstStdout.expected) {
    expectedBox.style.display = 'block';
    document.getElementById('expected-output').textContent = firstStdout.expected;
  } else {
    expectedBox.style.display = 'none';
  }

  // 3-level hints
  renderHints(lv, n);

  // Editor vs choice
  if (lv.type === 'choice') {
    document.getElementById('editor-section').style.display = 'none';
    document.getElementById('choice-section').style.display = 'block';
    renderChoices(lv.choices);
  } else {
    document.getElementById('editor-section').style.display = 'block';
    document.getElementById('choice-section').style.display = 'none';
    document.getElementById('code-editor').value = lv.starter || '';
    document.getElementById('output-body').textContent = 'รอรันโค้ด...';
    document.getElementById('output-body').className = 'output-body';
  }

  showScreen('game-screen');
}

// ─── Hint system ───────────────────────────────────────────────
function renderHints(lv, n) {
  const container = document.getElementById('hint-buttons');
  container.innerHTML = '';
  const defs = [
    { label: '💡 คำใบ้เบาๆ',      penalty: 'ฟรี!',  cls: '',       boxId: 'hint-box-level-1', textId: 'hint-text-1' },
    { label: '🔶 คำใบ้ปานกลาง',   penalty: '-10 XP', cls: 'level2', boxId: 'hint-box-level-2', textId: 'hint-text-2' },
    { label: '🔴 เฉลยแนวทาง',     penalty: '-25 XP', cls: 'level3', boxId: 'hint-box-level-3', textId: 'hint-text-3' },
  ];
  defs.forEach(d => document.getElementById(d.boxId).classList.remove('show'));
  if (!lv.hints) return;
  lv.hints.slice(0, 3).forEach((hintText, i) => {
    const d = defs[i];
    document.getElementById(d.textId).textContent = hintText;
    const btn = document.createElement('button');
    btn.className = `btn-hint-level ${d.cls}`;
    const used = hintsUsed[n] && hintsUsed[n].includes(i);
    if (used) btn.classList.add('used');
    btn.innerHTML = `${d.label} <span class="xp-penalty">${d.penalty}</span>`;
    btn.onclick = () => toggleHintLevel(n, i, d.boxId);
    container.appendChild(btn);
  });
}

function toggleHintLevel(levelNum, hintIdx, boxId) {
  const box = document.getElementById(boxId);
  const showing = box.classList.contains('show');
  ['hint-box-level-1','hint-box-level-2','hint-box-level-3'].forEach(id =>
    document.getElementById(id).classList.remove('show'));
  if (!showing) {
    box.classList.add('show');
    if (!hintsUsed[levelNum]) hintsUsed[levelNum] = [];
    if (!hintsUsed[levelNum].includes(hintIdx)) {
      hintsUsed[levelNum].push(hintIdx);
      xpPenalty += [0, 10, 25][hintIdx];
    }
  }
}

// ─── Choice system ─────────────────────────────────────────────
function renderChoices(choices) {
  const area = document.getElementById('choices-area');
  area.innerHTML = '';
  const labels = ['A','B','C','D'];
  (choices || []).forEach((choice, i) => {
    const btn = document.createElement('div');
    btn.className = 'choice-btn';
    btn.innerHTML = `<div class="choice-label">${labels[i]}</div><div class="choice-text">${choice.text}</div>`;
    btn.onclick = () => selectChoice(i);
    area.appendChild(btn);
  });
}

function selectChoice(idx) {
  selectedChoice = idx;
  document.querySelectorAll('.choice-btn').forEach((b, i) => b.classList.toggle('selected', i === idx));
}

// ═══════════════════════════════════════════════════════════════
// RUN (just run + show output — no modal)
// ═══════════════════════════════════════════════════════════════
async function runCode() {
  const code = document.getElementById('code-editor').value;
  const lv   = LEVELS[currentLevel];
  const btn  = document.getElementById('run-btn');
  btn.disabled = true; btn.textContent = '⏳ กำลังรัน...';

  if (!pyodideReady) showLoading('กำลังโหลด Python (ครั้งแรกอาจนาน ~30 วินาที)...');

  try {
    const pkgs = [];
    if (code.includes('numpy') || code.includes(' np'))    pkgs.push('numpy');
    if (code.includes('pandas') || code.includes(' pd'))   pkgs.push('pandas');

    const results = await CodeQuestEngine.validate('python', code, lv.tests, { packages: pkgs });
    pyodideReady = true;
    hideLoading();

    const ob = document.getElementById('output-body');
    const r  = results[0];
    if (r) {
      ob.textContent = r.output || '(ไม่มี output)';
      ob.className   = `output-body${r.error ? ' error' : ''}`;
    }
  } catch(err) {
    hideLoading();
    const ob = document.getElementById('output-body');
    ob.textContent = err.message || 'เกิดข้อผิดพลาด';
    ob.className   = 'output-body error';
  }

  btn.disabled = false; btn.textContent = '▶ รันโค้ด';
}

// ═══════════════════════════════════════════════════════════════
// SUBMIT (validate + modal)
// ═══════════════════════════════════════════════════════════════
async function submitCode() {
  const code = document.getElementById('code-editor').value;
  const lv   = LEVELS[currentLevel];
  const btn  = document.getElementById('run-btn');
  btn.disabled = true;

  attempts[currentLevel] = (attempts[currentLevel] || 0) + 1;

  if (!pyodideReady) showLoading('กำลังโหลด Python (ครั้งแรกอาจนาน ~30 วินาที)...');

  try {
    const pkgs = [];
    if (code.includes('numpy') || code.includes(' np'))    pkgs.push('numpy');
    if (code.includes('pandas') || code.includes(' pd'))   pkgs.push('pandas');

    const results = await CodeQuestEngine.validate('python', code, lv.tests, { packages: pkgs });
    pyodideReady = true;
    hideLoading();

    // Show output
    const ob = document.getElementById('output-body');
    const r  = results[0];
    if (r) {
      ob.textContent = r.output || '(ไม่มี output)';
      ob.className   = `output-body${r.error ? ' error' : ''}`;
    }

    const { allPassed } = CodeQuestEngine.calculateScore(results);
    showResult(allPassed, lv);
  } catch(err) {
    hideLoading();
    const ob = document.getElementById('output-body');
    ob.textContent = err.message || 'เกิดข้อผิดพลาด';
    ob.className   = 'output-body error';
    showResult(false, lv, err.message);
  }

  btn.disabled = false;
}

function submitChoice() {
  const lv = LEVELS[currentLevel];
  if (selectedChoice < 0) return;
  const correct = lv.choices[selectedChoice].correct;
  const btns = document.querySelectorAll('.choice-btn');
  lv.choices.forEach((c, i) => {
    if (c.correct) btns[i].classList.add('correct');
    else if (i === selectedChoice) btns[i].classList.add('wrong');
  });
  setTimeout(() => {
    showResult(correct, lv);
    btns.forEach(b => b.classList.remove('correct','wrong','selected'));
  }, 800);
}

function showResult(success, lv, errorMsg) {
  const modal   = document.getElementById('result-modal');
  const content = document.getElementById('modal-content');
  if (success) {
    content.className = 'modal-content';
    document.getElementById('modal-icon').textContent  = currentLevel === 28 ? '🏆' : '🎉';
    document.getElementById('modal-title').textContent = 'ผ่านด่าน!';
    document.getElementById('modal-text').innerHTML    = lv.successMsg || `ผ่านด่าน ${currentLevel}!`;
    const earned = Math.max(0, lv.xp - xpPenalty);
    document.getElementById('modal-xp').textContent   = `+${earned} XP`;
    const btn = document.getElementById('modal-btn');
    if (currentLevel === 28) {
      btn.textContent = '🏆 จบ AI Quest!';
      btn.className   = 'btn-next-modal';
      btn.onclick     = () => { closeModal(); showScreen('victory-screen'); };
    } else {
      btn.textContent = 'ด่านถัดไป →';
      btn.className   = 'btn-next-modal';
      btn.onclick     = () => { closeModal(); navigateTo(currentLevel + 1); };
    }
    saveProgress();
  } else {
    content.className = 'modal-content fail';
    document.getElementById('modal-icon').textContent  = '💥';
    document.getElementById('modal-title').textContent = 'ยังไม่ถูก!';
    document.getElementById('modal-text').textContent  = errorMsg || 'ลองรันโค้ดก่อน เทียบ output กับผลลัพธ์ที่ต้องการ แล้วแก้ไข — ใช้คำใบ้ได้นะ!';
    document.getElementById('modal-xp').textContent    = '';
    const btn = document.getElementById('modal-btn');
    btn.textContent = '🔄 ลองอีกครั้ง';
    btn.className   = 'btn-retry';
    btn.onclick     = closeModal;
  }
  modal.classList.add('show');
}

function closeModal() { document.getElementById('result-modal').classList.remove('show'); }

// ═══════════════════════════════════════════════════════════════
// SAVE PROGRESS
// ═══════════════════════════════════════════════════════════════
function saveProgress() {
  const lv = LEVELS[currentLevel];
  if (!completedLocal.has(currentLevel)) {
    completedLocal.add(currentLevel);
    xpLocal[currentLevel] = Math.max(0, lv.xp - xpPenalty);
  }

  const saved = JSON.parse(localStorage.getItem(`cq_progress_${GAME_KEY}`) || '{}');
  saved.completed = [...completedLocal];
  saved.xpMap     = xpLocal;
  saved.totalXp   = Object.values(xpLocal).reduce((a,b) => a+b, 0);
  localStorage.setItem(`cq_progress_${GAME_KEY}`, JSON.stringify(saved));

  updateNavXP();

  if (window.CodeQuestAuth?.currentUser && window.CodeQuestProgress) {
    CodeQuestProgress.completeLevel(
      currentLevel, lv.xp,
      (hintsUsed[currentLevel] || []).length,
      attempts[currentLevel] || 1,
      lv.tests.length, lv.tests.length
    ).catch(console.warn);
  }
}

function updateNavXP() {
  const total = Object.values(xpLocal).reduce((a,b) => a+b, 0);
  const el = document.getElementById('nav-xp');
  if (el) el.textContent = `${total} XP`;
  const bar = document.getElementById('xp-bar-fill');
  if (bar) bar.style.width = Math.min(100, (total / 2800) * 100) + '%';
}

// ═══════════════════════════════════════════════════════════════
// HELPERS
// ═══════════════════════════════════════════════════════════════
function resetCode() {
  const ed = document.getElementById('code-editor');
  if (ed) ed.value = LEVELS[currentLevel].starter;
  const ob = document.getElementById('output-body');
  if (ob) { ob.textContent = 'รอรันโค้ด...'; ob.className = 'output-body'; }
}

function showFinish() {
  const totalXp = Object.values(xpLocal).reduce((a,b) => a+b, 0);
  const titleEl = document.getElementById('victory-title');
  const subEl   = document.getElementById('victory-sub');
  const xpEl    = document.getElementById('victory-xp');
  if (titleEl) titleEl.textContent = 'จบ AI Quest แล้ว!';
  if (subEl)   subEl.textContent   = 'คุณผ่านครบ 28 ด่าน ตอนนี้คุณมีพื้นฐาน Python, NumPy, pandas และ Machine Learning แล้ว';
  if (xpEl)    xpEl.textContent    = `รวม ${totalXp} XP`;
  showScreen('victory-screen');
}

function showLoading(msg) {
  const el = document.getElementById('loading-text');
  if (el) el.textContent = msg;
  const ov = document.getElementById('loading');
  if (ov) ov.classList.add('visible');
}
function hideLoading() {
  const ov = document.getElementById('loading');
  if (ov) ov.classList.remove('visible');
}

function escHtml(str) {
  return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Keyboard: Tab in editor
document.addEventListener('keydown', e => {
  if (e.key === 'Tab' && document.activeElement.id === 'code-editor') {
    e.preventDefault();
    const ta = document.activeElement;
    const s = ta.selectionStart, end = ta.selectionEnd;
    ta.value = ta.value.substring(0, s) + '    ' + ta.value.substring(end);
    ta.selectionStart = ta.selectionEnd = s + 4;
  }
  if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
    e.preventDefault(); runCode();
  }
});

// Boot
boot();
</script>
</body>
</html>
