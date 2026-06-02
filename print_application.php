<?php
declare(strict_types=1);

session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo '<p>Invalid application ID.</p>';
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare(
        'SELECT id, full_name, phone, email, location, id_type, id_number,
                loan_type, amount, purpose, status,
                DATE_FORMAT(submitted_at, "%d %M %Y %H:%i") AS submitted_at
         FROM loan_applications WHERE id = ?'
    );
    $stmt->execute([$id]);
    $app = $stmt->fetch();

    if (!$app) {
        http_response_code(404);
        echo '<p>Application not found.</p>';
        exit;
    }

    $repayments  = [];
    $totalPaid   = 0.0;
    $outstanding = (float)$app['amount'];

    try {
        $repStmt = $pdo->prepare(
            'SELECT amount, note, DATE_FORMAT(recorded_at, "%d %M %Y %H:%i") AS recorded_at
             FROM repayments WHERE loan_id = ? ORDER BY recorded_at ASC'
        );
        $repStmt->execute([$id]);
        $repayments  = $repStmt->fetchAll();
        $totalPaid   = (float)array_sum(array_column($repayments, 'amount'));
        $outstanding = max(0.0, (float)$app['amount'] - $totalPaid);
    } catch (Throwable $e) {
        // repayments table may not exist on older installs
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo '<p>Server error.</p>';
    exit;
}

