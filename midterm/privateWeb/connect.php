<?php
// Thông tin kết nối
$servername  = 'localhost';
$dbname = "DzoanXuanThanh";
$username = 'root';
$password = '';

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>