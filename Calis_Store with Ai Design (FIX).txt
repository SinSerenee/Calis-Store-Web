Calis_Store with Ai Design (FIX)

Alur, Fitur, Password DLL :

Fitur-Fitur Lainnya yang Tersedia:
1.	Pengelolaan Produk oleh Admin:
o	Tambah, Edit, dan Hapus Produk: Admin dapat menambah, mengedit, dan menghapus produk dalam database.
o	Kelola Gambar Produk: Admin dapat mengunggah gambar produk saat menambah atau mengedit produk.
2.	Pengelolaan Pengguna:
o	Admin dapat mengelola akun pengguna, termasuk memverifikasi, menghapus, atau memperbarui informasi pengguna.
3.	Pengelolaan Stok:
o	Admin dapat memperbarui jumlah stok produk yang tersedia di sistem.
4.	Pembelian Produk oleh User:
o	Pengguna dapat membeli produk yang tersedia dengan harga yang lebih murah jika mereka adalah member, berkat diskon yang diterima.
o	Pengguna juga bisa membeli berapa produknya
5.	Riwayat Pembelian User dan Member
o	Pengguna dan member dapat melihat Riwayat Pembelian barang yang telah mereka beli
6.	Keamanan Data Pengguna:
o	Setiap data pengguna, produk, dan transaksi dijaga keamanannya untuk mencegah potensi akses tidak sah dan serangan.



________________________________________
Alur Pengguna (User Flow):
1.	Login: Pengguna, member atau admin akan masuk ke sistem menggunakan username dan password yang sudah disediakan.

Untuk Admin:
o	Username: admin1
o	Password: 123
Untuk Member:
o	Username: member2
o	Password: 234
Untuk User:
o	Username: user3
o	Password: 345

2.	Dashboard:
o	Setelah login, pengguna akan diarahkan ke dashboard mereka.
o	Pengguna biasa akan melihat produk biasa sedangkan untuk member ada diskon khusus 10%, dan admin akan memiliki akses untuk mengelola produk.

3.	Melihat Produk dan Pembelian: 
Pengguna dapat melihat produk yang tersedia dan membeli produk jika produk tersebut tersedia dalam stok.

4.	Logout:
Pengguna atau admin dapat keluar dari sistem dengan mengklik tombol logout, yang akan menghancurkan sesi mereka dan mengarahkan mereka kembali ke halaman login.
1. admin_dashboard.php
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

2. admin_purchases.php
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "INSERT INTO products (name, description, price, stock) VALUES ('$name', '$description', '$price', '$stock')";
    $koneksi->query($query);
    header("Location: admin_dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            margin: 20px 0;
        }
        input, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
        }
        button:hover {
            background: #218838;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <a href="logout.php">Logout</a>
        <h3>Tambah Produk</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Produk" required>
            <textarea name="description" placeholder="Deskripsi Produk" required></textarea>
            <input type="number" name="price" placeholder="Harga Produk" required>
            <input type="number" name="stock" placeholder="Stok Produk" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="add_product">Tambah Produk</button>
        </form>
    </div>
</body>
</html>
3. edit_product.php
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

4. koneksi.php
<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'calisthenics_store';

$koneksi = new mysqli($host, $user, $pass, $db_name);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>

5. login.php
<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] == 'member') {
            header("Location: member_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
    } else {
        $error = "Login Anda Salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

6. logout.php
<?php
session_start();
session_destroy();
header("Location: login.php");
?>

7. member_dashboard.php
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$query = "SELECT * FROM products";
$result = $koneksi->query($query);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Member";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calisthenics Store - Member</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
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
            background-color: #007bff;
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
        .discount {
            color: green;
            font-weight: bold;
        }
        .action-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .buy-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .buy-button:hover {
            background-color: #218838;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
        input[type="number"] {
            width: 70px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Calisthenics Store</h1>
        <p class="welcome">Selamat datang, <?= htmlspecialchars($username) ?>! Anda mendapatkan diskon 10% untuk setiap produk.</p>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <h2>Daftar Produk</h2>
        <a href="purchase_history2.php" style="display: inline-block; margin: 10px 0; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">Lihat Riwayat Pembelian</a>
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga Normal</th>
                    <th>Harga Member</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <?php $discount_price = $row['price'] * 0.9; ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                    <td class="discount">Rp <?= number_format($discount_price, 2, ',', '.') ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <?php if ($row['stock'] > 0): ?>
                            <form action="purchase2.php" method="POST">
                                <div class="action-container">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <input type="number" name="quantity" min="1" max="<?= $row['stock'] ?>" placeholder="Jumlah" required>
                                    <button type="submit" class="buy-button">Beli</button>
                                </div>
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

8. purchase.php
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    if ($quantity <= 0) {
        echo "<script>alert('Jumlah pembelian harus lebih dari 0!'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($product['stock'] >= $quantity) {
            $new_stock = $product['stock'] - $quantity;
            $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
            $koneksi->query($update_query);

            $total_price = $product['price'] * $quantity;

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

9. purchase2.php
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

    if ($quantity <= 0) {
        echo "<script>alert('Jumlah harus lebih dari 0!'); window.location.href = 'member_dashboard.php';</script>";
        exit;
    }

    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($product['stock'] >= $quantity) {
            $new_stock = $product['stock'] - $quantity;
            $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
            $koneksi->query($update_query);

            $total_price = $product['price'] * $quantity * 0.9;
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

10. user_dashboard.php
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

11. purchase_history.php
<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}
include('koneksi.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT ph.purchase_date, p.name, p.description, ph.quantity, ph.total_price
          FROM purchase_history ph
          JOIN products p ON ph.product_id = p.id
          WHERE ph.user_id = '$user_id'
          ORDER BY ph.purchase_date DESC";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
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
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a.back-link {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.back-link:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Riwayat Pembelian</h1>
        <a href="user_dashboard.php" class="back-link">Kembali ke Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['purchase_date']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>Rp <?= number_format($row['total_price'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Belum ada riwayat pembelian.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

12. purchase_history2.php
<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}
include('koneksi.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT ph.purchase_date, p.name, p.description, ph.quantity, ph.total_price
          FROM purchase_history ph
          JOIN products p ON ph.product_id = p.id
          WHERE ph.user_id = '$user_id'
          ORDER BY ph.purchase_date DESC";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
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
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a.back-link {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.back-link:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Riwayat Pembelian</h1>
        <a href="member_dashboard.php" class="back-link">Kembali ke Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['purchase_date']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>Rp <?= number_format($row['total_price'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Belum ada riwayat pembelian.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>






