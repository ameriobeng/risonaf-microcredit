CREATE DATABASE IF NOT EXISTS microcredit_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE microcredit_db;

CREATE TABLE IF NOT EXISTS loan_applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  email VARCHAR(150) NOT NULL,
  location VARCHAR(150) NOT NULL,
  loan_type ENUM('Personal Loan', 'Business Loan', 'Group Loan') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  purpose TEXT NOT NULL,
  submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_loan_type (loan_type),
  INDEX idx_submitted_at (submitted_at)
);
