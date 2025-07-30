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
