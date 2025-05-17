<?php
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

// Xử lý tải lên tài liệu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = trim($_POST['description']);
    
    // Kiểm tra file tải lên
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Kiểm tra phần mở rộng
        if (!in_array($file_ext, $allowed_ext)) {
            $error = 'Phần mở rộng file không được hỗ trợ. Chỉ chấp nhận: ' . implode(', ', $allowed_ext);
        } 
        // Kiểm tra kích thước (giới hạn 10MB)
        elseif ($file_size > 10 * 1024 * 1024) {
            $error = 'Kích thước file vượt quá giới hạn (10MB)';
        } else {
            // Tạo tên file duy nhất
            $new_file_name = uniqid() . '_' . $file_name;
            $upload_path = 'uploads/' . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Lưu thông tin file vào cơ sở dữ liệu
                $stmt = $conn->prepare("INSERT INTO files (filename, filepath, description, filesize, filetype, user_id, upload_date) 
                                        VALUES (:filename, :filepath, :description, :filesize, :filetype, :user_id, NOW())");
                $stmt->bindParam(':filename', $file_name);
                $stmt->bindParam(':filepath', $new_file_name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':filesize', $file_size);
                $stmt->bindParam(':filetype', $file_ext);
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    $message = 'Tài liệu đã được tải lên thành công!';
                } else {
                    $error = 'Đã xảy ra lỗi khi lưu thông tin tài liệu.';
                    // Xóa file nếu không lưu được vào cơ sở dữ liệu
                    unlink($upload_path);
                }
            } else {
                $error = 'Đã xảy ra lỗi khi tải file lên.';
            }
        }
    } else {
        $error = 'Vui lòng chọn một file để tải lên.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tải lên tài liệu | Hệ thống quản lý tài liệu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
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
                    <li class="nav-item"><a class="nav-link active" href="upload.php">Tải lên</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="profile.php">Hồ sơ</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Tải lên tài liệu</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file" class="form-label">Chọn tài liệu:</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                    <div class="form-text">Hỗ trợ: pdf, doc(x), xls(x), ppt(x), txt, zip, rar (tối đa 10MB)</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả tài liệu:</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Tải lên</button>
            </form>
        </div>
    </div>
</div>

<footer class="text-center text-muted py-4 mt-5 border-top bg-white">
    &copy; <?php echo date('Y'); ?> Hệ thống quản lý tài liệu
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
