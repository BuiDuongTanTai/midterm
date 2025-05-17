<?php
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Lấy thông tin người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id LIMIT 1");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Xử lý cập nhật thông tin người dùng
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $old_password = $_POST['old_password']; // Mật khẩu cũ

    // Kiểm tra mật khẩu cũ
    if (!password_verify($old_password, $user['password'])) {
        $error = 'Mật khẩu cũ không chính xác.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error = 'Mật khẩu và xác nhận mật khẩu không khớp.';
    } else {
        // Mã hóa mật khẩu mới nếu có
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, full_name = :full_name, password = :password WHERE id = :user_id");
            $stmt->bindParam(':password', $hashed_password);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, full_name = :full_name WHERE id = :user_id");
        }

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            $success = 'Thông tin đã được cập nhật thành công.';
        } else {
            $error = 'Đã xảy ra lỗi khi cập nhật thông tin.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ người dùng | Hệ thống quản lý tài liệu</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <i class="bi bi-folder-fill me-2"></i> ShareDocs
    </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="files.php">Danh sách tài liệu</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="upload.php">Tải lên</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link active" href="profile.php">Hồ sơ</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="mb-4">
        <h2 class="text-center">Hồ sơ người dùng</h2>
        <p class="text-center text-muted">Chỉnh sửa thông tin cá nhân của bạn.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" class="card shadow-sm p-4">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Họ và tên:</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <!-- Mật khẩu cũ -->
        <div class="mb-3">
            <label for="old_password" class="form-label">Mật khẩu cũ:</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>

        <!-- Mật khẩu mới -->
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

<footer class="text-center text-muted py-3 bg-white border-top">
    &copy; <?php echo date('Y'); ?> Hệ thống quản lý tài liệu
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
