<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Risonaf Microcredit Ghana | Smart Financial Support</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #16a34a;
      --primary-dark: #15803d;
      --primary-light: #dcfce7;
      --gold: #d97706;
      --gold-light: #fef3c7;
      --dark: #0f172a;
      --text: #1e293b;
      --muted: #64748b;
      --light: #f8fafc;
      --card: #ffffff;
      --border: #e2e8f0;
      --success: #16a34a;
      --danger: #dc2626;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: var(--light);
      color: var(--text);
      line-height: 1.6;
    }

    .container {
      width: min(1100px, 92%);
      margin: 0 auto;
    }

    /* ── HEADER ── */
    header {
      background: var(--dark);
      color: white;
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 16px rgba(0,0,0,.18);
    }

    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: .6rem;
      font-weight: 800;
      font-size: 1.05rem;
      letter-spacing: -.2px;
    }

    .brand-icon {
      width: 34px;
      height: 34px;
      background: linear-gradient(135deg, var(--primary), var(--gold));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    .links {
      display: flex;
      gap: .4rem;
      flex-wrap: wrap;
    }

    .links a {
      color: #94a3b8;
      text-decoration: none;
      font-weight: 500;
      font-size: .92rem;
      padding: .45rem .75rem;
      border-radius: 8px;
      transition: color .15s, background .15s;
    }

    .links a:hover {
      color: white;
      background: rgba(255,255,255,.1);
    }

    /* ── HERO ── */
    .hero {
      background: linear-gradient(135deg, var(--dark) 0%, #1e3a2f 60%, #14532d 100%);
      color: white;
      padding: 5rem 0 4rem;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: -80px;
      right: -80px;
      width: 420px;
      height: 420px;
      background: radial-gradient(circle, rgba(217,119,6,.18) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: rgba(22,163,74,.2);
      border: 1px solid rgba(22,163,74,.4);
      color: #86efac;
      font-size: .82rem;
      font-weight: 600;
      padding: .35rem .8rem;
      border-radius: 100px;
      margin-bottom: 1.2rem;
      letter-spacing: .3px;
    }

    .hero h1 {
      font-size: clamp(1.9rem, 4vw, 3rem);
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 1rem;
      letter-spacing: -.5px;
    }

    .hero h1 span {
      color: #fbbf24;
    }

    .hero p {
      font-size: 1.05rem;
      color: #94a3b8;
      max-width: 560px;
      margin-bottom: 2rem;
    }

    .hero-actions {
      display: flex;
      gap: .8rem;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      font-family: inherit;
      font-weight: 700;
      font-size: .95rem;
      padding: .8rem 1.4rem;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: all .15s;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
    }

    .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }

    .btn-outline {
      background: transparent;
      color: white;
      border: 1.5px solid rgba(255,255,255,.3);
    }

    .btn-outline:hover { border-color: white; background: rgba(255,255,255,.08); }

    /* ── TRUST BAR ── */
    .trust-bar {
      background: white;
      border-bottom: 1px solid var(--border);
      padding: .9rem 0;
    }

    .trust-items {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
    }

    .trust-item {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .88rem;
      font-weight: 600;
      color: var(--muted);
    }

    .trust-item span:first-child {
      font-size: 1.1rem;
    }

    /* ── SECTIONS ── */
    section { padding: 3rem 0; }

    .section-label {
      font-size: .8rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--primary);
      margin-bottom: .5rem;
    }

    .section-title {
      font-size: clamp(1.4rem, 2.5vw, 2rem);
      font-weight: 800;
      color: var(--dark);
      letter-spacing: -.4px;
      margin-bottom: .5rem;
    }

    .section-sub {
      color: var(--muted);
      font-size: .97rem;
      margin-bottom: 2rem;
    }

    /* ── SERVICE CARDS ── */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 1.2rem;
    }

    .service-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      transition: box-shadow .2s, transform .2s;
    }

    .service-card:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.09);
      transform: translateY(-3px);
    }

    .service-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 1rem;
    }

    .icon-green { background: var(--primary-light); }
    .icon-gold  { background: var(--gold-light); }
    .icon-blue  { background: #dbeafe; }
    .icon-purple{ background: #ede9fe; }

    .service-card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: .4rem;
      color: var(--dark);
    }

    .service-card p {
      font-size: .9rem;
      color: var(--muted);
      line-height: 1.55;
    }

    /* ── LOAN FORM ── */
    .form-section {
      background: linear-gradient(180deg, #f0fdf4 0%, var(--light) 100%);
    }

    .form-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 4px 24px rgba(0,0,0,.06);
      max-width: 780px;
      margin: 0 auto;
    }

    .form-header {
      display: flex;
      align-items: center;
      gap: .75rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1.2rem;
      border-bottom: 1px solid var(--border);
    }

    .form-header-icon {
      width: 44px;
      height: 44px;
      background: var(--primary-light);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      flex-shrink: 0;
    }

    .form-header h2 {
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--dark);
      letter-spacing: -.3px;
    }

    .form-header p {
      font-size: .88rem;
      color: var(--muted);
      margin-top: .1rem;
    }

    form { display: grid; gap: 1rem; }

    .row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }

    .field label {
      display: block;
      font-size: .88rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: .4rem;
    }

    .field input,
    .field select,
    .field textarea {
      width: 100%;
      padding: .72rem .85rem;
      border: 1.5px solid #cbd5e1;
      border-radius: 10px;
      font: inherit;
      font-size: .95rem;
      background: white;
      color: var(--text);
      transition: border-color .15s, box-shadow .15s;
      outline: none;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }

    .field textarea { min-height: 110px; resize: vertical; }

    .submit-btn {
      width: 100%;
      justify-content: center;
      font-size: 1rem;
      padding: .9rem 1.4rem;
      background: var(--primary);
      color: white;
      border-radius: 12px;
      margin-top: .4rem;
    }

    .submit-btn:hover { background: var(--primary-dark); transform: translateY(-1px); }

    .msg {
      font-size: .9rem;
      font-weight: 600;
      min-height: 1.2rem;
      padding: .5rem .75rem;
      border-radius: 8px;
    }

    .msg.success {
      background: var(--primary-light);
      color: #15803d;
    }

    .msg.error {
      background: #fee2e2;
      color: #b91c1c;
    }

    /* ── SUBMISSIONS LIST ── */
    .submissions-section {
      margin-top: 1.5rem;
      padding-top: 1.2rem;
      border-top: 1px solid var(--border);
    }

    .submissions-section h3 {
      font-size: .92rem;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-bottom: .8rem;
    }

    .submissions-list {
      max-height: 300px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: .6rem;
    }

    .submission-item {
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: .9rem 1rem;
      background: var(--light);
      font-size: .9rem;
    }

    .submission-item strong { color: var(--dark); }

    .submission-item .sub-meta {
      font-size: .82rem;
      color: var(--muted);
      margin-top: .25rem;
    }

    .loan-badge {
      display: inline-block;
      font-size: .75rem;
      font-weight: 700;
      padding: .15rem .5rem;
      border-radius: 100px;
      background: var(--primary-light);
      color: var(--primary-dark);
      margin-left: .3rem;
    }

    /* ── ABOUT ── */
    .about-card {
      background: var(--dark);
      color: white;
      border-radius: 20px;
      padding: 2.5rem;
    }

    .about-card h2 {
      font-size: 1.6rem;
      font-weight: 800;
      margin-bottom: 1rem;
      letter-spacing: -.4px;
    }

    .about-card h2 span { color: #fbbf24; }

    .about-card p {
      color: #94a3b8;
      line-height: 1.7;
      margin-bottom: .8rem;
    }

    /* ── FOOTER ── */
    footer {
      background: var(--dark);
      color: #475569;
      text-align: center;
      padding: 1.5rem 0;
      font-size: .88rem;
      border-top: 1px solid #1e293b;
    }

    footer span { color: #94a3b8; }

    @media (max-width: 680px) {
      .row { grid-template-columns: 1fr; }
      .links { gap: .2rem; }
      .hero { padding: 3.5rem 0 3rem; }
      .form-card { padding: 1.4rem; }
      .trust-items { gap: 1rem; }
    }
  </style>
</head>
<body>

  <header>
    <div class="container nav">
      <div class="brand">
        <div class="brand-icon">🏦</div>
        Risonaf Microcredit
      </div>
      <nav class="links">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#apply">Apply</a>
        <a href="#about">About</a>
      </nav>
    </div>
  </header>

  <main id="home">

    <section class="hero">
      <div class="container">
        <div class="hero-badge">🇬🇭 Serving Ghana Since 2020</div>
        <h1>Financial Support That<br /><span>Moves Ghana Forward</span></h1>
        <p>
          Flexible, affordable loans for individuals, small businesses, and community groups.
          Fast processing. Transparent terms. Customer-first service.
        </p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="#apply">Apply for a Loan</a>
          <a class="btn btn-outline" href="#services">Our Services</a>
        </div>
      </div>
    </section>

    <div class="trust-bar">
      <div class="container">
        <div class="trust-items">
          <div class="trust-item"><span>✅</span> Verified & Secure</div>
          <div class="trust-item"><span>⚡</span> Fast Processing</div>
          <div class="trust-item"><span>🤝</span> Flexible Repayment</div>
          <div class="trust-item"><span>📞</span> Dedicated Support</div>
          <div class="trust-item"><span>🔒</span> Data Protected</div>
        </div>
      </div>
    </div>

    <section id="services">
      <div class="container">
        <div class="section-label">What We Offer</div>
        <div class="section-title">Our Loan Services</div>
        <div class="section-sub">Designed for real needs across Ghana.</div>

        <div class="services-grid">
          <article class="service-card">
            <div class="service-icon icon-green">👤</div>
            <h3>Personal Loans</h3>
            <p>Support for education, health, rent, and urgent personal expenses with flexible repayment.</p>
          </article>
          <article class="service-card">
            <div class="service-icon icon-gold">🏪</div>
            <h3>Business Loans</h3>
            <p>Affordable capital for stock, equipment, and expansion of small and growing businesses.</p>
          </article>
          <article class="service-card">
            <div class="service-icon icon-blue">👥</div>
            <h3>Group Loans</h3>
            <p>Financial support for cooperatives and community groups with shared responsibility.</p>
          </article>
          <article class="service-card">
            <div class="service-icon icon-purple">🚀</div>
            <h3>Quick Processing</h3>
            <p>Fast review and transparent repayment terms with dedicated customer support throughout.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="apply" class="form-section">
      <div class="container">
        <div class="section-label">Get Started</div>
        <div class="section-title" style="text-align:center">Apply for a Loan</div>
        <div class="section-sub" style="text-align:center;margin-bottom:1.5rem">Complete the form below and our team will review your application.</div>

        <div class="form-card">
          <div class="form-header">
            <div class="form-header-icon">📋</div>
            <div>
              <h2>Loan Application Form</h2>
              <p>All fields are required. Your data is handled securely.</p>
            </div>
          </div>

          <form id="loanForm">
            <div class="row">
              <div class="field">
                <label for="fullName">Full Name</label>
                <input id="fullName" name="fullName" placeholder="e.g. Kofi Mensah" required />
              </div>
              <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" placeholder="e.g. 0244 000 000" required />
              </div>
            </div>

            <div class="row">
              <div class="field">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" placeholder="you@example.com" required />
              </div>
              <div class="field">
                <label for="location">Location (Ghana)</label>
                <input id="location" name="location" placeholder="e.g. Accra, Kumasi…" required />
              </div>
            </div>

            <div class="row">
              <div class="field">
                <label for="loanType">Loan Type</label>
                <select id="loanType" name="loanType" required>
                  <option value="">-- Select loan type --</option>
                  <option>Personal Loan</option>
                  <option>Business Loan</option>
                  <option>Group Loan</option>
                </select>
              </div>
              <div class="field">
                <label for="amount">Amount Requested (GHS)</label>
                <input id="amount" name="amount" type="number" min="1" placeholder="e.g. 5000" required />
              </div>
            </div>

            <div class="field">
              <label for="purpose">Purpose of Loan</label>
              <textarea id="purpose" name="purpose" placeholder="Briefly describe how you plan to use the loan…" required></textarea>
            </div>

            <button class="btn submit-btn" type="submit">Submit Application →</button>
            <div class="msg" id="formMsg" aria-live="polite"></div>
          </form>

          <div class="submissions-section">
            <h3>Applications Received</h3>
            <div class="submissions-list" id="submissionsList">
              <p style="color:var(--muted);font-size:.9rem">Loading…</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about">
      <div class="container">
        <div class="about-card">
          <h2>About <span>Risonaf Microcredit</span></h2>
          <p>
            Risonaf Microcredit Ghana is committed to improving financial inclusion by providing accessible
            and responsible micro-loan solutions across Ghana.
          </p>
          <p>
            Through fair repayment structures, financial guidance, and customer-centered service, we help
            individuals, entrepreneurs, and groups build stability, grow businesses, and achieve long-term goals.
          </p>
        </div>
      </div>
    </section>

  </main>

  <footer>
    <div class="container">
      <span>© 2026 Risonaf Microcredit Ghana. All rights reserved.</span>
    </div>
  </footer>

  <script>
    const form = document.getElementById("loanForm");
    const msg = document.getElementById("formMsg");
    const submissionsList = document.getElementById("submissionsList");

    function showMessage(text, type) {
      msg.textContent = text;
      msg.className = "msg " + (type || "");
      if (text) {
        setTimeout(() => {
          msg.textContent = "";
          msg.className = "msg";
        }, 3500);
      }
    }

    function escapeHTML(str) {
      return String(str ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
    }

    async function renderSubmissions() {
      submissionsList.innerHTML = '<p style="color:var(--muted);font-size:.9rem">Loading…</p>';
      try {
        const res = await fetch("api/submit_count.php", { method: "GET" });
        const data = await res.json();
        const count = data.count ?? 0;
        submissionsList.innerHTML = count > 0
          ? `<div class="submission-item"><strong>${count}</strong> application${count !== 1 ? 's' : ''} received. Our team will be in touch shortly.</div>`
          : '<p style="color:var(--muted);font-size:.9rem">No submissions yet. Be the first to apply!</p>';
      } catch {
        submissionsList.innerHTML = '<p style="color:var(--muted);font-size:.9rem">Unable to load status.</p>';
      }
    }

    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      try {
        const response = await fetch("api/submit.php", { method: "POST", body: formData });
        const result = await response.json();
        if (!response.ok || !result.success) {
          showMessage(result.message || "Failed to submit application.", "error");
          return;
        }
        form.reset();
        showMessage("Application submitted successfully.", "success");
        await renderSubmissions();
      } catch {
        showMessage("Network/server error while submitting.", "error");
      }
    });

    renderSubmissions();
  </script>
</body>
</html>
