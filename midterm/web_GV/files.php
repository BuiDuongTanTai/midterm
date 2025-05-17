<?php
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Tìm kiếm tài liệu
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
$params = [];

if (!empty($search)) {
    $searchCondition = "WHERE filename LIKE :search OR description LIKE :search";
    $params[':search'] = "%$search%";
}

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // số tài liệu trên mỗi trang
$offset = ($page - 1) * $limit;

// Đếm tổng số tài liệu
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM files $searchCondition");
if (!empty($params)) {
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
}
$stmt->execute();
$totalFiles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalFiles / $limit);

// Lấy danh sách tài liệu
$stmt = $conn->prepare("SELECT f.*, u.username 
                        FROM files f 
                        JOIN users u ON f.user_id = u.id 
                        $searchCondition 
                        ORDER BY f.upload_date DESC 
                        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

if (!empty($params)) {
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
}

$stmt->execute();
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách tài liệu | Hệ thống quản lý tài liệu</title>
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
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link active" href="files.php">Danh sách tài liệu</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="upload.php">Tải lên</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="profile.php">Hồ sơ</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">Đã xoá tài liệu thành công.</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'notfound'): ?>
        <div class="alert alert-warning">Không tìm thấy tài liệu để xoá.</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Danh sách tài liệu</h2>
        <form class="d-flex" method="GET" action="files.php">
            <input type="text" class="form-control me-2" name="search" placeholder="Tìm kiếm tài liệu..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-outline-secondary">Tìm</button>
        </form>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tên tài liệu</th>
                    <th>Mô tả</th>
                    <th>Người tải lên</th>
                    <th>Ngày tải lên</th>
                    <th>Kích thước</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file['filename']); ?></td>
                    <td><?php echo htmlspecialchars($file['description']); ?></td>
                    <td><?php echo htmlspecialchars($file['username']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($file['upload_date'])); ?></td>
                    <td><?php echo round($file['filesize'] / 1024, 2); ?> KB</td>
                    <td>
                        <a href="download.php?id=<?php echo $file['id']; ?>" class="btn btn-sm btn-primary">Tải xuống</a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="deletefile.php?id=<?php echo $file['id']; ?>" 
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xoá tài liệu này không?');">
                                Xóa
                            </a>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; ?>
                <?php if (empty($files)): ?>
                <tr>
                    <td colspan="6" class="text-center">Không tìm thấy tài liệu nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">&laquo; Trước</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Tiếp &raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>


<footer class="text-center text-muted py-3 bg-white border-top">
    &copy; <?php echo date('Y'); ?> Hệ thống quản lý tài liệu
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
