<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>💳 เติมเครดิต — ผจญภัยแดนโค้ด</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700;800&family=Fira+Code:wght@400;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="/auth.js"></script>
<link rel="stylesheet" href="/includes/navbar.css">
<style>
:root {
  --bg: #0f0e17; --card: #1a1932;
  --accent: #a855f7; --accent2: #4ECDC4; --accent3: #FFE66D;
  --text: #fffffe; --text-dim: #94a1b2;
  --success: #10b981; --border: rgba(255,255,255,0.07);
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Prompt',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }

.nav {
  position:sticky; top:0; z-index:100;
  background:rgba(15,14,23,0.95); backdrop-filter:blur(20px);
  border-bottom:1px solid rgba(168,85,247,0.15);
  padding:0 24px; height:60px;
  display:flex; align-items:center; justify-content:space-between;
}
.nav-logo { font-size:1.05rem; font-weight:800; background:linear-gradient(135deg,#a855f7,#4ECDC4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.nav-btn { background:rgba(168,85,247,0.12); border:1px solid rgba(168,85,247,0.25); color:var(--text); font-family:'Prompt',sans-serif; font-size:0.8rem; font-weight:600; padding:6px 14px; border-radius:8px; cursor:pointer; text-decoration:none; }

.page { max-width:540px; margin:0 auto; padding:40px 20px 80px; }

/* Balance card */
.balance-card {
  background:linear-gradient(135deg,rgba(168,85,247,0.12),rgba(78,205,196,0.08));
  border:1px solid rgba(168,85,247,0.25);
  border-radius:20px; padding:24px;
  text-align:center; margin-bottom:28px;
}
.balance-label { font-size:0.85rem; color:var(--text-dim); margin-bottom:8px; }
.balance-num { font-size:3.5rem; font-weight:800; color:var(--accent3); font-family:'Fira Code',monospace; line-height:1; }
.balance-unit { font-size:0.9rem; color:var(--text-dim); margin-top:4px; }
.balance-note { margin-top:12px; font-size:0.8rem; color:var(--text-dim); }

/* Steps */
.steps { display:flex; gap:0; margin-bottom:28px; }
.step { flex:1; text-align:center; padding:10px 4px; font-size:0.75rem; color:var(--text-dim); font-weight:600; position:relative; }
.step.active { color:var(--accent); }
.step-num { width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,0.05); border:2px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 6px; font-size:0.75rem; font-weight:800; }
.step.active .step-num { background:var(--accent); border-color:var(--accent); color:#fff; }
.step.done .step-num { background:var(--success); border-color:var(--success); color:#fff; }
.step-line { position:absolute; top:23px; left:calc(50% + 14px); right:calc(-50% + 14px); height:2px; background:rgba(255,255,255,0.06); }
.step:last-child .step-line { display:none; }

/* Cards */
.card { background:var(--card); border:1px solid var(--border); border-radius:18px; padding:22px; margin-bottom:20px; }
.card h3 { font-size:0.95rem; font-weight:700; margin-bottom:14px; }

/* Amount selector */
.amount-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
.amount-btn {
  padding:14px 8px; border-radius:12px; border:2px solid var(--border);
  background:rgba(255,255,255,0.03); cursor:pointer;
  color:var(--text-dim); font-family:'Prompt',sans-serif;
  text-align:center; transition:all 0.2s;
}
.amount-btn:hover { border-color:rgba(168,85,247,0.4); color:var(--text); background:rgba(168,85,247,0.06); }
.amount-btn.selected { border-color:var(--accent); background:rgba(168,85,247,0.12); color:var(--text); }
.amount-btn .baht { font-size:1.2rem; font-weight:800; display:block; }
.amount-btn .credits { font-size:0.7rem; color:var(--text-dim); margin-top:3px; }
.amount-btn.selected .credits { color:var(--accent2); }

.custom-amount { margin-top:12px; display:flex; align-items:center; gap:10px; }
.custom-amount input { flex:1; background:rgba(255,255,255,0.04); border:1px solid var(--border); color:var(--text); font-family:'Prompt',sans-serif; font-size:0.9rem; padding:10px 14px; border-radius:10px; outline:none; }
.custom-amount input:focus { border-color:var(--accent); }
.custom-amount span { font-size:0.85rem; color:var(--text-dim); white-space:nowrap; }

/* QR section */
.qr-section { text-align:center; }
.qr-box { display:inline-block; background:#fff; padding:16px; border-radius:14px; margin-bottom:16px; }
.promptpay-info { font-size:0.85rem; color:var(--text-dim); margin-bottom:8px; }
.promptpay-num { font-size:1.1rem; font-weight:700; color:var(--accent3); font-family:'Fira Code',monospace; }
.amount-display { margin-top:10px; font-size:1.4rem; font-weight:800; color:var(--success); }
.qr-note { font-size:0.78rem; color:var(--text-dim); margin-top:10px; line-height:1.6; }

/* Slip note */
textarea.slip-note {
  width:100%; background:rgba(255,255,255,0.04); border:1px solid var(--border);
  color:var(--text); font-family:'Prompt',sans-serif; font-size:0.88rem;
  padding:12px 14px; border-radius:10px; outline:none; resize:none; height:80px;
}
textarea.slip-note:focus { border-color:var(--accent); }
.slip-label { font-size:0.8rem; color:var(--text-dim); margin-bottom:6px; }

/* Submit */
.btn-submit {
  width:100%; padding:14px; border-radius:14px; border:none;
  background:linear-gradient(135deg,#a855f7,#7c3aed); color:#fff;
  font-family:'Prompt',sans-serif; font-size:1rem; font-weight:700;
  cursor:pointer; transition:all 0.2s;
  box-shadow:0 4px 14px rgba(168,85,247,0.3);
}
.btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(168,85,247,0.4); }
.btn-submit:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

/* Success */
.success-card {
  display:none; background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.25);
  border-radius:18px; padding:32px; text-align:center;
}
.success-card.visible { display:block; }
.success-icon { font-size:3.5rem; margin-bottom:12px; }
.success-card h2 { font-size:1.4rem; font-weight:800; color:var(--success); margin-bottom:8px; }
.success-card p { color:var(--text-dim); font-size:0.9rem; line-height:1.7; }

/* Login wall */
.login-wall { text-align:center; padding:60px 20px; }
.login-wall h2 { font-size:1.5rem; font-weight:800; margin-bottom:10px; }
.login-wall p { color:var(--text-dim); margin-bottom:20px; }
.btn-login { display:inline-block; background:linear-gradient(135deg,#a855f7,#7c3aed); color:#fff; font-family:'Prompt',sans-serif; font-size:0.9rem; font-weight:700; padding:12px 28px; border-radius:12px; text-decoration:none; }
</style>
</head>
<body>

<?php $activePage = 'credits'; $backUrl = '/index.php'; $backLabel = '← หน้าหลัก'; include __DIR__ . '/includes/navbar.php'; ?>

<div class="page" id="page-content">
  <div style="text-align:center;padding:60px;color:var(--text-dim);">กำลังโหลด...</div>
</div>

<script>
const PROMPTPAY_NUMBER = '0624106706';
const API = '/api';

let selectedAmount = 100;
let currentUser = null;

// ─── PromptPay QR Generator ───────────────────────────────────
function crc16(str) {
  let crc = 0xFFFF;
  for (let i = 0; i < str.length; i++) {
    crc ^= str.charCodeAt(i) << 8;
    for (let j = 0; j < 8; j++) {
      crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
      crc &= 0xFFFF;
    }
  }
  return crc.toString(16).toUpperCase().padStart(4, '0');
}

function tlv(tag, value) {
  return tag + String(value).length.toString().padStart(2, '0') + value;
}

function generatePromptPayPayload(phone, amount) {
  // normalize: 0624106706 → 0066624106706
  const normalized = '0066' + phone.replace(/^0/, '');
  const accountInfo = tlv('00', 'A000000677010111') + tlv('01', normalized);

  let payload = tlv('00', '01') + tlv('01', '12') + tlv('29', accountInfo) + '5303764';
  if (amount) payload += tlv('54', parseFloat(amount).toFixed(2));
  payload += '5802TH' + '6304';
  return payload + crc16(payload);
}

function renderQR(payload, containerId) {
  const el = document.getElementById(containerId);
  if (!el) return;
  el.innerHTML = '';
  new QRCode(el, {
    text: payload,
    width: 200, height: 200,
    colorDark: '#000000', colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.M
  });
}

// ─── INIT ─────────────────────────────────────────────────────
async function init() {
  if (window.CodeQuestAuth) await CodeQuestAuth.init();
  currentUser = window.CodeQuestAuth?.currentUser || null;

  if (!currentUser) {
    renderLoginWall();
    return;
  }

  const balRes = await fetch(`${API}/credits.php?action=balance`, {
    headers: { 'Authorization': 'Bearer ' + (localStorage.getItem('cq_token') || '') }
  }).then(r => r.json()).catch(() => ({ credits: 0 }));

  renderPage(balRes.credits || 0);
}

function renderLoginWall() {
  document.getElementById('page-content').innerHTML = `
    <div class="login-wall">
      <div style="font-size:3rem;margin-bottom:16px;">🔐</div>
      <h2>กรุณาเข้าสู่ระบบก่อน</h2>
      <p>ต้องล็อกอินก่อนถึงจะเติมเครดิตได้</p>
      <button class="btn-login" onclick="CodeQuestAuth.openModal()" style="border:none;cursor:pointer;">
        👤 เข้าสู่ระบบ / สมัครสมาชิก
      </button>
    </div>`;
}

function onAuthChange(event) {
  if (event === 'SIGNED_IN') init();
}

function renderPage(credits) {
  document.getElementById('page-content').innerHTML = `
    <!-- Balance -->
    <div class="balance-card">
      <div class="balance-label">เครดิตคงเหลือของคุณ</div>
      <div class="balance-num" id="balance-num">${credits}</div>
      <div class="balance-unit">เครดิต</div>
      <div class="balance-note">1 เครดิต = ถาม AI Tutor ได้ 1 ครั้ง</div>
    </div>

    <!-- Steps -->
    <div class="steps">
      <div class="step active" id="step1">
        <div class="step-num">1</div>เลือกจำนวนเงิน<div class="step-line"></div>
      </div>
      <div class="step" id="step2">
        <div class="step-num">2</div>สแกนจ่าย<div class="step-line"></div>
      </div>
      <div class="step" id="step3">
        <div class="step-num">3</div>แจ้งการโอน
      </div>
    </div>

    <!-- Step 1: Amount -->
    <div class="card" id="card-amount">
      <h3>💰 เลือกจำนวนที่ต้องการเติม</h3>
      <div class="amount-grid">
        <div class="amount-btn" onclick="selectAmount(50)" data-amt="50">
          <span class="baht">฿50</span>
          <div class="credits">50 เครดิต</div>
        </div>
        <div class="amount-btn selected" onclick="selectAmount(100)" data-amt="100">
          <span class="baht">฿100</span>
          <div class="credits">100 เครดิต</div>
        </div>
        <div class="amount-btn" onclick="selectAmount(200)" data-amt="200">
          <span class="baht">฿200</span>
          <div class="credits">200 เครดิต</div>
        </div>
        <div class="amount-btn" onclick="selectAmount(500)" data-amt="500">
          <span class="baht">฿500</span>
          <div class="credits">500 เครดิต</div>
        </div>
      </div>
      <div class="custom-amount">
        <input type="number" id="custom-amt" placeholder="หรือใส่จำนวนเอง" min="20" max="10000" oninput="selectCustom(this.value)">
        <span>บาท (ขั้นต่ำ ฿20)</span>
      </div>
      <button class="btn-submit" style="margin-top:16px;" onclick="goToStep2()">ถัดไป: สแกน QR →</button>
    </div>

    <!-- Step 2: QR Code (hidden initially) -->
    <div class="card" id="card-qr" style="display:none;">
      <h3>📱 สแกน QR โอนเงิน</h3>
      <div class="qr-section">
        <div class="qr-box" id="qr-container"></div>
        <div class="promptpay-info">PromptPay · โอนมาที่เบอร์</div>
        <div class="promptpay-num">${PROMPTPAY_NUMBER}</div>
        <div class="amount-display" id="qr-amount-display"></div>
        <div class="qr-note">
          สแกนด้วย Mobile Banking ใดก็ได้<br>
          จำนวนเงินถูกกรอกไว้ให้แล้ว ไม่ต้องพิมพ์เพิ่ม
        </div>
      </div>
      <button class="btn-submit" style="margin-top:20px;background:linear-gradient(135deg,#10b981,#059669);" onclick="goToStep3()">
        โอนแล้ว → แจ้งการโอน
      </button>
    </div>

    <!-- Step 3: Slip note (hidden initially) -->
    <div class="card" id="card-slip" style="display:none;">
      <h3>📝 แจ้งการโอนเงิน</h3>
      <div class="slip-label">เลขอ้างอิง / ข้อความจาก slip (ถ้ามี) — ไม่บังคับ</div>
      <textarea class="slip-note" id="slip-note" placeholder="เช่น เลขอ้างอิง 123456789 วันที่ 30/03/2026 เวลา 14:30"></textarea>
      <div style="font-size:0.78rem;color:var(--text-dim);margin-top:8px;line-height:1.6;">
        ℹ️ admin จะได้รับอีเมลแจ้งเตือนและเพิ่มเครดิตให้ภายใน <strong>24 ชั่วโมง</strong>
      </div>
      <button class="btn-submit" id="submit-btn" style="margin-top:16px;" onclick="submitRequest()">
        ✅ ส่งคำขอเติมเครดิต
      </button>
    </div>

    <!-- Success -->
    <div class="success-card" id="success-card">
      <div class="success-icon">🎉</div>
      <h2>ส่งคำขอแล้ว!</h2>
      <p>
        ระบบได้แจ้ง admin เรียบร้อยแล้ว<br>
        เครดิตจะถูกเพิ่มให้ภายใน 24 ชั่วโมง<br><br>
        <strong style="color:var(--accent3)" id="success-detail"></strong>
      </p>
      <button onclick="location.reload()" style="margin-top:20px;padding:10px 24px;border-radius:12px;border:none;background:rgba(255,255,255,0.08);color:var(--text);font-family:'Prompt',sans-serif;cursor:pointer;">
        เติมเพิ่มอีก
      </button>
    </div>
  `;
}

function selectAmount(amt) {
  selectedAmount = amt;
  document.querySelectorAll('.amount-btn').forEach(b => {
    b.classList.toggle('selected', parseInt(b.dataset.amt) === amt);
  });
  document.getElementById('custom-amt').value = '';
}

function selectCustom(val) {
  const amt = parseInt(val);
  if (amt >= 20) {
    selectedAmount = amt;
    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
  }
}

function goToStep2() {
  if (!selectedAmount || selectedAmount < 20) { alert('กรุณาเลือกจำนวนเงิน (ขั้นต่ำ ฿20)'); return; }

  setStep(2);
  document.getElementById('card-amount').style.display = 'none';
  document.getElementById('card-qr').style.display = 'block';

  const payload = generatePromptPayPayload(PROMPTPAY_NUMBER, selectedAmount);
  renderQR(payload, 'qr-container');
  document.getElementById('qr-amount-display').textContent = `฿${selectedAmount.toLocaleString()} บาท`;
}

function goToStep3() {
  setStep(3);
  document.getElementById('card-qr').style.display = 'none';
  document.getElementById('card-slip').style.display = 'block';
}

function setStep(n) {
  for (let i = 1; i <= 3; i++) {
    const el = document.getElementById(`step${i}`);
    if (!el) continue;
    el.classList.remove('active', 'done');
    if (i < n) el.classList.add('done');
    else if (i === n) el.classList.add('active');
  }
}

async function submitRequest() {
  const btn = document.getElementById('submit-btn');
  const slipNote = document.getElementById('slip-note').value.trim();

  btn.disabled = true; btn.textContent = '⏳ กำลังส่ง...';

  try {
    const token = localStorage.getItem('cq_token') || '';
    const res = await fetch(`${API}/credits.php?action=request`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({ amount_thb: selectedAmount, slip_note: slipNote })
    });
    const json = await res.json();

    if (json.error) throw new Error(json.error);

    // Show success
    document.getElementById('card-slip').style.display = 'none';
    const sc = document.getElementById('success-card');
    sc.classList.add('visible');
    document.getElementById('success-detail').textContent =
      `คุณจะได้รับ ${json.credits_given} เครดิต (จาก ฿${json.amount_thb})`;

  } catch (e) {
    alert('เกิดข้อผิดพลาด: ' + e.message);
    btn.disabled = false; btn.textContent = '✅ ส่งคำขอเติมเครดิต';
  }
}

init();
</script>
</body>
</html>
