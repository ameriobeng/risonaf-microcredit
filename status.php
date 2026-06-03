<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Track Application | Risonaf Loans Ghana</title>
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
      --success:     #0f6d3d;
      --success-bg:  #e5f3ec;
      --warning-bg:  #fef8e7;
      --warning:     #7c5a0a;
      --danger:      #be2222;
      --danger-bg:   #fde8e8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }

    /* Header */
    header { background: var(--navy); color: white; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,.06); }
    .nav { display: flex; justify-content: space-between; align-items: center; width: min(1100px, 92%); margin: 0 auto; }
    .brand { display: flex; align-items: center; gap: .65rem; text-decoration: none; color: white; }
    .brand-mark { width: 34px; height: 34px; background: var(--gold); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .78rem; font-weight: 800; color: var(--navy); }
    .brand-name { font-size: .97rem; font-weight: 700; }
    .brand-sub { font-size: .63rem; font-weight: 500; color: rgba(255,255,255,.4); letter-spacing: .6px; text-transform: uppercase; margin-top: 1px; }
    .nav-link { color: rgba(255,255,255,.65); text-decoration: none; font-size: .86rem; font-weight: 500; padding: .4rem .75rem; border-radius: 6px; transition: all .15s; }
    .nav-link:hover { color: white; background: rgba(255,255,255,.09); }

    /* Hero strip */
    .page-hero { background: var(--navy); padding: 2.5rem 0 2rem; border-bottom: 1px solid rgba(255,255,255,.06); }
    .page-hero-inner { width: min(1100px, 92%); margin: 0 auto; }
    .page-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gold-bright); margin-bottom: .4rem; }
    .page-hero h1 { font-size: 1.6rem; font-weight: 800; color: white; letter-spacing: -.4px; }
    .page-hero p { font-size: .9rem; color: rgba(255,255,255,.55); margin-top: .3rem; }

    main { flex: 1; padding: 2.5rem 0 4rem; }
    .container { width: min(520px, 92%); margin: 0 auto; }

    .card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 2rem; box-shadow: 0 4px 24px rgba(12,35,64,.07); }

    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .84rem; font-weight: 600; margin-bottom: .38rem; }
    .field input { width: 100%; padding: .7rem .85rem; border: 1.5px solid #cdd4df; border-radius: 8px; font: inherit; font-size: .93rem; outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); }

    .btn-submit { width: 100%; padding: .82rem; background: var(--navy); color: white; border: none; border-radius: 9px; font: inherit; font-size: .97rem; font-weight: 700; cursor: pointer; transition: background .15s; margin-top: .4rem; }
    .btn-submit:hover { background: var(--navy-mid); }
    .btn-submit:disabled { opacity: .55; cursor: not-allowed; }

    .msg { margin-top: .9rem; padding: .65rem .9rem; border-radius: 8px; font-size: .87rem; font-weight: 600; display: none; }
    .msg.error { background: var(--danger-bg); color: var(--danger); border: 1px solid #f5c6c6; display: block; }

    /* Result card */
    .result { margin-top: 1.6rem; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; display: none; }
    .result.visible { display: block; }
    .result-head { padding: .95rem 1.2rem; background: var(--navy); display: flex; align-items: center; justify-content: space-between; gap: .8rem; }
    .result-head .applicant { font-weight: 700; font-size: .97rem; color: white; }
    .result-head .ref { font-size: .78rem; color: rgba(255,255,255,.5); margin-top: .1rem; }
    .result-body { padding: 1.1rem 1.2rem; display: grid; gap: .65rem; }
    .result-row { display: flex; justify-content: space-between; align-items: center; font-size: .88rem; }
    .result-row .lbl { color: var(--muted); }
    .result-row .val { font-weight: 600; color: var(--text); }
    .divider { border: none; border-top: 1px solid var(--border); margin: .2rem 0; }

    .status-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .78rem; font-weight: 700; padding: .28rem .72rem; border-radius: 100px; }
    .status-pill.Pending  { background: var(--warning-bg); color: var(--warning); }
    .status-pill.Approved { background: var(--success-bg); color: var(--success); }
    .status-pill.Rejected { background: var(--danger-bg);  color: var(--danger); }
    .status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; opacity: .7; }

    /* ── SCHEDULE ── */
    .schedule-section { padding: 1rem 1.2rem; border-top: 1px solid var(--border); }
    .schedule-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); margin-bottom: .7rem; }
    .schedule-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; margin-bottom: .6rem; }
    .sched-card { border: 1px solid var(--border); border-radius: 9px; padding: .65rem .8rem; text-align: center; }
    .sched-card.paid     { background: var(--success-bg); border-color: #a7d9bc; }
    .sched-card.overdue  { background: var(--danger-bg);  border-color: #f5c6c6; }
    .sched-card.upcoming { background: var(--light); }
    .sched-month  { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: .25rem; }
    .sched-amount { font-size: .97rem; font-weight: 800; color: var(--navy); }
    .sched-date   { font-size: .72rem; color: var(--muted); margin-top: .2rem; }
    .sched-status { font-size: .7rem; font-weight: 700; margin-top: .3rem; }
    .sched-card.paid     .sched-status { color: var(--success); }
    .sched-card.overdue  .sched-status { color: var(--danger); }
    .sched-card.upcoming .sched-status { color: var(--muted); }
    .due-info { display: flex; align-items: center; gap: .5rem; font-size: .83rem; color: var(--muted); margin-bottom: .5rem; }
    .due-info strong { color: var(--text); }

    /* ── REPAYMENTS ── */
    .result-repay { padding: 1rem 1.2rem; border-top: 1px solid var(--border); }
    .repay-section-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); margin-bottom: .65rem; }
    .repay-summary { display: flex; gap: 1.8rem; margin-bottom: .8rem; flex-wrap: wrap; }
    .repay-kv .lbl { font-size: .75rem; color: var(--muted); display: block; margin-bottom: 2px; }
    .repay-kv .val { font-size: 1rem; font-weight: 700; }
    .repay-kv .val.green { color: var(--success); }
    .repay-kv .val.red   { color: var(--danger); }
    .repay-bar-wrap { display: flex; align-items: center; gap: .7rem; margin-bottom: .9rem; }
    .repay-bar { flex: 1; height: 7px; background: var(--border); border-radius: 4px; overflow: hidden; }
    .repay-fill { height: 100%; background: var(--success); border-radius: 4px; transition: width .6s ease; }
    .repay-pct { font-size: .8rem; font-weight: 700; color: var(--muted); white-space: nowrap; min-width: 60px; text-align: right; }
    .repay-history-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); margin-bottom: .5rem; }
    .repay-item { display: flex; justify-content: space-between; align-items: center; padding: .42rem 0; border-bottom: 1px solid var(--border); font-size: .85rem; }
    .repay-item:last-child { border-bottom: none; }
    .repay-item .r-amount { font-weight: 700; color: var(--success); }
    .repay-item .r-date   { color: var(--muted); font-size: .8rem; }
    .repay-none { font-size: .83rem; color: var(--muted); font-style: italic; }

    footer { background: var(--navy); color: rgba(255,255,255,.35); text-align: center; padding: 1.2rem 0; font-size: .8rem; }
  </style>
