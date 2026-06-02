<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$token      = trim((string)($_GET['token'] ?? ''));
$tokenValid = false;
$tokenError = '';

if ($token === '') {
    $tokenError = 'No reset token provided.';
} else {
    try {
        $pdo  = getPDO();
        $stmt = $pdo->prepare(
            'SELECT id FROM password_resets
             WHERE token = ? AND used = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)'
        );
        $stmt->execute([$token]);
        if ($stmt->fetch()) {
            $tokenValid = true;
        } else {
            $tokenError = 'This reset link has expired or has already been used. Please request a new one.';
        }
    } catch (Throwable $e) {
        $tokenError = 'Server error — please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password | Risonaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy:        #0c2340;
      --navy-mid:    #163556;
      --gold:        #b8862a;
      --text:        #1b2535;
      --muted:       #677080;
      --light:       #f3f5f8;
      --border:      #d9e0eb;
      --success:     #0f6d3d;
      --success-bg:  #e5f3ec;
      --danger:      #be2222;
      --danger-bg:   #fde8e8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--navy); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; background-image: radial-gradient(ellipse 70% 60% at 100% 0%, rgba(184,134,42,.1) 0%, transparent 55%); }
    .wrap { width: 100%; max-width: 400px; }
    .brand { display: flex; align-items: center; justify-content: center; gap: .7rem; margin-bottom: 2rem; text-decoration: none; }
    .brand-mark { width: 42px; height: 42px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; font-weight: 800; color: var(--navy); letter-spacing: -.5px; }
    .brand-text { text-align: left; }
    .brand-name { font-size: 1rem; font-weight: 700; color: white; }
    .brand-sub { font-size: .65rem; font-weight: 500; color: rgba(255,255,255,.4); letter-spacing: .8px; text-transform: uppercase; margin-top: 1px; }
    .card { background: white; border-radius: 14px; padding: 2.2rem; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
    .card-head { margin-bottom: 1.5rem; }
    .card-head h1 { font-size: 1.25rem; font-weight: 800; color: var(--navy); letter-spacing: -.3px; margin-bottom: .3rem; }
    .card-head p { font-size: .85rem; color: var(--muted); }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .83rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .field input { width: 100%; padding: .72rem .9rem; border: 1.5px solid var(--border); border-radius: 8px; font: inherit; font-size: .93rem; color: var(--text); background: var(--light); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); background: white; }
    .btn-submit { width: 100%; padding: .82rem; background: var(--navy); color: white; font: inherit; font-size: .97rem; font-weight: 700; border: none; border-radius: 9px; cursor: pointer; margin-top: .5rem; transition: background .15s, transform .1s; }
    .btn-submit:hover { background: var(--navy-mid); transform: translateY(-1px); }
    .btn-submit:disabled { opacity: .55; cursor: not-allowed; transform: none; }
    .alert { border-radius: 8px; padding: .7rem 1rem; font-size: .85rem; font-weight: 600; margin-bottom: 1rem; }
    .alert.success { background: var(--success-bg); color: var(--success); border: 1px solid #a7d9bc; }
    .alert.error   { background: var(--danger-bg);  color: var(--danger);  border: 1px solid #f5c6c6; }
    .card-divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0 1.2rem; }
    .back-link { display: block; text-align: center; font-size: .85rem; color: var(--muted); text-decoration: none; }
    .back-link:hover { color: var(--navy); }
    .foot { margin-top: 1.4rem; text-align: center; font-size: .8rem; color: rgba(255,255,255,.35); }
    .foot a { color: rgba(255,255,255,.5); text-decoration: none; }
  </style>
</head>
<body>
  <div class="wrap">
    <a class="brand" href="index.php">
      <div class="brand-mark">RL</div>
      <div class="brand-text">
        <div class="brand-name">Risonaf Loans</div>
        <div class="brand-sub">Admin Portal</div>
      </div>
    </a>

    <div class="card">
      <div class="card-head">
        <h1>Reset Password</h1>
        <p>Enter your new admin password below.</p>
      </div>

      <?php if (!$tokenValid): ?>
        <div class="alert error"><?= htmlspecialchars($tokenError, ENT_QUOTES, 'UTF-8') ?></div>
        <hr class="card-divider" />
        <a class="back-link" href="forgot_password.php">Request a new reset link</a>
      <?php else: ?>
        <div class="alert" id="alertBox" style="display:none"></div>
        <form id="resetForm">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>" />
          <div class="field">
            <label for="new_password">New Password</label>
            <input id="new_password" name="new_password" type="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters" />
          </div>
          <div class="field">
            <label for="confirm_password">Confirm New Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required minlength="8" autocomplete="new-password" placeholder="Repeat password" />
          </div>
          <button class="btn-submit" type="submit" id="submitBtn">Set New Password</button>
        </form>
        <hr class="card-divider" />
        <a class="back-link" href="login.php">&#8592; Back to Sign In</a>
      <?php endif; ?>
    </div>

    <div class="foot"><a href="index.php">&#8592; Back to public site</a></div>
  </div>

  <?php if ($tokenValid): ?>
  <script>
    const form      = document.getElementById('resetForm');
    const alertBox  = document.getElementById('alertBox');
    const submitBtn = document.getElementById('submitBtn');

    function showAlert(msg, type) {
      alertBox.textContent = msg;
      alertBox.className   = 'alert ' + type;
      alertBox.style.display = 'block';
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const pw = document.getElementById('new_password').value;
      const cf = document.getElementById('confirm_password').value;
      if (pw !== cf) { showAlert('Passwords do not match.', 'error'); return; }
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving…';
      try {
        const res  = await fetch('api/do_reset.php', { method: 'POST', body: new FormData(form) });
        const data = await res.json();
        if (data.success) {
          showAlert(data.message, 'success');
          form.style.display = 'none';
          setTimeout(() => { window.location.href = 'login.php'; }, 2000);
        } else {
          showAlert(data.message || 'Error resetting password.', 'error');
        }
      } catch {
        showAlert('Network error — please try again.', 'error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Set New Password';
      }
    });
  </script>
  <?php endif; ?>
</body>
</html>
