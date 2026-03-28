// ============================================================
// CodeQuest Auth & Progress System
// ผจญภัยแดนโค้ด - ระบบสมาชิกและเก็บ Progress
// ============================================================
// ใช้ร่วมกับ Supabase - ต้องตั้งค่า SUPABASE_URL และ SUPABASE_ANON_KEY
// ============================================================

const SUPABASE_URL = 'YOUR_SUPABASE_URL';       // ← ใส่ URL ของ Supabase project
const SUPABASE_ANON_KEY = 'YOUR_SUPABASE_ANON_KEY'; // ← ใส่ anon/public key

// Initialize Supabase client
let supabase = null;

function initSupabase() {
  if (typeof window.supabase !== 'undefined' && window.supabase.createClient) {
    supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
  }
  return supabase;
}

// ============================================================
// AUTH FUNCTIONS
// ============================================================

const CodeQuestAuth = {
  currentUser: null,
  profile: null,

  // เช็คว่า login อยู่ไหม
  async init() {
    if (!supabase) initSupabase();
    if (!supabase) return null;

    const { data: { session } } = await supabase.auth.getSession();
    if (session?.user) {
      this.currentUser = session.user;
      await this.loadProfile();
    }

    // Listen for auth changes
    supabase.auth.onAuthStateChange(async (event, session) => {
      if (event === 'SIGNED_IN' && session?.user) {
        this.currentUser = session.user;
        await this.loadProfile();
        this.updateUI();
        if (typeof onAuthChange === 'function') onAuthChange('SIGNED_IN');
      } else if (event === 'SIGNED_OUT') {
        this.currentUser = null;
        this.profile = null;
        this.updateUI();
        if (typeof onAuthChange === 'function') onAuthChange('SIGNED_OUT');
      }
    });

    this.updateUI();
    return this.currentUser;
  },

  // โหลดข้อมูล profile
  async loadProfile() {
    if (!this.currentUser) return;
    const { data } = await supabase
      .from('profiles')
      .select('*')
      .eq('id', this.currentUser.id)
      .single();
    this.profile = data;
  },

  // สมัครสมาชิกด้วย Email
  async signUp(email, password, displayName) {
    const { data, error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        data: { display_name: displayName }
      }
    });
    if (error) throw error;
    return data;
  },

  // Login ด้วย Email
  async signIn(email, password) {
    const { data, error } = await supabase.auth.signInWithPassword({
      email,
      password
    });
    if (error) throw error;
    return data;
  },

  // Login ด้วย Google
  async signInWithGoogle() {
    const { data, error } = await supabase.auth.signInWithOAuth({
      provider: 'google',
      options: {
        redirectTo: window.location.href
      }
    });
    if (error) throw error;
    return data;
  },

  // Login ด้วย GitHub
  async signInWithGitHub() {
    const { data, error } = await supabase.auth.signInWithOAuth({
      provider: 'github',
      options: {
        redirectTo: window.location.href
      }
    });
    if (error) throw error;
    return data;
  },

  // Logout
  async signOut() {
    await supabase.auth.signOut();
    this.currentUser = null;
    this.profile = null;
    this.updateUI();
  },

  // อัพเดท display name
  async updateDisplayName(name) {
    if (!this.currentUser) return;
    await supabase
      .from('profiles')
      .update({ display_name: name })
      .eq('id', this.currentUser.id);
    this.profile.display_name = name;
  },

  // แสดง/ซ่อน UI ตามสถานะ login
  updateUI() {
    const authBtn = document.getElementById('auth-btn');
    const userInfo = document.getElementById('user-info');
    const userName = document.getElementById('user-name');

    if (this.currentUser && this.profile) {
      if (authBtn) authBtn.style.display = 'none';
      if (userInfo) userInfo.style.display = 'flex';
      if (userName) userName.textContent = this.profile.display_name || this.currentUser.email;
    } else {
      if (authBtn) authBtn.style.display = 'inline-flex';
      if (userInfo) userInfo.style.display = 'none';
    }
  },

  // สร้าง Auth Modal HTML
  createAuthModal() {
    if (document.getElementById('auth-modal')) return;

    const modal = document.createElement('div');
    modal.id = 'auth-modal';
    modal.innerHTML = `
      <div class="auth-overlay" onclick="CodeQuestAuth.closeModal()"></div>
      <div class="auth-box">
        <button class="auth-close" onclick="CodeQuestAuth.closeModal()">&times;</button>
        <div class="auth-logo">🏰 ผจญภัยแดนโค้ด</div>
        <div class="auth-tabs">
          <button class="auth-tab active" onclick="CodeQuestAuth.switchTab('login')">เข้าสู่ระบบ</button>
          <button class="auth-tab" onclick="CodeQuestAuth.switchTab('register')">สมัครสมาชิก</button>
        </div>

        <div id="auth-login-form" class="auth-form">
          <div class="auth-social">
            <button class="social-btn google" onclick="CodeQuestAuth.signInWithGoogle()">
              <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
              Google
            </button>
            <button class="social-btn github" onclick="CodeQuestAuth.signInWithGitHub()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
              GitHub
            </button>
          </div>
          <div class="auth-divider"><span>หรือใช้อีเมล</span></div>
          <input type="email" id="login-email" placeholder="อีเมล" class="auth-input">
          <input type="password" id="login-password" placeholder="รหัสผ่าน" class="auth-input">
          <button class="auth-submit" onclick="CodeQuestAuth.handleLogin()">เข้าสู่ระบบ</button>
          <div id="login-error" class="auth-error"></div>
        </div>

        <div id="auth-register-form" class="auth-form" style="display:none">
          <input type="text" id="reg-name" placeholder="ชื่อที่แสดง" class="auth-input">
          <input type="email" id="reg-email" placeholder="อีเมล" class="auth-input">
          <input type="password" id="reg-password" placeholder="รหัสผ่าน (อย่างน้อย 6 ตัว)" class="auth-input">
          <input type="password" id="reg-password2" placeholder="ยืนยันรหัสผ่าน" class="auth-input">
          <button class="auth-submit" onclick="CodeQuestAuth.handleRegister()">สมัครสมาชิก</button>
          <div id="reg-error" class="auth-error"></div>
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
    const modal = document.getElementById('auth-modal');
    if (modal) modal.classList.remove('show');
  },

  switchTab(tab) {
    const tabs = document.querySelectorAll('.auth-tab');
    tabs.forEach(t => t.classList.remove('active'));
    if (tab === 'login') {
      tabs[0].classList.add('active');
      document.getElementById('auth-login-form').style.display = 'block';
      document.getElementById('auth-register-form').style.display = 'none';
    } else {
      tabs[1].classList.add('active');
      document.getElementById('auth-login-form').style.display = 'none';
      document.getElementById('auth-register-form').style.display = 'block';
    }
  },

  async handleLogin() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const errEl = document.getElementById('login-error');
    errEl.textContent = '';

    if (!email || !password) {
      errEl.textContent = 'กรุณากรอกอีเมลและรหัสผ่าน';
      return;
    }

    try {
      await this.signIn(email, password);
      this.closeModal();
    } catch (e) {
      errEl.textContent = e.message === 'Invalid login credentials'
        ? 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
        : e.message;
    }
  },

  async handleRegister() {
    const name = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const pw = document.getElementById('reg-password').value;
    const pw2 = document.getElementById('reg-password2').value;
    const errEl = document.getElementById('reg-error');
    const successEl = document.getElementById('reg-success');
    errEl.textContent = '';
    successEl.textContent = '';

    if (!name || !email || !pw) {
      errEl.textContent = 'กรุณากรอกข้อมูลให้ครบ';
      return;
    }
    if (pw.length < 6) {
      errEl.textContent = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
      return;
    }
    if (pw !== pw2) {
      errEl.textContent = 'รหัสผ่านไม่ตรงกัน';
      return;
    }

    try {
      await this.signUp(email, pw, name);
      successEl.textContent = 'สมัครสำเร็จ! กรุณาเช็คอีเมลเพื่อยืนยัน';
    } catch (e) {
      errEl.textContent = e.message;
    }
  }
};

// ============================================================
// PROGRESS FUNCTIONS
// ============================================================

const CodeQuestProgress = {
  gameKey: '',
  localStorageKey: '',

  init(gameKey, localStorageKey) {
    this.gameKey = gameKey;
    this.localStorageKey = localStorageKey;
  },

  // โหลด progress (Supabase ถ้า login, localStorage ถ้าไม่ login)
  async load() {
    // ถ้า login → โหลดจาก Supabase
    if (CodeQuestAuth.currentUser && supabase) {
      try {
        const { data } = await supabase
          .from('game_progress')
          .select('*')
          .eq('user_id', CodeQuestAuth.currentUser.id)
          .eq('game_key', this.gameKey)
          .single();

        if (data) {
          // Sync กลับลง localStorage ด้วย
          const localData = {
            currentLevel: data.current_level,
            maxLevel: data.max_level_reached,
            xp: data.total_xp,
            completedLevels: data.completed_levels || [],
            levelScores: data.level_scores || {}
          };
          localStorage.setItem(this.localStorageKey, JSON.stringify(localData));
          return localData;
        }
      } catch (e) {
        console.log('Supabase load failed, falling back to localStorage');
      }
    }

    // Fallback: localStorage
    const saved = localStorage.getItem(this.localStorageKey);
    return saved ? JSON.parse(saved) : null;
  },

  // บันทึก progress
  async save(progressData) {
    // บันทึกลง localStorage เสมอ
    localStorage.setItem(this.localStorageKey, JSON.stringify(progressData));

    // ถ้า login → บันทึกลง Supabase ด้วย
    if (CodeQuestAuth.currentUser && supabase) {
      try {
        const { error } = await supabase
          .from('game_progress')
          .upsert({
            user_id: CodeQuestAuth.currentUser.id,
            game_key: this.gameKey,
            current_level: progressData.currentLevel || 1,
            max_level_reached: progressData.maxLevel || 1,
            total_xp: progressData.xp || 0,
            completed_levels: progressData.completedLevels || [],
            level_scores: progressData.levelScores || {}
          }, {
            onConflict: 'user_id,game_key'
          });

        if (!error) {
          // อัพเดท total XP ใน profiles
          await supabase.rpc('recalc_total_xp', { uid: CodeQuestAuth.currentUser.id });
        }
      } catch (e) {
        console.log('Supabase save failed:', e);
      }
    }
  },

  // Sync localStorage → Supabase (เรียกตอน login ครั้งแรก)
  async syncLocalToCloud() {
    if (!CodeQuestAuth.currentUser || !supabase) return;

    const saved = localStorage.getItem(this.localStorageKey);
    if (!saved) return;

    const localData = JSON.parse(saved);

    // เช็คว่า cloud มีข้อมูลอยู่แล้วไหม
    const { data: cloudData } = await supabase
      .from('game_progress')
      .select('*')
      .eq('user_id', CodeQuestAuth.currentUser.id)
      .eq('game_key', this.gameKey)
      .single();

    // ถ้า cloud ไม่มี หรือ local ไปไกลกว่า → อัพโหลด local
    if (!cloudData || (localData.maxLevel > (cloudData.max_level_reached || 0))) {
      await this.save(localData);
    }
    // ถ้า cloud ไปไกลกว่า → ดาวน์โหลด cloud
    else if (cloudData.max_level_reached > (localData.maxLevel || 0)) {
      const cloudProgress = {
        currentLevel: cloudData.current_level,
        maxLevel: cloudData.max_level_reached,
        xp: cloudData.total_xp,
        completedLevels: cloudData.completed_levels || [],
        levelScores: cloudData.level_scores || {}
      };
      localStorage.setItem(this.localStorageKey, JSON.stringify(cloudProgress));
    }
  }
};

// ============================================================
// AUTH CSS (injected into page)
// ============================================================

function injectAuthStyles() {
  if (document.getElementById('cq-auth-styles')) return;
  const style = document.createElement('style');
  style.id = 'cq-auth-styles';
  style.textContent = `
    /* Auth Button in Top Bar */
    #auth-btn {
      background: linear-gradient(135deg, var(--accent, #ff6b6b), var(--accent4, #a855f7));
      border: none;
      color: #fff;
      padding: 8px 20px;
      border-radius: 25px;
      font-family: 'Prompt', sans-serif;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    #auth-btn:hover { transform: scale(1.05); filter: brightness(1.1); }

    #user-info {
      display: none;
      align-items: center;
      gap: 10px;
      color: var(--text, #fff);
      font-family: 'Prompt', sans-serif;
    }
    #user-info .user-avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent2, #4ecdc4), var(--accent4, #a855f7));
      display: flex; align-items: center; justify-content: center;
      font-size: 0.9rem;
    }
    #user-name { font-size: 0.85rem; font-weight: 500; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    #logout-btn {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: var(--text-dim, #94a1b2);
      padding: 5px 12px;
      border-radius: 15px;
      font-family: 'Prompt', sans-serif;
      font-size: 0.75rem;
      cursor: pointer;
      transition: all 0.3s;
    }
    #logout-btn:hover { background: var(--error, #ef4444); color: #fff; border-color: transparent; }

    /* Auth Modal */
    #auth-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000; }
    #auth-modal.show { display: flex; align-items: center; justify-content: center; }
    .auth-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); }
    .auth-box {
      position: relative;
      background: linear-gradient(145deg, #1a1932, #0f0e17);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 40px;
      width: 420px;
      max-width: 90vw;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 25px 60px rgba(0,0,0,0.5);
      animation: authPop 0.3s ease;
    }
    @keyframes authPop { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    .auth-close {
      position: absolute; top: 15px; right: 20px;
      background: none; border: none; color: #94a1b2;
      font-size: 1.8rem; cursor: pointer; transition: color 0.3s;
    }
    .auth-close:hover { color: #ff6b6b; }
    .auth-logo {
      text-align: center; font-size: 1.5rem; font-weight: 800;
      font-family: 'Prompt', sans-serif;
      background: linear-gradient(135deg, #ff6b6b, #4ecdc4, #ffe66d);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      margin-bottom: 25px;
    }
    .auth-tabs { display: flex; gap: 5px; margin-bottom: 25px; }
    .auth-tab {
      flex: 1; padding: 10px; border: none;
      background: rgba(255,255,255,0.05);
      color: #94a1b2; border-radius: 10px;
      font-family: 'Prompt', sans-serif;
      font-size: 0.9rem; font-weight: 600;
      cursor: pointer; transition: all 0.3s;
    }
    .auth-tab.active { background: linear-gradient(135deg, #ff6b6b, #a855f7); color: #fff; }
    .auth-input {
      width: 100%; padding: 12px 16px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 10px; color: #fff;
      font-family: 'Prompt', sans-serif;
      font-size: 0.9rem; margin-bottom: 12px;
      outline: none; transition: border-color 0.3s;
    }
    .auth-input:focus { border-color: #4ecdc4; }
    .auth-input::placeholder { color: #555; }
    .auth-submit {
      width: 100%; padding: 12px;
      background: linear-gradient(135deg, #ff6b6b, #a855f7);
      border: none; border-radius: 10px;
      color: #fff; font-family: 'Prompt', sans-serif;
      font-size: 1rem; font-weight: 700;
      cursor: pointer; transition: all 0.3s;
      margin-top: 5px;
    }
    .auth-submit:hover { transform: translateY(-2px); filter: brightness(1.1); }
    .auth-error { color: #ef4444; font-size: 0.8rem; margin-top: 10px; text-align: center; font-family: 'Prompt', sans-serif; }
    .auth-success { color: #10b981; font-size: 0.8rem; margin-top: 10px; text-align: center; font-family: 'Prompt', sans-serif; }
    .auth-social { display: flex; gap: 10px; margin-bottom: 15px; }
    .social-btn {
      flex: 1; padding: 10px; border: 1px solid rgba(255,255,255,0.15);
      border-radius: 10px; color: #fff;
      font-family: 'Prompt', sans-serif; font-size: 0.85rem;
      cursor: pointer; display: flex; align-items: center;
      justify-content: center; gap: 8px; transition: all 0.3s;
    }
    .social-btn.google { background: rgba(219,68,55,0.2); }
    .social-btn.google:hover { background: rgba(219,68,55,0.4); }
    .social-btn.github { background: rgba(255,255,255,0.05); }
    .social-btn.github:hover { background: rgba(255,255,255,0.15); }
    .auth-divider {
      text-align: center; margin: 15px 0; position: relative;
      color: #555; font-size: 0.8rem; font-family: 'Prompt', sans-serif;
    }
    .auth-divider::before, .auth-divider::after {
      content: ''; position: absolute; top: 50%;
      width: 35%; height: 1px; background: rgba(255,255,255,0.1);
    }
    .auth-divider::before { left: 0; }
    .auth-divider::after { right: 0; }

    /* Cloud sync indicator */
    .sync-badge {
      display: inline-flex; align-items: center; gap: 4px;
      font-size: 0.7rem; color: var(--accent2, #4ecdc4);
      background: rgba(78,205,196,0.1);
      padding: 2px 8px; border-radius: 10px;
      font-family: 'Prompt', sans-serif;
    }
    .sync-badge.offline { color: var(--text-dim, #94a1b2); background: rgba(255,255,255,0.05); }
  `;
  document.head.appendChild(style);
}

// ============================================================
// INJECT AUTH UI INTO TOP BAR
// ============================================================

function injectAuthUI() {
  injectAuthStyles();

  // หา top-bar แล้วเพิ่มปุ่ม login / user info
  const topBar = document.querySelector('.top-bar');
  if (!topBar) return;

  // สร้าง container สำหรับ auth
  const authContainer = document.createElement('div');
  authContainer.style.cssText = 'display:flex;align-items:center;gap:10px;margin-left:auto;';
  authContainer.innerHTML = `
    <span class="sync-badge offline" id="sync-badge">💾 Local</span>
    <button id="auth-btn" onclick="CodeQuestAuth.openModal()">👤 เข้าสู่ระบบ</button>
    <div id="user-info">
      <div class="user-avatar" id="user-avatar">👤</div>
      <span id="user-name"></span>
      <button id="logout-btn" onclick="CodeQuestAuth.signOut()">ออก</button>
    </div>
  `;
  topBar.appendChild(authContainer);
}

// ============================================================
// AUTO-INIT
// ============================================================

document.addEventListener('DOMContentLoaded', async () => {
  // โหลด Supabase SDK
  if (SUPABASE_URL !== 'YOUR_SUPABASE_URL') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2';
    script.onload = async () => {
      initSupabase();
      injectAuthUI();
      await CodeQuestAuth.init();

      // อัพเดท sync badge
      const badge = document.getElementById('sync-badge');
      if (badge && CodeQuestAuth.currentUser) {
        badge.className = 'sync-badge';
        badge.innerHTML = '☁️ Cloud Sync';
      }
    };
    document.head.appendChild(script);
  } else {
    // ถ้ายังไม่ตั้งค่า Supabase → แสดงปุ่มแต่ไม่ทำงาน
    injectAuthUI();
  }
});
