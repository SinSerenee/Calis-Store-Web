# Calis_Store

**Calis_Store** adalah aplikasi CRUD berbasis PHP & MySQL untuk mengelola toko online alat-alat calisthenics. Aplikasi ini memiliki sistem login multi-role: **admin**, **member**, dan **user biasa**, dengan fitur dan tampilan yang berbeda-beda.

---

## 🗂️ Struktur File

- `admin_dashboard.php` – CRUD produk untuk admin.
- `admin_purchases.php` – Tambah produk oleh admin.
- `edit_product.php` – Edit produk.
- `member_dashboard.php` – Tampilan produk khusus member dengan diskon.
- `user_dashboard.php` – Tampilan produk untuk user biasa.
- `purchase.php` – Proses beli oleh user biasa.
- `purchase2.php` – Proses beli oleh member.
- `history.php` – Menampilkan riwayat pembelian.
- `login.php` – Halaman login semua user.
- `logout.php` – Logout dan destroy session.
- `ceklogin.php` – Proses autentikasi login.
- `koneksi.php` – Konfigurasi koneksi database.

---

## 💾 Struktur Database SQL

Kamu bisa langsung salin semua query ini ke phpMyAdmin atau terminal SQL:

```sql
-- Buat database terlebih dahulu
CREATE DATABASE calisthenics_store;
USE calisthenics_store;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member', 'user') NOT NULL
);

-- Tabel products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price INT NOT NULL,
    stock INT NOT NULL
);

-- Tabel transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
 -- Masukkan data user
 INSERT INTO users (username, password, role) VALUES 
('admin1', MD5('admin123'), 'admin'),
('member1', MD5('member123'), 'member'),
('user1', MD5('user123'), 'user');
