<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Risonaf Microcredit Ghana | Smart Financial Support</title>
  <style>
    :root{
      --primary:#0d6efd;
      --secondary:#0b5ed7;
      --dark:#0f172a;
      --light:#f8fafc;
      --muted:#64748b;
      --success:#198754;
      --danger:#dc3545;
      --card:#ffffff;
      --border:#e2e8f0;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Arial, Helvetica, sans-serif;
      background:#f1f5f9;
      color:#0f172a;
      line-height:1.5;
    }
    .container{
      width:min(1100px, 92%);
      margin:0 auto;
    }
    header{
      background:linear-gradient(135deg, var(--primary), #3b82f6);
      color:white;
      padding:1rem 0;
      position:sticky;
      top:0;
      z-index:10;
      box-shadow:0 2px 10px rgba(0,0,0,.08);
    }
    .nav{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:1rem;
    }
    .brand{
      font-weight:700;
      font-size:1.1rem;
    }
    .links{
      display:flex;
      gap:.8rem;
      flex-wrap:wrap;
    }
    .links a{
      color:white;
      text-decoration:none;
      font-weight:600;
      padding:.4rem .6rem;
      border-radius:8px;
    }
    .links a:hover{
      background:rgba(255,255,255,.15);
    }

    .hero{
      padding:4rem 0 3rem;
      background:linear-gradient(180deg, #e0edff, transparent);
    }
    .hero h1{
      margin:0 0 .8rem;
      font-size:clamp(1.7rem, 3vw, 2.6rem);
    }
    .hero p{
      max-width:700px;
      color:#1e293b;
      margin:0 0 1rem;
    }
    .cta{
      display:inline-block;
      background:var(--primary);
      color:#fff;
      text-decoration:none;
      font-weight:700;
      padding:.75rem 1rem;
      border-radius:10px;
    }
    .cta:hover{background:var(--secondary)}

    section{
      padding:2.2rem 0;
    }
    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:14px;
      padding:1rem;
      box-shadow:0 4px 12px rgba(2,6,23,.04);
    }
    .grid{
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
      gap:1rem;
    }
    .service h3{margin:.2rem 0}
    .muted{color:var(--muted)}

    form{
      display:grid;
      gap:.8rem;
    }
    .row{
      display:grid;
      grid-template-columns:repeat(2, minmax(0,1fr));
      gap:.8rem;
    }
    label{
      display:block;
      font-weight:600;
      margin-bottom:.3rem;
    }
    input, select, textarea{
      width:100%;
      padding:.65rem .7rem;
      border:1px solid #cbd5e1;
      border-radius:10px;
      font:inherit;
      background:white;
    }
    textarea{min-height:110px; resize:vertical}
    button{
      border:none;
      background:var(--primary);
      color:white;
      padding:.75rem 1rem;
      font-weight:700;
      border-radius:10px;
      cursor:pointer;
    }
    button:hover{background:var(--secondary)}
    .msg{
      margin-top:.4rem;
      font-weight:700;
      min-height:1.2rem;
    }
    .msg.success{color:var(--success)}
    .msg.error{color:var(--danger)}

    .submissions{
      margin-top:1rem;
      max-height:280px;
      overflow:auto;
      border-top:1px dashed #cbd5e1;
      padding-top:.8rem;
    }
    .submission-item{
      border:1px solid #e2e8f0;
      border-radius:10px;
      padding:.7rem;
      margin-bottom:.6rem;
      background:#f8fafc;
      font-size:.95rem;
    }

    footer{
      background:var(--dark);
      color:#cbd5e1;
      text-align:center;
      padding:1rem 0;
      margin-top:1.4rem;
    }

    @media (max-width:700px){
      .row{grid-template-columns:1fr}
      .links{justify-content:flex-end}
    }
  </style>
</head>
<body>
  <header>
    <div class="container nav">
      <div class="brand">Risonaf Microcredit Ghana</div>
      <nav class="links">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#apply">Apply</a>
        <a href="#about">About Us</a>
      </nav>
    </div>
  </header>

  <main id="home">
    <section class="hero">
      <div class="container">
        <h1>Smart Financial Support For Your Goals – What We Do.</h1>
        <p>
          We offer flexible and affordable loan services to individuals, small businesses, and groups.
          Our goal is to bridge financial gaps and empower economic growth across Ghana.
        </p>
        <a class="cta" href="#apply">Apply for a Loan</a>
      </div>
    </section>

    <section id="services">
      <div class="container">
        <h2>Our Loan Services</h2>
        <p class="muted">Flexible options designed for real needs.</p>
        <div class="grid">
          <article class="card service">
            <h3>Personal Loans</h3>
            <p>Support for education, health, rent, and urgent personal expenses.</p>
          </article>
          <article class="card service">
            <h3>Business Loans</h3>
            <p>Affordable capital for stock, equipment, and expansion of small businesses.</p>
          </article>
          <article class="card service">
            <h3>Group Loans</h3>
            <p>Financial support for cooperatives and community groups with shared responsibility.</p>
          </article>
          <article class="card service">
            <h3>Quick Processing</h3>
            <p>Fast review and transparent repayment terms with customer-first support.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="apply">
      <div class="container">
        <div class="card">
          <h2>Loan Data Collection Form</h2>
          <p class="muted">Fill in your details to submit your loan request on the platform.</p>

          <form id="loanForm">
            <div class="row">
              <div>
                <label for="fullName">Full Name</label>
                <input id="fullName" name="fullName" required />
              </div>
              <div>
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" required />
              </div>
            </div>

            <div class="row">
              <div>
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" required />
              </div>
              <div>
                <label for="location">Location (Ghana)</label>
                <input id="location" name="location" required />
              </div>
            </div>

            <div class="row">
              <div>
                <label for="loanType">Loan Type</label>
                <select id="loanType" name="loanType" required>
                  <option value="">-- Select --</option>
                  <option>Personal Loan</option>
                  <option>Business Loan</option>
                  <option>Group Loan</option>
                </select>
              </div>
              <div>
                <label for="amount">Amount Requested (GHS)</label>
                <input id="amount" name="amount" type="number" min="1" required />
              </div>
            </div>

            <div>
              <label for="purpose">Purpose of Loan</label>
              <textarea id="purpose" name="purpose" required></textarea>
            </div>

            <button type="submit">Submit Application</button>
            <div class="msg" id="formMsg" aria-live="polite"></div>
          </form>

          <div class="submissions">
            <h3>Recent Submitted Applications (Database)</h3>
            <div id="submissionsList" class="muted">Loading submissions...</div>
          </div>
        </div>
      </div>
    </section>

    <section id="about">
      <div class="container">
        <div class="card">
          <h2>About Us</h2>
          <p>
            Risonaf Microcredit Ghana is committed to improving financial inclusion by providing accessible
            and responsible micro-loan solutions. We focus on empowering individuals, entrepreneurs,
            and groups with funding that creates measurable impact in communities.
          </p>
          <p>
            Through fair repayment structures, financial guidance, and customer-centered service, we
            help people build stability, grow businesses, and achieve long-term goals.
          </p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="container">
      © 2026 Microcredit Ghana. All rights reserved.
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
        .replaceAll("<", "<")
        .replaceAll(">", ">")
        .replaceAll('"', """)
        .replaceAll("'", "&#039;");
    }

    async function fetchApplications() {
      const res = await fetch("api/get_applications.php", { method: "GET" });
      return res.json();
    }

    async function renderSubmissions() {
      submissionsList.innerHTML = "Loading submissions...";
      try {
        const data = await fetchApplications();
        const items = Array.isArray(data.applications) ? data.applications : [];

        if (!items.length) {
          submissionsList.innerHTML = "No submissions yet.";
          return;
        }

        submissionsList.innerHTML = items.slice(0, 20).map(item => `
          <div class="submission-item">
            <strong>${escapeHTML(item.fullName)}</strong> (${escapeHTML(item.loanType)}) - GHS ${escapeHTML(item.amount)}<br/>
            <span>Phone: ${escapeHTML(item.phone)} | Location: ${escapeHTML(item.location)}</span><br/>
            <span class="muted">Purpose: ${escapeHTML(item.purpose)}</span><br/>
            <small class="muted">Submitted: ${escapeHTML(item.submittedAt)}</small>
          </div>
        `).join("");
      } catch {
        submissionsList.innerHTML = "Unable to load submissions.";
      }
    }

    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      try {
        const response = await fetch("api/submit.php", {
          method: "POST",
          body: formData
        });

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
