// ============================================================
// CodeQuest Auth & Progress System v2 — PHP Backend
// ผจญภัยแดนโค้ด
// ============================================================
// ทำงานร่วมกับ api/auth.php, api/progress.php
// ไม่ต้องการ Supabase
// ============================================================

const API_BASE = './api';   // เปลี่ยนเป็น '/api' ถ้า HTML อยู่ที่ root

// ============================================================
// AUTH FUNCTIONS
// ============================================================

const CodeQuestAuth = {
  currentUser: null,
  profile:     null,
  token:       null,

  // เช็คว่า login อยู่ไหม (เรียกตอนโหลดหน้า)
  async init() {
    this.token = localStorage.getItem('cq_token');
    if (!this.token) {
      this.updateUI();
      return null;
    }
    try {
      const res  = await this._api('GET', '/auth.php?action=me');
      if (res.error) { this._clearSession(); return null; }
      this.currentUser = res.user;
      this.profile     = res.user;
      this.updateUI();
      return this.currentUser;
    } catch {
      this._clearSession();
      return null;
    }
  },

  // สมัครสมาชิก
  async signUp(email, password, displayName) {
    const res = await this._api('POST', '/auth.php?action=register', {
      email, password, display_name: displayName
    });
    if (res.error) throw new Error(res.error);
    this._setSession(res.token, res.user);
    return res;
  },

  // เข้าสู่ระบบ
  async signIn(email, password) {
    const res = await this._api('POST', '/auth.php?action=login', {
      email, password
    });
    if (res.error) throw new Error(res.error);
    this._setSession(res.token, res.user);
    return res;
  },

  // ออกจากระบบ
  async signOut() {
    await this._api('POST', '/auth.php?action=logout').catch(() => {});
    this._clearSession();
    this.updateUI();
  },

  // อัปเดตโปรไฟล์
  async updateProfile(data) {
    const res = await this._api('POST', '/auth.php?action=update_profile', data);
    if (res.error) throw new Error(res.error);
    this.profile = { ...this.profile, ...data };
    this.currentUser = this.profile;
    this.updateUI();
    return res;
  },

  // ─── Session helpers ────────────────────────────────────────

  _setSession(token, user) {
    this.token       = token;
    this.currentUser = user;
    this.profile     = user;
    localStorage.setItem('cq_token', token);
    this.updateUI();

    if (typeof onAuthChange === 'function') onAuthChange('SIGNED_IN');
  },

  _clearSession() {
    this.token       = null;
    this.currentUser = null;
    this.profile     = null;
    localStorage.removeItem('cq_token');
    if (typeof onAuthChange === 'function') onAuthChange('SIGNED_OUT');
  },

  // ─── HTTP helper ────────────────────────────────────────────

  async _api(method, path, body) {
    const opts = {
      method,
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
    };
    if (this.token) opts.headers['Authorization'] = `Bearer ${this.token}`;
    if (body) opts.body = JSON.stringify(body);

    let res;
    try {
      res = await fetch(API_BASE + path, opts);
    } catch (e) {
      throw new Error('ไม่สามารถเชื่อมต่อ server ได้ (' + e.message + ')');
    }
    const json = await res.json().catch(() => null);
    if (json === null) {
      throw new Error(`API error: HTTP ${res.status} — กรุณาตรวจสอบว่าอัพโหลด PHP files แล้ว`);
    }
    return json;
  },

  // ─── UI helpers ─────────────────────────────────────────────

  updateUI() {
    const authBtn   = document.getElementById('auth-btn');
    const userInfo  = document.getElementById('user-info');
    const userName  = document.getElementById('user-name');
    const syncBadge = document.getElementById('sync-badge');
    const adminItem = document.getElementById('admin-nav-item');

    if (this.currentUser) {
      if (authBtn)   authBtn.style.display  = 'none';
      if (userInfo)  userInfo.style.display = 'flex';
      if (userName)  userName.textContent   = this.profile?.display_name || this.currentUser.email;
      if (syncBadge) { syncBadge.className = 'sync-badge'; syncBadge.innerHTML = '☁️ Saved'; }
      if (adminItem) adminItem.style.display = this.profile?.is_admin ? 'list-item' : 'none';
    } else {
      if (authBtn)   authBtn.style.display  = 'inline-flex';
      if (userInfo)  userInfo.style.display = 'none';
      if (syncBadge) { syncBadge.className = 'sync-badge offline'; syncBadge.innerHTML = '💾 Local'; }
      if (adminItem) adminItem.style.display = 'none';
    }
  },

  // ─── Modal ──────────────────────────────────────────────────

  createAuthModal() {
    if (document.getElementById('auth-modal')) return;
    const modal = document.createElement('div');
    modal.id    = 'auth-modal';
    modal.innerHTML = `
      <div class="auth-overlay" onclick="CodeQuestAuth.closeModal()"></div>
      <div class="auth-box">
        <button class="auth-close" onclick="CodeQuestAuth.closeModal()">&times;</button>
        <div class="auth-logo">🏰 ผจญภัยแดนโค้ด</div>

        <div class="auth-tabs">
          <button class="auth-tab active" onclick="CodeQuestAuth.switchTab('login')">เข้าสู่ระบบ</button>
          <button class="auth-tab" onclick="CodeQuestAuth.switchTab('register')">สมัครสมาชิก</button>
        </div>

        <!-- LOGIN -->
        <div id="auth-login-form" class="auth-form">
          <input type="email"    id="login-email"    placeholder="อีเมล"     class="auth-input">
          <input type="password" id="login-password" placeholder="รหัสผ่าน" class="auth-input">
          <button class="auth-submit" onclick="CodeQuestAuth.handleLogin()">เข้าสู่ระบบ</button>
          <div id="login-error" class="auth-error"></div>
        </div>

        <!-- REGISTER -->
        <div id="auth-register-form" class="auth-form" style="display:none">
          <input type="text"     id="reg-name"      placeholder="ชื่อที่แสดง"              class="auth-input">
          <input type="email"    id="reg-email"     placeholder="อีเมล"                    class="auth-input">
          <input type="text"     id="reg-school"    placeholder="โรงเรียน / มหาวิทยาลัย (ไม่บังคับ)" class="auth-input">
          <input type="password" id="reg-password"  placeholder="รหัสผ่าน (อย่างน้อย 6 ตัว)" class="auth-input">
          <input type="password" id="reg-password2" placeholder="ยืนยันรหัสผ่าน"           class="auth-input">
          <button class="auth-submit" onclick="CodeQuestAuth.handleRegister()">สมัครสมาชิก</button>
          <div id="reg-error"   class="auth-error"></div>
          <div id="reg-success" class="auth-success"></div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  },

  openModal() {
    this.createAuthModal();
    document.getElementById('auth-modal').classList.add('show');
  },

  closeModal() {
    document.getElementById('auth-modal')?.classList.remove('show');
  },

  switchTab(tab) {
    const tabs = document.querySelectorAll('.auth-tab');
    const loginF = document.getElementById('auth-login-form');
    const regF   = document.getElementById('auth-register-form');
    tabs.forEach(t => t.classList.remove('active'));
    if (tab === 'login') {
      tabs[0].classList.add('active');
      loginF.style.display = 'block';
      regF.style.display   = 'none';
    } else {
      tabs[1].classList.add('active');
      loginF.style.display = 'none';
      regF.style.display   = 'block';
    }
  },

  async handleLogin() {
    const email    = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const errEl    = document.getElementById('login-error');
    errEl.textContent = '';

    if (!email || !password) { errEl.textContent = 'กรุณากรอกอีเมลและรหัสผ่าน'; return; }

    const btn = document.querySelector('#auth-login-form .auth-submit');
    btn.disabled     = true;
    btn.textContent  = 'กำลังเข้าสู่ระบบ...';

    try {
      await this.signIn(email, password);
      this.closeModal();
    } catch (e) {
      errEl.textContent = e.message;
    } finally {
      btn.disabled    = false;
      btn.textContent = 'เข้าสู่ระบบ';
    }
  },

  async handleRegister() {
    const name  = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const school= document.getElementById('reg-school').value.trim();
    const pw    = document.getElementById('reg-password').value;
    const pw2   = document.getElementById('reg-password2').value;
    const errEl = document.getElementById('reg-error');
    const okEl  = document.getElementById('reg-success');
    errEl.textContent = '';
    okEl.textContent  = '';

    if (!name || !email || !pw) { errEl.textContent = 'กรุณากรอกข้อมูลให้ครบ'; return; }
    if (pw.length < 6)          { errEl.textContent = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัว'; return; }
    if (pw !== pw2)             { errEl.textContent = 'รหัสผ่านไม่ตรงกัน'; return; }

    const btn = document.querySelector('#auth-register-form .auth-submit');
    btn.disabled    = true;
    btn.textContent = 'กำลังสมัคร...';

    try {
      await this.signUp(email, pw, name);
      // Update school if provided
      if (school) await this.updateProfile({ school }).catch(() => {});
      okEl.textContent = '🎉 สมัครสำเร็จ! ยินดีต้อนรับสู่ CodeQuest';
      setTimeout(() => this.closeModal(), 1500);
    } catch (e) {
      errEl.textContent = e.message;
    } finally {
      btn.disabled    = false;
      btn.textContent = 'สมัครสมาชิก';
    }
  }
};

// ============================================================
// PROGRESS FUNCTIONS
// ============================================================

const CodeQuestProgress = {
  gameKey:          '',
  localStorageKey:  '',

  init(gameKey, localStorageKey) {
    this.gameKey         = gameKey;
    this.localStorageKey = localStorageKey;
  },

  // โหลด progress — cloud ถ้า login, localStorage ถ้าไม่ login
  async load() {
    if (CodeQuestAuth.currentUser) {
      try {
        const res = await CodeQuestAuth._api(
          'GET', `/progress.php?game=${this.gameKey}`
        );
        if (!res.error && res.data) {
          localStorage.setItem(this.localStorageKey, JSON.stringify(res.data));
          return res.data;
        }
      } catch { /* fallback */ }
    }
    const saved = localStorage.getItem(this.localStorageKey);
    return saved ? JSON.parse(saved) : null;
  },

  // บันทึก progress
  async save(progressData) {
    // บันทึก local เสมอ (instant)
    localStorage.setItem(this.localStorageKey, JSON.stringify(progressData));

    // ส่งขึ้น cloud ถ้า login
    if (CodeQuestAuth.currentUser) {
      try {
        await CodeQuestAuth._api('POST', '/progress.php', {
          game_key:        this.gameKey,
          ...progressData
        });
      } catch { /* silent fail */ }
    }
  },

  // บันทึกเมื่อผ่านด่าน (atomic)
  async completeLevel(levelNum, xpGained, hintsUsed = 0, attempts = 1, passedCases = 0, totalCases = 0) {
    if (!CodeQuestAuth.currentUser) return;
    try {
      await CodeQuestAuth._api('POST', '/progress.php?action=complete', {
        game_key:     this.gameKey,
        level_num:    levelNum,
        xp_gained:    xpGained,
        hints_used:   hintsUsed,
        attempts,
        passed_cases: passedCases,
        total_cases:  totalCases,
      });
    } catch { /* silent fail */ }
  },

  // Sync local → cloud (เรียกหลัง login)
  async syncLocalToCloud() {
    if (!CodeQuestAuth.currentUser) return;
    const saved = localStorage.getItem(this.localStorageKey);
    if (!saved) return;
    const localData = JSON.parse(saved);
    if (!localData || !localData.maxLevel) return;

    try {
      const cloud = await CodeQuestAuth._api(
        'GET', `/progress.php?game=${this.gameKey}`
      );
      // ถ้า local ไปได้ไกลกว่า → push ขึ้น cloud
      if (!cloud.data || localData.maxLevel > (cloud.data.maxLevel || 0)) {
        await this.save(localData);
      }
    } catch { /* silent */ }
  }
};

// ============================================================
// AUTH UI INJECTION
// ============================================================

function injectAuthUI() {
  injectAuthStyles();
  const topBar = document.querySelector('.top-bar');
  if (!topBar) return;

  const container = document.createElement('div');
  container.style.cssText = 'display:flex;align-items:center;gap:10px;margin-left:auto;';
  container.innerHTML = `
    <span class="sync-badge offline" id="sync-badge">💾 Local</span>
    <button id="auth-btn" onclick="CodeQuestAuth.openModal()">👤 เข้าสู่ระบบ</button>
    <div id="user-info" style="display:none">
      <div class="user-avatar" id="user-avatar">👤</div>
      <span id="user-name"></span>
      <button id="logout-btn" onclick="CodeQuestAuth.signOut()">ออก</button>
    </div>
  `;
  topBar.appendChild(container);
}

function injectAuthStyles() {
  if (document.getElementById('cq-auth-styles')) return;
  const style = document.createElement('style');
  style.id = 'cq-auth-styles';
  style.textContent = `
    #auth-btn {
      background: linear-gradient(135deg, var(--accent, #ff6b6b), var(--accent4, #a855f7));
      border: none; color: #fff; padding: 8px 20px; border-radius: 25px;
      font-family: 'Prompt', sans-serif; font-size: .85rem; font-weight: 600;
      cursor: pointer; transition: all .3s; display: inline-flex; align-items: center; gap: 6px;
    }
    #auth-btn:hover { transform: scale(1.05); filter: brightness(1.1); }
    #user-info { display: none; align-items: center; gap: 10px; color: #fff; font-family: 'Prompt', sans-serif; }
    .user-avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: linear-gradient(135deg, #4ecdc4, #a855f7);
      display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    #user-name { font-size: .85rem; font-weight: 500; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    #logout-btn {
      background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
      color: #94a1b2; padding: 5px 12px; border-radius: 15px;
      font-family: 'Prompt', sans-serif; font-size: .75rem; cursor: pointer; transition: all .3s;
    }
    #logout-btn:hover { background: #ef4444; color: #fff; border-color: transparent; }
    .sync-badge {
      font-size: .7rem; color: #4ecdc4; background: rgba(78,205,196,.1);
      padding: 2px 8px; border-radius: 10px; font-family: 'Prompt', sans-serif;
    }
    .sync-badge.offline { color: #94a1b2; background: rgba(255,255,255,.05); }

    /* Auth Modal */
    #auth-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:10000; }
    #auth-modal.show { display:flex; align-items:center; justify-content:center; }
    .auth-overlay { position:absolute; inset:0; background:rgba(0,0,0,.7); backdrop-filter:blur(5px); }
    .auth-box {
      position:relative; background:linear-gradient(145deg,#1a1932,#0f0e17);
      border:1px solid rgba(255,255,255,.1); border-radius:20px; padding:40px;
      width:420px; max-width:90vw; max-height:90vh; overflow-y:auto;
      box-shadow:0 25px 60px rgba(0,0,0,.5); animation:authPop .3s ease; z-index:1;
    }
    @keyframes authPop { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }
    .auth-close {
      position:absolute; top:15px; right:20px; background:none; border:none;
      color:#94a1b2; font-size:1.8rem; cursor:pointer; transition:color .3s;
    }
    .auth-close:hover { color:#ff6b6b; }
    .auth-logo {
      text-align:center; font-size:1.5rem; font-weight:800;
      font-family:'Prompt',sans-serif;
      background:linear-gradient(135deg,#ff6b6b,#4ecdc4,#ffe66d);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
      margin-bottom:25px;
    }
    .auth-tabs { display:flex; gap:5px; margin-bottom:25px; }
    .auth-tab {
      flex:1; padding:10px; border:none; background:rgba(255,255,255,.05);
      color:#94a1b2; border-radius:10px; font-family:'Prompt',sans-serif;
      font-size:.9rem; font-weight:600; cursor:pointer; transition:all .3s;
    }
    .auth-tab.active { background:linear-gradient(135deg,#ff6b6b,#a855f7); color:#fff; }
    .auth-input {
      width:100%; padding:12px 16px; background:rgba(255,255,255,.05);
      border:1px solid rgba(255,255,255,.1); border-radius:10px; color:#fff;
      font-family:'Prompt',sans-serif; font-size:.9rem; margin-bottom:12px;
      outline:none; transition:border-color .3s; box-sizing:border-box;
    }
    .auth-input:focus { border-color:#4ecdc4; }
    .auth-input::placeholder { color:#555; }
    .auth-submit {
      width:100%; padding:12px; background:linear-gradient(135deg,#ff6b6b,#a855f7);
      border:none; border-radius:10px; color:#fff; font-family:'Prompt',sans-serif;
      font-size:1rem; font-weight:700; cursor:pointer; transition:all .3s; margin-top:5px;
    }
    .auth-submit:hover:not(:disabled) { transform:translateY(-2px); filter:brightness(1.1); }
    .auth-submit:disabled { opacity:.6; cursor:not-allowed; }
    .auth-error   { color:#ef4444; font-size:.8rem; margin-top:10px; text-align:center; font-family:'Prompt',sans-serif; }
    .auth-success { color:#10b981; font-size:.8rem; margin-top:10px; text-align:center; font-family:'Prompt',sans-serif; }
  `;
  document.head.appendChild(style);
}

// ============================================================
// AUTO-INIT — เรียกอัตโนมัติเมื่อ DOM พร้อม
// ============================================================
document.addEventListener('DOMContentLoaded', async () => {
  injectAuthUI();
  await CodeQuestAuth.init();
});