</head>
<body>

  <header>
    <div class="nav">
      <a class="brand" href="index.php">
        <div class="brand-mark">RL</div>
        <div>
          <div class="brand-name">Risonaf Loans</div>
          <div class="brand-sub">Ghana</div>
        </div>
      </a>
      <a class="nav-link" href="index.php#apply">Apply for a Loan</a>
    </div>
  </header>

  <div class="page-hero">
    <div class="page-hero-inner">
      <div class="page-eyebrow">Self-Service</div>
      <h1>Track Your Application</h1>
      <p>Enter the phone number and ID number you used when applying.</p>
    </div>
  </div>

  <main>
    <div class="container">
      <div class="card">
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
          <button class="btn-submit" type="submit" id="submitBtn">Check Status</button>
          <div class="msg" id="msg"></div>
        </form>

        <div class="result" id="result">
          <div class="result-head">
            <div>
              <div class="applicant" id="rName"></div>
              <div class="ref" id="rRef"></div>
            </div>
            <div id="rStatus"></div>
          </div>
          <div class="result-body">
            <div class="result-row">
              <span class="lbl">Loan Type</span>
              <span class="val" id="rType"></span>
            </div>
            <div class="result-row">
              <span class="lbl">Amount Requested</span>
              <span class="val" id="rAmount"></span>
            </div>
            <hr class="divider" />
            <div class="result-row">
              <span class="lbl">Date Submitted</span>
              <span class="val" id="rDate"></span>
            </div>
          </div>
          <div class="schedule-section" id="schedSection" style="display:none">
            <div class="schedule-title">Repayment Schedule</div>
            <div class="due-info" id="dueInfo"></div>
            <div class="schedule-grid" id="schedGrid"></div>
          </div>

          <div class="result-repay" id="resultRepay" style="display:none">
            <div class="repay-section-title">Repayment Summary</div>
            <div class="repay-summary">
              <div class="repay-kv"><span class="lbl">Total Paid</span><span class="val green" id="rPaid"></span></div>
              <div class="repay-kv"><span class="lbl">Outstanding</span><span class="val" id="rOutstanding"></span></div>
            </div>
            <div class="repay-bar-wrap">
              <div class="repay-bar"><div class="repay-fill" id="rProgressBar" style="width:0%"></div></div>
              <span class="repay-pct" id="rProgressPct">0% repaid</span>
            </div>
            <div id="rRepayHistory"></div>
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
    function fmt(v) { return 'GHS ' + new Intl.NumberFormat('en-GH').format(v); }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      msgEl.className = 'msg';
      resultEl.classList.remove('visible');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Checking…';
      try {
        const params = new URLSearchParams({ phone: document.getElementById('phone').value.trim(), id_number: document.getElementById('idNumber').value.trim() });
        const res  = await fetch('api/check_status.php?' + params);
        const data = await res.json();
        if (!res.ok || !data.success) { msgEl.textContent = data.message || 'No matching application found.'; msgEl.className = 'msg error'; return; }
        const app = data.application;
        document.getElementById('rName').textContent   = app.fullName;
        document.getElementById('rRef').textContent    = 'Reference #' + app.id;
        document.getElementById('rType').textContent   = app.loanType;
        document.getElementById('rAmount').textContent = fmt(app.amount);
        document.getElementById('rDate').textContent   = app.submittedAt;
        const s = app.status || 'Pending';
        document.getElementById('rStatus').innerHTML = `<span class="status-pill ${s}"><span class="status-dot"></span>${s}</span>`;

        // Repayment schedule
        const schedEl   = document.getElementById('schedSection');
        const schedGrid = document.getElementById('schedGrid');
        const dueInfo   = document.getElementById('dueInfo');
        if (app.schedule && app.schedule.length > 0) {
          dueInfo.innerHTML = app.dueDate
            ? `Due date: <strong>${app.dueDate}</strong>`
            : '';
          schedGrid.innerHTML = app.schedule.map(s => `
            <div class="sched-card ${s.status.toLowerCase()}">
              <div class="sched-month">${s.month}</div>
              <div class="sched-amount">${fmt(s.amount)}</div>
              <div class="sched-date">${s.dueDate}</div>
              <div class="sched-status">${s.status === 'Paid' ? '✓ Paid' : s.status === 'Overdue' ? '⚠ Overdue' : '○ Upcoming'}</div>
            </div>
          `).join('');
          schedEl.style.display = 'block';
        } else {
          schedEl.style.display = 'none';
        }

        // Repayment section
        const repayEl = document.getElementById('resultRepay');
        if (typeof app.totalPaid !== 'undefined') {
          document.getElementById('rPaid').textContent = fmt(app.totalPaid);
          const outEl = document.getElementById('rOutstanding');
          outEl.textContent = fmt(app.outstanding);
          outEl.className = 'val ' + (app.outstanding > 0 ? 'red' : 'green');
          const pct = app.amount > 0 ? Math.min(100, Math.round(app.totalPaid / app.amount * 100)) : 0;
          document.getElementById('rProgressBar').style.width = pct + '%';
          document.getElementById('rProgressPct').textContent = pct + '% repaid';
          const histEl = document.getElementById('rRepayHistory');
          if (app.repayments && app.repayments.length > 0) {
            histEl.innerHTML = '<div class="repay-history-title">Payment History</div>'
              + app.repayments.map(r =>
                  `<div class="repay-item"><span class="r-amount">${fmt(r.amount)}</span><span class="r-date">${r.recordedAt}</span></div>`
                ).join('');
          } else {
            histEl.innerHTML = '<p class="repay-none">No payments recorded yet.</p>';
          }
          repayEl.style.display = 'block';
        } else {
          repayEl.style.display = 'none';
        }

        resultEl.classList.add('visible');
      } catch { msgEl.textContent = 'Network error — please try again.'; msgEl.className = 'msg error'; }
      finally { submitBtn.disabled = false; submitBtn.textContent = 'Check Status'; }
    });
  </script>
</body>
</html>