function h(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function fmtGHS(float $v): string { return 'GHS ' . number_format($v, 2); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Application #<?= $id ?> | Risonaf Loans Ghana</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; font-size: 12pt; color: #1b2535; background: white; }
    .page { max-width: 780px; margin: 0 auto; padding: 2cm; }

    .logo-bar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2cm; padding-bottom: .7cm; border-bottom: 2.5px solid #0c2340; }
    .logo-name { font-size: 17pt; font-weight: bold; color: #0c2340; }
    .logo-sub  { font-size: 9pt; color: #677080; margin-top: 3px; }
    .ref-block { text-align: right; }
    .ref-no  { font-size: 14pt; font-weight: bold; color: #0c2340; }
    .ref-lbl { font-size: 9pt; color: #677080; margin-top: 2px; }

    h2 { font-size: 10pt; font-weight: bold; color: #0c2340; text-transform: uppercase; letter-spacing: 0.6px; margin: 0 0 .4cm; padding-bottom: .2cm; border-bottom: 1px solid #d9e0eb; }
    .section { margin-bottom: .75cm; }

    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: .2cm .8cm; }
    .kv .lbl { font-size: 8pt; text-transform: uppercase; letter-spacing: 0.4px; color: #677080; margin-bottom: 1px; }
    .kv .val { font-size: 11pt; font-weight: bold; color: #1b2535; }

    .status-chip { display: inline-block; font-size: 9pt; font-weight: bold; padding: 2px 10px; border-radius: 20px; }
    .Pending  { background: #fef8e7; color: #7c5a0a; border: 1px solid #f5d98b; }
    .Approved { background: #e5f3ec; color: #0f6d3d; border: 1px solid #a7d9bc; }
    .Rejected { background: #fde8e8; color: #be2222; border: 1px solid #f5c6c6; }

    .purpose-box { background: #f3f5f8; border: 1px solid #d9e0eb; border-radius: 5px; padding: .35cm .45cm; font-size: 11pt; line-height: 1.55; }

    .rep-summary { display: flex; gap: 2cm; margin-bottom: .5cm; }
    .rep-kv .lbl { font-size: 8pt; text-transform: uppercase; color: #677080; }
    .rep-kv .val { font-size: 12pt; font-weight: bold; color: #1b2535; }
    .rep-kv .val.green { color: #0f6d3d; }
    .rep-kv .val.red   { color: #be2222; }

    table { width: 100%; border-collapse: collapse; font-size: 10pt; }
    th { background: #f5f7fa; padding: .18cm .4cm; text-align: left; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.4px; color: #677080; border-bottom: 1px solid #d9e0eb; }
    td { padding: .18cm .4cm; border-bottom: 1px solid #edf0f5; }
    tr:last-child td { border-bottom: none; }

    .footer { margin-top: 1.2cm; padding-top: .45cm; border-top: 1px solid #d9e0eb; display: flex; justify-content: space-between; font-size: 8pt; color: #677080; }

    @media screen {
      body { background: #f3f5f8; }
      .page { background: white; box-shadow: 0 4px 24px rgba(0,0,0,.12); margin: 1.5rem auto; }
      .print-bar { background: #0c2340; color: white; padding: .75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; font-family: Arial, sans-serif; }
      .print-bar span { font-size: .88rem; color: rgba(255,255,255,.7); }
      .btn-print { background: #b8862a; color: #0c2340; font-family: inherit; font-weight: 700; font-size: .85rem; padding: .48rem 1.1rem; border: none; border-radius: 6px; cursor: pointer; }
      .btn-print:hover { background: #d4a73a; }
      .btn-back { color: rgba(255,255,255,.65); text-decoration: none; font-size: .84rem; font-family: Arial, sans-serif; }
      .btn-back:hover { color: white; }
    }
    @media print {
      .print-bar { display: none !important; }
      body { background: white; }
      .page { padding: 1.5cm; box-shadow: none; max-width: 100%; }
    }
  </style>
</head>
<body>

  <div class="print-bar">
    <a class="btn-back" href="admin.php">&#8592; Back to Dashboard</a>
    <span>Application #<?= $id ?> &mdash; Risonaf Loans Ghana</span>
    <button class="btn-print" onclick="window.print()">Print / Save PDF</button>
  </div>

  <div class="page">

    <div class="logo-bar">
      <div>
        <div class="logo-name">Risonaf Loans Ghana</div>
        <div class="logo-sub">Responsible Microfinance Solutions</div>
      </div>
      <div class="ref-block">
        <div class="ref-lbl">Application Reference</div>
        <div class="ref-no">#<?= $id ?></div>
        <div class="ref-lbl">Submitted: <?= h($app['submitted_at']) ?></div>
      </div>
    </div>

    <div class="section">
      <h2>Applicant Information</h2>
      <div class="grid">
        <div class="kv"><div class="lbl">Full Name</div><div class="val"><?= h($app['full_name']) ?></div></div>
        <div class="kv"><div class="lbl">Phone</div><div class="val"><?= h($app['phone']) ?></div></div>
        <div class="kv"><div class="lbl">Email</div><div class="val"><?= h($app['email']) ?></div></div>
        <div class="kv"><div class="lbl">Location</div><div class="val"><?= h($app['location']) ?></div></div>
        <div class="kv"><div class="lbl">ID Type</div><div class="val"><?= h($app['id_type']) ?></div></div>
        <div class="kv"><div class="lbl">ID Number</div><div class="val"><?= h($app['id_number']) ?></div></div>
      </div>
    </div>

    <div class="section">
      <h2>Loan Details</h2>
      <div class="grid">
        <div class="kv"><div class="lbl">Loan Type</div><div class="val"><?= h($app['loan_type']) ?></div></div>
        <div class="kv"><div class="lbl">Amount Requested</div><div class="val"><?= h(fmtGHS((float)$app['amount'])) ?></div></div>
        <div class="kv" style="margin-top:.2cm">
          <div class="lbl">Status</div>
          <div class="val"><span class="status-chip <?= h($app['status'] ?: 'Pending') ?>"><?= h($app['status'] ?: 'Pending') ?></span></div>
        </div>
      </div>
    </div>

    <div class="section">
      <h2>Purpose of Loan</h2>
      <div class="purpose-box"><?= h($app['purpose']) ?></div>
    </div>

    <div class="section">
      <h2>Repayment Summary</h2>
      <div class="rep-summary">
        <div class="rep-kv"><div class="lbl">Loan Amount</div><div class="val"><?= h(fmtGHS((float)$app['amount'])) ?></div></div>
        <div class="rep-kv"><div class="lbl">Total Paid</div><div class="val green"><?= h(fmtGHS($totalPaid)) ?></div></div>
        <div class="rep-kv"><div class="lbl">Outstanding</div><div class="val <?= $outstanding > 0 ? 'red' : 'green' ?>"><?= h(fmtGHS($outstanding)) ?></div></div>
      </div>

      <?php if ($repayments): ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Amount (GHS)</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($repayments as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($r['recorded_at']) ?></td>
            <td><?= h(fmtGHS((float)$r['amount'])) ?></td>
            <td><?= h((string)($r['note'] ?? '')) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
      <p style="font-size:10pt;color:#677080">No repayments recorded yet.</p>
      <?php endif; ?>
    </div>

    <div class="footer">
      <span>Risonaf Loans Ghana &copy; <?= date('Y') ?></span>
      <span>Printed: <?= date('d M Y H:i') ?></span>
      <span>CONFIDENTIAL</span>
    </div>

  </div>

  <script>
    if (new URLSearchParams(window.location.search).get('print') === '1') window.print();
  </script>
</body>
</html>
