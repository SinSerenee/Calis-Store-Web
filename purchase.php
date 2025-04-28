<?php
session_start();

// Pastikan hanya pengguna biasa yang dapat mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    // Validasi input quantity
    if ($quantity <= 0) {
        echo "<script>alert('Jumlah pembelian harus lebih dari 0!'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    // Ambil data produk dari database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Cek stok mencukupi
        if ($product['stock'] >= $quantity) {
            $new_stock = $product['stock'] - $quantity;
            $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
            $koneksi->query($update_query);

            // Hitung total harga
            $total_price = $product['price'] * $quantity;

            // Simpan ke riwayat pembelian
            $insert_query = "INSERT INTO purchase_history (user_id, product_id, quantity, total_price)
                             VALUES ('$user_id', '$product_id', '$quantity', '$total_price')";
            $koneksi->query($insert_query);

            echo "<script>alert('Pembelian berhasil!'); window.location.href = 'user_dashboard.php';</script>";
        } else {
            echo "<script>alert('Stok tidak mencukupi!'); window.location.href = 'user_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href = 'user_dashboard.php';</script>";
    }
}
?>
