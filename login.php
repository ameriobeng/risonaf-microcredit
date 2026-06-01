<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

session_start();

// Already logged in → go straight to dashboard
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
      --primary:       #16a34a;
      --primary-dark:  #15803d;
      --primary-light: #dcfce7;
      --gold:          #d97706;
      --dark:          #0f172a;
      --text:          #1e293b;
      --muted:         #64748b;
      --light:         #f8fafc;
      --border:        #e2e8f0;
      --danger:        #dc2626;
      --danger-light:  #fee2e2;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: var(--dark);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background-image:
        radial-gradient(circle at 20% 30%, rgba(22,163,74,.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(217,119,6,.10) 0%, transparent 50%);
    }

    .login-wrap {
      width: 100%;
      max-width: 420px;
    }

    /* ── BRAND ── */
    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .65rem;
      margin-bottom: 2rem;
      text-decoration: none;
    }

    .brand-icon {
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, var(--primary), var(--gold));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }

    .brand-name {
      font-size: 1.1rem;
      font-weight: 800;
      color: white;
      letter-spacing: -.3px;
    }

    .brand-name span {
      display: block;
      font-size: .72rem;
      font-weight: 500;
      color: #64748b;
      letter-spacing: .3px;
      margin-top: .05rem;
    }

    /* ── CARD ── */
    .card {
      background: white;
      border-radius: 20px;
      padding: 2.2rem 2rem;
      box-shadow: 0 24px 60px rgba(0,0,0,.3);
    }

    .card-header {
      margin-bottom: 1.8rem;
    }

    .card-header h1 {
      font-size: 1.35rem;
      font-weight: 800;
      color: var(--dark);
      letter-spacing: -.4px;
      margin-bottom: .3rem;
    }

    .card-header p {
      font-size: .88rem;
      color: var(--muted);
    }

    /* ── ERROR ── */
    .alert-error {
      background: var(--danger-light);
      color: var(--danger);
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: .75rem 1rem;
      font-size: .88rem;
      font-weight: 600;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    /* ── FORM ── */
    .field {
      margin-bottom: 1rem;
    }

    .field label {
      display: block;
      font-size: .85rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: .4rem;
    }

    .field input {
      width: 100%;
      padding: .75rem .9rem;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font: inherit;
      font-size: .95rem;
      color: var(--text);
      background: var(--light);
      outline: none;
      transition: border-color .15s, box-shadow .15s;
    }

    .field input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22,163,74,.12);
      background: white;
    }

    .btn-login {
      width: 100%;
      padding: .85rem;
      background: var(--primary);
      color: white;
      font: inherit;
      font-size: 1rem;
      font-weight: 700;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      margin-top: .5rem;
      transition: background .15s, transform .1s;
    }

    .btn-login:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
    }

    .btn-login:active { transform: translateY(0); }

    /* ── FOOTER ── */
    .login-footer {
      margin-top: 1.5rem;
      text-align: center;
      font-size: .82rem;
      color: #475569;
    }

    .login-footer a {
      color: #64748b;
      text-decoration: none;
    }

    .login-footer a:hover { color: white; }
  </style>
</head>
<body>

  <div class="login-wrap">
    <a class="brand" href="index.php">
      <div class="brand-icon">🏦</div>
      <div class="brand-name">
        Risonaf Loans
        <span>ADMIN PORTAL</span>
      </div>
    </a>

    <div class="card">
      <div class="card-header">
        <h1>Welcome back</h1>
        <p>Sign in to access the admin dashboard.</p>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php" autocomplete="on">
        <div class="field">
          <label for="username">Username</label>
          <input
            id="username"
            name="username"
            type="text"
            placeholder="admin"
            autocomplete="username"
            required
          />
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          />
        </div>

        <button class="btn-login" type="submit">Sign In →</button>
      </form>
    </div>

    <div class="login-footer">
      <a href="index.php">← Back to public site</a>
    </div>
  </div>

</body>
</html>
