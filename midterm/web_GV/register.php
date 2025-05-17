<?php
require_once 'connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $full_name  = trim($_POST['full_name']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $role       = 'user';

    // Kiểm tra rỗng
    if (empty($username) || empty($email) || empty($full_name) || empty($password) || empty($confirm) || empty($role)) {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    }
    // Kiểm tra định dạng email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    }
    // Kiểm tra độ dài mật khẩu
    elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    }
    // Kiểm tra mật khẩu khớp
    elseif ($password !== $confirm) {
        $error = 'Mật khẩu xác nhận không khớp.';
    }
    else {
        // Kiểm tra tài khoản đã tồn tại
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = 'Tên đăng nhập hoặc email đã được sử dụng.';
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) 
                                    VALUES (:username, :password, :email, :full_name, :role)");
            $stmt->execute([
                ':username'   => $username,
                ':password'   => $hashed_password,
                ':email'      => $email,
                ':full_name'  => $full_name,
                ':role'       => $role
            ]);

            $success = 'Đăng ký thành công! Bạn có thể <a href="login.php">đăng nhập tại đây</a>.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký | Hệ thống quản lý tài liệu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="login.php">
        <i class="bi bi-folder-fill me-2"></i> ShareDocs
    </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="register.php">Đăng ký</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Đăng ký tài khoản</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên:</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                    </form>

                    <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center text-muted py-3 bg-white border-top">
    &copy; <?php echo date('Y'); ?> Hệ thống quản lý tài liệu
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
