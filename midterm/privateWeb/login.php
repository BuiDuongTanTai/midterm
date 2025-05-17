<?php
session_start();
require_once 'connect.php';

// Xử lý đăng nhập
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } else {
        // Chuẩn bị statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        if ($stmt === false) {
            die("Lỗi prepare: " . $conn->error);
        }

        // Gán tham số
        $stmt->bind_param("s", $username); // "s" là kiểu string

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['sdt'] = $user['username'];   
                header('Location: pri.php');
                exit();
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
            }
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
        }

        $stmt->close();
    }
}



?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Hệ thống quản lý thông tin cá nhân</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="login.php">
            <i class="bi bi-folder-fill me-2"></i> Profile
        </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" >Chào thầy Dzoãn Xuân Thanh</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="min-width: 400px;">
            <h2 class="text-center mb-4">Đăng nhập</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">SĐT:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

                <!-- <div class="text-center mt-3">
                    <p>Quên mật khẩu? <a href="register.php">Đăng ký</a></p>
                </div> -->
            </form>
        </div>
    </div>
    <footer class="text-center text-muted py-3 bg-white border-top">
        &copy; <?php echo date('Y'); ?> Hệ thống quản lý thông tin cá nhân
    </footer>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</body>

</html>