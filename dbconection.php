<?php
// Koneksi ke database
$servername = "localhost";  // Server MySQL
$username = "root";         // Username MySQL (default: root)
$password = "";             // Password MySQL (kosongkan jika tidak ada)
$dbname = "u_wibu";         // Nama database

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>
