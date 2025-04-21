<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

$query = "SELECT * FROM products";
$result = $koneksi->query($query);

if (!$result) {
    die("Error mengambil data produk: " . $koneksi->error);
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "User";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calisthenics Store - User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .header .welcome {
            margin-top: 10px;
            font-size: 1.2em;
        }
        .header .logout {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .header .logout:hover {
            background-color: #c82333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
            object-fit: cover;
        }
        .buy-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .buy-button:hover {
            background-color: #0056b3;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Calisthenics Store</h1>
        <p class="welcome">Selamat datang, <?= $username ?>! Jelajahi produk kami dan belanja sekarang.</p>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <h2>Daftar Produk</h2>
        <a href="purchase_history.php" style="display: inline-block; margin: 10px 0; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Lihat Riwayat Pembelian</a>
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <?php if ($row['stock'] > 0): ?>
                            <form action="purchase.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <input type="number" name="quantity" min="1" max="<?= htmlspecialchars($row['stock']) ?>" placeholder="Jumlah" required>
                                <button type="submit" class="buy-button">Beli</button>
                            </form>
                        <?php else: ?>
                            <span class="out-of-stock">Stok Habis</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>