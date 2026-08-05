# 🫧 The Washland Portal

> **Full-stack PHP 8.2 / MySQL 8 dashboard** για τη διαχείριση, παρακολούθηση και στατιστική επεξεργασία καρτών πελατών και συνεργατών.

---

## 📖 TLDR

Το **Washland Portal** είναι ένα single-page back-office εργαλείο CRUD χτισμένο σε **PHP με strict types και PDO**, **MySQL**, **Bootstrap 5.3** και **DataTables**.

Ο κώδικας είναι open source και μπορεί να τρέξει σε shared hosting, Docker ή local XAMPP και LAMP εγκατάσταση. Το UI είναι στα ελληνικά, ενώ τα technical keywords και ο κώδικας παραμένουν στα αγγλικά.

---

## ✨ Key Features

| Λειτουργία | Περιγραφή |
|---|---|
| **Real-time Stats** | Dashboard cards με `Σύνολο Καρτών`, `Συνολικές Πωλήσεις (€)`, `Partner Cards`, `Client Cards` από το endpoint `api/stats.php` |
| **Responsive CRUD Table** | DataTables v1.13 με Ajax source από `api/card_read.php`, Excel, PDF και Print actions, Greek i18n JSON και Bootstrap 5 theme |
| **Modal Editing** | Bootstrap modal update form συνδεδεμένη με `api/card_update.php` |
| **Server-side Validation** | Required-field checks, allowed card types, strict `YYYY-MM-DD` date validation, database-safe price bounds and duplicate-code handling |
| **REST-like API Layer** | `/api/card_{create|read|update|delete}.php` με JSON output και explicit HTTP status codes στις write operations |
| **Env-driven Config** | Credentials μέσω `getenv()` για Docker, CI και production environments |
| **Greek UX** | UI, toasts και error messages στα ελληνικά, με αγγλικό codebase για ευκολότερη συντήρηση |

---

## ⚙️ Tech Stack

| Layer | Stack |
|---|---|
| **Frontend** | HTML5, Bootstrap 5.3, DataTables 1.13, Vanilla JS, Fetch API |
| **Backend** | PHP 8.2, `declare(strict_types=1)`, PDO και MySQL, χωρίς Composer |
| **Database** | MySQL 8 InnoDB με `utf8mb4` |
| **Build and Dev** | Optional Docker Compose, Apache 2.4 ή Nginx-FPM |

---

## 🏗️ Folder Structure

```text
The-Washland-Portal/
├── api/
│   ├── db.php
│   ├── card_*.php
│   └── stats.php
├── assets/
│   ├── css/custom.css
│   ├── js/main.js
│   └── js/datatables-greek.json
├── sql/schema.sql
├── templates/
├── index.php
└── add_card.php
```

---

## 🔌 Database Schema

```sql
CREATE TABLE `cards` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `card_code`     VARCHAR(255) NOT NULL UNIQUE,
  `fullname`      VARCHAR(255) NOT NULL,
  `description`   TEXT,
  `purchase_date` DATE NOT NULL,
  `type`          ENUM('Συνεργάτης','Πελάτης') NOT NULL,
  `price`         DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🛠️ Installation and Setup

### 1. Clone and environment

```bash
git clone https://github.com/zpthanos/The-Washland-Portal.git
cd The-Washland-Portal
cp .env.example .env
```

```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=washland
DB_USER=root
DB_PASS=secret
DB_CHARSET=utf8mb4
DB_TIMEZONE=Europe/Athens
DB_PERSISTENT=true
```

### 2. Database

```bash
mysql -u root -p < sql/schema.sql
```

### 3. PHP server

```bash
php -S localhost:8000
```

Μετάβαση στο `http://localhost:8000/` για το dashboard.

### Optional Docker

```yaml
services:
  php:
    image: php:8.2-apache
    ports: ["8080:80"]
    volumes: ["./:/var/www/html"]
    environment:
      DB_HOST: db
      DB_NAME: washland
      DB_USER: root
      DB_PASS: root
  db:
    image: mysql:8
    env_file: .env
    volumes:
      - washland-data:/var/lib/mysql
volumes:
  washland-data: {}
```

---

## 📲 API Reference

| Verb | Endpoint | Body | Returns |
|---|---|---|---|
| **POST** | `/api/card_create.php` | `card_code, fullname, purchase_date, type, price` | `{ success, msg }` |
| **GET** | `/api/card_read.php` | None | Array of card records |
| **POST** | `/api/card_update.php` | `id` and card fields | `{ success, msg }` |
| **POST** | `/api/card_delete.php` | `id` | `{ success, msg }` |
| **GET** | `/api/stats.php` | None | `{ total_cards, total_sales, partner_cards, client_cards }` |

The create, update and delete endpoints return JSON and set explicit HTTP status codes for successful writes, invalid input, unsupported methods, missing records, duplicate codes and server errors. Depending on the endpoint and result, these include `201`, `400`, `404`, `405`, `409`, `422` and `500`.

### Create validation

`api/card_create.php` validates:

- required fields
- allowed card type values
- exact `YYYY-MM-DD` dates
- prices from `0` to `99999999.99` with up to two decimals
- maximum 255-character card codes and customer names
- duplicate card codes with an HTTP `409` response

---

## 🔐 Security Notes

- **SQL injection** is reduced through PDO prepared statements
- **Input validation** runs before create and update writes
- **Strict types** are enabled across the PHP endpoints
- **Error responses** avoid exposing database exception details
- **CSRF protection** is not currently implemented and should be added before public internet exposure

---

## 🌍 Internationalisation

Το UI και το DataTables locale χρησιμοποιούν ελληνικά. Για νέο locale, προσθέστε νέο JSON αρχείο και αλλάξτε το `language.url` στο `main.js`.

---

## 👩‍💻 Contribution Guide

1. Fork the repository and create a focused branch
2. Follow PSR-12 conventions where practical
3. Run PHP syntax checks before opening a pull request
4. Describe the behaviour changed and how it was verified

---

## 🙏 Credits

- Bootstrap 5.3
- DataTables
- Bootstrap Icons
- Idea, design and codebase by **@zpthanos**
