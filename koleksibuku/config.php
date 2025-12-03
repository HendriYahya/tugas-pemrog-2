<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

$host = 'localhost';
$user = 'root';        // ganti jika user MySQL berbeda
$pass = '';            // isi password MySQL kamu
$db   = 'koleksi_buku';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Koneksi gagal ' . $conn->connect_error);
}
?>
