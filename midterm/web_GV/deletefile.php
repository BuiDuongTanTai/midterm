<?php
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: files.php');
    exit();
}

$id = (int)$_GET['id'];

// Lấy đường dẫn file để xóa trên server
$stmt = $conn->prepare("SELECT filepath FROM files WHERE id = :id");
$stmt->execute([':id' => $id]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if ($file) {
    $filePath = 'uploads/' . $file['filepath'];
    
    // Xoá bản ghi trong database
    $stmt = $conn->prepare("DELETE FROM files WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // Xoá file trên server (nếu tồn tại)
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    header('Location: files.php?msg=deleted');
    exit();
} else {
    header('Location: files.php?msg=notfound');
    exit();
}
