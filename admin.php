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
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    :root {
      --navy:        #0c2340;
      --navy-mid:    #163556;
      --navy-light:  #e6edf7;
      --gold:        #b8862a;
      --gold-bright: #d4a73a;
      --gold-light:  #fdf5e4;
      --text:        #1b2535;
      --muted:       #677080;
      --light:       #f3f5f8;
      --card:        #ffffff;
      --border:      #d9e0eb;
      --success:     #0f6d3d;
      --success-bg:  #e5f3ec;
      --warning-bg:  #fef8e7;
      --warning:     #7c5a0a;
      --danger:      #be2222;
      --danger-dark: #991b1b;
      --danger-bg:   #fde8e8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); line-height: 1.5; min-height: 100vh; }
    .container { width: min(1280px, 96%); margin: 0 auto; }

    /* ── HEADER ── */
    header { background: var(--navy); color: white; padding: .9rem 0; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,.06); }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .brand { display: flex; align-items: center; gap: .65rem; text-decoration: none; color: white; }
    .brand-mark { width: 32px; height: 32px; background: var(--gold); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 800; color: var(--navy); flex-shrink: 0; }
    .brand-name { font-size: .97rem; font-weight: 700; }
    .admin-tag { font-size: .67rem; font-weight: 700; background: rgba(184,134,42,.2); color: var(--gold-bright); border: 1px solid rgba(184,134,42,.25); padding: .16rem .5rem; border-radius: 100px; letter-spacing: .5px; text-transform: uppercase; }
    .header-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; font-family: inherit; font-weight: 600; font-size: .84rem; padding: .5rem .9rem; border-radius: 7px; border: none; cursor: pointer; text-decoration: none; transition: all .15s; }
    .btn-ghost { background: rgba(255,255,255,.08); color: rgba(255,255,255,.85); border: 1px solid rgba(255,255,255,.14); }
    .btn-ghost:hover { background: rgba(255,255,255,.15); color: white; }
    .btn-danger { background: var(--danger); color: white; }
    .btn-danger:hover { background: var(--danger-dark); }

    /* ── MAIN ── */
    main { padding: 1.4rem 0 3rem; }

    /* ── STAT CARDS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(155px, 1fr)); gap: .85rem; margin-bottom: 1.1rem; }
    .stat-card { background: white; border: 1px solid var(--border); border-radius: 10px; padding: 1.1rem 1.2rem; display: flex; flex-direction: column; gap: .45rem; border-left: 3px solid transparent; transition: box-shadow .2s, border-color .2s; }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(12,35,64,.08); }
    .stat-card.c-navy   { border-left-color: var(--navy); }
    .stat-card.c-gold   { border-left-color: var(--gold); }
    .stat-card.c-blue   { border-left-color: #3b82f6; }
    .stat-card.c-indigo { border-left-color: #6366f1; }
    .stat-card.c-teal   { border-left-color: #0d9488; }
    .stat-card.c-amber  { border-left-color: #d97706; }
    .stat-card.c-green  { border-left-color: var(--success); }
    .stat-card.c-red    { border-left-color: var(--danger); }
    .stat-label { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; }
    .stat-value { font-size: 1.55rem; font-weight: 800; color: var(--navy); letter-spacing: -.5px; line-height: 1; }

    /* ── PANEL ── */
    .panel { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(12,35,64,.05); }
    .panel-header { padding: 1rem 1.2rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #fafbfd; }
    .panel-header h2 { font-size: .97rem; font-weight: 700; color: var(--navy); }
    .panel-header p { font-size: .8rem; color: var(--muted); margin-top: .1rem; }
    .panel-header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

    /* ── FILTERS ── */
    .filters { padding: .85rem 1.2rem; border-bottom: 1px solid var(--border); display: grid; grid-template-columns: 1fr auto auto; gap: .75rem; background: #f8f9fb; }
    .search-wrap { position: relative; }
    .search-icon { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
    .filters input, .filters select { font-family: inherit; font-size: .88rem; border: 1.5px solid #cdd4df; border-radius: 7px; padding: .58rem .8rem; background: white; color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; }
    .filters input { padding-left: 2.2rem; }
    .filters input:focus, .filters select:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.08); }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    th { background: #f5f7fa; padding: .65rem 1rem; text-align: left; font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .7px; border-bottom: 1px solid var(--border); white-space: nowrap; }
    td { padding: .72rem 1rem; font-size: .88rem; border-bottom: 1px solid #edf0f5; vertical-align: middle; color: var(--text); }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f7f9fc; }

    /* ── BADGES ── */
    .loan-badge { display: inline-block; font-size: .7rem; font-weight: 700; padding: .18rem .55rem; border-radius: 100px; white-space: nowrap; }
    .badge-personal  { background: var(--navy-light); color: var(--navy); }
    .badge-business  { background: var(--gold-light);  color: #7c5a0a; }
    .badge-group     { background: #dbeafe;             color: #1e40af; }
    .badge-default   { background: #f1f5f9;             color: var(--muted); }

    .status-badge { display: inline-flex; align-items: center; gap: .3rem; font-size: .7rem; font-weight: 700; padding: .2rem .6rem; border-radius: 100px; white-space: nowrap; }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: .7; flex-shrink: 0; }
    .status-pending  { background: var(--warning-bg); color: var(--warning); }
    .status-approved { background: var(--success-bg); color: var(--success); }
    .status-rejected { background: var(--danger-bg);  color: var(--danger); }

    /* ── ACTION BUTTONS ── */
    .action-btns { display: flex; gap: .35rem; flex-wrap: wrap; }
    .btn-approve, .btn-reject, .btn-pending {
      font-family: inherit; font-size: .72rem; font-weight: 700;
      padding: .28rem .6rem; border-radius: 6px; border: 1px solid transparent; cursor: pointer;
      transition: all .15s; white-space: nowrap;
    }
    .btn-approve { background: var(--success-bg); color: var(--success); border-color: #a7d9bc; }
    .btn-approve:hover { background: #c8ebd8; }
    .btn-reject  { background: var(--danger-bg);  color: var(--danger);  border-color: #f5c6c6; }
    .btn-reject:hover  { background: #fad4d4; }
    .btn-pending { background: var(--warning-bg); color: var(--warning); border-color: #f5e1a4; }
    .btn-pending:hover { background: #fdeec2; }

    .amount-cell { font-weight: 700; color: var(--navy); white-space: nowrap; }
    .date-cell { font-size: .8rem; color: var(--muted); white-space: nowrap; }

    /* ── EMPTY ── */
    .empty-state { padding: 3rem 1.5rem; text-align: center; color: var(--muted); }
    .empty-state p { font-size: .9rem; }

    /* ── STATUS BAR ── */
    .panel-footer { padding: .7rem 1.2rem; border-top: 1px solid var(--border); background: #f8f9fb; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
    .status-msg { font-size: .85rem; font-weight: 600; color: var(--success); min-height: 1.2rem; }

    /* ── PAGINATION ── */
    .pagination { display: flex; align-items: center; gap: .3rem; flex-wrap: wrap; }
    .page-btn { font-family: inherit; font-size: .8rem; font-weight: 600; padding: .28rem .6rem; border-radius: 6px; border: 1.5px solid var(--border); background: white; color: var(--text); cursor: pointer; transition: all .15s; }
    .page-btn:hover:not(:disabled) { border-color: var(--navy); color: var(--navy); }
    .page-btn.active { background: var(--navy); color: white; border-color: var(--navy); }
    .page-btn:disabled { opacity: .4; cursor: not-allowed; }
    .page-info { font-size: .8rem; color: var(--muted); }

    /* ── EXPORT BTN ── */
    .btn-export { background: white; color: var(--navy); border: 1.5px solid var(--border); font-size: .83rem; padding: .5rem .85rem; border-radius: 7px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; transition: all .15s; }
    .btn-export:hover { border-color: var(--navy); background: var(--navy-light); }

    /* ── NOTES BTN ── */
    .btn-notes { background: white; color: var(--muted); font-family: inherit; font-size: .72rem; font-weight: 600; padding: .28rem .6rem; border-radius: 6px; border: 1px solid var(--border); cursor: pointer; transition: all .15s; white-space: nowrap; }
    .btn-notes:hover { border-color: var(--navy); color: var(--navy); }
    .btn-notes.has-note { background: var(--gold-light); color: #7c5a0a; border-color: #e8c97a; }

    /* ── CHART PANEL ── */
    .chart-panel { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 10px rgba(12,35,64,.05); margin-bottom: 1rem; }
    .chart-header { font-size: .9rem; font-weight: 700; color: var(--navy); margin-bottom: 1rem; }
    .chart-wrap { position: relative; height: 200px; }

    /* ── REPAYMENT BTN ── */
    .btn-repay { background: var(--navy-light); color: var(--navy); font-family: inherit; font-size: .72rem; font-weight: 600; padding: .28rem .6rem; border-radius: 6px; border: 1px solid #b8cbe6; cursor: pointer; transition: all .15s; white-space: nowrap; }
    .btn-repay:hover { background: #d1dff0; }

    /* ── MODAL ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(12,35,64,.5); z-index: 200; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.open { display: flex; }
    .modal { background: white; border-radius: 14px; width: min(520px, 100%); max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 60px rgba(12,35,64,.25); }
    .modal-header { padding: 1.1rem 1.3rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #fafbfd; border-radius: 14px 14px 0 0; }
    .modal-header h3 { font-size: .97rem; font-weight: 700; color: var(--navy); }
    .modal-close { background: none; border: none; font-size: 1.1rem; cursor: pointer; color: var(--muted); padding: .2rem .4rem; border-radius: 5px; transition: background .15s; }
    .modal-close:hover { background: #f1f5f9; }
    .modal-body { padding: 1.2rem 1.3rem; display: flex; flex-direction: column; gap: 1rem; }
    .modal-summary { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .8rem; background: var(--light); border-radius: 9px; padding: 1rem; border: 1px solid var(--border); }
    .modal-summary .kv-label { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
    .modal-summary .kv-value { font-size: .92rem; font-weight: 700; color: var(--navy); }
    .rep-list { display: flex; flex-direction: column; gap: .45rem; max-height: 220px; overflow-y: auto; }
    .rep-item { background: var(--light); border: 1px solid var(--border); border-radius: 8px; padding: .65rem .9rem; display: flex; justify-content: space-between; align-items: flex-start; gap: .5rem; }
    .rep-item .rep-amount { font-weight: 700; color: var(--success); white-space: nowrap; }
    .rep-item .rep-note { font-size: .8rem; color: var(--muted); }
    .rep-item .rep-date { font-size: .75rem; color: var(--muted); white-space: nowrap; }
    .rep-form { display: grid; gap: .75rem; }
    .rep-form label { font-size: .83rem; font-weight: 600; display: block; margin-bottom: .28rem; color: var(--text); }
    .rep-form input, .rep-form textarea { width: 100%; padding: .62rem .8rem; border: 1.5px solid #cdd4df; border-radius: 8px; font: inherit; font-size: .88rem; outline: none; transition: border-color .15s, box-shadow .15s; }
    .rep-form input:focus, .rep-form textarea:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.09); }
    .rep-form textarea { min-height: 70px; resize: vertical; }
    .btn-rep-submit { background: var(--navy); color: white; border: none; font: inherit; font-weight: 700; font-size: .88rem; padding: .6rem 1.15rem; border-radius: 8px; cursor: pointer; transition: background .15s; }
    .btn-rep-submit:hover { background: var(--navy-mid); }
    .rep-status { font-size: .83rem; font-weight: 600; min-height: 1.1rem; }
    .rep-status.ok  { color: var(--success); }
    .rep-status.err { color: var(--danger); }

    /* ── CONFIRM MODAL ── */
    .confirm-input { width: 100%; padding: .62rem .8rem; border: 1.5px solid #cdd4df; border-radius: 8px; font: inherit; font-size: .93rem; outline: none; transition: border-color .15s; margin-top: .5rem; }
    .confirm-input:focus { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(190,34,34,.1); }
    .confirm-warning { font-size: .88rem; color: var(--muted); line-height: 1.55; }
    .confirm-warning strong { color: var(--danger); }
    .btn-confirm-delete { background: var(--danger); color: white; border: none; font: inherit; font-weight: 700; font-size: .88rem; padding: .6rem 1.15rem; border-radius: 8px; cursor: pointer; transition: background .15s; }
    .btn-confirm-delete:hover { background: var(--danger-dark); }
    .btn-confirm-delete:disabled { opacity: .4; cursor: not-allowed; }
    .modal-footer { display: flex; gap: .6rem; justify-content: flex-end; padding-top: .4rem; }
    .btn-cancel { background: #f1f5f9; color: var(--text); border: none; font: inherit; font-weight: 600; font-size: .88rem; padding: .6rem 1.15rem; border-radius: 8px; cursor: pointer; }
    .btn-cancel:hover { background: #e2e8f0; }

    /* ── FILTERS ROW 2 ── */
    .filters-row2 { padding: .6rem 1.2rem; border-bottom: 1px solid var(--border); background: #f8f9fb; display: flex; gap: .75rem; flex-wrap: wrap; align-items: center; }
    .filter-group { display: flex; align-items: center; gap: .4rem; }
    .filter-group .fg-label { font-size: .78rem; font-weight: 600; color: var(--muted); white-space: nowrap; }
    .filter-group input[type="number"], .filter-group input[type="date"] { font-family: inherit; font-size: .83rem; border: 1.5px solid #cdd4df; border-radius: 7px; padding: .46rem .65rem; background: white; color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; }
    .filter-group input[type="number"] { width: 110px; }
    .filter-group input[type="date"]   { width: 148px; }
    .filter-group input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(12,35,64,.08); }
    .filter-sep { font-size: .78rem; color: var(--muted); }
    .btn-clear-filters { font-family: inherit; font-size: .78rem; font-weight: 600; padding: .44rem .9rem; border-radius: 7px; border: 1.5px solid #cdd4df; background: white; color: var(--muted); cursor: pointer; transition: all .15s; margin-left: auto; }
    .btn-clear-filters:hover { border-color: var(--navy); color: var(--navy); }

    /* ── PRINT BTN ── */
    .btn-print-app { background: white; color: var(--muted); font-family: inherit; font-size: .72rem; font-weight: 600; padding: .28rem .6rem; border-radius: 6px; border: 1px solid var(--border); cursor: pointer; transition: all .15s; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; gap: .25rem; }
    .btn-print-app:hover { border-color: var(--navy); color: var(--navy); }

    @media (max-width: 760px) { .filters { grid-template-columns: 1fr; } .stats-grid { grid-template-columns: repeat(2,1fr); } .filters-row2 { flex-direction: column; align-items: flex-start; } .btn-clear-filters { margin-left: 0; } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

  <header>
    <div class="container header-row">
      <div style="display:flex;align-items:center;gap:.65rem;">
        <a class="brand" href="index.php">
          <div class="brand-mark">RL</div>
          <span class="brand-name">Risonaf Loans</span>
        </a>
        <span class="admin-tag">Admin</span>
      </div>
      <div class="header-actions">
        <a class="btn btn-ghost" href="index.php">Public Site</a>
        <a class="btn btn-ghost" href="settings.php">Settings</a>
        <a class="btn btn-ghost" href="api/logout.php">Sign Out</a>
        <button class="btn btn-danger" id="clearAllBtn" type="button">Clear All</button>
      </div>
    </div>
  </header>

  <main>
    <div class="container">

      <div class="stats-grid">
        <div class="stat-card c-navy">
          <div class="stat-label">Total Applications</div>
          <div class="stat-value" id="totalApplications">—</div>
        </div>
        <div class="stat-card c-gold">
          <div class="stat-label">Total Value (GHS)</div>
          <div class="stat-value" id="totalAmount">—</div>
        </div>
        <div class="stat-card c-blue">
          <div class="stat-label">Personal</div>
          <div class="stat-value" id="personalCount">—</div>
        </div>
        <div class="stat-card c-indigo">
          <div class="stat-label">Business</div>
          <div class="stat-value" id="businessCount">—</div>
        </div>
        <div class="stat-card c-teal">
          <div class="stat-label">Group</div>
          <div class="stat-value" id="groupCount">—</div>
        </div>
        <div class="stat-card c-amber">
          <div class="stat-label">Pending</div>
          <div class="stat-value" id="pendingCount">—</div>
        </div>
        <div class="stat-card c-green">
          <div class="stat-label">Approved</div>
          <div class="stat-value" id="approvedCount">—</div>
        </div>
        <div class="stat-card c-red">
          <div class="stat-label">Rejected</div>
          <div class="stat-value" id="rejectedCount">—</div>
        </div>
      </div>

      <div class="chart-panel">
        <div class="chart-header">📊 Applications — Last 6 Months</div>
        <div class="chart-wrap"><canvas id="appChart"></canvas></div>
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
            <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
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

        <div class="filters-row2">
          <div class="filter-group">
            <span class="fg-label">Amount (GHS):</span>
            <input id="amountMin" type="number" min="0" placeholder="Min" title="Minimum amount" />
            <span class="filter-sep">–</span>
            <input id="amountMax" type="number" min="0" placeholder="Max" title="Maximum amount" />
          </div>
          <div class="filter-group">
            <span class="fg-label">Submitted:</span>
            <input id="dateFrom" type="date" title="From date" />
            <span class="filter-sep">–</span>
            <input id="dateTo" type="date" title="To date" />
          </div>
          <button class="btn-clear-filters" id="clearFiltersBtn" type="button">Clear All Filters</button>
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
                <th>Notes</th>
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
          <div class="pagination" id="pagination"></div>
        </div>
      </div>

    </div>
  </main>

  <!-- ── REPAYMENT MODAL ────────────────────────────────────────────────── -->
  <div class="modal-overlay" id="repayModal" role="dialog" aria-modal="true" aria-labelledby="repayModalTitle">
    <div class="modal">
      <div class="modal-header">
        <h3 id="repayModalTitle">Repayments</h3>
        <button class="modal-close" id="repayClose" aria-label="Close">✕</button>
      </div>
      <div class="modal-body">
        <div class="modal-summary" id="repSummary"></div>
        <div>
          <div style="font-size:.85rem;font-weight:700;margin-bottom:.5rem;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;">Payment History</div>
          <div class="rep-list" id="repList"><p style="color:var(--muted);font-size:.88rem">Loading…</p></div>
        </div>
        <div>
          <div style="font-size:.85rem;font-weight:700;margin-bottom:.6rem;color:var(--dark);">Record New Payment</div>
          <div class="rep-form">
            <input type="hidden" id="repLoanId" />
            <div>
              <label for="repAmount">Amount (GHS)</label>
              <input id="repAmount" type="number" min="1" placeholder="e.g. 500" />
            </div>
            <div>
              <label for="repNote">Note (optional)</label>
              <textarea id="repNote" placeholder="e.g. Cash payment via agent"></textarea>
            </div>
            <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;">
              <button class="btn-rep-submit" id="repSubmitBtn">Save Payment</button>
              <div class="rep-status" id="repStatus"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── NOTES MODAL ──────────────────────────────────────────────────── -->
  <div class="modal-overlay" id="notesModal" role="dialog" aria-modal="true" aria-labelledby="notesModalTitle">
    <div class="modal">
      <div class="modal-header">
        <h3 id="notesModalTitle">Internal Notes</h3>
        <button class="modal-close" id="notesClose" aria-label="Close">✕</button>
      </div>
      <div class="modal-body">
        <p style="font-size:.85rem;color:var(--muted);margin-bottom:.5rem">Notes are for internal use only and are not visible to the applicant.</p>
        <input type="hidden" id="notesLoanId" />
        <div class="rep-form">
          <div>
            <label for="notesText">Notes</label>
            <textarea id="notesText" rows="5" placeholder="Add internal notes about this application…"></textarea>
          </div>
          <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;">
            <button class="btn-rep-submit" id="notesSaveBtn">Save Notes</button>
            <div class="rep-status" id="notesStatus"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── CLEAR-ALL CONFIRM MODAL ────────────────────────────────────────── -->
  <div class="modal-overlay" id="clearModal" role="dialog" aria-modal="true" aria-labelledby="clearModalTitle">
    <div class="modal">
      <div class="modal-header">
        <h3 id="clearModalTitle">Confirm Clear All Data</h3>
        <button class="modal-close" id="clearClose" aria-label="Close">✕</button>
      </div>
      <div class="modal-body">
        <p class="confirm-warning">
          This will <strong>permanently delete every application record</strong> from the database.
          This action cannot be undone.
        </p>
        <div>
          <label for="confirmInput" style="font-size:.88rem;font-weight:600;">
            Type <strong>DELETE</strong> to confirm:
          </label>
          <input class="confirm-input" id="confirmInput" type="text" placeholder="DELETE" autocomplete="off" />
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" id="clearCancelBtn">Cancel</button>
          <button class="btn-confirm-delete" id="clearConfirmBtn" disabled>Delete Everything</button>
        </div>
        <div class="rep-status" id="clearStatus"></div>
      </div>
    </div>
  </div>

  <script>
    const CSRF_TOKEN = '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>';
    const PAGE_SIZE  = 25;

    const els = {
      total:    document.getElementById('totalApplications'),
      amount:   document.getElementById('totalAmount'),
      personal: document.getElementById('personalCount'),
      business: document.getElementById('businessCount'),
      group:    document.getElementById('groupCount'),
      pending:  document.getElementById('pendingCount'),
      approved: document.getElementById('approvedCount'),
      rejected: document.getElementById('rejectedCount'),
      search:       document.getElementById('searchInput'),
      typeFilter:   document.getElementById('loanTypeFilter'),
      statusFilter: document.getElementById('statusFilter'),
      tbody:      document.getElementById('applicationsBody'),
      empty:      document.getElementById('emptyState'),
      clearBtn:   document.getElementById('clearAllBtn'),
      statusMsg:  document.getElementById('statusMsg'),
      pagination: document.getElementById('pagination'),
    };

    let allApplications = [];
    let currentPage     = 1;
    let filteredCache   = [];
    let appChart        = null;

    // ── Helpers ───────────────────────────────────────────────────────────────
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
      els.statusMsg.style.color = isError ? 'var(--danger)' : 'var(--success)';
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
      const query     = normalize(els.search.value.trim());
      const type      = els.typeFilter.value;
      const status    = els.statusFilter.value;
      const amountMin = parseFloat(document.getElementById('amountMin').value) || 0;
      const amountMax = parseFloat(document.getElementById('amountMax').value) || Infinity;
      const dateFrom  = document.getElementById('dateFrom').value;
      const dateTo    = document.getElementById('dateTo').value;

      return items.filter(item => {
        const matchType   = !type   || item.loanType === type;
        const matchStatus = !status || (item.status || 'Pending') === status;
        const blob = [item.fullName, item.phone, item.email, item.location, item.purpose].map(normalize).join(' ');
        const matchQuery  = !query  || blob.includes(query);
        const amount      = Number(item.amount || 0);
        const matchAmount = amount >= amountMin && amount <= amountMax;
        const itemDate    = (item.submittedAt || '').substring(0, 10);
        const matchFrom   = !dateFrom || itemDate >= dateFrom;
        const matchTo     = !dateTo   || itemDate <= dateTo;
        return matchType && matchStatus && matchQuery && matchAmount && matchFrom && matchTo;
      });
    }

    // ── Pagination ────────────────────────────────────────────────────────────
    function renderPagination(total) {
      const pages = Math.max(1, Math.ceil(total / PAGE_SIZE));
      if (pages <= 1) { els.pagination.innerHTML = ''; return; }

      let html = `<button class="page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
      for (let p = 1; p <= pages; p++) {
        if (pages > 7 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== pages) {
          if (p === currentPage - 3 || p === currentPage + 3) html += `<span class="page-info">…</span>`;
          continue;
        }
        html += `<button class="page-btn ${p===currentPage?'active':''}" onclick="goPage(${p})">${p}</button>`;
      }
      html += `<button class="page-btn" onclick="goPage(${currentPage+1})" ${currentPage===pages?'disabled':''}>›</button>`;
      html += `<span class="page-info">${total} records</span>`;
      els.pagination.innerHTML = html;
    }

    function goPage(p) {
      const pages = Math.ceil(filteredCache.length / PAGE_SIZE);
      currentPage = Math.max(1, Math.min(p, pages));
      renderTablePage();
    }

    function renderTablePage() {
      const items = filteredCache;
      if (!items.length) {
        els.tbody.innerHTML = '';
        els.empty.style.display = 'block';
        els.pagination.innerHTML = '';
        return;
      }
      els.empty.style.display = 'none';
      const start = (currentPage - 1) * PAGE_SIZE;
      const page  = items.slice(start, start + PAGE_SIZE);

      els.tbody.innerHTML = page.map(item => `
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
              <button class="btn-repay"   onclick="openRepayModal(${item.id})">💳 Repayments</button>
              <a class="btn-print-app" href="print_application.php?id=${item.id}" target="_blank">🖨️ Print</a>
            </div>
          </td>
          <td>
            <button class="btn-notes${item.notes ? ' has-note' : ''}"
                    onclick="openNotesModal(${item.id})"
                    title="${item.notes ? escapeHTML(item.notes.substring(0,80)) : 'Add note'}">
              ${item.notes ? '📝' : '➕'} Notes
            </button>
          </td>
          <td class="date-cell">${escapeHTML(item.submittedAt)}</td>
        </tr>
      `).join('');

      renderPagination(items.length);
    }

    function renderFiltered() {
      currentPage   = 1;
      filteredCache = filterApplications(allApplications);
      renderTablePage();
    }

    // ── Status update ─────────────────────────────────────────────────────────
    async function updateStatus(id, status) {
      const data = new FormData();
      data.append('id', id);
      data.append('status', status);
      data.append('csrf_token', CSRF_TOKEN);
      try {
        const res    = await fetch('api/update_status.php', { method: 'POST', body: data });
        const result = await res.json();
        if (result.success) {
          const cell = document.getElementById('status-' + id);
          if (cell) cell.innerHTML = statusBadge(status);
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

    // ── Chart ─────────────────────────────────────────────────────────────────
    function renderChart(chartData) {
      const labels   = chartData.map(r => r.month);
      const totals   = chartData.map(r => Number(r.total));
      const approved = chartData.map(r => Number(r.approved));
      const rejected = chartData.map(r => Number(r.rejected));

      if (appChart) appChart.destroy();
      const ctx = document.getElementById('appChart').getContext('2d');
      appChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            { label: 'Total',    data: totals,   backgroundColor: 'rgba(12,35,64,.15)',  borderColor: '#0c2340', borderWidth: 1.5, borderRadius: 4 },
            { label: 'Approved', data: approved, backgroundColor: 'rgba(15,109,61,.2)',  borderColor: '#0f6d3d', borderWidth: 1.5, borderRadius: 4 },
            { label: 'Rejected', data: rejected, backgroundColor: 'rgba(190,34,34,.18)', borderColor: '#be2222', borderWidth: 1.5, borderRadius: 4 },
          ],
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { labels: { font: { family: 'Inter', size: 12 }, boxWidth: 12 } } },
          scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } },
            y: { beginAtZero: true, ticks: { precision: 0, font: { family: 'Inter', size: 11 } } },
          },
        },
      });
    }

    // ── Load data ─────────────────────────────────────────────────────────────
    async function loadData() {
      const response = await fetch('api/get_applications.php');
      const data     = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Failed to load');
      allApplications = Array.isArray(data.applications) ? data.applications : [];

      const pending  = allApplications.filter(a => (a.status || 'Pending') === 'Pending').length;
      const approved = allApplications.filter(a => a.status === 'Approved').length;
      const rejected = allApplications.filter(a => a.status === 'Rejected').length;
      const stats = { ...data.stats, pendingCount: pending, approvedCount: approved, rejectedCount: rejected };

      renderStats(stats);
      renderFiltered();
      if (Array.isArray(data.chart) && data.chart.length) renderChart(data.chart);
    }

    els.search.addEventListener('input', renderFiltered);
    els.typeFilter.addEventListener('change', renderFiltered);
    els.statusFilter.addEventListener('change', renderFiltered);

    ['amountMin', 'amountMax', 'dateFrom', 'dateTo'].forEach(id => {
      document.getElementById(id).addEventListener('input', renderFiltered);
    });

    document.getElementById('clearFiltersBtn').addEventListener('click', () => {
      els.search.value         = '';
      els.typeFilter.value     = '';
      els.statusFilter.value   = '';
      document.getElementById('amountMin').value = '';
      document.getElementById('amountMax').value = '';
      document.getElementById('dateFrom').value  = '';
      document.getElementById('dateTo').value    = '';
      renderFiltered();
    });

    // ── Clear-all modal ───────────────────────────────────────────────────────
    const clearModal      = document.getElementById('clearModal');
    const confirmInput    = document.getElementById('confirmInput');
    const clearConfirmBtn = document.getElementById('clearConfirmBtn');
    const clearStatus     = document.getElementById('clearStatus');

    els.clearBtn.addEventListener('click', () => {
      confirmInput.value = '';
      clearConfirmBtn.disabled = true;
      clearStatus.textContent = '';
      clearStatus.className = 'rep-status';
      clearModal.classList.add('open');
      setTimeout(() => confirmInput.focus(), 50);
    });

    confirmInput.addEventListener('input', () => {
      clearConfirmBtn.disabled = confirmInput.value !== 'DELETE';
    });

    document.getElementById('clearClose').addEventListener('click',    () => clearModal.classList.remove('open'));
    document.getElementById('clearCancelBtn').addEventListener('click', () => clearModal.classList.remove('open'));
    clearModal.addEventListener('click', e => { if (e.target === clearModal) clearModal.classList.remove('open'); });

    clearConfirmBtn.addEventListener('click', async () => {
      if (confirmInput.value !== 'DELETE') return;
      clearConfirmBtn.disabled = true;
      try {
        const fd = new FormData();
        fd.append('csrf_token', CSRF_TOKEN);
        const res  = await fetch('api/clear_applications.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Failed to clear');
        clearModal.classList.remove('open');
        await loadData();
        setStatus('All application data has been cleared.');
      } catch (err) {
        clearStatus.textContent = err.message || 'Unable to clear data.';
        clearStatus.className = 'rep-status err';
        clearConfirmBtn.disabled = false;
      }
    });

    // ── Repayment modal ───────────────────────────────────────────────────────
    const repayModal   = document.getElementById('repayModal');
    const repList      = document.getElementById('repList');
    const repSummary   = document.getElementById('repSummary');
    const repLoanId    = document.getElementById('repLoanId');
    const repAmount    = document.getElementById('repAmount');
    const repNote      = document.getElementById('repNote');
    const repSubmitBtn = document.getElementById('repSubmitBtn');
    const repStatus    = document.getElementById('repStatus');

    document.getElementById('repayClose').addEventListener('click', () => repayModal.classList.remove('open'));
    repayModal.addEventListener('click', e => { if (e.target === repayModal) repayModal.classList.remove('open'); });

    async function openRepayModal(loanId) {
      repLoanId.value = loanId;
      repAmount.value = '';
      repNote.value   = '';
      repStatus.textContent = '';
      repStatus.className = 'rep-status';
      repList.innerHTML = '<p style="color:var(--muted);font-size:.88rem">Loading…</p>';
      repSummary.innerHTML = '';
      repayModal.classList.add('open');
      await refreshRepayments(loanId);
    }

    async function refreshRepayments(loanId) {
      try {
        const res  = await fetch(`api/get_repayments.php?loan_id=${loanId}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        const loan = data.loan;
        repSummary.innerHTML = `
          <div><div class="kv-label">Applicant</div><div class="kv-value">${escapeHTML(loan.full_name)}</div></div>
          <div><div class="kv-label">Loan Type</div><div class="kv-value">${escapeHTML(loan.loan_type)}</div></div>
          <div><div class="kv-label">Loan Amount</div><div class="kv-value">GHS ${fmt(loan.amount)}</div></div>
          <div><div class="kv-label">Total Paid</div><div class="kv-value" style="color:var(--primary)">GHS ${fmt(data.totalPaid)}</div></div>
          <div><div class="kv-label">Outstanding</div><div class="kv-value" style="color:${data.outstanding>0?'var(--danger)':'var(--primary)'}">GHS ${fmt(Math.max(0,data.outstanding))}</div></div>
        `;

        if (!data.repayments.length) {
          repList.innerHTML = '<p style="color:var(--muted);font-size:.88rem">No payments recorded yet.</p>';
          return;
        }
        repList.innerHTML = data.repayments.map(r => `
          <div class="rep-item">
            <div>
              <div class="rep-amount">GHS ${fmt(r.amount)}</div>
              ${r.note ? `<div class="rep-note">${escapeHTML(r.note)}</div>` : ''}
            </div>
            <div class="rep-date">${escapeHTML(r.recordedAt)}</div>
          </div>
        `).join('');
      } catch (err) {
        repList.innerHTML = `<p style="color:var(--danger);font-size:.88rem">${escapeHTML(err.message)}</p>`;
      }
    }

    repSubmitBtn.addEventListener('click', async () => {
      const loanId = repLoanId.value;
      const amount = repAmount.value.trim();
      if (!amount || Number(amount) <= 0) {
        repStatus.textContent = 'Enter a valid amount.';
        repStatus.className = 'rep-status err';
        return;
      }
      repSubmitBtn.disabled = true;
      repStatus.textContent = '';
      try {
        const fd = new FormData();
        fd.append('loan_id',    loanId);
        fd.append('amount',     amount);
        fd.append('note',       repNote.value.trim());
        fd.append('csrf_token', CSRF_TOKEN);
        const res  = await fetch('api/add_repayment.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.success) throw new Error(data.message);
        repAmount.value = '';
        repNote.value   = '';
        repStatus.textContent = 'Payment recorded.';
        repStatus.className = 'rep-status ok';
        await refreshRepayments(loanId);
      } catch (err) {
        repStatus.textContent = err.message || 'Error saving payment.';
        repStatus.className = 'rep-status err';
      } finally {
        repSubmitBtn.disabled = false;
      }
    });

    // ── Notes modal ──────────────────────────────────────────────────────────
    const notesModal   = document.getElementById('notesModal');
    const notesLoanId  = document.getElementById('notesLoanId');
    const notesText    = document.getElementById('notesText');
    const notesSaveBtn = document.getElementById('notesSaveBtn');
    const notesStatus  = document.getElementById('notesStatus');

    document.getElementById('notesClose').addEventListener('click', () => notesModal.classList.remove('open'));
    notesModal.addEventListener('click', e => { if (e.target === notesModal) notesModal.classList.remove('open'); });

    function openNotesModal(loanId) {
      notesLoanId.value = loanId;
      notesStatus.textContent = '';
      notesStatus.className = 'rep-status';
      const app = allApplications.find(a => a.id == loanId);
      notesText.value = app ? (app.notes || '') : '';
      notesModal.classList.add('open');
      setTimeout(() => notesText.focus(), 50);
    }

    notesSaveBtn.addEventListener('click', async () => {
      const loanId = notesLoanId.value;
      notesSaveBtn.disabled = true;
      notesStatus.textContent = '';
      try {
        const fd = new FormData();
        fd.append('id',         loanId);
        fd.append('notes',      notesText.value.trim());
        fd.append('csrf_token', CSRF_TOKEN);
        const res  = await fetch('api/save_notes.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        // Update local copy
        const app = allApplications.find(a => a.id == loanId);
        if (app) app.notes = notesText.value.trim();

        notesStatus.textContent = 'Saved.';
        notesStatus.className = 'rep-status ok';
        // Refresh the notes button style in the table
        renderTablePage();
        setTimeout(() => notesModal.classList.remove('open'), 700);
      } catch (err) {
        notesStatus.textContent = err.message || 'Error saving notes.';
        notesStatus.className = 'rep-status err';
      } finally {
        notesSaveBtn.disabled = false;
      }
    });

    // ── Init ──────────────────────────────────────────────────────────────────
    loadData().catch(err => {
      els.tbody.innerHTML = '';
      els.empty.style.display = 'block';
      els.empty.querySelector('p').textContent = 'Unable to load applications.';
      setStatus(err.message || 'Failed to load data.', true);
    });
  </script>
</body>
</html>
