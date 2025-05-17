<?php
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Đếm số tài liệu
$stmt = $conn->query("SELECT COUNT(*) as total FROM files");
$totalFiles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Đếm tài liệu mới trong 7 ngày qua
$stmt = $conn->query("SELECT COUNT(*) as new_files FROM files WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$newFiles = $stmt->fetch(PDO::FETCH_ASSOC)['new_files'];

// Lấy 5 tài liệu mới nhất
$stmt = $conn->query("SELECT * FROM files ORDER BY upload_date DESC LIMIT 5");
$recentFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điều khiển | Hệ thống quản lý tài liệu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <li class="nav-item"><a class="nav-link active" href="dashboard.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="files.php">Danh sách tài liệu</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="upload.php">Tải lên</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="profile.php">Hồ sơ</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="mb-4">
        <h2 class="text-center">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-center text-muted">Chào mừng đến với hệ thống quản lý tài liệu.</p>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tổng số tài liệu</h5>
                    <p class="display-6"><?php echo $totalFiles; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tài liệu mới (7 ngày)</h5>
                    <p class="display-6"><?php echo $newFiles; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tài liệu mới nhất</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên tài liệu</th>
                            <th>Ngày tải lên</th>
                            <th>Kích thước</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentFiles): ?>
                            <?php foreach ($recentFiles as $file): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($file['filename']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($file['upload_date'])); ?></td>
                                    <td><?php echo round($file['filesize'] / 1024, 2); ?> KB</td>
                                    <td>
                                        <a href="download.php?id=<?php echo $file['id']; ?>" class="btn btn-sm btn-outline-primary">Tải xuống</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Không có tài liệu nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="files.php" class="btn btn-primary btn-sm">Xem tất cả tài liệu</a>
        </div>
    </div>
</div>

<footer class="text-center text-muted py-3 bg-white border-top">
    &copy; <?php echo date('Y'); ?> Hệ thống quản lý tài liệu
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>