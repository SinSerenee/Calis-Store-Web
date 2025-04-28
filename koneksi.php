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
