<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Loan Policy | Risonaf Loans Ghana</title>
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
      --gold-light:  #fdf5e4;
      --text:        #1b2535;
      --muted:       #677080;
      --light:       #f3f5f8;
      --border:      #d9e0eb;
      --success:     #0f6d3d;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', Arial, sans-serif; background: var(--light); color: var(--text); line-height: 1.7; min-height: 100vh; display: flex; flex-direction: column; }
    .container { width: min(1100px, 92%); margin: 0 auto; }

    /* Header */
    header { background: var(--navy); color: white; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,.06); position: sticky; top: 0; z-index: 100; }
    .nav { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .brand { display: flex; align-items: center; gap: .7rem; text-decoration: none; color: white; }
    .brand-mark { width: 36px; height: 36px; background: var(--gold); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 800; color: var(--navy); }
    .brand-text { font-size: 1rem; font-weight: 700; }
    .brand-text small { display: block; font-size: .65rem; font-weight: 500; color: rgba(255,255,255,.5); letter-spacing: .5px; text-transform: uppercase; }
    .nav-link { color: rgba(255,255,255,.7); text-decoration: none; font-size: .88rem; font-weight: 500; padding: .42rem .75rem; border-radius: 6px; transition: all .15s; }
    .nav-link:hover { color: white; background: rgba(255,255,255,.08); }
    .nav-link.cta { color: var(--navy); background: var(--gold); font-weight: 600; }
    .nav-link.cta:hover { background: var(--gold-bright); }

    /* Hero */
    .page-hero { background: var(--navy); padding: 3rem 0 2.5rem; }
    .page-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--gold-bright); margin-bottom: .5rem; }
    .page-hero h1 { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: white; letter-spacing: -.4px; margin-bottom: .5rem; }
    .page-hero p { font-size: .95rem; color: rgba(255,255,255,.55); max-width: 560px; }

    /* Layout */
    .policy-wrap { display: grid; grid-template-columns: 220px 1fr; gap: 2.5rem; padding: 2.5rem 0 4rem; flex: 1; }

    /* Sidebar TOC */
    .toc { position: sticky; top: 80px; align-self: start; }
    .toc-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: .9rem; }
    .toc-list { list-style: none; display: flex; flex-direction: column; gap: .15rem; }
    .toc-list a { display: block; font-size: .84rem; font-weight: 500; color: var(--muted); text-decoration: none; padding: .35rem .65rem; border-left: 2px solid var(--border); border-radius: 0 5px 5px 0; transition: all .15s; }
    .toc-list a:hover { color: var(--navy); border-left-color: var(--navy); background: var(--navy-light); }
    .toc-list a.active { color: var(--navy); border-left-color: var(--gold); background: var(--gold-light); font-weight: 600; }

    /* Policy content */
    .policy-content { min-width: 0; }
    .policy-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 2.5rem; box-shadow: 0 2px 16px rgba(12,35,64,.06); }

    .policy-section { padding-bottom: 2.2rem; margin-bottom: 2.2rem; border-bottom: 1px solid var(--border); }
    .policy-section:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }

    .section-num { font-size: .72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gold); margin-bottom: .35rem; }
    .section-title { font-size: 1.15rem; font-weight: 800; color: var(--navy); letter-spacing: -.3px; margin-bottom: 1rem; padding-bottom: .6rem; border-bottom: 2px solid var(--navy-light); }

    .policy-section p { font-size: .93rem; color: var(--text); margin-bottom: .75rem; }
    .policy-section p:last-child { margin-bottom: 0; }

    /* Definition list */
    .def-list { display: flex; flex-direction: column; gap: .6rem; margin: .75rem 0; }
    .def-item { display: grid; grid-template-columns: 160px 1fr; gap: .5rem 1rem; font-size: .92rem; padding: .5rem .75rem; background: var(--light); border-radius: 7px; border-left: 3px solid var(--gold); }
    .def-term { font-weight: 700; color: var(--navy); }
    .def-desc { color: var(--text); }

    /* Features list */
    .feature-list { list-style: none; display: flex; flex-direction: column; gap: .4rem; margin: .75rem 0; }
    .feature-list li { display: flex; align-items: flex-start; gap: .6rem; font-size: .92rem; padding: .4rem 0; border-bottom: 1px solid var(--border); }
    .feature-list li:last-child { border-bottom: none; }
    .feature-list li::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--gold); flex-shrink: 0; margin-top: .45em; }

    /* Lettered / numbered sub-items */
    .sub-list { list-style: none; display: flex; flex-direction: column; gap: .35rem; margin: .6rem 0; padding-left: .5rem; }
    .sub-list li { display: flex; gap: .75rem; font-size: .92rem; }
    .sub-list li .sub-marker { font-weight: 700; color: var(--navy); flex-shrink: 0; min-width: 28px; }

    /* Warning box */
    .warning-box { background: #fdf5e4; border: 1px solid #e8c97a; border-left: 4px solid var(--gold); border-radius: 8px; padding: .85rem 1rem; font-size: .88rem; color: #7c5a0a; margin: .75rem 0; }

    /* Scroll-to-top */
    .back-top { display: inline-flex; align-items: center; gap: .4rem; font-size: .83rem; font-weight: 600; color: var(--muted); text-decoration: none; margin-top: 1.5rem; transition: color .15s; }
    .back-top:hover { color: var(--navy); }

    footer { background: var(--navy); color: rgba(255,255,255,.4); padding: 1.5rem 0; font-size: .83rem; border-top: 1px solid rgba(255,255,255,.06); }
    .footer-inner { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .75rem; }
    .footer-links { display: flex; gap: 1.2rem; }
    .footer-links a { color: rgba(255,255,255,.45); text-decoration: none; font-size: .82rem; transition: color .15s; }
    .footer-links a:hover { color: rgba(255,255,255,.8); }

    @media (max-width: 768px) { .policy-wrap { grid-template-columns: 1fr; } .toc { position: static; } .def-item { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

  <header>
    <div class="container nav">
      <a class="brand" href="index.php">
        <div class="brand-mark">RL</div>
        <div class="brand-text">Risonaf Loans <small>Ghana</small></div>
      </a>
      <nav style="display:flex;gap:.2rem;flex-wrap:wrap;">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link" href="status.php">Track Application</a>
        <a class="nav-link cta" href="index.php#apply">Apply Now</a>
      </nav>
    </div>
  </header>

  <div class="page-hero">
    <div class="container">
      <div class="page-eyebrow">Legal &amp; Compliance</div>
      <h1>Loan Policy</h1>
      <p>Please read this policy carefully before applying. By submitting an application you agree to be bound by these terms.</p>
    </div>
  </div>

  <main style="flex:1">
    <div class="container">
      <div class="policy-wrap">

        <!-- Sidebar TOC -->
        <aside class="toc">
          <div class="toc-title">Contents</div>
          <ul class="toc-list" id="tocList">
            <li><a href="#s1">1. Definitions</a></li>
            <li><a href="#s2">2. About the Product</a></li>
            <li><a href="#s3">3. Eligibility</a></li>
            <li><a href="#s4">4. Application &amp; Approval</a></li>
            <li><a href="#s5">5. Loan Amount &amp; Disbursement</a></li>
            <li><a href="#s6">6. Repayment</a></li>
            <li><a href="#s7">7. Interest &amp; Fees</a></li>
            <li><a href="#s8">8. Default</a></li>
            <li><a href="#s9">9. Governing Law</a></li>
            <li><a href="#s10">10. Amendments</a></li>
          </ul>
        </aside>

        <!-- Policy body -->
        <div class="policy-content">
          <div class="policy-card">

            <!-- Section 1 -->
            <section class="policy-section" id="s1">
              <div class="section-num">Section 1</div>
              <h2 class="section-title">Definitions</h2>
              <p>For the purposes of this Loan Policy, the following terms shall have the meanings assigned to them below:</p>
              <div class="def-list">
                <div class="def-item"><span class="def-term">Borrower</span><span class="def-desc">Any individual or entity that has applied for and received a loan from Risonaf Loans.</span></div>
                <div class="def-item"><span class="def-term">Loan</span><span class="def-desc">The principal amount approved and disbursed by Risonaf Loans to the Borrower.</span></div>
                <div class="def-item"><span class="def-term">Loan Agreement</span><span class="def-desc">The agreement executed between Risonaf Loans and the Borrower incorporating this Loan Policy and any related documents.</span></div>
                <div class="def-item"><span class="def-term">Loan Term</span><span class="def-desc">Three (3) months from the date of disbursement.</span></div>
                <div class="def-item"><span class="def-term">Interest</span><span class="def-desc">Twenty percent (20%) of the approved loan amount over the Loan Term.</span></div>
                <div class="def-item"><span class="def-term">Processing Fee</span><span class="def-desc">A non-refundable fee of five percent (5%) of the approved loan amount, payable upfront.</span></div>
                <div class="def-item"><span class="def-term">Outstanding Balance</span><span class="def-desc">Any unpaid principal, accrued interest, fees, penalties, and other amounts due under the Loan Agreement.</span></div>
                <div class="def-item"><span class="def-term">Due Date</span><span class="def-desc">The final repayment date specified in the Loan Agreement.</span></div>
                <div class="def-item"><span class="def-term">Late Repayment Fee</span><span class="def-desc">A penalty of five percent (5%) per month on the Outstanding Balance where repayment is not made by the Due Date.</span></div>
              </div>
            </section>

            <!-- Section 2 -->
            <section class="policy-section" id="s2">
              <div class="section-num">Section 2</div>
              <h2 class="section-title">About the Product</h2>
              <p>Risonaf Loans provides short-term loans designed to meet the immediate financial needs of eligible borrowers.</p>
              <p>Key product features include:</p>
              <ul class="feature-list">
                <li><strong>Loan Term:</strong>&nbsp;Three (3) months.</li>
                <li><strong>Interest Rate:</strong>&nbsp;Twenty percent (20%) of the loan amount for the entire loan period.</li>
                <li><strong>Processing Fee:</strong>&nbsp;Five percent (5%) of the approved loan amount payable upfront.</li>
                <li><strong>Repayment:</strong>&nbsp;Full repayment of principal, interest, and any applicable charges by the Due Date.</li>
                <li><strong>Late Repayment Fee:</strong>&nbsp;Five percent (5%) per month on any Outstanding Balance after the Due Date.</li>
              </ul>
            </section>

            <!-- Section 3 -->
            <section class="policy-section" id="s3">
              <div class="section-num">Section 3</div>
              <h2 class="section-title">Eligibility</h2>
              <p>To qualify for a loan, an applicant must:</p>
              <ul class="sub-list">
                <li><span class="sub-marker">a.</span><span>Be at least eighteen (18) years of age.</span></li>
                <li><span class="sub-marker">b.</span><span>Possess a valid government-issued identification document.</span></li>
                <li><span class="sub-marker">c.</span><span>Provide accurate and complete personal, financial, and contact information.</span></li>
                <li><span class="sub-marker">d.</span><span>Demonstrate the ability to repay the loan.</span></li>
                <li><span class="sub-marker">e.</span><span>Meet any additional credit assessment criteria established by Risonaf Loans from time to time.</span></li>
              </ul>
              <p>Risonaf Loans reserves the right to reject any application that does not satisfy its eligibility requirements.</p>
            </section>

            <!-- Section 4 -->
            <section class="policy-section" id="s4">
              <div class="section-num">Section 4</div>
              <h2 class="section-title">Application and Approval</h2>
              <ul class="sub-list">
                <li><span class="sub-marker">4.1</span><span>Loan applications shall be submitted in the manner prescribed by Risonaf Loans.</span></li>
                <li><span class="sub-marker">4.2</span><span>Applicants shall provide all information and supporting documents reasonably required by Risonaf Loans.</span></li>
                <li><span class="sub-marker">4.3</span><span>Risonaf Loans may conduct credit, identity, employment, income, and reference checks as part of its assessment process.</span></li>
                <li><span class="sub-marker">4.4</span><span>Approval of a loan application is at the sole discretion of Risonaf Loans.</span></li>
                <li><span class="sub-marker">4.5</span><span>The approval decision, approved loan amount, repayment obligations, and applicable fees shall be communicated to the applicant before disbursement.</span></li>
              </ul>
            </section>

            <!-- Section 5 -->
            <section class="policy-section" id="s5">
              <div class="section-num">Section 5</div>
              <h2 class="section-title">Loan Amount and Disbursement</h2>
              <ul class="sub-list">
                <li><span class="sub-marker">5.1</span><span>The loan amount shall be determined by Risonaf Loans based on its assessment of the applicant.</span></li>
                <li><span class="sub-marker">5.2</span><span>A processing fee equal to five percent (5%) of the approved loan amount shall be payable before loan disbursement.</span></li>
                <li><span class="sub-marker">5.3</span><span>Upon receipt of the processing fee and completion of all required documentation, the approved loan amount shall be disbursed to the Borrower through the agreed payment method.</span></li>
                <li><span class="sub-marker">5.4</span><span>Risonaf Loans reserves the right to set minimum and maximum loan limits at its discretion.</span></li>
              </ul>
            </section>

            <!-- Section 6 -->
            <section class="policy-section" id="s6">
              <div class="section-num">Section 6</div>
              <h2 class="section-title">Repayment</h2>
              <ul class="sub-list">
                <li><span class="sub-marker">6.1</span><span>The Borrower shall repay the loan in accordance with the repayment schedule provided by Risonaf Loans.</span></li>
                <li><span class="sub-marker">6.2</span><span>Unless otherwise agreed in writing, the Borrower shall repay the full principal amount together with applicable interest and charges within three (3) months from the date of disbursement.</span></li>
                <li><span class="sub-marker">6.3</span><span>Repayment shall be made through the payment channels approved by Risonaf Loans.</span></li>
                <li><span class="sub-marker">6.4</span><span>The Borrower may make early repayment at any time. Unless otherwise agreed, the full interest applicable to the Loan Term shall remain payable.</span></li>
                <li><span class="sub-marker">6.5</span><span>The Borrower shall ensure that sufficient funds are available to meet repayment obligations on or before the Due Date.</span></li>
              </ul>
            </section>

            <!-- Section 7 -->
            <section class="policy-section" id="s7">
              <div class="section-num">Section 7</div>
              <h2 class="section-title">Interest and Fees</h2>

              <p><strong>7.1 Interest</strong></p>
              <ul class="sub-list">
                <li><span class="sub-marker">a.</span><span>Interest shall be charged at a fixed rate of twenty percent (20%) of the approved loan amount for the three-month Loan Term.</span></li>
                <li><span class="sub-marker">b.</span><span>Interest shall accrue from the date of disbursement and become payable in accordance with the repayment schedule.</span></li>
              </ul>

              <p style="margin-top:.9rem"><strong>7.2 Processing Fee</strong></p>
              <ul class="sub-list">
                <li><span class="sub-marker">a.</span><span>A processing fee equal to five percent (5%) of the approved loan amount shall be charged.</span></li>
                <li><span class="sub-marker">b.</span><span>The processing fee is payable upfront before loan disbursement.</span></li>
                <li><span class="sub-marker">c.</span><span>The processing fee is non-refundable.</span></li>
              </ul>

              <p style="margin-top:.9rem"><strong>7.3 Late Repayment Fee</strong></p>
              <div class="warning-box">⚠️ Failure to repay by the Due Date will result in a late fee of 5% per month on the outstanding balance.</div>
              <ul class="sub-list">
                <li><span class="sub-marker">a.</span><span>Failure to repay the Outstanding Balance by the Due Date shall constitute a default.</span></li>
                <li><span class="sub-marker">b.</span><span>Upon default, a Late Repayment Fee of five percent (5%) per month shall be charged on the Outstanding Balance until full repayment is made.</span></li>
                <li><span class="sub-marker">c.</span><span>The Late Repayment Fee shall be calculated monthly and added to the Borrower's Outstanding Balance.</span></li>
              </ul>

              <p style="margin-top:.9rem"><strong>7.4 Other Charges</strong></p>
              <p>Risonaf Loans reserves the right to recover any reasonable costs incurred in collecting overdue amounts, to the extent permitted by applicable law.</p>
            </section>

            <!-- Section 8 -->
            <section class="policy-section" id="s8">
              <div class="section-num">Section 8</div>
              <h2 class="section-title">Default</h2>
              <p>A Borrower shall be deemed to be in default if:</p>
              <ul class="sub-list">
                <li><span class="sub-marker">a.</span><span>Any payment due under the Loan Agreement remains unpaid after the Due Date;</span></li>
                <li><span class="sub-marker">b.</span><span>The Borrower provides false or misleading information during the application process; or</span></li>
                <li><span class="sub-marker">c.</span><span>The Borrower breaches any material provision of the Loan Agreement.</span></li>
              </ul>
              <p>Upon default, Risonaf Loans may pursue all lawful remedies available for recovery of the Outstanding Balance.</p>
            </section>

            <!-- Section 9 -->
            <section class="policy-section" id="s9">
              <div class="section-num">Section 9</div>
              <h2 class="section-title">Governing Law</h2>
              <p>This Loan Policy and all Loan Agreements entered into pursuant to it shall be governed by and construed in accordance with the laws of the applicable jurisdiction in which Risonaf Loans operates.</p>
            </section>

            <!-- Section 10 -->
            <section class="policy-section" id="s10">
              <div class="section-num">Section 10</div>
              <h2 class="section-title">Amendments</h2>
              <p>Risonaf Loans reserves the right to amend this Loan Policy from time to time. Any amendments shall be communicated to Borrowers and shall become effective upon notification.</p>
            </section>

            <a class="back-top" href="#top">&#8593; Back to top</a>

          </div><!-- .policy-card -->

          <div style="margin-top:1.5rem;padding:1.2rem 1.5rem;background:white;border:1px solid var(--border);border-radius:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
              <div style="font-size:.88rem;font-weight:700;color:var(--navy);margin-bottom:.2rem">Ready to apply?</div>
              <div style="font-size:.83rem;color:var(--muted)">Fast decisions. Fair terms. Transparent pricing.</div>
            </div>
            <a href="index.php#apply" style="background:var(--navy);color:white;text-decoration:none;font-weight:700;font-size:.9rem;padding:.72rem 1.4rem;border-radius:8px;display:inline-flex;align-items:center;gap:.4rem;transition:background .15s;">Apply for a Loan &#8594;</a>
          </div>

        </div><!-- .policy-content -->
      </div><!-- .policy-wrap -->
    </div>
  </main>

  <footer>
    <div class="container footer-inner">
      <span>&copy; 2026 Risonaf Loans Ghana. All rights reserved.</span>
      <div class="footer-links">
        <a href="index.php">Home</a>
        <a href="policy.php">Loan Policy</a>
        <a href="status.php">Track Application</a>
        <a href="index.php#apply">Apply Now</a>
      </div>
    </div>
  </footer>

  <script>
    // Highlight active TOC section on scroll
    const sections = document.querySelectorAll('.policy-section');
    const tocLinks = document.querySelectorAll('.toc-list a');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          tocLinks.forEach(a => a.classList.remove('active'));
          const active = document.querySelector(`.toc-list a[href="#${entry.target.id}"]`);
          if (active) active.classList.add('active');
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px' });

    sections.forEach(s => observer.observe(s));
  </script>
</body>
</html>
