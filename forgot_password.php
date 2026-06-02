<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | Risonaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy:        #0c2340;
      --navy-mid:    #163556;
      --gold:        #b8862a;
      --gold-bright: #d4a73a;
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
    body { font-family: 'Inter', Arial, sans-serif; background: var(--navy); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; background-image: radial-gradient(ellipse 70% 60% at 100% 0%, rgba(184,134,42,.1) 0%, transparent 55%), radial-gradient(ellipse 50% 70% at 0% 100%, rgba(22,53,86,.7) 0%, transparent 55%); }
    .wrap { width: 100%; max-width: 400px; }
    .brand { display: flex; align-items: center; justify-content: center; gap: .7rem; margin-bottom: 2rem; text-decoration: none; }
    .brand-mark { width: 42px; height: 42px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; font-weight: 800; color: var(--navy); letter-spacing: -.5px; }
    .brand-text { text-align: left; }
    .brand-name { font-size: 1rem; font-weight: 700; color: white; }
    .brand-sub { font-size: .65rem; font-weight: 500; color: rgba(255,255,255,.4); letter-spacing: .8px; text-transform: uppercase; margin-top: 1px; }
    .card { background: white; border-radius: 14px; padding: 2.2rem; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
    .card-head { margin-bottom: 1.5rem; }
    .card-head h1 { font-size: 1.25rem; font-weight: 800; color: var(--navy); letter-spacing: -.3px; margin-bottom: .3rem; }
    .card-head p { font-size: .85rem; color: var(--muted); line-height: 1.6; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .83rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .field input { width: 100%; padding: .72rem .9rem; border: 1.5px solid var(--border); border-radius: 8px; font: inherit; font-size: .93rem; color: var(--text); background: var(--light); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); background: white; }
    .btn-submit { width: 100%; padding: .82rem; background: var(--navy); color: white; font: inherit; font-size: .97rem; font-weight: 700; border: none; border-radius: 9px; cursor: pointer; margin-top: .5rem; transition: background .15s, transform .1s; }
    .btn-submit:hover { background: var(--navy-mid); transform: translateY(-1px); }
    .btn-submit:disabled { opacity: .55; cursor: not-allowed; transform: none; }
    .alert { border-radius: 8px; padding: .7rem 1rem; font-size: .85rem; font-weight: 600; margin-bottom: 1rem; display: none; }
    .alert.success { background: var(--success-bg); color: var(--success); border: 1px solid #a7d9bc; display: block; }
    .alert.error   { background: var(--danger-bg);  color: var(--danger);  border: 1px solid #f5c6c6; display: block; }
    .card-divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0 1.2rem; }
    .back-link { display: block; text-align: center; font-size: .85rem; color: var(--muted); text-decoration: none; }
    .back-link:hover { color: var(--navy); }
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
        <h1>Forgot Password</h1>
        <p>Enter your admin email address. If it matches our records, you'll receive a password reset link.</p>
      </div>

      <div class="alert" id="alertBox"></div>

      <form id="forgotForm">
        <div class="field">
          <label for="email">Admin Email Address</label>
          <input id="email" name="email" type="email" placeholder="admin@risonaf.com" required autocomplete="email" />
        </div>
        <button class="btn-submit" type="submit" id="submitBtn">Send Reset Link</button>
      </form>

      <hr class="card-divider" />
      <a class="back-link" href="login.php">&#8592; Back to Sign In</a>
    </div>

    <div class="foot"><a href="index.php">&#8592; Back to public site</a></div>
  </div>

  <script>
    const form      = document.getElementById('forgotForm');
    const alertBox  = document.getElementById('alertBox');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.className = 'alert';
      alertBox.textContent = '';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending…';
      try {
        const res  = await fetch('api/request_reset.php', { method: 'POST', body: new FormData(form) });
        const data = await res.json();
        alertBox.textContent = data.message || (data.success ? 'Reset link sent.' : 'Something went wrong.');
        alertBox.className   = 'alert ' + (data.success ? 'success' : 'error');
        if (data.success) form.style.display = 'none';
      } catch {
        alertBox.textContent = 'Network error — please try again.';
        alertBox.className   = 'alert error';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Reset Link';
      }
    });
  </script>
</body>
</html>
