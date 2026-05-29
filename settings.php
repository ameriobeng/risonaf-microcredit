<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings | Risobaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary:       #16a34a;
      --primary-dark:  #15803d;
      --primary-light: #dcfce7;
      --gold:          #d97706;
      --dark:          #0f172a;
      --text:          #1e293b;
      --muted:         #64748b;
      --light:         #f8fafc;
      --card:          #ffffff;
      --border:        #e2e8f0;
      --danger:        #dc2626;
      --danger-light:  #fee2e2;
      --success-light: #dcfce7;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); min-height: 100vh; }
    .container { width: min(1200px, 94%); margin: 0 auto; }

    header {
      background: var(--dark);
      color: white;
      padding: 1rem 0;
      box-shadow: 0 2px 16px rgba(0,0,0,.2);
      position: sticky; top: 0; z-index: 100;
    }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .brand { display: flex; align-items: center; gap: .6rem; font-weight: 800; font-size: 1rem; }
    .brand-icon { width: 34px; height: 34px; background: linear-gradient(135deg, var(--primary), var(--gold)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .admin-tag { font-size: .72rem; font-weight: 700; background: rgba(22,163,74,.25); color: #86efac; border: 1px solid rgba(22,163,74,.3); padding: .18rem .55rem; border-radius: 100px; letter-spacing: .4px; text-transform: uppercase; }
    .header-actions { display: flex; gap: .6rem; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; font-family: inherit; font-weight: 700; font-size: .88rem; padding: .6rem 1rem; border-radius: 9px; border: none; cursor: pointer; text-decoration: none; transition: all .15s; }
    .btn-ghost { background: rgba(255,255,255,.1); color: white; border: 1px solid rgba(255,255,255,.18); }
    .btn-ghost:hover { background: rgba(255,255,255,.18); }

    main { padding: 2rem 0 3rem; }

    .page-title { font-size: 1.4rem; font-weight: 800; color: var(--dark); letter-spacing: -.4px; margin-bottom: .3rem; }
    .page-sub { font-size: .9rem; color: var(--muted); margin-bottom: 1.8rem; }

    .panel { background: white; border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.04); max-width: 520px; }
    .panel-header { padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--border); }
    .panel-header h2 { font-size: 1rem; font-weight: 700; color: var(--dark); }
    .panel-header p { font-size: .83rem; color: var(--muted); margin-top: .2rem; }
    .panel-body { padding: 1.4rem; }

    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .85rem; font-weight: 600; color: var(--text); margin-bottom: .4rem; }
    .field input { width: 100%; padding: .72rem .85rem; border: 1.5px solid #cbd5e1; border-radius: 10px; font: inherit; font-size: .95rem; background: var(--light); color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); background: white; }

    .btn-save { width: 100%; justify-content: center; padding: .8rem; font-size: .95rem; background: var(--primary); color: white; border-radius: 10px; margin-top: .5rem; }
    .btn-save:hover { background: var(--primary-dark); transform: translateY(-1px); }

    .alert { border-radius: 10px; padding: .75rem 1rem; font-size: .88rem; font-weight: 600; margin-bottom: 1rem; display: none; }
    .alert.success { background: var(--success-light); color: var(--primary-dark); border: 1px solid #bbf7d0; }
    .alert.error   { background: var(--danger-light);  color: var(--danger);        border: 1px solid #fecaca; }
    .alert.show { display: flex; align-items: center; gap: .5rem; }
  </style>
</head>
<body>
  <header>
    <div class="container header-row">
      <div class="brand">
        <div class="brand-icon">🏦</div>
        Risobaf Loans
        <span class="admin-tag">Admin</span>
      </div>
      <div class="header-actions">
        <a class="btn btn-ghost" href="admin.php">← Dashboard</a>
        <a class="btn btn-ghost" href="api/logout.php">Sign Out</a>
      </div>
    </div>
  </header>

  <main>
    <div class="container">
      <div class="page-title">Settings</div>
      <div class="page-sub">Manage your admin account.</div>

      <div class="panel">
        <div class="panel-header">
          <h2>🔐 Change Password</h2>
          <p>Choose a strong password of at least 8 characters.</p>
        </div>
        <div class="panel-body">
          <div class="alert" id="alertBox"></div>
          <form id="changePasswordForm">
            <div class="field">
              <label for="current_password">Current Password</label>
              <input type="password" id="current_password" name="current_password" required autocomplete="current-password" />
            </div>
            <div class="field">
              <label for="new_password">New Password</label>
              <input type="password" id="new_password" name="new_password" required autocomplete="new-password" minlength="8" />
            </div>
            <div class="field">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" minlength="8" />
            </div>
            <button class="btn btn-save" type="submit">Update Password</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    const form     = document.getElementById('changePasswordForm');
    const alertBox = document.getElementById('alertBox');

    function showAlert(message, type) {
      alertBox.textContent = (type === 'success' ? '✅ ' : '⚠️ ') + message;
      alertBox.className = 'alert show ' + type;
      if (type === 'success') setTimeout(() => { alertBox.className = 'alert'; }, 4000);
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const data = new FormData(form);
      try {
        const res    = await fetch('api/change_password.php', { method: 'POST', body: data });
        const result = await res.json();
        if (result.success) {
          form.reset();
          showAlert(result.message, 'success');
        } else {
          showAlert(result.message, 'error');
        }
      } catch {
        showAlert('Network error — please try again.', 'error');
      }
    });
  </script>
</body>
</html>
