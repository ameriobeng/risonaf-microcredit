<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Risonaf Loans Ghana | Responsible Microfinance Solutions</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy:         #0c2340;
      --navy-mid:     #163556;
      --navy-light:   #e6edf7;
      --gold:         #b8862a;
      --gold-bright:  #d4a73a;
      --gold-light:   #fdf5e4;
      --text:         #1b2535;
      --muted:        #677080;
      --light:        #f3f5f8;
      --card:         #ffffff;
      --border:       #d9e0eb;
      --success:      #0f6d3d;
      --success-bg:   #e5f3ec;
      --danger:       #be2222;
      --danger-bg:    #fde8e8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); line-height: 1.6; }
    .container { width: min(1100px, 92%); margin: 0 auto; }

    /* ── HEADER ── */
    header { background: var(--navy); color: white; padding: 1rem 0; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,.06); }
    .nav { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .brand { display: flex; align-items: center; gap: .7rem; text-decoration: none; color: white; }
    .brand-mark {
      width: 36px; height: 36px;
      background: var(--gold);
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: .85rem; font-weight: 800; color: var(--navy); letter-spacing: -.5px;
    }
    .brand-text { font-size: 1rem; font-weight: 700; letter-spacing: -.2px; }
    .brand-text small { display: block; font-size: .65rem; font-weight: 500; color: rgba(255,255,255,.5); letter-spacing: .5px; text-transform: uppercase; margin-top: -2px; }
    .nav-links { display: flex; gap: .2rem; flex-wrap: wrap; }
    .nav-link { color: rgba(255,255,255,.7); text-decoration: none; font-size: .88rem; font-weight: 500; padding: .42rem .75rem; border-radius: 6px; transition: all .15s; }
    .nav-link:hover { color: white; background: rgba(255,255,255,.08); }
    .nav-link.cta { color: var(--navy); background: var(--gold); font-weight: 600; }
    .nav-link.cta:hover { background: var(--gold-bright); }

    /* ── HERO ── */
    .hero {
      background: var(--navy);
      color: white;
      padding: 5rem 0 4.5rem;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 80% at 110% 50%, rgba(184,134,42,.12) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at -10% 80%, rgba(22,53,86,.8) 0%, transparent 60%);
      pointer-events: none;
    }
    .hero-inner { position: relative; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
    .hero-label { display: inline-flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--gold-bright); margin-bottom: 1.1rem; }
    .hero-label::before { content: ''; width: 20px; height: 2px; background: var(--gold); border-radius: 2px; }
    .hero h1 { font-size: clamp(1.9rem, 3.5vw, 2.8rem); font-weight: 800; line-height: 1.18; letter-spacing: -.5px; margin-bottom: 1.1rem; }
    .hero h1 em { font-style: normal; color: var(--gold-bright); }
    .hero p { font-size: 1rem; color: rgba(255,255,255,.7); max-width: 480px; margin-bottom: 2rem; line-height: 1.7; }
    .hero-actions { display: flex; gap: .8rem; flex-wrap: wrap; }
    .btn-hero-primary { background: var(--gold); color: var(--navy); font: inherit; font-weight: 700; font-size: .95rem; padding: .8rem 1.6rem; border: none; border-radius: 7px; cursor: pointer; text-decoration: none; transition: all .15s; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-hero-primary:hover { background: var(--gold-bright); transform: translateY(-1px); }
    .btn-hero-outline { background: transparent; color: white; font: inherit; font-weight: 600; font-size: .95rem; padding: .8rem 1.5rem; border: 1.5px solid rgba(255,255,255,.3); border-radius: 7px; cursor: pointer; text-decoration: none; transition: all .15s; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-hero-outline:hover { border-color: rgba(255,255,255,.7); background: rgba(255,255,255,.06); }

    /* Hero right panel */
    .hero-card { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 14px; padding: 1.6rem; backdrop-filter: blur(8px); }
    .hero-card-title { font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: var(--gold-bright); margin-bottom: 1.1rem; }
    .hero-stats { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
    .hero-stat { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08); border-radius: 10px; padding: .9rem 1rem; }
    .hero-stat-val { font-size: 1.5rem; font-weight: 800; color: white; letter-spacing: -.5px; }
    .hero-stat-lbl { font-size: .75rem; color: rgba(255,255,255,.5); margin-top: .2rem; }
    .hero-divider { border: none; border-top: 1px solid rgba(255,255,255,.08); margin: 1rem 0; }
    .hero-note { font-size: .8rem; color: rgba(255,255,255,.4); text-align: center; }

    /* ── TRUST BAR ── */
    .trust-bar { background: white; border-bottom: 1px solid var(--border); padding: .85rem 0; }
    .trust-items { display: flex; gap: 2.5rem; flex-wrap: wrap; justify-content: center; }
    .trust-item { display: flex; align-items: center; gap: .55rem; font-size: .83rem; font-weight: 600; color: var(--muted); }
    .trust-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }

    /* ── SECTIONS ── */
    section { padding: 3.5rem 0; }
    .section-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--gold); margin-bottom: .55rem; }
    .section-title { font-size: clamp(1.4rem, 2.5vw, 2rem); font-weight: 800; color: var(--navy); letter-spacing: -.4px; margin-bottom: .5rem; }
    .section-sub { color: var(--muted); font-size: .96rem; margin-bottom: 2.2rem; }

    /* ── SERVICE CARDS ── */
    .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.2rem; }
    .service-card { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 1.6rem; transition: box-shadow .2s, transform .2s; position: relative; overflow: hidden; }
    .service-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--navy); }
    .service-card:hover { box-shadow: 0 8px 28px rgba(12,35,64,.1); transform: translateY(-2px); }
    .service-icon { width: 44px; height: 44px; background: var(--navy-light); border-radius: 9px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
    .service-icon svg { color: var(--navy); }
    .service-card h3 { font-size: .97rem; font-weight: 700; color: var(--navy); margin-bottom: .4rem; }
    .service-card p { font-size: .88rem; color: var(--muted); line-height: 1.6; }

    /* ── FORM ── */
    .form-section { background: linear-gradient(180deg, #eef2f8 0%, var(--light) 100%); }
    .form-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 2.2rem; box-shadow: 0 4px 24px rgba(12,35,64,.06); max-width: 780px; margin: 0 auto; }
    .form-head { display: flex; align-items: flex-start; gap: .9rem; margin-bottom: 1.8rem; padding-bottom: 1.4rem; border-bottom: 1px solid var(--border); }
    .form-head-icon { width: 42px; height: 42px; background: var(--navy); border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .form-head h2 { font-size: 1.2rem; font-weight: 800; color: var(--navy); letter-spacing: -.3px; }
    .form-head p { font-size: .85rem; color: var(--muted); margin-top: .15rem; }
    form { display: grid; gap: 1rem; }
    .row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .field label { display: block; font-size: .84rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .field input, .field select, .field textarea { width: 100%; padding: .7rem .85rem; border: 1.5px solid #cdd4df; border-radius: 8px; font: inherit; font-size: .93rem; background: white; color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; }
    .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.1); }
    .field textarea { min-height: 110px; resize: vertical; }
    .submit-btn { width: 100%; justify-content: center; font-size: .97rem; font-weight: 700; padding: .88rem 1.4rem; background: var(--navy); color: white; border: none; border-radius: 9px; cursor: pointer; font-family: inherit; margin-top: .4rem; transition: all .15s; }
    .submit-btn:hover { background: var(--navy-mid); transform: translateY(-1px); }
    .msg { font-size: .88rem; font-weight: 600; min-height: 1.2rem; padding: .5rem .75rem; border-radius: 7px; }
    .msg.success { background: var(--success-bg); color: var(--success); }
    .msg.error { background: var(--danger-bg); color: var(--danger); }
    .policy-agree { display: flex; align-items: flex-start; gap: .65rem; padding: .75rem; background: var(--gold-light); border: 1px solid #e8c97a; border-radius: 8px; }
    .policy-agree input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--navy); flex-shrink: 0; margin-top: 2px; cursor: pointer; }
    .policy-agree label { font-size: .87rem; color: var(--text); line-height: 1.55; cursor: pointer; }
    .policy-agree label a { color: var(--navy); font-weight: 600; text-decoration: underline; }
    .upload-field label { display: block; font-size: .84rem; font-weight: 600; color: var(--text); margin-bottom: .38rem; }
    .upload-field label span { font-weight: 400; color: var(--muted); font-size: .8rem; }
    .upload-field input[type="file"] { width: 100%; padding: .6rem .85rem; border: 1.5px dashed #cdd4df; border-radius: 8px; font: inherit; font-size: .88rem; background: var(--light); color: var(--text); cursor: pointer; }
    .upload-field input[type="file"]:focus { border-color: var(--navy); outline: none; }

    /* ── SUBMISSIONS ── */
    .submissions-section { margin-top: 1.6rem; padding-top: 1.3rem; border-top: 1px solid var(--border); }
    .submissions-section h3 { font-size: .76rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .9px; margin-bottom: .8rem; }
    .submission-item { border: 1px solid var(--border); border-radius: 9px; padding: .85rem 1rem; background: var(--light); font-size: .88rem; color: var(--muted); }

    /* ── ABOUT ── */
    .about-card { background: var(--navy); color: white; border-radius: 14px; padding: 2.5rem; }
    .about-card h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: -.4px; }
    .about-card h2 em { font-style: normal; color: var(--gold-bright); }
    .about-card p { color: rgba(255,255,255,.65); line-height: 1.75; margin-bottom: .8rem; }

    /* ── FOOTER ── */
    footer { background: var(--navy); color: rgba(255,255,255,.4); text-align: center; padding: 1.5rem 0; font-size: .83rem; border-top: 1px solid rgba(255,255,255,.06); }
    footer span { color: rgba(255,255,255,.6); }

    /* ── LOAN BADGE ── */
    .loan-badge { display: inline-block; font-size: .72rem; font-weight: 600; padding: .15rem .55rem; border-radius: 100px; background: var(--navy-light); color: var(--navy); margin-left: .3rem; }

    /* ── CALCULATOR ── */
    .calc-section { background: var(--navy); padding: 3.5rem 0; }
    .calc-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; align-items: start; }
    .calc-left-head { margin-bottom: 1.6rem; }
    .calc-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--gold-bright); margin-bottom: .45rem; }
    .calc-title { font-size: 1.5rem; font-weight: 800; color: white; letter-spacing: -.4px; margin-bottom: .4rem; }
    .calc-sub { font-size: .88rem; color: rgba(255,255,255,.5); line-height: 1.6; }
    .calc-field { margin-bottom: 1rem; }
    .calc-field label { display: block; font-size: .83rem; font-weight: 600; color: rgba(255,255,255,.7); margin-bottom: .38rem; }
    .calc-field input, .calc-field select { width: 100%; padding: .7rem .85rem; border: 1.5px solid rgba(255,255,255,.15); border-radius: 8px; font: inherit; font-size: .93rem; background: rgba(255,255,255,.08); color: white; outline: none; transition: border-color .15s, box-shadow .15s; }
    .calc-field input::placeholder { color: rgba(255,255,255,.3); }
    .calc-field input:focus, .calc-field select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,42,.2); }
    .calc-field select option { background: #0c2340; color: white; }
    .btn-apply-amount { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.1rem; background: var(--gold); color: var(--navy); font: inherit; font-weight: 700; font-size: .9rem; padding: .7rem 1.3rem; border: none; border-radius: 8px; cursor: pointer; transition: all .15s; }
    .btn-apply-amount:hover { background: var(--gold-bright); transform: translateY(-1px); }
    .calc-results { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; gap: .85rem; }
    .calc-results-title { font-size: .72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gold-bright); margin-bottom: .2rem; }
    .calc-row { display: flex; justify-content: space-between; align-items: center; }
    .calc-row .c-lbl { font-size: .85rem; color: rgba(255,255,255,.55); }
    .calc-row .c-val { font-size: 1.05rem; font-weight: 700; color: white; }
    .calc-row.highlight .c-val { color: var(--gold-bright); font-size: 1.4rem; }
    .calc-hr { border: none; border-top: 1px solid rgba(255,255,255,.08); }
    .calc-disclaimer { font-size: .75rem; color: rgba(255,255,255,.3); text-align: center; line-height: 1.55; margin-top: .4rem; }

    @media (max-width: 760px) { .hero-inner { grid-template-columns: 1fr; } .hero-card { display: none; } .row { grid-template-columns: 1fr; } .form-card { padding: 1.4rem; } .trust-items { gap: 1rem; } .calc-inner { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

  <header>
    <div class="container nav">
      <a class="brand" href="index.php">
        <div class="brand-mark">RL</div>
        <div class="brand-text">
          Risonaf Loans
          <small>Ghana</small>
        </div>
      </a>
      <nav class="nav-links">
        <a class="nav-link" href="#home">Home</a>
        <a class="nav-link" href="#services">Services</a>
        <a class="nav-link" href="#about">About</a>
        <a class="nav-link" href="status.php">Track Application</a>
        <a class="nav-link cta" href="#apply">Apply Now</a>
      </nav>
    </div>
  </header>

  <main id="home">

    <section class="hero">
      <div class="container">
        <div class="hero-inner">
          <div>
            <div class="hero-label">Trusted Microfinance · Ghana</div>
            <h1>Financing That Helps <em>Ghana Grow</em></h1>
            <p>
              Flexible, transparent, and responsible loans for individuals,
              small businesses, and community groups across Ghana.
              Fast decisions. Fair terms.
            </p>
            <div class="hero-actions">
              <a class="btn-hero-primary" href="#apply">Apply for a Loan</a>
              <a class="btn-hero-outline" href="#services">Our Services</a>
            </div>
          </div>
          <div>
            <div class="hero-card">
              <div class="hero-card-title">Why Risonaf</div>
              <div class="hero-stats">
                <div class="hero-stat">
                  <div class="hero-stat-val">3-Day</div>
                  <div class="hero-stat-lbl">Processing time</div>
                </div>
                <div class="hero-stat">
                  <div class="hero-stat-val">Low %</div>
                  <div class="hero-stat-lbl">Interest rates</div>
                </div>
                <div class="hero-stat">
                  <div class="hero-stat-val">3 Types</div>
                  <div class="hero-stat-lbl">Loan products</div>
                </div>
                <div class="hero-stat">
                  <div class="hero-stat-val">100%</div>
                  <div class="hero-stat-lbl">Transparent terms</div>
                </div>
              </div>
              <hr class="hero-divider" />
              <div class="hero-note">Serving communities across Ghana since 2020</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="trust-bar">
      <div class="container">
        <div class="trust-items">
          <div class="trust-item"><div class="trust-dot"></div>Verified &amp; Regulated</div>
          <div class="trust-item"><div class="trust-dot"></div>Fast Processing</div>
          <div class="trust-item"><div class="trust-dot"></div>Flexible Repayment</div>
          <div class="trust-item"><div class="trust-dot"></div>Dedicated Support</div>
          <div class="trust-item"><div class="trust-dot"></div>Secure &amp; Confidential</div>
        </div>
      </div>
    </div>

    <section id="services">
      <div class="container">
        <div class="section-eyebrow">What We Offer</div>
        <div class="section-title">Our Loan Products</div>
        <div class="section-sub">Designed for the real financial needs of Ghanaians.</div>

        <div class="services-grid">
          <article class="service-card">
            <div class="service-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
            <h3>Personal Loans</h3>
            <p>Support for education, healthcare, rent, and urgent personal needs with flexible repayment schedules.</p>
          </article>
          <article class="service-card">
            <div class="service-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <h3>Business Loans</h3>
            <p>Capital for stock, equipment, and business growth. Designed for small and medium enterprises in Ghana.</p>
          </article>
          <article class="service-card">
            <div class="service-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3>Group Loans</h3>
            <p>Shared-responsibility financing for cooperatives and community groups building financial stability together.</p>
          </article>
          <article class="service-card">
            <div class="service-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <h3>Quick Processing</h3>
            <p>Decisions within 3 business days. Transparent terms, no hidden fees, and dedicated support throughout.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="calculator" class="calc-section">
      <div class="container">
        <div class="calc-inner">
          <div>
            <div class="calc-left-head">
              <div class="calc-eyebrow">Plan Your Loan</div>
              <div class="calc-title">Repayment Calculator</div>
              <div class="calc-sub">Estimate your monthly payment before you apply. Adjust the amount, term, and rate to see the breakdown instantly.</div>
            </div>
            <div class="calc-field">
              <label for="calcAmount">Loan Amount (GHS)</label>
              <input id="calcAmount" type="number" min="1000" max="2000" placeholder="GHS 1,000 – 2,000" value="2000" />
            </div>
            <div class="calc-field">
              <label for="calcTerm">Repayment Period</label>
              <select id="calcTerm">
                <option value="3" selected>3 months</option>
                <option value="6">6 months</option>
                <option value="12">12 months</option>
                <option value="18">18 months</option>
                <option value="24">24 months</option>
              </select>
            </div>
            <div class="calc-field">
              <label for="calcRate">Flat Interest Rate (% of loan amount)</label>
              <input id="calcRate" type="number" min="0.1" max="100" step="0.1" value="20" />
            </div>
            <div class="calc-field">
              <label for="calcProcFee">Processing Fee (%)</label>
              <input id="calcProcFee" type="number" min="0" max="20" step="0.1" value="5" />
            </div>
            <button class="btn-apply-amount" id="applyCalcBtn" type="button">Apply for this Amount →</button>
          </div>
          <div>
            <div class="calc-results-title">Your Estimate</div>
            <div class="calc-results">
              <div class="calc-row highlight">
                <span class="c-lbl">Monthly Payment</span>
                <span class="c-val" id="calcMonthly">—</span>
              </div>
              <hr class="calc-hr" />
              <div class="calc-row">
                <span class="c-lbl">Loan Amount</span>
                <span class="c-val" id="calcPrincipal">—</span>
              </div>
              <div class="calc-row">
                <span class="c-lbl">Processing Fee (upfront)</span>
                <span class="c-val" id="calcFee">—</span>
              </div>
              <div class="calc-row">
                <span class="c-lbl">Amount Disbursed</span>
                <span class="c-val" id="calcDisbursed">—</span>
              </div>
              <hr class="calc-hr" />
              <div class="calc-row">
                <span class="c-lbl">Total Interest</span>
                <span class="c-val" id="calcInterest">—</span>
              </div>
              <div class="calc-row">
                <span class="c-lbl">Total Repayable</span>
                <span class="c-val" id="calcTotal">—</span>
              </div>
              <p class="calc-disclaimer">* Estimates based on flat interest on the loan amount. Processing fee is paid upfront and not added to repayments. Your final schedule will be confirmed at approval.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="apply" class="form-section">
      <div class="container">
        <div class="section-eyebrow" style="text-align:center">Get Started</div>
        <div class="section-title" style="text-align:center">Apply for a Loan</div>
        <div class="section-sub" style="text-align:center;margin-bottom:1.8rem">Complete the form below. Our team reviews every application personally.</div>

        <div class="form-card">
          <div class="form-head">
            <div class="form-head-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
              <h2>Loan Application Form</h2>
              <p>All fields are required. Your information is handled with strict confidentiality.</p>
            </div>
          </div>

          <form id="loanForm" enctype="multipart/form-data">
            <div class="row">
              <div class="field">
                <label for="fullName">Full Name</label>
                <input id="fullName" name="fullName" placeholder="e.g. Kofi Mensah" required />
              </div>
              <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" type="tel" placeholder="e.g. 0244000000"
                       pattern="^(0\d{9}|\+233\d{9})$"
                       title="Enter a valid Ghana phone number, e.g. 0244000000 or +233244000000"
                       required />
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
                <label for="idType">ID Card Type</label>
                <select id="idType" name="idType" required>
                  <option value="">— Select ID type —</option>
                  <option>Ghana Card</option>
                  <option>Passport</option>
                  <option>Driver's License</option>
                  <option>Voter's ID</option>
                </select>
              </div>
              <div class="field">
                <label for="idNumber">ID Card Number</label>
                <input id="idNumber" name="idNumber" placeholder="e.g. GHA-000000000-0" required />
              </div>
            </div>

            <div class="row">
              <div class="field">
                <label for="loanType">Loan Type</label>
                <select id="loanType" name="loanType" required>
                  <option value="">— Select loan type —</option>
                  <option>Personal Loan</option>
                  <option>Business Loan</option>
                  <option>Group Loan</option>
                </select>
              </div>
              <div class="field">
                <label for="amount">Amount Requested (GHS)</label>
                <input id="amount" name="amount" type="number" min="1000" max="2000"
                       placeholder="GHS 1,000 – 2,000" required />
              </div>
            </div>

            <div class="field">
              <label for="purpose">Purpose of Loan</label>
              <textarea id="purpose" name="purpose" placeholder="Briefly describe how you plan to use this loan…" required></textarea>
            </div>

            <div class="field upload-field">
              <label for="idDocument">Ghana ID Document <span>(Optional — JPG, PNG or PDF, max 3MB)</span></label>
              <input id="idDocument" name="idDocument" type="file" accept=".jpg,.jpeg,.png,.pdf" />
            </div>

            <div class="policy-agree">
              <input type="checkbox" id="policyAgree" name="policyAgree" required />
              <label for="policyAgree">
                I have read and agree to the <a href="policy.php" target="_blank">Risonaf Loans Loan Policy</a>, including the interest rate (20%), processing fee (5%), and late repayment fee (5% per month). I confirm that all information provided is accurate and complete.
              </label>
            </div>

            <button class="submit-btn" type="submit">Submit Application</button>
            <div class="msg" id="formMsg" aria-live="polite"></div>
          </form>

          <div class="submissions-section">
            <h3>Applications Received</h3>
            <div id="submissionsList">
              <p style="color:var(--muted);font-size:.88rem">Loading…</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about">
      <div class="container">
        <div class="about-card">
          <h2>About <em>Risonaf Loans</em></h2>
          <p>
            Risonaf Loans Ghana is committed to improving financial inclusion by providing accessible,
            responsible, and affordable micro-loan solutions across Ghana.
          </p>
          <p>
            Through fair repayment structures, transparent pricing, and customer-centred service, we
            help individuals, entrepreneurs, and community groups build financial stability, grow
            businesses, and achieve their long-term goals.
          </p>
        </div>
      </div>
    </section>

  </main>

  <footer>
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;">
      <span>© 2026 Risonaf Loans Ghana. All rights reserved.</span>
      <div style="display:flex;gap:1.2rem;">
        <a href="policy.php" style="color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;" onmouseover="this.style.color='rgba(255,255,255,.85)'" onmouseout="this.style.color='rgba(255,255,255,.5)'">Loan Policy</a>
        <a href="status.php" style="color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;" onmouseover="this.style.color='rgba(255,255,255,.85)'" onmouseout="this.style.color='rgba(255,255,255,.5)'">Track Application</a>
      </div>
    </div>
  </footer>

  <script>
    const form  = document.getElementById("loanForm");
    const msg   = document.getElementById("formMsg");
    const subEl = document.getElementById("submissionsList");

    function showMessage(text, type) {
      msg.textContent = text;
      msg.className = "msg " + (type || "");
      if (text) setTimeout(() => { msg.textContent = ""; msg.className = "msg"; }, 4000);
    }

    async function renderSubmissions() {
      try {
        const res  = await fetch("api/submit_count.php");
        const data = await res.json();
        const n = data.count ?? 0;
        subEl.innerHTML = n > 0
          ? `<div class="submission-item"><strong>${n}</strong> application${n !== 1 ? 's' : ''} received. Our team will be in touch shortly.</div>`
          : '<p style="color:var(--muted);font-size:.88rem">No submissions yet — be the first to apply.</p>';
      } catch {
        subEl.innerHTML = '<p style="color:var(--muted);font-size:.88rem">Unable to load status.</p>';
      }
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const btn = form.querySelector('.submit-btn');
      btn.disabled = true; btn.textContent = 'Submitting…';
      try {
        const res    = await fetch("api/submit.php", { method: "POST", body: new FormData(form) });
        const result = await res.json();
        if (!res.ok || !result.success) { showMessage(result.message || "Failed to submit.", "error"); return; }
        form.reset();
        showMessage("Application submitted. A confirmation has been sent to your email.", "success");
        await renderSubmissions();
      } catch {
        showMessage("Network error — please try again.", "error");
      } finally {
        btn.disabled = false; btn.textContent = 'Submit Application';
      }
    });

    renderSubmissions();

    // ── Loan calculator ───────────────────────────────────────────────────────
    const fmtCalc = v => 'GHS ' + new Intl.NumberFormat('en-GH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(v);

    function calcLoan() {
      const amount  = parseFloat(document.getElementById('calcAmount').value)  || 0;
      const months  = parseInt(document.getElementById('calcTerm').value)      || 3;
      const rate    = parseFloat(document.getElementById('calcRate').value)    || 20;   // flat % of principal
      const feeRate = parseFloat(document.getElementById('calcProcFee').value) || 5;   // processing fee %

      const blank = ['calcMonthly','calcPrincipal','calcFee','calcDisbursed','calcInterest','calcTotal'];
      if (amount < 1000) { blank.forEach(id => { document.getElementById(id).textContent = '—'; }); return; }

      const procFee  = amount * (feeRate / 100);          // 5% of principal, paid upfront
      const disbursed = amount - procFee;                 // what borrower actually receives
      const interest  = amount * (rate / 100);            // flat rate on full principal
      const total     = amount + interest;                // total to repay (excl. fee)
      const monthly   = total / months;                   // equal monthly instalments

      document.getElementById('calcMonthly').textContent   = fmtCalc(monthly);
      document.getElementById('calcPrincipal').textContent = fmtCalc(amount);
      document.getElementById('calcFee').textContent       = fmtCalc(procFee);
      document.getElementById('calcDisbursed').textContent = fmtCalc(disbursed);
      document.getElementById('calcInterest').textContent  = fmtCalc(interest);
      document.getElementById('calcTotal').textContent     = fmtCalc(total);
    }

    ['calcAmount', 'calcTerm', 'calcRate', 'calcProcFee'].forEach(id => {
      document.getElementById(id).addEventListener('input', calcLoan);
    });

    document.getElementById('applyCalcBtn').addEventListener('click', () => {
      const amount = parseFloat(document.getElementById('calcAmount').value);
      if (amount >= 1000) document.getElementById('amount').value = amount;
      document.getElementById('apply').scrollIntoView({ behavior: 'smooth' });
    });

    calcLoan();
  </script>
</body>
</html>
