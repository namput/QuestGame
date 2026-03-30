/**
 * CodeQuest AI Tutor v2.0
 * ผู้ช่วยสอน AI ที่รู้บริบทเต็มรูปแบบ
 *
 * ฟีเจอร์:
 *   - ปุ่มลอยมุมขวาล่าง เปิด/ปิด chat panel
 *   - รู้ level, โจทย์, โค้ดนักเรียน, ผลลัพธ์ test cases
 *   - สนทนาเป็นภาษาไทย, ตอบเป็นโค้ดได้
 *   - ประวัติ session เก็บใน sessionStorage
 *   - เชื่อมกับ endpoint ที่กำหนดได้ (Supabase Edge / Netlify / PHP)
 *
 * วิธีใช้:
 *   <script src="codequest-tutor.js"></script>
 *   CodeQuestTutor.init({
 *     endpoint: '/api/ai-tutor',   // หรือ URL ของ edge function
 *     questType: 'python',
 *     getLevelContext: () => ({    // callback ดึงบริบทปัจจุบัน
 *       levelNum:   currentLevel,
 *       levelTitle: levels[currentLevel].title,
 *       challenge:  levels[currentLevel].challenge,
 *       code:       editor.value,
 *       testResults: lastTestResults  // array จาก CodeQuestEngine.validate()
 *     })
 *   });
 */

'use strict';

