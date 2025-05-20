<?php
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra ID tài liệu
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: files.php');
    exit();
}

$file_id = (int)$_GET['id'];

// Lấy thông tin file
$stmt = $conn->prepare("SELECT * FROM files WHERE id = :id");
$stmt->bindParam(':id', $file_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    $file_path = 'uploads/' . $file['filepath'];
    
    if (file_exists($file_path)) {
        // Cập nhật số lượt tải xuống
        $stmt = $conn->prepare("UPDATE files SET downloads = downloads + 1 WHERE id = :id");
        $stmt->bindParam(':id', $file_id);
        $stmt->execute();
        
        // Thiết lập header cho việc tải xuống
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file['filename'] . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Đọc và xuất file
        readfile($file_path);
        exit();
    } else {
        // File không tồn tại trong hệ thống
        $_SESSION['error'] = 'Tài liệu không tồn tại trên hệ thống.';
        header('Location: files.php');
        exit();
    }
} else {
    // ID không hợp lệ
    $_SESSION['error'] = 'Tài liệu không tồn tại.';
    header('Location: files.php');
    exit();
}
?>