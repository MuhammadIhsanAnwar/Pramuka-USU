<?php
// public/artisan/index.php
// Simple web UI for the artisan runner at /artisan
require __DIR__ . '/session_init.php';
if (empty($_SESSION['artisan_unlocked'])) {
  header('Location: /artisan/login.php');
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Artisan Runner</title>
  <style>
    :root{--bg:#0b1220;--panel:#0f1724;--accent:#06b6d4;--text:#e6eef6}
    body{margin:0;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,'Helvetica Neue',Arial;background:linear-gradient(180deg,#071021 0%,#081322 100%);color:var(--text)}
    .wrap{max-width:980px;margin:36px auto;padding:20px}
    .card{background:linear-gradient(180deg,rgba(255,255,255,0.02),rgba(255,255,255,0.01));border-radius:10px;padding:18px;box-shadow:0 6px 24px rgba(2,6,23,0.6)}
    h1{margin:0 0 10px;font-size:20px}
    .controls{display:flex;gap:8px;align-items:center;margin-bottom:12px}
    input[type=password], select, input[type=text]{background:#071426;border:1px solid rgba(255,255,255,0.04);color:var(--text);padding:10px;border-radius:6px}
    button{background:var(--accent);color:#022;padding:10px 12px;border:none;border-radius:6px;font-weight:600;cursor:pointer}
    button.secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--text)}
    .terminal{background:#020617;color:#cfeef8;padding:14px;border-radius:8px;height:360px;overflow:auto;font-family:monospace;font-size:13px;line-height:1.4;border:1px solid rgba(255,255,255,0.03)}
    .meta{font-size:12px;color:#9fb6bf;margin-bottom:8px}
    label{font-size:13px;color:#9fb6bf;white-space:nowrap}
    .small{font-size:12px;color:#99b3bb}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Artisan Runner</h1>
      <div class="meta">Masukkan PIN untuk mengakses runner. Halaman ini mengeksekusi perintah artisan dan menampilkan output terminal.</div>

      <div id="controls-wrapper">
        <div class="controls" style="margin-top:12px;flex-direction:column;gap:10px">
          <div style="display:flex;gap:8px;width:100%">
            <label for="cmd">Perintah</label>
            <select id="cmd" style="flex:1">
              <option value="">-- Full Optimize Sequence --</option>
              <option value="optimize:clear">optimize:clear</option>
              <option value="cache:clear">cache:clear</option>
              <option value="config:cache">config:cache</option>
              <option value="route:cache">route:cache</option>
              <option value="view:cache">view:cache</option>
              <option value="optimize">optimize</option>
              <option value="migrate">migrate</option>
            </select>
            <button id="run">Run</button>
            <button id="clear" class="secondary">Clear</button>
          </div>

          <div style="display:flex;gap:8px;width:100%;align-items:center">
            <label for="manual-cmd">Manual</label>
            <input id="manual-cmd" type="text" placeholder="php artisan <command> --option=value" style="flex:1;padding:10px;border-radius:6px;border:1px solid rgba(255,255,255,0.04);background:#071426;color:var(--text)" />
            <button id="run-manual">Run Manual</button>
          </div>

          <div style="display:flex;gap:8px;width:100%;align-items:center">
            <label for="seeder-class">DB Seed</label>
            <input id="seeder-class" type="text" placeholder="Optional: SeederClass" style="flex:1;padding:10px;border-radius:6px;border:1px solid rgba(255,255,255,0.04);background:#071426;color:var(--text)" />
            <button id="run-seed">Run Seed</button>
          </div>

          <div class="small">Opsional: masukkan perintah custom setelah unlock; untuk opsi gunakan format <code>--option=value</code></div>
          <div id="terminal" class="terminal" aria-live="polite"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const term = document.getElementById('terminal');
    const cmdEl = document.getElementById('cmd');
    const runBtn = document.getElementById('run');
    const clearBtn = document.getElementById('clear');
    const manualCmdEl = document.getElementById('manual-cmd');
    const runManualBtn = document.getElementById('run-manual');
    const seederClassEl = document.getElementById('seeder-class');
    const runSeedBtn = document.getElementById('run-seed');

    function appendLine(text){
      term.textContent += text;
      term.scrollTop = term.scrollHeight;
    }

    clearBtn.addEventListener('click', ()=> term.textContent='');

    async function runCommand(cmd){
      term.textContent = '';
      runBtn.disabled = true; runBtn.textContent='Running...';
      runManualBtn.disabled = true;
      runSeedBtn.disabled = true;
      try{
        const url = 'artisan-runner.php' + (cmd ? '?cmd=' + encodeURIComponent(cmd) : '');
        const res = await fetch(url, { method: 'GET', cache: 'no-store' });
        appendLine('\n[HTTP status] ' + res.status + ' ' + res.statusText + '\n');
        if (!res.ok) {
          const text = await res.text();
          appendLine(text + '\n');
          return;
        }
        if (!res.body){
          const text = await res.text(); appendLine(text); throw new Error('No streaming');
        }
        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        while(true){
          const { value, done } = await reader.read();
          if (done) break;
          appendLine(decoder.decode(value));
        }
      }catch(err){
        appendLine('\n[Error] ' + (err.message||err) + '\n');
      }finally{
        runBtn.disabled = false; runBtn.textContent='Run';
        runManualBtn.disabled = false; runSeedBtn.disabled = false;
      }
    }

    runBtn.addEventListener('click', ()=>{
      let cmd = manualCmdEl.value.trim() || cmdEl.value.trim();
      if (!cmd) {
        cmd = ''; // run default full optimize sequence when no selection is made
      }
      runCommand(cmd);
    });

    runManualBtn.addEventListener('click', ()=>{
      const cmd = manualCmdEl.value.trim();
      if (!cmd) { alert('Isi perintah manual'); return; }
      runCommand(cmd);
    });

    runSeedBtn.addEventListener('click', ()=>{
      const cls = seederClassEl.value.trim();
      let cmd = 'db:seed';
      if (cls) cmd += ' --class=' + cls;
      runCommand(cmd);
    });
  </script>
</body>
</html>
