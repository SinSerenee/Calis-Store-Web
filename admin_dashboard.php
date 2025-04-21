<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

function sanitize($value) {
    global $koneksi;
    return $koneksi->real_escape_string(trim($value));
}

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $upload_ok = 1;
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
        echo "<script>alert('Harap isi semua data produk dengan benar.');</script>";
        $upload_ok = 0;
    }

    if (!empty($_FILES["image"]["tmp_name"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File bukan gambar.');</script>";
            $upload_ok = 0;
        }

        if ($_FILES["image"]["size"] > 2000000) {
            echo "<script>alert('Ukuran gambar melebihi 2MB.');</script>";
            $upload_ok = 0;
        }

        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Format gambar harus JPG, JPEG, PNG, atau GIF.');</script>";
            $upload_ok = 0;
        }

        if ($_FILES['image']['error'] !== 0) {
            echo "<script>alert('Terjadi error saat upload file: kode " . $_FILES['image']['error'] . "');</script>";
            $upload_ok = 0;
        }

        if ($upload_ok) {
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO products (name, description, price, stock, image_path)
                          VALUES ('$name', '$description', '$price', '$stock', '$target_file')";

                if ($koneksi->query($query)) {
                    echo "<script>alert('Produk berhasil ditambahkan.'); window.location.href='admin_dashboard.php';</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan produk ke database.');</script>";
                }
            } else {
                echo "<script>alert('Gagal menyimpan file gambar ke server.');</script>";
            }
        }
    } else {
        echo "<script>alert('Harap pilih gambar produk.');</script>";
    }
}

// Proses hapus produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
    $id = intval($_POST['product_id']);
    $query = "DELETE FROM products WHERE id = $id";
    $koneksi->query($query);
    echo "<script>alert('Produk berhasil dihapus.'); window.location.href='admin_dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { width: 80%; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #343a40; }
        form input, form textarea, form button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        .product-image { width: 100px; height: 100px; object-fit: cover; }
        .delete-button { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" style="float:right; margin-bottom: 10px; background-color:#dc3545; color:white; padding:10px; border-radius:5px;">Logout</a>
        <h2>Tambah Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Produk" required>
            <textarea name="description" placeholder="Deskripsi Produk" required></textarea>
            <input type="number" name="price" placeholder="Harga Produk" required>
            <input type="number" name="stock" placeholder="Stok Produk" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="add_product">Tambah Produk</button>
        </form>

        <h2>Daftar Produk</h2>
        <table>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php
            $result = $koneksi->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" class="product-image" alt=""></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button class="delete-button" name="delete_product">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
