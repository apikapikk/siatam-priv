# Rincian Sistem dan Teknologi yang Digunakan
**Sistem Informasi Bimbingan Belajar (Siatama Privat)**

Dokumen ini berisi spesifikasi perangkat keras dan perangkat lunak yang digunakan dalam pengembangan dan pengoperasian sistem Siatama Privat untuk kebutuhan dokumentasi dan laporan tugas.

---

## 1. Perangkat Keras (Hardware Minimal)

Spesifikasi perangkat keras yang dibutuhkan pada server/environment eksekusi maupun laptop pembuat (*development environment*):

| Perangkat Keras | Spesifikasi Minimal (Server / Client) | Spesifikasi Laptop Pembuat (Recommended) |
| :--- | :--- | :--- |
| **Processor (CPU)** | Intel Core i3 / AMD Ryzen 3 (Dual-Core 2.0 GHz) | Intel Core i5 / AMD Ryzen 5 (Quad-Core 2.5 GHz ke atas) |
| **Memori (RAM)** | 4 GB DDR4 | 8 GB – 16 GB DDR4/DDR5 |
| **Penyimpanan (Storage)** | SSD 128 GB (SATA) | SSD 256 GB / 512 GB NVMe |
| **Kartu Grafis (GPU)** | Integrated Graphics (Intel HD / AMD Radeon) | Integrated Graphics / Dedicated GPU (NVIDIA / AMD) |
| **Layar / Display** | Resolusi 1366x768 | Resolusi Full HD (1920x1080) |

---

## 2. Perangkat Lunak (Software Stack)

Teknologi, *tooling*, dan *software stack* yang digunakan dalam pembuatan dan penyajian aplikasi:

* **Sistem Operasi (OS):**
  * **OS Pembuat (Development):** Linux (Ubuntu / Linux Mint) / Windows 10/11.
  * **OS Server (Production):** Linux Server (Ubuntu Server 20.04/22.04 LTS) / Web Hosting berbasis Linux.
* **Integrated Development Environment (IDE) & Text Editor:**
  * Visual Studio Code (VS Code) / Antigravity IDE.
* **Database Management System (DBMS):**
  * **Database Engine:** MySQL 8.0 / MariaDB.
  * **Database Driver:** PHP Data Objects (PDO) dengan *Prepared Statements* (keamanan SQL Injection).
  * **Database Management Tool:** phpMyAdmin / DBeaver / TablePlus.
* **Bahasa Pemrograman (Programming Languages):**
  * **Back-End:** PHP (Versi 8.0+ / 8.1+ / 8.2+).
  * **Front-End:** HTML5, CSS3, JavaScript (ES6+ Native).
* **Framework & Arsitektur Sistem:**
  * **Arsitektur Back-End:** Custom Lightweight MVC (Model-View-Controller) Architecture dengan *PSR-4 Autoloading* dan Custom HTTP Router.
  * **Framework Front-End:** Tailwind CSS (via CDN) dengan konfigurasi kustom *Sage & Harvest Design System* serta Plugin `@tailwindcss/forms`.
* **Web Server:**
  * Apache HTTP Server / Nginx (atau PHP Built-in Web Server `php -S localhost:8000` pada tahap pengembangan).
* **Version Control System (VCS):**
  * Git (Hosted di GitHub / GitLab).
