<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    echo "<pre>";
    print_r($_FILES); // DEBUG info

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "✅ Upload berhasil! Gambar disimpan di $target_file";
    } else {
        echo "❌ Upload gagal!";
    }
    echo "</pre>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <button type="submit">Upload Gambar</button>
</form>
