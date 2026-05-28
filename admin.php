<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Risonaf Microcredit Ghana</title>
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
      --danger: #dc2626;
      --danger-dark: #b91c1c;
      --danger-light: #fee2e2;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: var(--light);
      color: var(--text);
      line-height: 1.5;
      min-height: 100vh;
    }

    .container {
      width: min(1200px, 94%);
      margin: 0 auto;
    }

    /* ── HEADER ── */
    header {
      background: var(--dark);
      color: white;
      padding: 1rem 0;
      box-shadow: 0 2px 16px rgba(0,0,0,.2);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: .8rem;
      flex-wrap: wrap;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: .6rem;
      font-weight: 800;
      font-size: 1rem;
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
      flex-shrink: 0;
    }

    .admin-tag {
      font-size: .72rem;
      font-weight: 700;
      background: rgba(22,163,74,.25);
      color: #86efac;
      border: 1px solid rgba(22,163,74,.3);
      padding: .18rem .55rem;
      border-radius: 100px;
      letter-spacing: .4px;
      text-transform: uppercase;
    }

    .header-actions {
      display: flex;
      gap: .6rem;
      flex-wrap: wrap;
      align-items: center;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      font-family: inherit;
      font-weight: 700;
      font-size: .88rem;
      padding: .6rem 1rem;
      border-radius: 9px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: all .15s;
    }

    .btn-ghost {
      background: rgba(255,255,255,.1);
      color: white;
      border: 1px solid rgba(255,255,255,.18);
    }

    .btn-ghost:hover { background: rgba(255,255,255,.18); }

    .btn-danger {
      background: var(--danger);
      color: white;
    }

    .btn-danger:hover { background: var(--danger-dark); }

    /* ── MAIN ── */
    main { padding: 1.5rem 0 3rem; }

    /* ── STAT CARDS ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
      gap: 1rem;
      margin-bottom: 1.2rem;
    }

    .stat-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.2rem 1.3rem;
      display: flex;
      flex-direction: column;
      gap: .5rem;
      transition: box-shadow .2s;
    }

    .stat-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.08); }

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-label {
      font-size: .8rem;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .stat-icon {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .icon-green  { background: var(--primary-light); }
    .icon-gold   { background: var(--gold-light); }
    .icon-blue   { background: #dbeafe; }
    .icon-indigo { background: #e0e7ff; }
    .icon-pink   { background: #fce7f3; }

    .stat-value {
      font-size: 1.7rem;
      font-weight: 800;
      color: var(--dark);
      letter-spacing: -.5px;
      line-height: 1;
    }

    /* ── PANEL ── */
    .panel {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,.04);
    }

    .panel-header {
      padding: 1.1rem 1.3rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .panel-header h2 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--dark);
    }

    .panel-header p {
      font-size: .83rem;
      color: var(--muted);
      margin-top: .1rem;
    }

    /* ── FILTERS ── */
    .filters {
      padding: 1rem 1.3rem;
      border-bottom: 1px solid var(--border);
      display: grid;
      grid-template-columns: 1fr auto;
      gap: .8rem;
      background: #fafbfc;
    }

    .search-wrap {
      position: relative;
    }

    .search-icon {
      position: absolute;
      left: .8rem;
      top: 50%;
      transform: translateY(-50%);
      font-size: .95rem;
      pointer-events: none;
    }

    .filters input,
    .filters select {
      font-family: inherit;
      font-size: .9rem;
      border: 1.5px solid #cbd5e1;
      border-radius: 9px;
      padding: .62rem .8rem;
      background: white;
      color: var(--text);
      outline: none;
      transition: border-color .15s, box-shadow .15s;
      width: 100%;
    }

    .filters input { padding-left: 2.2rem; }

    .filters input:focus,
    .filters select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }

    /* ── TABLE ── */
    .table-wrap {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 820px;
    }

    th {
      background: #f8fafc;
      padding: .7rem 1rem;
      text-align: left;
      font-size: .75rem;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .6px;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }

    td {
      padding: .8rem 1rem;
      font-size: .9rem;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: top;
      color: var(--text);
    }

    tr:last-child td { border-bottom: none; }

    tr:hover td { background: #fafffe; }

    .loan-badge {
      display: inline-block;
      font-size: .72rem;
      font-weight: 700;
      padding: .2rem .55rem;
      border-radius: 100px;
      white-space: nowrap;
    }

    .badge-personal { background: var(--primary-light); color: #15803d; }
    .badge-business { background: var(--gold-light);    color: #92400e; }
    .badge-group    { background: #dbeafe;              color: #1d4ed8; }
    .badge-default  { background: #f1f5f9;              color: var(--muted); }

    .amount-cell {
      font-weight: 700;
      color: var(--dark);
      white-space: nowrap;
    }

    .date-cell {
      font-size: .82rem;
      color: var(--muted);
      white-space: nowrap;
    }

    .empty-state {
      padding: 3rem 1.5rem;
      text-align: center;
      color: var(--muted);
    }

    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: .75rem; }
    .empty-state p { font-size: .92rem; }

    /* ── STATUS ── */
    .panel-footer {
      padding: .75rem 1.3rem;
      border-top: 1px solid var(--border);
      background: #fafbfc;
    }

    .status-msg {
      font-size: .88rem;
      font-weight: 600;
      color: var(--primary);
      min-height: 1.3rem;
    }

    @media (max-width: 760px) {
      .filters { grid-template-columns: 1fr; }
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 480px) {
      .stats-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <header>
    <div class="container header-row">
      <div class="brand">
        <div class="brand-icon">🏦</div>
        Risonaf Microcredit
        <span class="admin-tag">Admin</span>
      </div>
      <div class="header-actions">
        <a class="btn btn-ghost" href="index.php">← Public Site</a>
        <a class="btn btn-ghost" href="api/logout.php">Sign Out</a>
        <button class="btn btn-danger" id="clearAllBtn" type="button">🗑 Clear All Data</button>
      </div>
    </div>
  </header>

  <main>
    <div class="container">

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-label">Total Applications</div>
            <div class="stat-icon icon-green">📋</div>
          </div>
          <div class="stat-value" id="totalApplications">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-label">Total Requested (GHS)</div>
            <div class="stat-icon icon-gold">💰</div>
          </div>
          <div class="stat-value" id="totalAmount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-label">Personal Loans</div>
            <div class="stat-icon icon-blue">👤</div>
          </div>
          <div class="stat-value" id="personalCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-label">Business Loans</div>
            <div class="stat-icon icon-indigo">🏪</div>
          </div>
          <div class="stat-value" id="businessCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-label">Group Loans</div>
            <div class="stat-icon icon-pink">👥</div>
          </div>
          <div class="stat-value" id="groupCount">—</div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <div>
            <h2>Loan Applications</h2>
            <p>Search, filter, and review submitted applications.</p>
          </div>
        </div>

        <div class="filters">
          <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input id="searchInput" type="text" placeholder="Search by name, phone, email, location, or purpose…" />
          </div>
          <select id="loanTypeFilter">
            <option value="">All types</option>
            <option value="Personal Loan">Personal Loan</option>
            <option value="Business Loan">Business Loan</option>
            <option value="Group Loan">Group Loan</option>
          </select>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Applicant</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Location</th>
                <th>Type</th>
                <th>Amount (GHS)</th>
                <th>Purpose</th>
                <th>Submitted</th>
              </tr>
            </thead>
            <tbody id="applicationsBody"></tbody>
          </table>
          <div id="emptyState" class="empty-state" style="display:none;">
            <div class="empty-icon">📭</div>
            <p>No applications found.</p>
          </div>
        </div>

        <div class="panel-footer">
          <div class="status-msg" id="statusMsg" aria-live="polite"></div>
        </div>
      </div>

    </div>
  </main>

  <script>
    const totalApplicationsEl = document.getElementById("totalApplications");
    const totalAmountEl       = document.getElementById("totalAmount");
    const personalCountEl     = document.getElementById("personalCount");
    const businessCountEl     = document.getElementById("businessCount");
    const groupCountEl        = document.getElementById("groupCount");

    const searchInput       = document.getElementById("searchInput");
    const loanTypeFilter    = document.getElementById("loanTypeFilter");
    const applicationsBody  = document.getElementById("applicationsBody");
    const emptyState        = document.getElementById("emptyState");
    const clearAllBtn       = document.getElementById("clearAllBtn");
    const statusMsg         = document.getElementById("statusMsg");

    let allApplications = [];

    function formatNumber(value) {
      return new Intl.NumberFormat("en-GH").format(value);
    }

    function escapeHTML(str) {
      return String(str ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
    }

    function loanBadge(type) {
      const map = {
        "Personal Loan": "badge-personal",
        "Business Loan": "badge-business",
        "Group Loan":    "badge-group",
      };
      const cls = map[type] || "badge-default";
      return `<span class="loan-badge ${cls}">${escapeHTML(type)}</span>`;
    }

    function setStatus(message, isError = false) {
      statusMsg.textContent = message;
      statusMsg.style.color = isError ? "var(--danger)" : "var(--primary)";
      if (message) {
        setTimeout(() => {
          if (statusMsg.textContent === message) statusMsg.textContent = "";
        }, 3000);
      }
    }

    function renderStats(stats) {
      totalApplicationsEl.textContent = stats.totalApplications ?? 0;
      totalAmountEl.textContent       = formatNumber(Number(stats.totalAmount ?? 0));
      personalCountEl.textContent     = stats.personalCount ?? 0;
      businessCountEl.textContent     = stats.businessCount ?? 0;
      groupCountEl.textContent        = stats.groupCount ?? 0;
    }

    function normalize(value) {
      return (value || "").toString().toLowerCase();
    }

    function filterApplications(items) {
      const query = normalize(searchInput.value.trim());
      const type  = loanTypeFilter.value;
      return items.filter(item => {
        const matchesType  = !type || item.loanType === type;
        const blob = [item.fullName, item.phone, item.email, item.location, item.purpose].map(normalize).join(" ");
        const matchesQuery = !query || blob.includes(query);
        return matchesType && matchesQuery;
      });
    }

    function renderTable(items) {
      if (!items.length) {
        applicationsBody.innerHTML = "";
        emptyState.style.display   = "block";
        return;
      }
      emptyState.style.display = "none";
      applicationsBody.innerHTML = items.map(item => `
        <tr>
          <td><strong>${escapeHTML(item.fullName)}</strong></td>
          <td>${escapeHTML(item.phone)}</td>
          <td>${escapeHTML(item.email)}</td>
          <td>${escapeHTML(item.location)}</td>
          <td>${loanBadge(item.loanType)}</td>
          <td class="amount-cell">${formatNumber(Number(item.amount || 0))}</td>
          <td>${escapeHTML(item.purpose)}</td>
          <td class="date-cell">${escapeHTML(item.submittedAt)}</td>
        </tr>
      `).join("");
    }

    async function loadData() {
      const response = await fetch("api/get_applications.php");
      const data     = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || "Failed to load applications");
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
        const data     = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || "Failed to clear applications");
        await loadData();
        setStatus("All application data has been cleared.");
      } catch (err) {
        setStatus(err.message || "Unable to clear data.", true);
      }
    });

    loadData().catch(err => {
      applicationsBody.innerHTML = "";
      emptyState.style.display   = "block";
      emptyState.querySelector("p").textContent = "Unable to load applications.";
      setStatus(err.message || "Failed to load data.", true);
    });
  </script>
</body>
</html>
