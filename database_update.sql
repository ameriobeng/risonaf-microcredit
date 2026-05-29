-- Run this once to add the status column to existing installations.
ALTER TABLE loan_applications
  ADD COLUMN status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'
  AFTER purpose;
