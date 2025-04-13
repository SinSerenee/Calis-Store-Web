<?php
require 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM products WHERE id = '$id'";
$result = $koneksi->query($query);

if ($result->num_rows == 0) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='admin_dashboard.php';</script>";
    exit;
}

$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $current_image = $product['image_path'];

    $new_image_path = $current_image;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $upload_ok = 1;

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

        if ($upload_ok && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $new_image_path = $target_file;

            if ($current_image && file_exists($current_image)) {
                unlink($current_image);
            }
        } else {
            echo "<script>alert('Gagal mengunggah gambar baru!');</script>";
        }
    }

    $update_query = "UPDATE products 
                     SET name = '$name', 
                         description = '$description', 
                         price = '$price', 
                         stock = '$stock', 
                         image_path = '$new_image_path' 
                     WHERE id = '$id'";
    if ($koneksi->query($update_query)) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui produk!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { width: 80%; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #343a40; }
        form input, form textarea, form button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        img { display: block; margin: 10px auto; max-width: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            <label>Deskripsi Produk</label>
            <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
            <label>Harga Produk</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" required>
            <label>Stok Produk</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
            <label>Gambar Produk (Biarkan kosong jika tidak ingin mengubah gambar)</label>
            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Gambar Produk">
            <input type="file" name="image" accept="image/*">
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>