const CodeQuestTutor = (() => {

  // ─── STATE ─────────────────────────────────────────────────────────────────
  let _config    = null;
  let _open      = false;
  let _loading   = false;
  let _messages  = [];  // { role: 'user'|'assistant', content: string }
  let _storageKey = 'cqt_session';

  // ─── INIT ──────────────────────────────────────────────────────────────────
  function init(config = {}) {
    _config = {
      endpoint:       config.endpoint       || null,
      questType:      config.questType      || 'python',
      getLevelContext: config.getLevelContext || (() => ({})),
      maxHistory:     config.maxHistory     || 20,
      placeholder:    config.placeholder    || 'ถามอะไรก็ได้เกี่ยวกับโจทย์นี้...',
    };

    _storageKey = `cqt_${_config.questType}`;
    _loadHistory();
    _inject();

    console.log('[CodeQuestTutor] initialized', _config.questType);
  }

  // ─── HISTORY ───────────────────────────────────────────────────────────────
  function _loadHistory() {
    try {
      const raw = sessionStorage.getItem(_storageKey);
      _messages = raw ? JSON.parse(raw) : [];
    } catch (_) {
      _messages = [];
    }
  }

  function _saveHistory() {
    try {
      // เก็บแค่ maxHistory ล่าสุด
      const trimmed = _messages.slice(-(_config.maxHistory * 2));
      sessionStorage.setItem(_storageKey, JSON.stringify(trimmed));
    } catch (_) {}
  }

  function clearHistory() {
    _messages = [];
    sessionStorage.removeItem(_storageKey);
    _renderMessages();
  }

  // ─── INJECT UI ─────────────────────────────────────────────────────────────
  function _inject() {
    if (document.getElementById('cqt-root')) return;
    _injectStyles();

    const root = document.createElement('div');
    root.id = 'cqt-root';
    root.innerHTML = `
      <!-- Floating Button -->
      <button id="cqt-btn" onclick="CodeQuestTutor.toggle()" title="ถาม AI Tutor">
        <span id="cqt-btn-icon">🤖</span>
        <span id="cqt-btn-label">AI Tutor</span>
        <span id="cqt-notif" class="cqt-notif" style="display:none"></span>
      </button>

      <!-- Chat Panel -->
      <div id="cqt-panel" class="cqt-panel">
        <div class="cqt-header">
          <div class="cqt-header-left">
            <span class="cqt-header-icon">🤖</span>
            <div>
              <div class="cqt-header-title">AI Tutor</div>
              <div class="cqt-header-sub">ผู้ช่วยสอนส่วนตัว</div>
            </div>
          </div>
          <div class="cqt-header-right">
            <button class="cqt-icon-btn" onclick="CodeQuestTutor.clearHistory()" title="ล้างประวัติ">🗑️</button>
            <button class="cqt-icon-btn" onclick="CodeQuestTutor.toggle()" title="ปิด">✕</button>
          </div>
        </div>

        <div id="cqt-messages" class="cqt-messages">
          <!-- messages rendered here -->
        </div>

        <div id="cqt-typing" class="cqt-typing" style="display:none">
          <div class="cqt-typing-dots"><span></span><span></span><span></span></div>
          <span>กำลังคิด...</span>
        </div>

        <div class="cqt-input-area">
          <div class="cqt-quick-btns" id="cqt-quick-btns">
            <button onclick="CodeQuestTutor.quickAsk('ทำไมโค้ดของฉันผิด?')">🔍 ทำไมผิด?</button>
            <button onclick="CodeQuestTutor.quickAsk('อธิบาย concept ของด่านนี้ให้ฟังหน่อย')">📚 อธิบาย</button>
            <button onclick="CodeQuestTutor.quickAsk('ให้ hint เบาๆ โดยไม่เฉลย')">💡 Hint</button>
            <button onclick="CodeQuestTutor.quickAsk('โค้ดของฉันมีปัญหาอะไรบ้าง?')">🐛 Debug</button>
          </div>
          <div class="cqt-input-row">
            <textarea
              id="cqt-input"
              placeholder="${_config.placeholder}"
              rows="1"
              onkeydown="CodeQuestTutor.onKeyDown(event)"
              oninput="CodeQuestTutor.autoResize(this)"
            ></textarea>
            <button id="cqt-send" onclick="CodeQuestTutor.send()" title="ส่ง (Enter)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
            </button>
          </div>
          <div class="cqt-footer-note">AI อาจผิดพลาดได้ · ใช้เป็นแนวทางเท่านั้น</div>
        </div>
      </div>
    `;
    document.body.appendChild(root);

    _renderMessages();

    // Welcome message ถ้ายังไม่มีประวัติ
    if (_messages.length === 0) {
      _addBotMessage(
        'สวัสดีครับ! 👋 ผมคือ AI Tutor ของ CodeQuest\n\n' +
        'ผมรู้บริบทของด่านที่คุณกำลังเล่นอยู่ครับ ' +
        'ถามได้เลยว่า "ทำไมโค้ดผิด" หรือ "อธิบาย concept" หรืออะไรก็ได้ ' +
        'ผมจะช่วยได้โดยไม่เฉลยคำตอบตรงๆ 😊'
      );
    }
  }

  // ─── TOGGLE ────────────────────────────────────────────────────────────────
  function toggle() {
    _open = !_open;
    const panel = document.getElementById('cqt-panel');
    const btn   = document.getElementById('cqt-btn');
    if (!panel) return;

    if (_open) {
      panel.classList.add('open');
      btn.classList.add('active');
      document.getElementById('cqt-input')?.focus();
      _scrollToBottom();
    } else {
      panel.classList.remove('open');
      btn.classList.remove('active');
    }
  }

  // ─── SEND ──────────────────────────────────────────────────────────────────
  function quickAsk(text) {
    const input = document.getElementById('cqt-input');
    if (input) { input.value = text; autoResize(input); }
    send();
  }

  async function send() {
    if (_loading) return;

    const input = document.getElementById('cqt-input');
    const text  = input?.value?.trim();
    if (!text) return;

    input.value = '';
    autoResize(input);

    // Hide quick buttons after first message
    const quickBtns = document.getElementById('cqt-quick-btns');
    if (quickBtns) quickBtns.style.display = 'none';

    // Add user message
    _messages.push({ role: 'user', content: text });
    _saveHistory();
    _renderMessages();
    _scrollToBottom();

    // Show typing indicator
    _loading = true;
    _setTyping(true);

    try {
      const ctx      = _config.getLevelContext?.() || {};
      const response = await _callAPI(text, ctx);

      _addBotMessage(response);
    } catch (e) {
      _addBotMessage(
        '❌ ขอโทษครับ เชื่อมต่อ AI ไม่ได้ตอนนี้\n\n' +
        'ลองใช้ hint ในเกมแทนก่อนนะครับ หรือลองใหม่อีกครั้ง'
      );
      console.error('[CodeQuestTutor] API error:', e);
    } finally {
      _loading = false;
      _setTyping(false);
    }
  }

  // ─── API CALL ──────────────────────────────────────────────────────────────
  async function _callAPI(userMessage, ctx) {
    if (!_config.endpoint) {
      // Demo mode (no endpoint configured)
      await new Promise(r => setTimeout(r, 800));
      return _demoResponse(userMessage, ctx);
    }

    const payload = {
      questType:    _config.questType,
      levelNum:     ctx.levelNum,
      levelTitle:   ctx.levelTitle,
      challenge:    ctx.challenge,
      studentCode:  ctx.code,
      testResults:  ctx.testResults,
      messages:     _messages.slice(-(_config.maxHistory * 2))
    };

    const res = await fetch(_config.endpoint, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(payload)
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    // Support both streaming (SSE) and regular JSON
    const contentType = res.headers.get('content-type') || '';

    if (contentType.includes('text/event-stream')) {
      return await _readStream(res);
    } else {
      const json = await res.json();
      return json.response || json.message || json.content || 'ไม่มีคำตอบ';
    }
  }

  async function _readStream(res) {
    const reader = res.body.getReader();
    const decoder = new TextDecoder();
    let full = '';

    // Add empty assistant message for streaming
    _messages.push({ role: 'assistant', content: '' });
    const msgIdx = _messages.length - 1;
    _renderMessages();

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;

      const chunk = decoder.decode(value);
      const lines = chunk.split('\n');

      for (const line of lines) {
        if (!line.startsWith('data: ')) continue;
        const data = line.slice(6).trim();
        if (data === '[DONE]') break;
        try {
          const json = JSON.parse(data);
          const token = json.choices?.[0]?.delta?.content
                     || json.delta?.text
                     || json.token
                     || '';
          full += token;
          _messages[msgIdx].content = full;
          _renderMessages();
          _scrollToBottom();
        } catch (_) {}
      }
    }

    _saveHistory();
    return full;
  }

  // ─── DEMO MODE (ไม่มี endpoint) ──────────────────────────────────────────
  function _demoResponse(userMessage, ctx) {
    const lower = userMessage.toLowerCase();

    if (lower.includes('ทำไม') && lower.includes('ผิด')) {
      if (ctx.testResults?.some(r => r.error)) {
        const err = ctx.testResults.find(r => r.error)?.error;
        return `จากที่ผมดูโค้ดของคุณ มี error นี้ครับ:\n\n\`${err}\`\n\nลองตรวจดู syntax และ indentation นะครับ`;
      }
      return `ผมยังดูโค้ดไม่ได้เต็มที่ แต่ลองตรวจดู:\n1. Indentation ถูกต้องไหม?\n2. ลืม `:` หลัง if/for/def ไหม?\n3. ชื่อตัวแปร spelling ถูกไหม?`;
    }

    if (lower.includes('hint') || lower.includes('ช่วย')) {
      return `💡 Hint เบาๆ นะครับ:\n\nลองอ่านโจทย์อีกครั้งช้าๆ แล้วถามตัวเองว่า "output ที่ต้องการคืออะไร" และ "input ที่ได้รับคืออะไร"\n\nถ้ายังติดอยู่ บอกผมได้ว่าติดตรงไหนครับ`;
    }

    if (lower.includes('อธิบาย') || lower.includes('concept')) {
      return `ผมพร้อมอธิบายครับ! แต่ตอนนี้ยังไม่ได้เชื่อมต่อ AI จริงๆ\n\nสำหรับ concept ของด่านนี้ ลองอ่านในส่วน Tutorial ทางซ้ายมือก่อนนะครับ มีตัวอย่างโค้ดอยู่ด้วย`;
    }

    return `ขอบคุณที่ถามนะครับ! ผมยังอยู่ใน demo mode อยู่\n\nถ้าอยากใช้ AI จริงๆ ให้ admin ตั้งค่า API endpoint ในระบบก่อนนะครับ 😊`;
  }

  // ─── RENDER ────────────────────────────────────────────────────────────────
  function _addBotMessage(content) {
    _messages.push({ role: 'assistant', content });
    _saveHistory();
    _renderMessages();
    _scrollToBottom();
  }

  function _renderMessages() {
    const container = document.getElementById('cqt-messages');
    if (!container) return;

    if (_messages.length === 0) {
      container.innerHTML = `
        <div class="cqt-empty">
          <div class="cqt-empty-icon">🤖</div>
          <div class="cqt-empty-text">ถามอะไรก็ได้ ผมช่วยได้ครับ</div>
        </div>
      `;
      return;
    }

    container.innerHTML = _messages.map(msg => `
      <div class="cqt-msg ${msg.role}">
        ${msg.role === 'assistant' ? '<div class="cqt-avatar">🤖</div>' : ''}
        <div class="cqt-bubble">
          ${_renderContent(msg.content)}
        </div>
        ${msg.role === 'user' ? '<div class="cqt-avatar user">👤</div>' : ''}
      </div>
    `).join('');
  }

  // Simple markdown-like renderer
  function _renderContent(text) {
    if (!text) return '';

    // Code blocks ```...```
    text = text.replace(/```(\w*)\n?([\s\S]*?)```/g, (_, lang, code) =>
      `<pre class="cqt-code"><code class="lang-${lang || 'text'}">${_esc(code.trim())}</code></pre>`
    );

    // Inline code `...`
    text = text.replace(/`([^`]+)`/g, (_, code) =>
      `<code class="cqt-inline-code">${_esc(code)}</code>`
    );

    // Bold **...**
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

    // Italic *...*
    text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');

    // Newlines → <br> (but not inside <pre>)
    text = text.replace(/\n/g, '<br>');

    // Numbered lists
    text = text.replace(/(\d+)\. (.*?)(<br>|$)/g, '<div class="cqt-li num"><span>$1.</span>$2</div>');

    // Bullet lists
    text = text.replace(/- (.*?)(<br>|$)/g, '<div class="cqt-li"><span>•</span>$1</div>');

    return text;
  }

  function _setTyping(show) {
    const el = document.getElementById('cqt-typing');
    if (el) el.style.display = show ? 'flex' : 'none';
    if (show) _scrollToBottom();
  }

  function _scrollToBottom() {
    const el = document.getElementById('cqt-messages');
    if (el) el.scrollTop = el.scrollHeight;
  }

  // ─── INPUT HELPERS ─────────────────────────────────────────────────────────
  function onKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      send();
    }
  }

  function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
  }

  // ─── CONTEXT HELPERS (เรียกจาก quest file) ───────────────────────────────
  // อัปเดต context ล่าสุด (เรียกหลัง validate หรือตอน level เปลี่ยน)
  function setLevelContext(ctx) {
    if (_config) {
      const prev = _config.getLevelContext;
      _config.getLevelContext = () => ({ ...prev(), ...ctx });
    }
  }

  // ─── STYLES ────────────────────────────────────────────────────────────────
  function _injectStyles() {
    if (document.getElementById('cqt-styles')) return;
    const s = document.createElement('style');
    s.id = 'cqt-styles';
    s.textContent = `
      /* ── Floating Button ── */
      #cqt-btn {
        position: fixed;
        bottom: 24px; right: 24px;
        z-index: 9000;
        display: flex; align-items: center; gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #7c3aed, #4ecdc4);
        border: none; border-radius: 50px;
        color: #fff; font-family: 'Prompt', sans-serif;
        font-size: .9rem; font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(124,58,237,.4);
        transition: transform .2s, box-shadow .2s;
      }
      #cqt-btn:hover  { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,58,237,.5); }
      #cqt-btn.active { background: linear-gradient(135deg, #5b21b6, #0e9f8e); }
      #cqt-btn-icon   { font-size: 1.1rem; }
      .cqt-notif {
        position: absolute; top: -4px; right: -4px;
        width: 10px; height: 10px;
        background: #ef4444; border-radius: 50%;
        border: 2px solid #0f0e17;
      }

      /* ── Panel ── */
      .cqt-panel {
        position: fixed;
        bottom: 80px; right: 24px;
        z-index: 8999;
        width: 380px;
        max-width: calc(100vw - 48px);
        height: 560px;
        max-height: calc(100vh - 120px);
        background: #13122b;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 20px;
        display: flex; flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,.6);
        transform: translateY(16px) scale(.97);
        opacity: 0;
        pointer-events: none;
        transition: transform .25s cubic-bezier(.34,1.56,.64,1), opacity .2s;
        overflow: hidden;
      }
      .cqt-panel.open {
        transform: translateY(0) scale(1);
        opacity: 1;
        pointer-events: auto;
      }

      /* ── Header ── */
      .cqt-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px;
        background: linear-gradient(135deg, rgba(124,58,237,.15), rgba(78,205,196,.1));
        border-bottom: 1px solid rgba(255,255,255,.08);
        flex-shrink: 0;
      }
      .cqt-header-left  { display: flex; align-items: center; gap: 10px; }
      .cqt-header-icon  { font-size: 1.5rem; }
      .cqt-header-title { font-family: 'Prompt', sans-serif; font-weight: 700; font-size: .95rem; color: #e2e8f0; }
      .cqt-header-sub   { font-family: 'Prompt', sans-serif; font-size: .72rem; color: #94a1b2; margin-top: 1px; }
      .cqt-header-right { display: flex; align-items: center; gap: 4px; }
      .cqt-icon-btn {
        background: none; border: none;
        color: #94a1b2; font-size: 1rem; cursor: pointer;
        padding: 4px 6px; border-radius: 6px;
        transition: background .2s, color .2s;
      }
      .cqt-icon-btn:hover { background: rgba(255,255,255,.08); color: #e2e8f0; }

      /* ── Messages ── */
      .cqt-messages {
        flex: 1; overflow-y: auto; padding: 16px;
        display: flex; flex-direction: column; gap: 12px;
        scroll-behavior: smooth;
      }
      .cqt-messages::-webkit-scrollbar { width: 4px; }
      .cqt-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

      .cqt-empty { text-align: center; margin: auto; opacity: .4; }
      .cqt-empty-icon { font-size: 2.5rem; margin-bottom: 8px; }
      .cqt-empty-text { font-family: 'Prompt', sans-serif; font-size: .85rem; color: #94a1b2; }

      .cqt-msg {
        display: flex; align-items: flex-end; gap: 8px;
      }
      .cqt-msg.user     { flex-direction: row-reverse; }
      .cqt-msg.assistant { flex-direction: row; }

      .cqt-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed, #4ecdc4);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; flex-shrink: 0;
      }
      .cqt-avatar.user { background: linear-gradient(135deg, #ff6b6b, #f59e0b); }

      .cqt-bubble {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 16px;
        font-family: 'Prompt', sans-serif; font-size: .83rem;
        line-height: 1.6; color: #e2e8f0;
      }
      .cqt-msg.user .cqt-bubble {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        border-bottom-right-radius: 4px;
      }
      .cqt-msg.assistant .cqt-bubble {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        border-bottom-left-radius: 4px;
      }

      /* Code in chat */
      .cqt-code {
        background: #0d0c1d; border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px; padding: 10px 12px;
        overflow-x: auto; margin: 8px 0;
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: .78rem; line-height: 1.6;
        white-space: pre; color: #e2e8f0;
      }
      .cqt-inline-code {
        font-family: 'Fira Code', monospace;
        background: rgba(124,58,237,.2); color: #c4b5fd;
        padding: 1px 5px; border-radius: 4px; font-size: .82em;
      }
      .cqt-li { display: flex; gap: 6px; margin: 2px 0; }
      .cqt-li span { flex-shrink: 0; color: #7c3aed; font-weight: 700; }

      /* ── Typing Indicator ── */
      .cqt-typing {
        display: flex; align-items: center; gap: 8px;
        padding: 4px 16px 8px;
        font-family: 'Prompt', sans-serif; font-size: .75rem; color: #94a1b2;
        flex-shrink: 0;
      }
      .cqt-typing-dots { display: flex; gap: 4px; }
      .cqt-typing-dots span {
        width: 6px; height: 6px; border-radius: 50%;
        background: #7c3aed; opacity: .4;
        animation: cqt-bounce .8s ease-in-out infinite;
      }
      .cqt-typing-dots span:nth-child(2) { animation-delay: .15s; }
      .cqt-typing-dots span:nth-child(3) { animation-delay: .3s; }
      @keyframes cqt-bounce {
        0%, 60%, 100% { opacity: .4; transform: translateY(0); }
        30%            { opacity: 1;  transform: translateY(-4px); }
      }

      /* ── Input Area ── */
      .cqt-input-area {
        padding: 10px 12px 12px;
        border-top: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
      }

      .cqt-quick-btns {
        display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 8px;
      }
      .cqt-quick-btns button {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 20px; color: #94a1b2;
        font-family: 'Prompt', sans-serif; font-size: .72rem;
        padding: 4px 10px; cursor: pointer;
        transition: all .2s;
      }
      .cqt-quick-btns button:hover {
        background: rgba(124,58,237,.15);
        border-color: rgba(124,58,237,.4);
        color: #c4b5fd;
      }

      .cqt-input-row {
        display: flex; align-items: flex-end; gap: 8px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 14px; padding: 8px 10px 8px 14px;
        transition: border-color .2s;
      }
      .cqt-input-row:focus-within { border-color: rgba(124,58,237,.5); }

      #cqt-input {
        flex: 1; background: none; border: none; outline: none;
        color: #e2e8f0; font-family: 'Prompt', sans-serif; font-size: .85rem;
        resize: none; min-height: 20px; max-height: 120px;
        line-height: 1.5;
      }
      #cqt-input::placeholder { color: #555; }

      #cqt-send {
        background: linear-gradient(135deg, #7c3aed, #4ecdc4);
        border: none; border-radius: 8px;
        color: #fff; width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; flex-shrink: 0;
        transition: transform .15s, opacity .15s;
      }
      #cqt-send:hover   { transform: scale(1.05); }
      #cqt-send:active  { transform: scale(.95); }
      #cqt-send:disabled { opacity: .5; cursor: not-allowed; }

      .cqt-footer-note {
        text-align: center; font-size: .65rem; color: #3d3d5c;
        font-family: 'Prompt', sans-serif; margin-top: 6px;
      }

      /* Mobile */
      @media (max-width: 480px) {
        .cqt-panel { right: 8px; bottom: 72px; width: calc(100vw - 16px); }
        #cqt-btn   { right: 16px; bottom: 16px; }
      }
    `;
    document.head.appendChild(s);
  }

  // ─── UTILS ────────────────────────────────────────────────────────────────
  function _esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // ─── PUBLIC API ───────────────────────────────────────────────────────────
  return {
    init,
    toggle,
    send,
    quickAsk,
    clearHistory,
    setLevelContext,
    onKeyDown,
    autoResize,
  };

})();
