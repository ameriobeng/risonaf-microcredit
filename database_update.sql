-- Run this once to add the status column to existing installations.
ALTER TABLE loan_applications
  ADD COLUMN status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'
  AFTER purpose;

-- Run this once to add ID card columns to existing installations.
ALTER TABLE loan_applications
  ADD COLUMN id_type   VARCHAR(50)  NOT NULL DEFAULT '' AFTER location,
  ADD COLUMN id_number VARCHAR(100) NOT NULL DEFAULT '' AFTER id_type;
