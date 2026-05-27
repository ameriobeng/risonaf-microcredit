<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Risonaf Microcredit Ghana</title>
  <style>
    :root {
      --bg: #f1f5f9;
      --card: #ffffff;
      --text: #0f172a;
      --muted: #64748b;
      --primary: #0d6efd;
      --danger: #dc3545;
      --danger-dark: #bb2d3b;
      --border: #e2e8f0;
      --success: #198754;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    .container {
      width: min(1150px, 94%);
      margin: 0 auto;
    }

    header {
      background: linear-gradient(135deg, var(--primary), #3b82f6);
      color: #fff;
      padding: 1rem 0;
      box-shadow: 0 2px 10px rgba(0,0,0,.08);
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: .8rem;
      flex-wrap: wrap;
    }

    .brand {
      font-weight: 700;
      font-size: 1.05rem;
    }

    .header-actions {
      display: flex;
      gap: .6rem;
      flex-wrap: wrap;
    }

    .btn {
      border: none;
      border-radius: 10px;
      padding: .65rem .9rem;
      cursor: pointer;
      font-weight: 700;
      text-decoration: none;
      display: inline-block;
      font-size: .95rem;
    }

    .btn-primary {
      background: #fff;
      color: var(--primary);
    }

    .btn-primary:hover { background: #eaf2ff; }

    .btn-danger {
      background: var(--danger);
      color: #fff;
    }

    .btn-danger:hover { background: var(--danger-dark); }

    main { padding: 1.2rem 0 2rem; }

    .panel {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1rem;
      box-shadow: 0 4px 12px rgba(2,6,23,.04);
      margin-bottom: 1rem;
    }

    .panel h2 { margin: 0 0 .35rem; font-size: 1.15rem; }

    .muted { color: var(--muted); margin: 0; }

    .stats-grid {
      margin-top: .8rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: .8rem;
    }

    .stat-card {
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: .8rem;
      background: #f8fafc;
    }

    .stat-title {
      color: var(--muted);
      font-size: .88rem;
      margin-bottom: .25rem;
    }

    .stat-value {
      font-size: 1.25rem;
      font-weight: 700;
    }

    .filters {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: .8rem;
      margin-top: .8rem;
    }

    input, select {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      padding: .62rem .7rem;
      font: inherit;
      background: white;
    }

    .table-wrap {
      overflow: auto;
      margin-top: .8rem;
      border: 1px solid var(--border);
      border-radius: 12px;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 860px;
    }

    th, td {
      text-align: left;
      padding: .7rem .65rem;
      border-bottom: 1px solid #edf2f7;
      vertical-align: top;
      font-size: .93rem;
    }

    th {
      background: #f8fafc;
      position: sticky;
      top: 0;
      z-index: 1;
      font-size: .85rem;
      color: #334155;
      text-transform: uppercase;
      letter-spacing: .3px;
    }

    .empty {
      padding: 1.1rem;
      color: var(--muted);
      font-weight: 600;
    }

    .status {
      margin-top: .65rem;
      font-weight: 700;
      color: var(--success);
      min-height: 1.2rem;
    }

    @media (max-width: 760px) {
      .filters { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <header>
    <div class="container header-row">
      <div class="brand">Risonaf Microcredit Ghana — Admin Dashboard</div>
      <div class="header-actions">
        <a class="btn btn-primary" href="index.php">Back to Public Site</a>
        <button class="btn btn-danger" id="clearAllBtn" type="button">Clear All Data</button>
      </div>
    </div>
  </header>

  <main class="container">
    <section class="panel">
      <h2>Application Summary</h2>
      <p class="muted">Overview of database-stored applications submitted from the public form.</p>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Total Applications</div>
          <div class="stat-value" id="totalApplications">0</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Amount Requested (GHS)</div>
          <div class="stat-value" id="totalAmount">0</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Personal Loans</div>
          <div class="stat-value" id="personalCount">0</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Business Loans</div>
          <div class="stat-value" id="businessCount">0</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Group Loans</div>
          <div class="stat-value" id="groupCount">0</div>
        </div>
      </div>
    </section>

    <section class="panel">
      <h2>Applications</h2>
      <p class="muted">Search and filter application records.</p>

      <div class="filters">
        <input id="searchInput" type="text" placeholder="Search by name, phone, email, location, or purpose..." />
        <select id="loanTypeFilter">
          <option value="">All loan types</option>
          <option value="Personal Loan">Personal Loan</option>
          <option value="Business Loan">Business Loan</option>
          <option value="Group Loan">Group Loan</option>
        </select>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Location</th>
              <th>Loan Type</th>
              <th>Amount (GHS)</th>
              <th>Purpose</th>
              <th>Submitted At</th>
            </tr>
          </thead>
          <tbody id="applicationsBody"></tbody>
        </table>
        <div id="emptyState" class="empty" style="display:none;">No applications found.</div>
      </div>

      <div class="status" id="statusMsg" aria-live="polite"></div>
    </section>
  </main>

  <script>
    const totalApplicationsEl = document.getElementById("totalApplications");
    const totalAmountEl = document.getElementById("totalAmount");
    const personalCountEl = document.getElementById("personalCount");
    const businessCountEl = document.getElementById("businessCount");
    const groupCountEl = document.getElementById("groupCount");

    const searchInput = document.getElementById("searchInput");
    const loanTypeFilter = document.getElementById("loanTypeFilter");
    const applicationsBody = document.getElementById("applicationsBody");
    const emptyState = document.getElementById("emptyState");
    const clearAllBtn = document.getElementById("clearAllBtn");
    const statusMsg = document.getElementById("statusMsg");

    let allApplications = [];

    function formatNumber(value) {
      return new Intl.NumberFormat("en-GH").format(value);
    }

    function escapeHTML(str) {
      return String(str ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "<")
        .replaceAll(">", ">")
        .replaceAll('"', """)
        .replaceAll("'", "&#039;");
    }

    function setStatus(message) {
      statusMsg.textContent = message;
      if (message) {
        setTimeout(() => {
          if (statusMsg.textContent === message) statusMsg.textContent = "";
        }, 2800);
      }
    }

    function renderStats(stats) {
      totalApplicationsEl.textContent = stats.totalApplications ?? 0;
      totalAmountEl.textContent = formatNumber(Number(stats.totalAmount ?? 0));
      personalCountEl.textContent = stats.personalCount ?? 0;
      businessCountEl.textContent = stats.businessCount ?? 0;
      groupCountEl.textContent = stats.groupCount ?? 0;
    }

    function normalize(value) {
      return (value || "").toString().toLowerCase();
    }

    function filterApplications(items) {
      const query = normalize(searchInput.value.trim());
      const type = loanTypeFilter.value;

      return items.filter(item => {
        const matchesType = !type || item.loanType === type;
        const blob = [
          item.fullName,
          item.phone,
          item.email,
          item.location,
          item.purpose
        ].map(normalize).join(" ");
        const matchesQuery = !query || blob.includes(query);
        return matchesType && matchesQuery;
      });
    }

    function renderTable(items) {
      if (!items.length) {
        applicationsBody.innerHTML = "";
        emptyState.style.display = "block";
        return;
      }

      emptyState.style.display = "none";
      applicationsBody.innerHTML = items.map(item => `
        <tr>
          <td>${escapeHTML(item.fullName)}</td>
          <td>${escapeHTML(item.phone)}</td>
          <td>${escapeHTML(item.email)}</td>
          <td>${escapeHTML(item.location)}</td>
          <td>${escapeHTML(item.loanType)}</td>
          <td>${formatNumber(Number(item.amount || 0))}</td>
          <td>${escapeHTML(item.purpose)}</td>
          <td>${escapeHTML(item.submittedAt)}</td>
        </tr>
      `).join("");
    }

    async function loadData() {
      const response = await fetch("api/get_applications.php");
      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "Failed to load applications");
      }

      allApplications = Array.isArray(data.applications) ? data.applications : [];
      renderStats(data.stats || {});
      renderTable(filterApplications(allApplications));
    }

    function renderFiltered() {
      renderTable(filterApplications(allApplications));
    }

    searchInput.addEventListener("input", renderFiltered);
    loanTypeFilter.addEventListener("change", renderFiltered);

    clearAllBtn.addEventListener("click", async () => {
      const confirmed = window.confirm("This will permanently remove all application records. Continue?");
      if (!confirmed) return;

      try {
        const response = await fetch("api/clear_applications.php", { method: "POST" });
        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "Failed to clear applications");
        }

        await loadData();
        setStatus("All application data has been cleared.");
      } catch (err) {
        setStatus(err.message || "Unable to clear data.");
      }
    });

    loadData().catch((err) => {
      applicationsBody.innerHTML = "";
      emptyState.style.display = "block";
      emptyState.textContent = "Unable to load applications.";
      setStatus(err.message || "Failed to load data");
    });
  </script>
</body>
</html>
