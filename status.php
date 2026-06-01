<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Check Application Status | Risonaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #16a34a; --primary-dark: #15803d; --primary-light: #dcfce7;
      --gold: #d97706; --dark: #0f172a; --text: #1e293b; --muted: #64748b;
      --light: #f8fafc; --border: #e2e8f0; --danger: #dc2626;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); min-height: 100vh; }
    .container { width: min(560px, 92%); margin: 0 auto; }

    header { background: var(--dark); color: white; padding: 1rem 0; }
    .nav { display: flex; justify-content: space-between; align-items: center; width: min(1100px, 92%); margin: 0 auto; }
    .brand { display: flex; align-items: center; gap: .6rem; font-weight: 800; font-size: 1.05rem; text-decoration: none; color: white; }
    .brand-icon { width: 34px; height: 34px; background: linear-gradient(135deg, var(--primary), var(--gold)); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .nav-link { color: #94a3b8; text-decoration: none; font-size: .9rem; font-weight: 500; padding: .4rem .7rem; border-radius: 8px; transition: color .15s, background .15s; }
    .nav-link:hover { color: white; background: rgba(255,255,255,.1); }

    main { padding: 3rem 0; }

    .card { background: white; border: 1px solid var(--border); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 24px rgba(0,0,0,.06); }
    .card-header { text-align: center; margin-bottom: 1.8rem; }
    .card-icon { width: 56px; height: 56px; background: var(--primary-light); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto .8rem; }
    .card-header h1 { font-size: 1.4rem; font-weight: 800; color: var(--dark); letter-spacing: -.3px; }
    .card-header p { color: var(--muted); font-size: .9rem; margin-top: .3rem; }

    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .88rem; font-weight: 600; margin-bottom: .4rem; }
    .field input { width: 100%; padding: .72rem .85rem; border: 1.5px solid #cbd5e1; border-radius: 10px; font: inherit; font-size: .95rem; outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }

    .btn-primary { width: 100%; padding: .85rem; background: var(--primary); color: white; border: none; border-radius: 12px; font: inherit; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .15s; margin-top: .4rem; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-primary:disabled { opacity: .6; cursor: not-allowed; }

    .msg { margin-top: 1rem; padding: .6rem .85rem; border-radius: 8px; font-size: .9rem; font-weight: 600; display: none; }
    .msg.error { background: #fee2e2; color: #b91c1c; display: block; }

    .result { margin-top: 1.5rem; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; display: none; }
    .result.visible { display: block; }
    .result-header { padding: 1rem 1.2rem; background: #f8fafc; border-bottom: 1px solid var(--border); }
    .result-header .applicant { font-weight: 700; font-size: 1rem; color: var(--dark); }
    .result-header .meta { font-size: .83rem; color: var(--muted); margin-top: .15rem; }
    .result-body { padding: 1rem 1.2rem; display: grid; gap: .6rem; }
    .result-row { display: flex; justify-content: space-between; align-items: center; font-size: .9rem; }
    .result-row .label { color: var(--muted); font-weight: 500; }
    .result-row .value { font-weight: 700; color: var(--dark); }

    .status-badge { display: inline-block; font-size: .78rem; font-weight: 700; padding: .25rem .65rem; border-radius: 100px; }
    .status-Pending  { background: #fef3c7; color: #92400e; }
    .status-Approved { background: var(--primary-light); color: #15803d; }
    .status-Rejected { background: #fee2e2; color: #b91c1c; }

    .divider { border: none; border-top: 1px solid var(--border); margin: .4rem 0; }

    footer { text-align: center; padding: 1.5rem 0; font-size: .85rem; color: var(--muted); }
  </style>
</head>
<body>

<header>
  <div class="nav">
    <a class="brand" href="index.php">
      <div class="brand-icon">🏦</div>
      Risonaf Loans
    </a>
    <a class="nav-link" href="index.php#apply">← Apply for a Loan</a>
  </div>
</header>

<main>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <div class="card-icon">🔍</div>
        <h1>Check Application Status</h1>
        <p>Enter the phone number and ID number you used when applying.</p>
      </div>

      <form id="statusForm">
        <div class="field">
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" placeholder="e.g. 0244000000" required
                 pattern="^(0\d{9}|\+233\d{9})$"
                 title="Enter a valid Ghana phone number" />
        </div>
        <div class="field">
          <label for="idNumber">ID Card Number</label>
          <input id="idNumber" name="idNumber" placeholder="e.g. GHA-000000000-0" required />
        </div>
        <button class="btn-primary" type="submit" id="submitBtn">Check Status</button>
        <div class="msg" id="msg"></div>
      </form>

      <div class="result" id="result">
        <div class="result-header">
          <div class="applicant" id="rName"></div>
          <div class="meta" id="rMeta"></div>
        </div>
        <div class="result-body">
          <div class="result-row">
            <span class="label">Loan Type</span>
            <span class="value" id="rType"></span>
          </div>
          <div class="result-row">
            <span class="label">Amount Requested</span>
            <span class="value" id="rAmount"></span>
          </div>
          <hr class="divider" />
          <div class="result-row">
            <span class="label">Status</span>
            <span id="rStatus"></span>
          </div>
          <div class="result-row">
            <span class="label">Submitted</span>
            <span class="value" id="rDate"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<footer>© 2026 Risonaf Loans Ghana. All rights reserved.</footer>

<script>
  const form      = document.getElementById('statusForm');
  const submitBtn = document.getElementById('submitBtn');
  const msgEl     = document.getElementById('msg');
  const resultEl  = document.getElementById('result');

  function showError(text) {
    msgEl.textContent = text;
    msgEl.className = 'msg error';
    resultEl.classList.remove('visible');
  }

  function fmt(v) { return 'GHS ' + new Intl.NumberFormat('en-GH').format(v); }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    msgEl.className = 'msg';
    resultEl.classList.remove('visible');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Checking…';

    const phone    = document.getElementById('phone').value.trim();
    const idNumber = document.getElementById('idNumber').value.trim();

    try {
      const params = new URLSearchParams({ phone, id_number: idNumber });
      const res  = await fetch('api/check_status.php?' + params);
      const data = await res.json();

      if (!res.ok || !data.success) {
        showError(data.message || 'No matching application found.');
        return;
      }

      const app = data.application;
      document.getElementById('rName').textContent   = app.fullName;
      document.getElementById('rMeta').textContent   = `Application #${app.id}`;
      document.getElementById('rType').textContent   = app.loanType;
      document.getElementById('rAmount').textContent = fmt(app.amount);
      document.getElementById('rDate').textContent   = app.submittedAt;

      const status = app.status || 'Pending';
      document.getElementById('rStatus').innerHTML =
        `<span class="status-badge status-${status}">${status}</span>`;

      resultEl.classList.add('visible');
    } catch {
      showError('Network error — please try again.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Check Status';
    }
  });
</script>
</body>
</html>
