<?php
// public/artisan/login.php
require __DIR__ . '/session_init.php';
if (!empty($_SESSION['artisan_unlocked'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Artisan Login</title>
  <style>
    body{display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#071021;color:#e6eef6;font-family:Inter,system-ui,Arial}
    .box{background:linear-gradient(180deg,#081322,#0b1220);border-radius:10px;padding:28px;box-shadow:0 10px 40px rgba(2,6,23,0.6);width:420px}
    h2{margin:0 0 12px;font-size:20px}
    input{width:100%;padding:12px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:#071426;color:#e6eef6}
    button{margin-top:12px;width:100%;padding:12px;border-radius:8px;border:none;background:#06b6d4;color:#022;font-weight:700;cursor:pointer}
    .small{font-size:13px;color:#9fb6bf;margin-top:8px}
  </style>
</head>
<body>
  <div class="box">
    <h2>Login Artisan Runner</h2>
    <form id="login">
      <input id="pin" name="pin" type="password" placeholder="Enter PIN" autocomplete="off" />
      <button type="submit">Unlock</button>
    </form>
    <div class="small">Masukkan PIN untuk membuka halaman Artisan Runner.</div>
    <div id="msg" class="small"></div>
  </div>
  <script>
    const form = document.getElementById('login');
    const pin = document.getElementById('pin');
    const msg = document.getElementById('msg');
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      msg.textContent='Checking...';
      const fd = new FormData(); fd.append('pin', pin.value);
      try{
        const res = await fetch('check_pin.php', { method:'POST', body: fd });
        if (res.ok) {
          window.location.href = 'index.php';
        } else {
          msg.textContent = 'PIN invalid';
        }
      }catch(err){
        msg.textContent = 'Error';
      }
    });
  </script>
</body>
</html>
