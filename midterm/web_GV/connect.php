<?php
// Thông tin kết nối
$host = 'localhost';
$dbname = 'document_management';
$username = 'root';
$password = '';

// Tạo kết nối
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Thiết lập chế độ lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Thiết lập phiên làm việc
session_start();
?>