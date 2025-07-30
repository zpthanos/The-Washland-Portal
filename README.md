# 🫧 The Washland Portal  
> **Full‑stack PHP 8.2 / MySQL 8 Dashboard** για την διαχείριση, παρακολούθηση και στατιστική επεξεργασία “καρτών” πελατών & συνεργατών.

---

## 📖 TL;DR  
Το **Washland Portal** είναι ένα _single‑page_ back‑office εργαλείο (CRUD dashboard) χτισμένο σε **PHP (strict types + PDO)**, **MySQL**, **Bootstrap 5.3** & **DataTables**.  
Ο κώδικας είναι 100 % open‑source, έτοιμος για **shared hosting**, **Docker** ή **local XAMPP/LAMP** εγκατάσταση και μιλάει αποκλειστικά 🇬🇷 **Greek** στο UI, ενώ κρατάει τα αγγλικά για όλα τα technical keywords.

---

## ✨ Key Features
| Λειτουργία | Περιγραφή |
|------------|-----------|
| **Real‑time Stats** | Dashboard cards με <br/>`Σύνολο Καρτών`, `Συνολικές Πωλήσεις (€)`, `Partner Cards`, `Client Cards` – live από endpoint&nbsp;`api/stats.php`. |
| **Responsive CRUD Table** | DataTables v1.13 με Ajax source (`api/card_read.php`), Excel/PDF/Print buttons, Greek i18n json & Bootstrap 5 theme. |
| **Modal Editing** | Inline modal (Bootstrap) update φόρμα συνδεδεμένη με `api/card_update.php`. |
| **Server‑side Validation** | PHP 8 Attribute‑level checks (strict_types, PDO prepared statements, custom `ValidationException`). |
| **REST‑like API Layer** | `/api/card_{create|read|update|delete}.php` με JSON output + proper HTTP status codes. |
| **Env‑Driven Config** | Όλα τα credentials διαβάζονται από `getenv()` ώστε να δουλεύει out‑of‑the‑box σε Docker, CI/CD ή production. |
| **Greek UX** | UI, Toasts & error messages σε πλήρη 🇬🇷, αλλά codebase & comments στα αγγλικά για universal readability. |

---

## 🏗️ Folder Structure
The-Washland-Portal/
├── api/ # REST‑like PHP endpoints
│ ├── db.php # PDO bootstrap & helpers
│ ├── card_*.php # create/read/update/delete
│ └── stats.php # aggregated metrics
├── assets/
│ ├── css/custom.css # utility-first custom rules
│ ├── js/main.js # ES6 class CardManager + Fetch API logic
│ └── js/datatables-greek.json
├── sql/schema.sql # MySQL DDL (STRICT_ALL_TABLES)
├── templates/ # Tiny PHP partials (header/footer)
├── index.php # Dashboard + table
└── add_card.php # “Νέα κάρτα” page


---

## ⚙️ Tech Stack
| Layer | Stack |
|-------|-------|
| **Frontend** | HTML 5, Bootstrap 5.3, DataTables 1.13, Vanilla JS (ES2022), Fetch API |
| **Backend**  | PHP 8.2 (strict_types), PDO (MySQL 8), Composer‑free |
| **Database** | MySQL 8 InnoDB (`utf8mb4`, `ROW_FORMAT=DYNAMIC`) |
| **Build/Dev**| Optional Docker Compose, Apache 2.4 / Nginx‑FPM, Xdebug ready |

---

## 🔌 Database Schema
```sql
CREATE TABLE `cards` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `card_code`   VARCHAR(255) NOT NULL UNIQUE,
  `fullname`    VARCHAR(255) NOT NULL,
  `description` TEXT,
  `purchase_date` DATE NOT NULL,
  `type` ENUM('Συνεργάτης','Πελάτης') NOT NULL,
  `price` DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


1️⃣ Clone + Environment
git clone https://github.com/<you>/The-Washland-Portal.git
cd The-Washland-Portal
cp .env.example .env

.env.example
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=washland
DB_USER=root
DB_PASS=secret
DB_CHARSET=utf8mb4
DB_TIMEZONE=Europe/Athens
DB_PERSISTENT=true


2️⃣ Database
mysql -u root -p < sql/schema.sql


3️⃣ PHP Server
php -S localhost:8000        # ή Apache vhost προς public path
Navigate στο http://localhost:8000/ →

🐳 Optional Docker
# docker-compose.yml
services:
  php:
    image: php:8.2-apache
    ports: [ "8080:80" ]
    volumes: [ "./:/var/www/html" ]
    environment:
      DB_HOST: db
      DB_NAME: washland
      DB_USER: root
      DB_PASS: root
  db:
    image: mysql:8
    env_file: .env
    volumes: [ "washland-data:/var/lib/mysql" ]
volumes: { washland-data: {} }


📲 API Reference
Verb	Endpoint	Body (x‑www‑form‑urlencoded)	Returns
POST	/api/card_create.php	card_code, fullname, purchase_date, type, price	{ success, msg }
GET	/api/card_read.php	–	[ { id, card_code, ... } ]
POST	/api/card_update.php	id + όλα τα πεδία	{ success, msg }
POST	/api/card_delete.php	id	{ success, msg }
GET	/api/stats.php	–	{ total_cards, total_sales, partner_cards, client_cards }

All endpoints return application/json; charset=utf‑8 & proper HTTP codes (400/422/500).
🔐 Security Notes

    SQL Injection → πλήρης προστασία με PDO prepared statements.

    XSS → Data escaped via Bootstrap modals / innerText; no dangerous innerHTML.

    Strict Types → declare(strict_types=1); σε όλα τα PHP αρχεία.

    CSRF → Ενδείκνυται future token implementation (π.χ. OWASP CSRFGuard) αν εκτεθεί public.

🌍 Internationalisation

Πλήρης υποστήριξη ελληνικών (i18n) στο UI & DataTables (assets/js/datatables-greek.json).
Για νέο locale απλά προσθέστε άλλο JSON και αλλάξτε language.url στο main.js.
