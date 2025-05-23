<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    // Validasi jumlah pembelian
    if ($quantity <= 0) {
        echo "<script>alert('Jumlah harus lebih dari 0!'); window.location.href = 'member_dashboard.php';</script>";
        exit;
    }

    // Ambil data produk
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($product['stock'] >= $quantity) {
            $new_stock = $product['stock'] - $quantity;
            $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
            $koneksi->query($update_query);

            // Simpan ke riwayat pembelian
            $total_price = $product['price'] * $quantity * 0.9; // Harga diskon
            $insert_query = "INSERT INTO purchase_history (user_id, product_id, quantity, total_price)
                             VALUES ('$user_id', '$product_id', '$quantity', '$total_price')";
            $koneksi->query($insert_query);

            echo "<script>alert('Pembelian berhasil!'); window.location.href = 'member_dashboard.php';</script>";
        } else {
            echo "<script>alert('Stok tidak mencukupi!'); window.location.href = 'member_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href = 'member_dashboard.php';</script>";
    }
}
?>
