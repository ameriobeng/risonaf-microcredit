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
  <title>Admin Dashboard | Risonaf Loans Ghana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary:       #16a34a;
      --primary-dark:  #15803d;
      --primary-light: #dcfce7;
      --gold:          #d97706;
      --gold-light:    #fef3c7;
      --dark:          #0f172a;
      --text:          #1e293b;
      --muted:         #64748b;
      --light:         #f8fafc;
      --card:          #ffffff;
      --border:        #e2e8f0;
      --danger:        #dc2626;
      --danger-dark:   #b91c1c;
      --danger-light:  #fee2e2;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); line-height: 1.5; min-height: 100vh; }
    .container { width: min(1200px, 94%); margin: 0 auto; }

    /* ── HEADER ── */
    header { background: var(--dark); color: white; padding: 1rem 0; box-shadow: 0 2px 16px rgba(0,0,0,.2); position: sticky; top: 0; z-index: 100; }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .brand { display: flex; align-items: center; gap: .6rem; font-weight: 800; font-size: 1rem; letter-spacing: -.2px; }
    .brand-icon { width: 34px; height: 34px; background: linear-gradient(135deg, var(--primary), var(--gold)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .admin-tag { font-size: .72rem; font-weight: 700; background: rgba(22,163,74,.25); color: #86efac; border: 1px solid rgba(22,163,74,.3); padding: .18rem .55rem; border-radius: 100px; letter-spacing: .4px; text-transform: uppercase; }
    .header-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; font-family: inherit; font-weight: 700; font-size: .88rem; padding: .6rem 1rem; border-radius: 9px; border: none; cursor: pointer; text-decoration: none; transition: all .15s; }
    .btn-ghost { background: rgba(255,255,255,.1); color: white; border: 1px solid rgba(255,255,255,.18); }
    .btn-ghost:hover { background: rgba(255,255,255,.18); }
    .btn-danger { background: var(--danger); color: white; }
    .btn-danger:hover { background: var(--danger-dark); }

    /* ── MAIN ── */
    main { padding: 1.5rem 0 3rem; }

    /* ── STAT CARDS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 1.2rem; }
    .stat-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.2rem 1.3rem; display: flex; flex-direction: column; gap: .5rem; transition: box-shadow .2s; }
    .stat-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.08); }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-label { font-size: .78rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
    .stat-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .icon-green  { background: var(--primary-light); }
    .icon-gold   { background: var(--gold-light); }
    .icon-blue   { background: #dbeafe; }
    .icon-indigo { background: #e0e7ff; }
    .icon-pink   { background: #fce7f3; }
    .icon-orange { background: #ffedd5; }
    .icon-emerald{ background: #d1fae5; }
    .icon-red    { background: #fee2e2; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--dark); letter-spacing: -.5px; line-height: 1; }

    /* ── PANEL ── */
    .panel { background: white; border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.04); }
    .panel-header { padding: 1.1rem 1.3rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .panel-header h2 { font-size: 1rem; font-weight: 700; color: var(--dark); }
    .panel-header p { font-size: .83rem; color: var(--muted); margin-top: .1rem; }
    .panel-header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

    /* ── FILTERS ── */
    .filters { padding: 1rem 1.3rem; border-bottom: 1px solid var(--border); display: grid; grid-template-columns: 1fr auto auto; gap: .8rem; background: #fafbfc; }
    .search-wrap { position: relative; }
    .search-icon { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); font-size: .95rem; pointer-events: none; }
    .filters input, .filters select { font-family: inherit; font-size: .9rem; border: 1.5px solid #cbd5e1; border-radius: 9px; padding: .62rem .8rem; background: white; color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; }
    .filters input { padding-left: 2.2rem; }
    .filters input:focus, .filters select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 960px; }
    th { background: #f8fafc; padding: .7rem 1rem; text-align: left; font-size: .75rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; border-bottom: 1px solid var(--border); white-space: nowrap; }
    td { padding: .75rem 1rem; font-size: .9rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: var(--text); }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafffe; }

    /* ── BADGES ── */
    .loan-badge { display: inline-block; font-size: .72rem; font-weight: 700; padding: .2rem .55rem; border-radius: 100px; white-space: nowrap; }
    .badge-personal  { background: var(--primary-light); color: #15803d; }
    .badge-business  { background: var(--gold-light);    color: #92400e; }
    .badge-group     { background: #dbeafe;              color: #1d4ed8; }
    .badge-default   { background: #f1f5f9;              color: var(--muted); }

    .status-badge { display: inline-block; font-size: .72rem; font-weight: 700; padding: .2rem .6rem; border-radius: 100px; white-space: nowrap; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-approved { background: var(--primary-light); color: #15803d; }
    .status-rejected { background: var(--danger-light);  color: var(--danger-dark); }

    /* ── ACTION BUTTONS ── */
    .action-btns { display: flex; gap: .4rem; flex-wrap: wrap; }
    .btn-approve, .btn-reject, .btn-pending {
      font-family: inherit; font-size: .75rem; font-weight: 700;
      padding: .3rem .65rem; border-radius: 7px; border: none; cursor: pointer;
      transition: all .15s; white-space: nowrap;
    }
    .btn-approve { background: var(--primary-light); color: #15803d; }
    .btn-approve:hover { background: #bbf7d0; }
    .btn-reject { background: var(--danger-light); color: var(--danger-dark); }
    .btn-reject:hover { background: #fecaca; }
    .btn-pending { background: #fef3c7; color: #92400e; }
    .btn-pending:hover { background: #fde68a; }

    .amount-cell { font-weight: 700; color: var(--dark); white-space: nowrap; }
    .date-cell { font-size: .82rem; color: var(--muted); white-space: nowrap; }

    /* ── EMPTY ── */
    .empty-state { padding: 3rem 1.5rem; text-align: center; color: var(--muted); }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: .75rem; }
    .empty-state p { font-size: .92rem; }

    /* ── STATUS BAR ── */
    .panel-footer { padding: .75rem 1.3rem; border-top: 1px solid var(--border); background: #fafbfc; }
    .status-msg { font-size: .88rem; font-weight: 600; color: var(--primary); min-height: 1.3rem; }

    /* ── EXPORT BTN ── */
    .btn-export { background: white; color: var(--primary); border: 1.5px solid var(--primary); font-size: .85rem; padding: .55rem .9rem; border-radius: 9px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; transition: all .15s; }
    .btn-export:hover { background: var(--primary-light); }

    @media (max-width: 760px) { .filters { grid-template-columns: 1fr; } .stats-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

  <header>
    <div class="container header-row">
      <div class="brand">
        <div class="brand-icon">🏦</div>
        Risonaf Loans
        <span class="admin-tag">Admin</span>
      </div>
      <div class="header-actions">
        <a class="btn btn-ghost" href="index.php">← Public Site</a>
        <a class="btn btn-ghost" href="settings.php">⚙️ Settings</a>
        <a class="btn btn-ghost" href="api/logout.php">Sign Out</a>
        <button class="btn btn-danger" id="clearAllBtn" type="button">🗑 Clear All</button>
      </div>
    </div>
  </header>

  <main>
    <div class="container">

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Total</div><div class="stat-icon icon-green">📋</div></div>
          <div class="stat-value" id="totalApplications">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Total (GHS)</div><div class="stat-icon icon-gold">💰</div></div>
          <div class="stat-value" id="totalAmount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Personal</div><div class="stat-icon icon-blue">👤</div></div>
          <div class="stat-value" id="personalCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Business</div><div class="stat-icon icon-indigo">🏪</div></div>
          <div class="stat-value" id="businessCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Group</div><div class="stat-icon icon-pink">👥</div></div>
          <div class="stat-value" id="groupCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Pending</div><div class="stat-icon icon-orange">⏳</div></div>
          <div class="stat-value" id="pendingCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Approved</div><div class="stat-icon icon-emerald">✅</div></div>
          <div class="stat-value" id="approvedCount">—</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Rejected</div><div class="stat-icon icon-red">❌</div></div>
          <div class="stat-value" id="rejectedCount">—</div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <div>
            <h2>Loan Applications</h2>
            <p>Search, filter, and update application statuses.</p>
          </div>
          <div class="panel-header-actions">
            <a class="btn-export" href="api/export_csv.php">⬇️ Export CSV</a>
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
          <select id="statusFilter">
            <option value="">All statuses</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
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
                <th>ID Type</th>
                <th>ID Number</th>
                <th>Loan Type</th>
                <th>Amount (GHS)</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Actions</th>
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
    const CSRF_TOKEN = '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>';

    const els = {
      total:    document.getElementById('totalApplications'),
      amount:   document.getElementById('totalAmount'),
      personal: document.getElementById('personalCount'),
      business: document.getElementById('businessCount'),
      group:    document.getElementById('groupCount'),
      pending:  document.getElementById('pendingCount'),
      approved: document.getElementById('approvedCount'),
      rejected: document.getElementById('rejectedCount'),
      search:   document.getElementById('searchInput'),
      typeFilter:   document.getElementById('loanTypeFilter'),
      statusFilter: document.getElementById('statusFilter'),
      tbody:    document.getElementById('applicationsBody'),
      empty:    document.getElementById('emptyState'),
      clearBtn: document.getElementById('clearAllBtn'),
      statusMsg:document.getElementById('statusMsg'),
    };

    let allApplications = [];

    function fmt(v) { return new Intl.NumberFormat('en-GH').format(v); }

    function escapeHTML(str) {
      return String(str ?? '')
        .replaceAll('&', '&amp;').replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    }

    function loanBadge(type) {
      const map = { 'Personal Loan': 'badge-personal', 'Business Loan': 'badge-business', 'Group Loan': 'badge-group' };
      return `<span class="loan-badge ${map[type] || 'badge-default'}">${escapeHTML(type)}</span>`;
    }

    function statusBadge(status) {
      const map = { 'Pending': 'status-pending', 'Approved': 'status-approved', 'Rejected': 'status-rejected' };
      return `<span class="status-badge ${map[status] || 'status-pending'}">${escapeHTML(status || 'Pending')}</span>`;
    }

    function setStatus(msg, isError = false) {
      els.statusMsg.textContent = msg;
      els.statusMsg.style.color = isError ? 'var(--danger)' : 'var(--primary)';
      if (msg) setTimeout(() => { if (els.statusMsg.textContent === msg) els.statusMsg.textContent = ''; }, 3000);
    }

    function renderStats(stats) {
      els.total.textContent    = stats.totalApplications ?? 0;
      els.amount.textContent   = fmt(Number(stats.totalAmount ?? 0));
      els.personal.textContent = stats.personalCount ?? 0;
      els.business.textContent = stats.businessCount ?? 0;
      els.group.textContent    = stats.groupCount    ?? 0;
      els.pending.textContent  = stats.pendingCount  ?? 0;
      els.approved.textContent = stats.approvedCount ?? 0;
      els.rejected.textContent = stats.rejectedCount ?? 0;
    }

    function normalize(v) { return (v || '').toString().toLowerCase(); }

    function filterApplications(items) {
      const query  = normalize(els.search.value.trim());
      const type   = els.typeFilter.value;
      const status = els.statusFilter.value;
      return items.filter(item => {
        const matchType   = !type   || item.loanType === type;
        const matchStatus = !status || (item.status || 'Pending') === status;
        const blob = [item.fullName, item.phone, item.email, item.location, item.purpose].map(normalize).join(' ');
        const matchQuery  = !query  || blob.includes(query);
        return matchType && matchStatus && matchQuery;
      });
    }

    function renderTable(items) {
      if (!items.length) {
        els.tbody.innerHTML = '';
        els.empty.style.display = 'block';
        return;
      }
      els.empty.style.display = 'none';
      els.tbody.innerHTML = items.map(item => `
        <tr id="row-${item.id}">
          <td><strong>${escapeHTML(item.fullName)}</strong></td>
          <td>${escapeHTML(item.phone)}</td>
          <td>${escapeHTML(item.email)}</td>
          <td>${escapeHTML(item.location)}</td>
          <td>${escapeHTML(item.idType)}</td>
          <td>${escapeHTML(item.idNumber)}</td>
          <td>${loanBadge(item.loanType)}</td>
          <td class="amount-cell">${fmt(Number(item.amount || 0))}</td>
          <td>${escapeHTML(item.purpose)}</td>
          <td id="status-${item.id}">${statusBadge(item.status)}</td>
          <td>
            <div class="action-btns">
              <button class="btn-approve" onclick="updateStatus(${item.id},'Approved')">✅ Approve</button>
              <button class="btn-reject"  onclick="updateStatus(${item.id},'Rejected')">❌ Reject</button>
              <button class="btn-pending" onclick="updateStatus(${item.id},'Pending')">⏳ Pending</button>
            </div>
          </td>
          <td class="date-cell">${escapeHTML(item.submittedAt)}</td>
        </tr>
      `).join('');
    }

    async function updateStatus(id, status) {
      const data = new FormData();
      data.append('id', id);
      data.append('status', status);
      data.append('csrf_token', CSRF_TOKEN);
      try {
        const res    = await fetch('api/update_status.php', { method: 'POST', body: data });
        const result = await res.json();
        if (result.success) {
          // Update badge in place without full reload
          const cell = document.getElementById('status-' + id);
          if (cell) cell.innerHTML = statusBadge(status);
          // Update local data
          const app = allApplications.find(a => a.id == id);
          if (app) app.status = status;
          setStatus(result.message);
        } else {
          setStatus(result.message, true);
        }
      } catch {
        setStatus('Network error — could not update status.', true);
      }
    }

    async function loadData() {
      const response = await fetch('api/get_applications.php');
      const data     = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Failed to load');
      allApplications = Array.isArray(data.applications) ? data.applications : [];

      // Compute status counts client-side
      const pending  = allApplications.filter(a => (a.status || 'Pending') === 'Pending').length;
      const approved = allApplications.filter(a => a.status === 'Approved').length;
      const rejected = allApplications.filter(a => a.status === 'Rejected').length;
      const stats = { ...data.stats, pendingCount: pending, approvedCount: approved, rejectedCount: rejected };

      renderStats(stats);
      renderTable(filterApplications(allApplications));
    }

    function renderFiltered() { renderTable(filterApplications(allApplications)); }

    els.search.addEventListener('input', renderFiltered);
    els.typeFilter.addEventListener('change', renderFiltered);
    els.statusFilter.addEventListener('change', renderFiltered);

    els.clearBtn.addEventListener('click', async () => {
      if (!window.confirm('This will permanently remove all application records. Continue?')) return;
      try {
        const clearData = new FormData();
        clearData.append('csrf_token', CSRF_TOKEN);
        const res  = await fetch('api/clear_applications.php', { method: 'POST', body: clearData });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Failed to clear');
        await loadData();
        setStatus('All application data has been cleared.');
      } catch (err) {
        setStatus(err.message || 'Unable to clear data.', true);
      }
    });

    loadData().catch(err => {
      els.tbody.innerHTML = '';
      els.empty.style.display = 'block';
      els.empty.querySelector('p').textContent = 'Unable to load applications.';
      setStatus(err.message || 'Failed to load data.', true);
    });
  </script>
</body>
</html>
