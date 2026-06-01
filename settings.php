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
  <title>Settings | Risonaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy:        #0c2340;
      --navy-mid:    #163556;
      --navy-light:  #e6edf7;
      --gold:        #b8862a;
      --gold-bright: #d4a73a;
      --text:        #1b2535;
      --muted:       #677080;
      --light:       #f3f5f8;
      --card:        #ffffff;
      --border:      #d9e0eb;
      --danger:      #be2222;
      --danger-bg:   #fde8e8;
      --success:     #0f6d3d;
      --success-bg:  #e5f3ec;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); min-height: 100vh; }
    .container { width: min(1200px, 94%); margin: 0 auto; }

    /* Header */
    header { background: var(--navy); color: white; padding: 1rem 0; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,.06); }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .brand { display: flex; align-items: center; gap: .65rem; text-decoration: none; color: white; }
    .brand-mark { width: 32px; height: 32px; background: var(--gold); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 800; color: var(--navy); }
    .brand-name { font-size: .97rem; font-weight: 700; }
    .admin-tag { font-size: .67rem; font-weight: 700; background: rgba(184,134,42,.2); color: var(--gold-bright); border: 1px solid rgba(184,134,42,.25); padding: .16rem .5rem; border-radius: 100px; letter-spacing: .5px; text-transform: uppercase; }
    .header-actions { display: flex; gap: .5rem; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; font-family: inherit; font-weight: 600; font-size: .84rem; padding: .5rem .9rem; border-radius: 7px; border: none; cursor: pointer; text-decoration: none; transition: all .15s; }
    .btn-ghost { background: rgba(255,255,255,.08); color: rgba(255,255,255,.85); border: 1px solid rgba(255,255,255,.14); }
    .btn-ghost:hover { background: rgba(255,255,255,.15); color: white; }

    main { padding: 2rem 0 3rem; }
    .page-title { font-size: 1.35rem; font-weight: 800; color: var(--navy); letter-spacing: -.4px; margin-bottom: .25rem; }
    .page-sub { font-size: .88rem; color: var(--muted); margin-bottom: 2rem; }

    .panel { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(12,35,64,.05); max-width: 500px; }
    .panel-header { padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--border); background: #fafbfd; }
    .panel-header h2 { font-size: .97rem; font-weight: 700; color: var(--navy); }
    .panel-header p { font-size: .82rem; color: var(--muted); margin-top: .2rem; }
    .panel-body { padding: 1.4rem; }

    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .83rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .field input { width: 100%; padding: .7rem .85rem; border: 1.5px solid #cdd4df; border-radius: 8px; font: inherit; font-size: .93rem; background: var(--light); color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); background: white; }

    .btn-save { width: 100%; justify-content: center; padding: .78rem; font-size: .93rem; background: var(--navy); color: white; border-radius: 8px; margin-top: .5rem; }
    .btn-save:hover { background: var(--navy-mid); transform: translateY(-1px); }

    .alert { border-radius: 8px; padding: .72rem 1rem; font-size: .85rem; font-weight: 600; margin-bottom: 1rem; display: none; align-items: center; gap: .5rem; }
    .alert.success { background: var(--success-bg); color: var(--success); border: 1px solid #a7d9bc; }
    .alert.error   { background: var(--danger-bg);  color: var(--danger);  border: 1px solid #f5c6c6; }
    .alert.show { display: flex; }
  </style>
</head>
<body>
  <header>
    <div class="container header-row">
      <div style="display:flex;align-items:center;gap:.65rem;">
        <a class="brand" href="index.php">
          <div class="brand-mark">RL</div>
          <span class="brand-name">Risonaf Loans</span>
        </a>
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
      <div class="page-sub">Manage your admin account credentials.</div>

      <div class="panel">
        <div class="panel-header">
          <h2>Change Password</h2>
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
    const CSRF_TOKEN = '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>';
    const form     = document.getElementById('changePasswordForm');
    const alertBox = document.getElementById('alertBox');

    function showAlert(message, type) {
      alertBox.textContent = (type === 'success' ? '✓ ' : '⚠ ') + message;
      alertBox.className = 'alert show ' + type;
      if (type === 'success') setTimeout(() => { alertBox.className = 'alert'; }, 4000);
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const data = new FormData(form);
      data.append('csrf_token', CSRF_TOKEN);
      try {
        const res    = await fetch('api/change_password.php', { method: 'POST', body: data });
        const result = await res.json();
        if (result.success) { form.reset(); showAlert(result.message, 'success'); }
        else showAlert(result.message, 'error');
      } catch { showAlert('Network error — please try again.', 'error'); }
    });
  </script>
</body>
</html>
