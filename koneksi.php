<?php
// Konfigurasi RDS
$host = 'database-sinzu.cgxshlk266oq.us-east-1.rds.amazonaws.com'; // contoh: calis-store-db.abc123xyz.rds.amazonaws.com
$user = 'admin';
$pass = 'MyP4ssw0rd';
$db_name = 'calisthenics_store';

// Koneksi ke MySQL Server (tanpa database dulu)
$conn = new mysqli($host, $user, $pass);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke server gagal: " . $conn->connect_error);
}

// Cek dan buat database jika belum ada
$conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`");

// Gunakan database tersebut
$conn->select_db($db_name);

// Buat tabel users jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member', 'user') NOT NULL
)");

// Buat tabel products jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    stock INT,
    image_path VARCHAR(255)
)");

// Buat tabel purchase_history jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS purchase_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    total_price DECIMAL(10,2),
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Cek dan insert user awal jika belum ada
$check = $conn->query("SELECT COUNT(*) as total FROM users");
$data = $check->fetch_assoc();
if ($data['total'] == 0) {
    $conn->query("INSERT INTO users (username, password, role) VALUES
        ('admin1', MD5('123'), 'admin'),
        ('member2', MD5('234'), 'member'),
        ('user3', MD5('345'), 'user')");
}

$koneksi = $conn;
?>