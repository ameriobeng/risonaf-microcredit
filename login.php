<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (
        ADMIN_PASSWORD_HASH !== '' &&
        hash_equals(ADMIN_USERNAME, $username) &&
        password_verify($password, ADMIN_PASSWORD_HASH)
    ) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        $_SESSION['csrf_token']      = bin2hex(random_bytes(32));
        header('Location: admin.php');
        exit;
    }

    $error = ADMIN_PASSWORD_HASH === ''
        ? 'Admin password not configured. Run generate_hash.php first.'
        : 'Incorrect username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | Risonaf Loans Ghana</title>
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
      --border:      #d9e0eb;
      --danger:      #be2222;
      --danger-bg:   #fde8e8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', Arial, sans-serif;
      background: var(--navy);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background-image:
        radial-gradient(ellipse 70% 60% at 100% 0%, rgba(184,134,42,.1) 0%, transparent 55%),
        radial-gradient(ellipse 50% 70% at 0% 100%, rgba(22,53,86,.7) 0%, transparent 55%);
    }
    .wrap { width: 100%; max-width: 400px; }

    /* Brand */
    .brand { display: flex; align-items: center; justify-content: center; gap: .7rem; margin-bottom: 2rem; text-decoration: none; }
    .brand-mark { width: 42px; height: 42px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; font-weight: 800; color: var(--navy); letter-spacing: -.5px; }
    .brand-text { text-align: left; }
    .brand-name { font-size: 1rem; font-weight: 700; color: white; letter-spacing: -.2px; }
    .brand-sub { font-size: .65rem; font-weight: 500; color: rgba(255,255,255,.4); letter-spacing: .8px; text-transform: uppercase; margin-top: 1px; }

    /* Card */
    .card { background: white; border-radius: 14px; padding: 2.2rem; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
    .card-head { margin-bottom: 1.8rem; }
    .card-head h1 { font-size: 1.3rem; font-weight: 800; color: var(--navy); letter-spacing: -.4px; margin-bottom: .28rem; }
    .card-head p { font-size: .85rem; color: var(--muted); }

    /* Error */
    .alert-error { background: var(--danger-bg); color: var(--danger); border: 1px solid #f5c6c6; border-radius: 8px; padding: .72rem 1rem; font-size: .85rem; font-weight: 600; margin-bottom: 1.2rem; display: flex; align-items: center; gap: .5rem; }

    /* Form */
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .83rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .field input { width: 100%; padding: .72rem .9rem; border: 1.5px solid var(--border); border-radius: 8px; font: inherit; font-size: .93rem; color: var(--text); background: var(--light); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); background: white; }
    .btn-login { width: 100%; padding: .82rem; background: var(--navy); color: white; font: inherit; font-size: .97rem; font-weight: 700; border: none; border-radius: 9px; cursor: pointer; margin-top: .5rem; transition: background .15s, transform .1s; }
    .btn-login:hover { background: var(--navy-mid); transform: translateY(-1px); }

    /* Divider */
    .card-divider { border: none; border-top: 1px solid var(--border); margin: 1.6rem 0 1.2rem; }
    .secure-note { display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: var(--muted); }
    .secure-note svg { flex-shrink: 0; }

    /* Footer */
    .foot { margin-top: 1.4rem; text-align: center; font-size: .8rem; color: rgba(255,255,255,.35); }
    .foot a { color: rgba(255,255,255,.5); text-decoration: none; }
    .foot a:hover { color: rgba(255,255,255,.8); }
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
        <h1>Sign In</h1>
        <p>Access the loan management dashboard.</p>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert-error">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" autocomplete="on">
        <div class="field">
          <label for="username">Username</label>
          <input id="username" name="username" type="text" placeholder="Enter username" autocomplete="username" required />
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="••••••••" autocomplete="current-password" required />
        </div>
        <button class="btn-login" type="submit">Sign In</button>
      </form>

      <hr class="card-divider" />
      <div class="secure-note">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Secure, encrypted connection
      </div>
    </div>

    <div class="foot">
      <a href="index.php">← Back to public site</a>
    </div>
  </div>
</body>
</html>
