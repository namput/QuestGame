/**
 * CodeQuest Engine v2.0
 * ระบบตรวจโค้ดสำหรับทุก Quest
 *
 * รองรับ:
 *   - Python  → Pyodide (WebAssembly) รันจริง + stdin/stdout/function/expression
 *   - JavaScript → Sandboxed eval + console capture + function call
 *   - SQL     → sql.js (SQLite WASM) รันจริง + row check / syntax check
 *   - HTML/CSS → iframe DOM validation + CSS check
 *
 * วิธีใช้:
 *   <script src="codequest-engine.js"></script>
 *   const results = await CodeQuestEngine.validate('python', code, testCases, options);
 *   CodeQuestEngine.renderResults(results, containerElement);
 */

'use strict';

const CodeQuestEngine = (() => {

  // ─── CONFIG ────────────────────────────────────────────────────────────────
  const PYODIDE_VERSION = '0.26.4';
  const PYODIDE_URL     = `https://cdn.jsdelivr.net/pyodide/v${PYODIDE_VERSION}/full/pyodide.js`;
  const PYODIDE_IDX     = `https://cdn.jsdelivr.net/pyodide/v${PYODIDE_VERSION}/full/`;
  const SQLJS_URL       = 'https://cdn.jsdelivr.net/npm/sql.js@1.11.0/dist/sql-wasm.js';
  const SQLJS_WASM      = 'https://cdn.jsdelivr.net/npm/sql.js@1.11.0/dist/sql-wasm.wasm';
  const TIMEOUT_MS      = 12000;

  // ─── STATE ─────────────────────────────────────────────────────────────────
  let _pyodide        = null;
  let _sqlJS          = null;
  let _pyodideReady   = false;
  let _pyodideLoading = null; // Promise
  let _sqlLoading     = null; // Promise

  // ─── SCRIPT LOADER ─────────────────────────────────────────────────────────
  function loadScript(src) {
    return new Promise((resolve, reject) => {
      if (document.querySelector(`script[src="${src}"]`)) { resolve(); return; }
      const s = document.createElement('script');
      s.src = src;
      s.onload  = resolve;
      s.onerror = () => reject(new Error(`โหลดสคริปต์ล้มเหลว: ${src}`));
      document.head.appendChild(s);
    });
  }

  function withTimeout(promise, ms = TIMEOUT_MS) {
    return Promise.race([
      promise,
      new Promise((_, reject) =>
        setTimeout(() => reject(new Error(`หมดเวลา (${ms / 1000} วินาที)`)), ms)
      )
    ]);
  }

  // ─── PYODIDE LOADER ────────────────────────────────────────────────────────
  async function ensurePyodide() {
    if (_pyodideReady) return _pyodide;
    if (_pyodideLoading) return _pyodideLoading;

    _pyodideLoading = (async () => {
      if (!window.loadPyodide) await loadScript(PYODIDE_URL);

      _pyodide = await window.loadPyodide({ indexURL: PYODIDE_IDX });

      // Inject Python helper ที่ใช้รันโค้ดนักเรียน
      await _pyodide.runPythonAsync(`
import sys, builtins, traceback
from io import StringIO

def _cq_exec(code, stdin_lines=None):
    """รันโค้ดนักเรียน คืน dict: output, error, ns"""
    _in  = iter(stdin_lines or [])
    _old_input  = builtins.input
    _old_stdout = sys.stdout
    _old_stderr = sys.stderr

    sys.stdout = StringIO()
    sys.stderr = StringIO()
    builtins.input = lambda prompt='': next(_in, '')

    ns  = {}
    err = None
    try:
        exec(compile(code, '<student>', 'exec'), ns)
    except Exception:
        err = traceback.format_exc().strip().split('\\n')[-1]
    finally:
        out = sys.stdout.getvalue().rstrip('\\n')
        sys.stdout = _old_stdout
        sys.stderr = _old_stderr
        builtins.input = _old_input

    return {'output': out, 'error': err, 'ns': ns}

def _cq_eval(expr, ns):
    """eval expression ใน namespace"""
    try:
        return {'result': repr(eval(expr, ns)), 'error': None}
    except Exception as e:
        return {'result': None, 'error': str(e)}
`);
      _pyodideReady = true;
      return _pyodide;
    })();

    return _pyodideLoading;
  }

  // โหลด packages เพิ่มเติม (numpy, pandas ฯลฯ)
  async function loadPackages(pkgList) {
    const py = await ensurePyodide();
    if (pkgList && pkgList.length > 0) {
      await py.loadPackage(pkgList);
    }
  }

  // ─── SQL.JS LOADER ─────────────────────────────────────────────────────────
  async function ensureSqlJS() {
    if (_sqlJS) return _sqlJS;
    if (_sqlLoading) return _sqlLoading;

    _sqlLoading = (async () => {
      if (!window.initSqlJs) await loadScript(SQLJS_URL);
      _sqlJS = await window.initSqlJs({ locateFile: () => SQLJS_WASM });
      return _sqlJS;
    })();

    return _sqlLoading;
  }

  // ─── PYTHON RUNNER ─────────────────────────────────────────────────────────
  /**
   * testCase types:
   *   'stdout'     → รัน code ด้วย stdin = tc.input[], เทียบ stdout กับ tc.expected
   *   'function'   → รัน code แล้ว eval tc.call ใน namespace, เทียบกับ tc.expected
   *   'expression' → รัน code แล้ว eval tc.expr ใน namespace, เทียบ repr() กับ tc.expected
   *   'no_error'   → รัน code ต้องไม่มี error
   */
  async function runPython(code, testCases, opts = {}) {
    await ensurePyodide();
    if (opts.packages) await loadPackages(opts.packages);

    const results = [];

    for (let i = 0; i < testCases.length; i++) {
      const tc = testCases[i];
      opts.onProgress?.(i, testCases.length);

      try {
        const type    = tc.type || 'stdout';
        let passed    = false;
        let actual    = '';
        let error     = null;

        if (type === 'stdout') {
          const inputs = tc.input !== undefined
            ? (Array.isArray(tc.input) ? tc.input : [String(tc.input)])
            : [];

          const res = await withTimeout(
            _pyodide.runPythonAsync(
              `_cq_exec(${JSON.stringify(code)}, ${JSON.stringify(inputs)})`
            )
          );
          const r = res.toJs({ dict_converter: Object.fromEntries });

          actual = r.output ?? '';
          error  = r.error  ?? null;

          if (!error) {
            passed = actual.trim() === String(tc.expected).trim();
          }

        } else if (type === 'function') {
          const execRes = await withTimeout(
            _pyodide.runPythonAsync(
              `_cq_exec(${JSON.stringify(code)})`
            )
          );
          const execR = execRes.toJs({ dict_converter: Object.fromEntries });

          if (execR.error) {
            error = execR.error;
          } else {
            const evalRes = await _pyodide.runPythonAsync(
              `_cq_eval(${JSON.stringify(tc.call)}, _cq_exec(${JSON.stringify(code)})['ns'])`
            );
            const evalR = evalRes.toJs({ dict_converter: Object.fromEntries });
            actual = evalR.result ?? '';
            error  = evalR.error  ?? null;
            if (!error) {
              passed = actual === pyRepr(tc.expected);
            }
          }

        } else if (type === 'expression') {
          const execRes = await withTimeout(
            _pyodide.runPythonAsync(
              `_cq_exec(${JSON.stringify(code)})`
            )
          );
          const execR = execRes.toJs({ dict_converter: Object.fromEntries });

          if (execR.error) {
            error = execR.error;
          } else {
            const evalRes = await _pyodide.runPythonAsync(
              `_cq_eval(${JSON.stringify(tc.expr)}, _cq_exec(${JSON.stringify(code)})['ns'])`
            );
            const evalR = evalRes.toJs({ dict_converter: Object.fromEntries });
            actual = evalR.result ?? '';
            error  = evalR.error  ?? null;
            if (!error) {
              passed = actual === pyRepr(tc.expected);
            }
          }

        } else if (type === 'no_error') {
          const res = await withTimeout(
            _pyodide.runPythonAsync(
              `_cq_exec(${JSON.stringify(code)})`
            )
          );
          const r = res.toJs({ dict_converter: Object.fromEntries });
          error  = r.error ?? null;
          actual = r.output ?? '';
          passed = !error;
        }

        results.push(makeResult(tc, i, testCases.length, passed, actual, error));

      } catch (e) {
        results.push(makeResult(tc, i, testCases.length, false, '', e.message));
      }
    }

    return results;
  }

  // ─── JAVASCRIPT RUNNER ─────────────────────────────────────────────────────
  /**
   * testCase types:
   *   'console'    → capture console.log output, เทียบกับ tc.expected
   *   'function'   → ต้อง define tc.functionName, call ด้วย tc.args, เทียบกับ tc.expected
   *   'expression' → eval tc.expr หลัง run code, เทียบกับ tc.expected
   *   'no_error'   → run code ต้องไม่มี error
   */
  function runJavaScript(code, testCases) {
    return testCases.map((tc, i) => {
      const type = tc.type || 'console';
      let passed = false;
      let actual = '';
      let error  = null;

      try {
        if (type === 'console') {
          const logs = [];
          const fakeConsole = {
            log:   (...a) => logs.push(a.map(stringifyVal).join(' ')),
            error: (...a) => logs.push('ERROR: ' + a.join(' ')),
            warn:  (...a) => logs.push('WARN: ' + a.join(' ')),
          };
          // safe sandbox: ไม่มี window, document, fetch
          const fn = new Function('console', code);
          fn(fakeConsole);
          actual = logs.join('\n').trim();
          passed = actual === String(tc.expected).trim();

        } else if (type === 'function') {
          // รัน code เพื่อ define function แล้วเรียก
          const ns = {};
          // eslint-disable-next-line no-new-func
          new Function('__ns__', `${code}\n__ns__['__fn__'] = ${tc.functionName};`)(ns);
          if (typeof ns.__fn__ !== 'function') {
            throw new Error(`ไม่พบฟังก์ชัน ${tc.functionName}`);
          }
          const result = ns.__fn__(...(tc.args || []));
          actual = stringifyVal(result);
          passed = JSON.stringify(result) === JSON.stringify(tc.expected);

        } else if (type === 'expression') {
          const ns = {};
          // eslint-disable-next-line no-new-func
          new Function('__ns__', `${code}\n__ns__['__val__'] = (${tc.expr});`)(ns);
          actual = stringifyVal(ns.__val__);
          passed = JSON.stringify(ns.__val__) === JSON.stringify(tc.expected);

        } else if (type === 'no_error') {
          // eslint-disable-next-line no-new-func
          new Function(code)();
          passed = true;
          actual = 'รันได้โดยไม่มี error';
        }

      } catch (e) {
        error = e.message;
      }

      return makeResult(tc, i, testCases.length, passed, actual, error);
    });
  }

  // ─── SQL RUNNER ────────────────────────────────────────────────────────────
  /**
   * testCase types:
   *   'query_result' → รัน SQL, เทียบ rows กับ tc.expectedRows
   *   'row_count'    → เทียบจำนวน rows กับ tc.expected
   *   'col_values'   → เทียบค่าใน column tc.column ว่า contains tc.expected[]
   *   'syntax_check' → เช็คว่า SQL มี keyword tc.keyword
   *   'no_error'     → รัน SQL ไม่มี error
   */
  async function runSQL(studentSQL, testCases, schema) {
    const SQL = await ensureSqlJS();
    const results = [];

    for (let i = 0; i < testCases.length; i++) {
      const tc   = testCases[i];
      const type = tc.type || 'query_result';
      let db     = null;
      let passed = false;
      let actual = '';
      let error  = null;

      try {
        if (type === 'syntax_check') {
          passed = studentSQL.toUpperCase().includes(tc.keyword.toUpperCase());
          actual = passed ? `พบ keyword "${tc.keyword}"` : `ไม่พบ keyword "${tc.keyword}"`;

        } else {
          db = new SQL.Database();
          if (schema) db.run(schema);

          if (type === 'no_error') {
            db.run(studentSQL);
            passed = true;
            actual = 'รัน SQL ได้โดยไม่มี error';

          } else {
            const qr = db.exec(studentSQL);
            const rows = qr.length > 0
              ? qr[0].values.map(row => {
                  const obj = {};
                  qr[0].columns.forEach((col, ci) => obj[col] = row[ci]);
                  return obj;
                })
              : [];

            if (type === 'query_result') {
              if (tc.expectedRows) {
                passed = compareRows(rows, tc.expectedRows, tc.strict);
                actual = JSON.stringify(rows.slice(0, 5));
              } else {
                passed = rows.length > 0;
                actual = `${rows.length} แถว`;
              }
            } else if (type === 'row_count') {
              passed = rows.length === tc.expected;
              actual = `${rows.length} แถว (ต้องการ ${tc.expected})`;
            } else if (type === 'col_values') {
              const vals = rows.map(r => String(r[tc.column]));
              passed = tc.expected.every(exp => vals.includes(String(exp)));
              actual = vals.join(', ');
            }
          }
        }

      } catch (e) {
        error = `SQL Error: ${e.message}`;
      } finally {
        try { db?.close(); } catch (_) {}
      }

      results.push(makeResult(tc, i, testCases.length, passed, actual, error));
    }

    return results;
  }

  // ─── HTML / CSS VALIDATOR ──────────────────────────────────────────────────
  /**
   * testCase types:
   *   'dom'          → doc.querySelector(tc.selector) ต้องมี/ไม่มี/นับ
   *   'dom_text'     → element มี textContent ตรงกับ tc.expected
   *   'dom_attr'     → element มี attribute tc.attr = tc.expected
   *   'dom_style'    → element มี computed style property tc.prop = tc.expected
   *   'css_contains' → CSS ใน <style> หรือ style attr มี tc.keyword
   *   'code_has'     → source code มี tc.keyword (case-insensitive)
   */
  function validateHTML(code, testCases) {
    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:800px;height:600px;visibility:hidden;';
    document.body.appendChild(iframe);

    const results = [];
    try {
      iframe.contentDocument.open();
      iframe.contentDocument.write(code);
      iframe.contentDocument.close();
      const doc = iframe.contentDocument;

      for (let i = 0; i < testCases.length; i++) {
        const tc   = testCases[i];
        const type = tc.type || 'dom';
        let passed = false;
        let actual = '';
        let error  = null;

        try {
          if (type === 'dom') {
            if (tc.check === 'count') {
              const n = doc.querySelectorAll(tc.selector).length;
              passed  = n === tc.expected;
              actual  = `พบ ${n} รายการ (ต้องการ ${tc.expected})`;
            } else if (tc.check === 'not_exists') {
              passed = !doc.querySelector(tc.selector);
              actual = passed ? 'ไม่พบ (ถูกต้อง)' : 'พบ (ไม่ควรมี)';
            } else {
              const el = doc.querySelector(tc.selector);
              passed   = el !== null;
              actual   = el ? `พบ <${tc.selector}>` : `ไม่พบ <${tc.selector}>`;
            }
          } else if (type === 'dom_text') {
            const el = doc.querySelector(tc.selector);
            actual   = el?.textContent?.trim() ?? '(ไม่พบ element)';
            passed   = actual === String(tc.expected).trim();
          } else if (type === 'dom_attr') {
            const el = doc.querySelector(tc.selector);
            actual   = el?.getAttribute(tc.attr) ?? '(ไม่พบ)';
            passed   = actual === String(tc.expected);
          } else if (type === 'dom_style') {
            const el = doc.querySelector(tc.selector);
            if (!el) {
              actual = '(ไม่พบ element)';
            } else {
              const cs = iframe.contentWindow.getComputedStyle(el);
              actual   = cs.getPropertyValue(tc.prop);
              passed   = actual === String(tc.expected);
            }
          } else if (type === 'css_contains') {
            const styleText = Array.from(doc.querySelectorAll('style'))
              .map(s => s.textContent).join('\n') + code;
            passed = styleText.toLowerCase().includes(tc.keyword.toLowerCase());
            actual = passed ? `พบ "${tc.keyword}"` : `ไม่พบ "${tc.keyword}"`;
          } else if (type === 'code_has') {
            passed = code.toLowerCase().includes(tc.keyword.toLowerCase());
            actual = passed ? `มี "${tc.keyword}"` : `ไม่มี "${tc.keyword}"`;
          }
        } catch (e) {
          error = e.message;
        }

        results.push(makeResult(tc, i, testCases.length, passed, actual, error));
      }
    } finally {
      document.body.removeChild(iframe);
    }

    return results;
  }

  // ─── MAIN VALIDATE ─────────────────────────────────────────────────────────
  /**
   * @param {string}   questType  'python' | 'javascript' | 'sql' | 'html' | 'htmlcss'
   * @param {string}   code       โค้ดของนักเรียน
   * @param {object[]} testCases  รายการ test cases
   * @param {object}   opts
   *   opts.schema     — SQL schema string (สำหรับ sql)
   *   opts.packages   — ['numpy','pandas'] (สำหรับ python)
   *   opts.onProgress — (current, total) => void
   * @returns {Promise<object[]>} results
   */
  async function validate(questType, code, testCases, opts = {}) {
    if (!code || !code.trim()) {
      return testCases.map((tc, i) =>
        makeResult(tc, i, testCases.length, false, '', 'กรุณาเขียนโค้ดก่อน')
      );
    }

    switch (questType) {
      case 'python':
        return runPython(code, testCases, opts);
      case 'javascript':
      case 'js':
        return runJavaScript(code, testCases);
      case 'sql':
        return runSQL(code, testCases, opts.schema);
      case 'html':
      case 'css':
      case 'htmlcss':
        return validateHTML(code, testCases);
      default:
        throw new Error(`ไม่รู้จัก questType: "${questType}"`);
    }
  }

  // ─── LEGACY COMPAT ─────────────────────────────────────────────────────────
  // สำหรับ quest ไฟล์เก่าที่ใช้ codeCheck / validate function โดยตรง
  async function validateLegacy(codeCheckFn, validateFn, code, iframeDoc) {
    try {
      const codeOk = codeCheckFn ? codeCheckFn(code) : true;
      const domOk  = validateFn  ? validateFn(iframeDoc, code) : true;
      const passed = codeOk && domOk;
      return [{
        label: 'ตรวจโค้ด',
        passed,
        actual: '',
        error: passed ? null : 'โค้ดยังไม่ถูกต้อง',
        points: 100
      }];
    } catch (e) {
      return [{ label: 'ตรวจโค้ด', passed: false, actual: '', error: e.message, points: 100 }];
    }
  }

  // ─── SCORING ───────────────────────────────────────────────────────────────
  function calculateScore(results) {
    const totalPts   = results.reduce((s, r) => s + (r.points || 0), 0);
    const earnedPts  = results.filter(r => r.passed).reduce((s, r) => s + (r.points || 0), 0);
    const passedCount = results.filter(r => r.passed).length;

    return {
      earned:      earnedPts,
      total:       totalPts,
      passedCount,
      totalCount:  results.length,
      percentage:  totalPts > 0 ? Math.round(earnedPts / totalPts * 100) : 0,
      allPassed:   passedCount === results.length && results.length > 0,
      xpGained:    earnedPts   // ใช้ points เป็น XP โดยตรง
    };
  }

  // ─── UI RENDERER ───────────────────────────────────────────────────────────
  function renderResults(results, container) {
    if (!container) return;
    const score = calculateScore(results);

    const scoreClass = score.allPassed ? 'all-pass'
      : score.passedCount > 0          ? 'partial'
      : 'fail';

    const scoreIcon = score.allPassed ? '🎉'
      : score.passedCount > 0          ? '⚡'
      : '❌';

    container.innerHTML = `
      <div class="cqe-results">
        <div class="cqe-score ${scoreClass}">
          <span class="cqe-score-icon">${scoreIcon}</span>
          <div class="cqe-score-info">
            <span class="cqe-score-main">ผ่าน ${score.passedCount}/${score.totalCount} test case</span>
            <span class="cqe-score-sub">${score.percentage}% • ${score.earned}/${score.total} คะแนน</span>
          </div>
          ${score.allPassed ? '<span class="cqe-badge-win">✨ ผ่าน!</span>' : ''}
        </div>
        <div class="cqe-cases">
          ${results.map((r, idx) => `
            <div class="cqe-case ${r.passed ? 'pass' : 'fail'}" data-idx="${idx}">
              <div class="cqe-case-header">
                <span class="cqe-case-status">${r.passed ? '✅' : '❌'}</span>
                <span class="cqe-case-label">${esc(r.label || `Test ${idx + 1}`)}</span>
                <span class="cqe-case-pts">${r.points} pts</span>
              </div>
              ${!r.passed ? `
                <div class="cqe-case-body">
                  ${r.error ? `<div class="cqe-line error"><span class="cqe-pill err">Error</span>${esc(r.error)}</div>` : ''}
                  ${!r.error && r.expected !== undefined ? `
                    <div class="cqe-line"><span class="cqe-pill exp">ต้องการ</span><code>${esc(String(r.expected ?? ''))}</code></div>
                    <div class="cqe-line"><span class="cqe-pill got">ได้รับ</span><code>${esc(String(r.actual ?? ''))}</code></div>
                  ` : ''}
                </div>
              ` : ''}
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // Show loading spinner in container
  function showLoading(container, msg = 'กำลังรันโค้ด...') {
    if (!container) return;
    container.innerHTML = `
      <div class="cqe-loading">
        <div class="cqe-spinner"></div>
        <span>${esc(msg)}</span>
      </div>
    `;
  }

  // ─── PRELOAD ───────────────────────────────────────────────────────────────
  // เรียกจาก quest file เพื่อให้ Pyodide โหลด background ก่อนที่จะรันจริง
  function preloadPyodide() {
    ensurePyodide().catch(() => {});
  }

  function preloadSqlJS() {
    ensureSqlJS().catch(() => {});
  }

  // ─── HELPERS ───────────────────────────────────────────────────────────────

  function makeResult(tc, idx, total, passed, actual, error) {
    const defaultPts = tc.points ?? Math.max(10, Math.floor(100 / total));
    return {
      label:    tc.label || `Test ${idx + 1}`,
      type:     tc.type || 'unknown',
      expected: tc.expected ?? tc.expectedRows ?? tc.keyword ?? '',
      passed,
      actual:   actual ?? '',
      error:    error  ?? null,
      points:   passed ? defaultPts : 0,
      maxPoints: defaultPts
    };
  }

  // Python repr ใน JS (สำหรับเทียบกับ Python's repr())
  function pyRepr(val) {
    if (val === null || val === undefined) return 'None';
    if (val === true)  return 'True';
    if (val === false) return 'False';
    if (typeof val === 'string') return `'${val.replace(/'/g, "\\'")}'`;
    if (Array.isArray(val)) return `[${val.map(pyRepr).join(', ')}]`;
    if (typeof val === 'object') {
      const pairs = Object.entries(val).map(([k, v]) => `${pyRepr(k)}: ${pyRepr(v)}`);
      return `{${pairs.join(', ')}}`;
    }
    return String(val);
  }

  function stringifyVal(v) {
    if (v === null || v === undefined) return String(v);
    if (typeof v === 'object') return JSON.stringify(v);
    return String(v);
  }

  function compareRows(actual, expected, strict = false) {
    if (!Array.isArray(actual) || !Array.isArray(expected)) return false;
    if (strict && actual.length !== expected.length) return false;
    return expected.every(expRow =>
      actual.some(actRow =>
        Object.keys(expRow).every(k => String(actRow[k]) === String(expRow[k]))
      )
    );
  }

  function esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ─── INJECT CSS ────────────────────────────────────────────────────────────
  function injectStyles() {
    if (document.getElementById('cqe-styles')) return;
    const style = document.createElement('style');
    style.id = 'cqe-styles';
    style.textContent = `
      /* CodeQuest Engine UI */
      .cqe-results { font-family: 'Prompt', 'Sarabun', sans-serif; }

      .cqe-score {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px; border-radius: 12px;
        margin-bottom: 12px; font-weight: 600;
        border: 1px solid;
      }
      .cqe-score.all-pass {
        background: rgba(16,185,129,.12);
        color: #10b981;
        border-color: rgba(16,185,129,.3);
      }
      .cqe-score.partial {
        background: rgba(245,158,11,.12);
        color: #f59e0b;
        border-color: rgba(245,158,11,.3);
      }
      .cqe-score.fail {
        background: rgba(239,68,68,.12);
        color: #ef4444;
        border-color: rgba(239,68,68,.3);
      }
      .cqe-score-icon { font-size: 1.4rem; }
      .cqe-score-info { flex: 1; display: flex; flex-direction: column; gap: 2px; }
      .cqe-score-main { font-size: .9rem; }
      .cqe-score-sub  { font-size: .75rem; opacity: .7; font-weight: 400; }
      .cqe-badge-win  {
        font-size: .75rem; font-weight: 700; letter-spacing: .05em;
        background: rgba(16,185,129,.2); color: #10b981;
        padding: 3px 10px; border-radius: 20px;
      }

      .cqe-cases { display: flex; flex-direction: column; gap: 6px; }

      .cqe-case {
        border-radius: 10px; overflow: hidden;
        border: 1px solid rgba(255,255,255,.07);
        transition: border-color .2s;
      }
      .cqe-case.pass { border-color: rgba(16,185,129,.2); }
      .cqe-case.fail { border-color: rgba(239,68,68,.2); }

      .cqe-case-header {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 12px; font-size: .83rem;
      }
      .cqe-case.pass .cqe-case-header { background: rgba(16,185,129,.06); }
      .cqe-case.fail .cqe-case-header { background: rgba(239,68,68,.06); }
      .cqe-case-label { flex: 1; }
      .cqe-case-pts   { font-size: .72rem; opacity: .55; font-family: 'Fira Code', monospace; }

      .cqe-case-body {
        padding: 8px 12px; border-top: 1px solid rgba(255,255,255,.05);
        display: flex; flex-direction: column; gap: 5px;
      }
      .cqe-line {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: .78rem; color: #94a1b2; line-height: 1.5;
      }
      .cqe-line.error { color: #f87171; }
      .cqe-line code {
        font-family: 'Fira Code', 'Courier New', monospace;
        background: rgba(255,255,255,.05); padding: 1px 6px;
        border-radius: 4px; word-break: break-all; color: #e2e8f0;
      }
      .cqe-pill {
        flex-shrink: 0; font-size: .68rem; font-weight: 700;
        padding: 1px 7px; border-radius: 6px; letter-spacing: .03em;
        text-transform: uppercase;
      }
      .cqe-pill.exp { background: rgba(99,102,241,.2); color: #818cf8; }
      .cqe-pill.got { background: rgba(239,68,68,.2);  color: #f87171; }
      .cqe-pill.err { background: rgba(239,68,68,.2);  color: #f87171; }

      /* Loading */
      .cqe-loading {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        padding: 24px; font-family: 'Prompt', sans-serif;
        font-size: .85rem; color: #94a1b2;
      }
      .cqe-spinner {
        width: 20px; height: 20px;
        border: 2px solid rgba(255,255,255,.1);
        border-top-color: #4ecdc4;
        border-radius: 50%;
        animation: cqe-spin .7s linear infinite;
      }
      @keyframes cqe-spin { to { transform: rotate(360deg); } }
    `;
    document.head.appendChild(style);
  }

  // Auto-inject styles when module loads
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectStyles);
  } else {
    injectStyles();
  }

  // ─── PUBLIC API ────────────────────────────────────────────────────────────
  return {
    validate,
    validateLegacy,
    runPython,
    runJavaScript,
    runSQL,
    validateHTML,
    calculateScore,
    renderResults,
    showLoading,
    preloadPyodide,
    preloadSqlJS,
    loadPackages,
    // expose for advanced use
    _ensurePyodide: ensurePyodide,
    _ensureSqlJS:   ensureSqlJS,
  };

})();
