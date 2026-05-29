# Risobaf Loans Ghana (PHP + MySQL)

This project has been migrated to **PHP + MySQL**.

## 1) Requirements

- PHP 8.0+
- MySQL 5.7+ (or MariaDB equivalent)

## 2) Database Setup

1. Create/import database schema:

```sql
SOURCE database.sql;
```

Or run the SQL manually in your MySQL client using contents of `database.sql`.

2. Update DB credentials in `config.php`:

```php
const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'microcredit_db';
const DB_USER = 'root';
const DB_PASS = '';
```

## 3) Run the App

From the project root:

```bash
php -S localhost:8000
```

Then open:

- Public page: `http://localhost:8000/index.php`
- Admin page: `http://localhost:8000/admin.php`

## 4) Project Structure

- `index.php` - Public-facing loan application page
- `admin.php` - Admin dashboard
- `config.php` - MySQL connection setup (PDO)
- `database.sql` - Database and table schema
- `api/submit.php` - Insert a new application
- `api/get_applications.php` - Fetch applications + summary stats
- `api/clear_applications.php` - Clear all applications

## 5) Notes

- All user inputs are validated server-side.
- Database access uses prepared statements (PDO).
- Admin clear operation truncates `loan_applications` table.
