<?php
require_once 'connect.php';

$username = 'admin';
$newPassword = 'admin123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
$stmt->execute([
    ':password' => $hashedPassword,
    ':username' => $username
]);

echo "Đã cập nhật mật khẩu cho tài khoản admin.";
?>
