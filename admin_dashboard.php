<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
        echo "<script>alert('Harap isi semua data dengan benar!');</script>";
    } else {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $upload_ok = 1;

        if (!empty($_FILES["image"]["tmp_name"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "<script>alert('File yang diunggah bukan gambar!');</script>";
                $upload_ok = 0;
            }

            if ($_FILES["image"]["size"] > 2000000) {
                echo "<script>alert('Ukuran file terlalu besar (maks. 2MB)!');</script>";
                $upload_ok = 0;
            }

            if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<script>alert('Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan!');</script>";
                $upload_ok = 0;
            }

            // Upload file
            if ($upload_ok && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Simpan data ke database
                $query = "INSERT INTO products (name, description, price, stock, image_path)
                          VALUES ('$name', '$description', '$price', '$stock', '$target_file')";
                if ($koneksi->query($query)) {
                    echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='admin_dashboard.php';</script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan saat menyimpan produk ke database!');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengunggah gambar!');</script>";
            }
        }
    }
}

// Proses penghapusan produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
    $id = intval($_POST['product_id']);
    $query_check = "SELECT * FROM products WHERE id = '$id'";
    $result_check = $koneksi->query($query_check);

    if ($result_check->num_rows > 0) {
        $query = "DELETE FROM products WHERE id = '$id'";
        if ($koneksi->query($query)) {
            echo "<script>alert('Produk berhasil dihapus!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus produk!');</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <a href="logout.php" style="position: absolute; right: 20px; top: 20px; background-color: #dc3545; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Logout</a>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { width: 80%; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #343a40; }
        form { margin-bottom: 20px; }
        input, textarea, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .product-image { width: 100px; height: 100px; object-fit: cover; }
        .delete-button { background-color: #dc3545; }
        .delete-button:hover { background-color: #c82333; }
        .edit-button { background-color: #ffc107; }
        .edit-button:hover { background-color: #e0a800; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Produk" required>
            <textarea name="description" placeholder="Deskripsi Produk" required></textarea>
            <input type="number" name="price" placeholder="Harga Produk" required>
            <input type="number" name="stock" placeholder="Stok Produk" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="add_product">Tambah Produk</button>
        </form>

        <h3>Daftar Produk</h3>
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
                <?php
                $query = "SELECT * FROM products";
                $result = $koneksi->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='" . $row['image_path'] . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'></td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>Rp " . number_format($row['price'], 2, ',', '.') . "</td>";
                    echo "<td>" . $row['stock'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_product.php?id=" . $row['id'] . "'><button class='edit-button'>Edit</button></a>";
                    echo "<form method='POST' style='display:inline;'>
                            <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_product' class='delete-button'>Hapus</button>
                          </form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
