<?php
session_start();
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['sdt'])) {
    header('Location: login.php');
    exit();
}

include 'get_data.php';

  













// // Xử lý form thêm thông tin cá nhân
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_info') {
//     $fullname = $_POST['fullname'];
//     $email = $_POST['email'];
//     $phone = $_POST['phone'];
//     $address = $_POST['address'];
//     $notes = $_POST['notes'];
    
//     $sql = "INSERT INTO personal_info (fullname, email, phone, address, notes) 
//             VALUES ('$fullname', '$email', '$phone', '$address', '$notes')";
    
//     if ($conn->query($sql) === TRUE) {
//         echo "<div class='alert alert-success'>Thông tin cá nhân đã được lưu thành công!</div>";
//     } else {
//         echo "<div class='alert alert-danger'>Lỗi: " . $sql . "<br>" . $conn->error . "</div>";
//     }
// }

// // Xử lý form thêm hình ảnh
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_image') {
//     $title = $_POST['image_title'];
//     $description = $_POST['image_description'];
    
//     // Xử lý file upload
//     $target_dir = "uploads/";
    
//     // Tạo thư mục uploads nếu chưa tồn tại
//     if (!file_exists($target_dir)) {
//         mkdir($target_dir, 0777, true);
//     }
    
//     $target_file = $target_dir . basename($_FILES["image_file"]["name"]);
//     $uploadOk = 1;
//     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
//     // Kiểm tra xem file có phải là ảnh thật không
//     $check = getimagesize($_FILES["image_file"]["tmp_name"]);
//     if($check === false) {
//         echo "<div class='alert alert-danger'>File không phải là hình ảnh.</div>";
//         $uploadOk = 0;
//     }
    
//     // Kiểm tra kích thước file
//     if ($_FILES["image_file"]["size"] > 5000000) { // 5MB
//         echo "<div class='alert alert-danger'>Xin lỗi, file của bạn quá lớn.</div>";
//         $uploadOk = 0;
//     }
    
//     // Cho phép các định dạng file cụ thể
//     if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
//         echo "<div class='alert alert-danger'>Xin lỗi, chỉ chấp nhận file JPG, JPEG, PNG & GIF.</div>";
//         $uploadOk = 0;
//     }
    
//     // Kiểm tra nếu $uploadOk = 0
//     if ($uploadOk == 0) {
//         echo "<div class='alert alert-danger'>Xin lỗi, file của bạn không được tải lên.</div>";
//     } else {
//         if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
//             $filename = basename($_FILES["image_file"]["name"]);
//             $filepath = $target_file;
            
//             $sql = "INSERT INTO images (title, description, filename, filepath) 
//                     VALUES ('$title', '$description', '$filename', '$filepath')";
            
//             if ($conn->query($sql) === TRUE) {
//                 echo "<div class='alert alert-success'>Hình ảnh đã được lưu thành công!</div>";
//             } else {
//                 echo "<div class='alert alert-danger'>Lỗi: " . $sql . "<br>" . $conn->error . "</div>";
//             }
//         } else {
//             echo "<div class='alert alert-danger'>Có lỗi xảy ra khi tải lên file của bạn.</div>";
//         }
//     }
// }

// // Xử lý form thêm liên kết bài báo
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_article') {
//     $title = $_POST['article_title'];
//     $url = $_POST['article_url'];
//     $description = $_POST['article_description'];
//     $tags = $_POST['article_tags'];
    
//     $sql = "INSERT INTO article_links (title, url, description, tags) 
//             VALUES ('$title', '$url', '$description', '$tags')";
    
//     if ($conn->query($sql) === TRUE) {
//         echo "<div class='alert alert-success'>Liên kết bài báo đã được lưu thành công!</div>";
//     } else {
//         echo "<div class='alert alert-danger'>Lỗi: " . $sql . "<br>" . $conn->error . "</div>";
//     }
// }

// // Xóa thông tin cá nhân
// if (isset($_GET['delete_info'])) {
//     $id = $_GET['delete_info'];
//     $sql = "DELETE FROM personal_info WHERE id=$id";
    
//     if ($conn->query($sql) === TRUE) {
//         echo "<div class='alert alert-success'>Thông tin cá nhân đã được xóa thành công!</div>";
//     } else {
//         echo "<div class='alert alert-danger'>Lỗi khi xóa thông tin: " . $conn->error . "</div>";
//     }
// }

// // Xóa hình ảnh
// if (isset($_GET['delete_image'])) {
//     $id = $_GET['delete_image'];
    
//     // Lấy thông tin file để xóa
//     $sql = "SELECT filepath FROM images WHERE id=$id";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $filepath = $row['filepath'];
        
//         // Xóa file nếu tồn tại
//         if (file_exists($filepath)) {
//             unlink($filepath);
//         }
//     }
    
//     $sql = "DELETE FROM images WHERE id=$id";
//     if ($conn->query($sql) === TRUE) {
//         echo "<div class='alert alert-success'>Hình ảnh đã được xóa thành công!</div>";
//     } else {
//         echo "<div class='alert alert-danger'>Lỗi khi xóa hình ảnh: " . $conn->error . "</div>";
//     }
// }

// // Xóa liên kết bài báo
// if (isset($_GET['delete_article'])) {
//     $id = $_GET['delete_article'];
//     $sql = "DELETE FROM article_links WHERE id=$id";
    
//     if ($conn->query($sql) === TRUE) {
//         echo "<div class='alert alert-success'>Liên kết bài báo đã được xóa thành công!</div>";
//     } else {
//         echo "<div class='alert alert-danger'>Lỗi khi xóa liên kết: " . $conn->error . "</div>";
//     }
// }


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - Hồ sơ Giảng viên</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary border-bottom mb-4 sticky-top">
        <div class="container">
            <!-- Brand / Title -->
            <a class="navbar-brand h4 mb-0" href="pri.php">Hồ sơ</a>

            <!-- Toggle button responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a href="#personal-info" class="nav-link active" data-tab="personal-info">Thông tin cá nhân</a>
                    </li>
                    <li class="nav-item">
                    <a href="#publications" class="nav-link" data-tab="publications">Thành tựu cá nhân</a>
                    </li>
                    <li class="nav-item">
                    <a href="#projects" class="nav-link" data-tab="projects">Dự án nghiên cứu</a>
                    </li>
                    <li class="nav-item">
                    <a href="#schedule" class="nav-link" data-tab="schedule">Lịch dạy & Làm việc</a>
                    </li>
                    <li class="nav-item">
                    <a href="#account" class="nav-link" data-tab="account">Cài đặt tài khoản</a>
                    </li>
                </ul>

                <!-- Nút đăng xuất canh phải -->
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>


    <main class="container">
        <div id="alert-box" class="alert alert-success d-none" role="alert">
            Thông tin đã được cập nhật thành công!
        </div>

        <div class="admin-layout d-flex">
            <div class="content-area flex-grow-1">
                <!-- Thông tin cá nhân -->
                <section id="personal-info" class="tab-content">
                    <h2 class="h5 mb-4">Thông tin cá nhân</h2>

                    <div class="mb-4">
                        <label for="introduction" class="form-label">Giới thiệu</label>
                        <textarea id="introduction" class="form-control" rows="10" style="resize: vertical;"><?= htmlspecialchars(implode("\n\n", $professor['bio'])) ?></textarea>
                    </div>

                    <h3 class="h6 mb-3">Thông tin liên hệ</h3>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" id="full_name" class="form-control" value="<?php echo htmlspecialchars($professor['full_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label">Chức vụ</label>
                            <input type="text" id="position" class="form-control" value="<?php echo htmlspecialchars($professor['position']); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="university" class="form-label">Trường</label>
                            <input type="text" id="university" class="form-control" value="<?php echo htmlspecialchars($professor['university']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="degree" class="form-label">Học vị</label>
                            <input type="text" id="degree" class="form-control" value="<?php echo htmlspecialchars($professor['degree']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="specialization" class="form-label">Chuyên ngành</label>
                        <input type="text" id="specialization" class="form-control" value="<?php echo htmlspecialchars($professor['specialization']); ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($professor['email']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" id="phone" class="form-control" value="<?php echo htmlspecialchars($professor['phone']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="office" class="form-label">Văn phòng</label>
                        <input type="text" id="office" class="form-control" value="<?php echo htmlspecialchars($professor['office']); ?>">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="scholar" class="form-label">Google Scholar</label>
                            <input type="url" id="scholar" class="form-control" value="<?php echo htmlspecialchars($professor['scholar_link']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="researchgate" class="form-label">ResearchGate</label>
                            <input type="url" id="researchgate" class="form-control" value="<?php echo htmlspecialchars($professor['research_gate_link']); ?>">
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary" id="savePersonalInfo">Lưu thông tin</button>
                    </div>
                </section>

                <!-- Bài báo khoa học -->
                <section id="publications" class="tab-content">
                    <h2 class="section-title">Bài báo khoa học</h2>
                    <div id="publication-list">
                        <?php foreach ($professor['publications'] as $index => $publication) : ?>
                            <div class="card <?php echo $index < 3 ? '' : 'd-none'; ?>" data-id="<?php echo htmlspecialchars($publication['id']); ?>">
                                <div class="card-header">
                                    <h3><?php echo htmlspecialchars($publication['title']); ?></h3>
                                    <div class="card-actions">
                                        <button class="btn btn-sm btn-primary edit-pub">Sửa</button>
                                        <button class="btn btn-sm btn-danger delete-pub">Xóa</button>
                                    </div>
                                </div>
                                <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($publication['authors']); ?></p>
                                <p><strong>Tạp chí:</strong> <?php echo htmlspecialchars($publication['journal']); ?></p>
                                <p><strong>Link PDF:</strong> <a href="<?php echo htmlspecialchars($publication['link']); ?>">Xem PDF</a></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="btn btn-primary" id="showMore">Hiển thị thêm bài báo</button>

                    <button class="btn btn-primary" id="addPublication">Thêm bài báo mới</button>

                    <!-- Modal Thêm/Sửa Bài Báo -->
                    <div class="modal fade" id="publicationModal" tabindex="-1" aria-labelledby="pubModalTitle" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pubModalTitle">Thêm bài báo mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                            <label for="pub-title" class="form-label">Tiêu đề</label>
                            <input type="text" id="pub-title" class="form-control">
                            </div>
                            <div class="mb-3">
                            <label for="pub-authors" class="form-label">Tác giả</label>
                            <input type="text" id="pub-authors" class="form-control" placeholder="Ngăn cách bằng dấu phẩy">
                            </div>
                            <div class="mb-3">
                            <label for="pub-journal" class="form-label">Tạp chí/Hội nghị</label>
                            <input type="text" id="pub-journal" class="form-control">
                            </div>
                            <div class="mb-3">
                            <label for="pub-pdf" class="form-label">File PDF</label>
                            <input type="file" id="pub-pdf" class="form-control" accept="application/pdf">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" id="savePublication">Lưu bài báo</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </section>

                <!-- Dự án nghiên cứu -->
                <section id="projects" class="tab-content">
                    <h2 class="section-title">Dự án nghiên cứu</h2>
                    <div id="project-list">
                        <?php foreach ($professor['projects'] as $index => $project) : ?>
                            <div class="card <?php echo $index < 3 ? '' : 'd-none'; ?>" data-id="<?php echo htmlspecialchars($project['id']); ?>">
                                <div class="card-header">
                                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                    <div class="card-actions">
                                        <button class="btn btn-sm btn-primary edit-proj">Sửa</button>
                                        <button class="btn btn-sm btn-danger delete-proj">Xóa</button>
                                    </div>
                                </div>
                                <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($project['description']); ?></p>
                                <p><strong>Link:</strong> <a href="<?php echo htmlspecialchars($project['link']); ?>"><?php echo htmlspecialchars($project['button_text']); ?></a></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="btn btn-primary" id="showMoreProjects">Hiển thị thêm dự án</button>
                    <button class="btn btn-primary" id="addProject">Thêm dự án mới</button>

                    <!-- Modal Thêm/Sửa Dự án -->
                    <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projModalTitle" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="projModalTitle">Thêm dự án mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="proj-title" class="form-label">Tên dự án</label>
                                        <input type="text" id="proj-title" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="proj-desc" class="form-label">Mô tả</label>
                                        <textarea id="proj-desc" class="form-control"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="proj-file" class="form-label">File đính kèm</label>
                                        <input type="file" id="proj-file" class="form-control">
                                        <div id="existing-file" class="mt-2 text-muted"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="proj-link-text" class="form-label">Mô tả iên kết</label>
                                        <input type="text" id="proj-link-text" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary" id="saveProject">Lưu dự án</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Lịch dạy & Giờ làm việc -->
                <section id="schedule" class="tab-content">
                    <h2 class="section-title">Lịch dạy và giờ làm việc</h2>

                    <div class="form-group">
                        <label>Chọn loại hoạt động</label>
                        <div class="checkbox-container">
                            <div class="checkbox-item">
                                <input type="checkbox" id="class-filter" checked>
                                <label for="class-filter">Lớp học</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="office-filter" checked>
                                <label for="office-filter">Giờ làm việc</label>
                            </div>
                        </div>
                    </div>

                    <div class="schedule-container">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Thứ 2</th>
                                    <th>Thứ 3</th>
                                    <th>Thứ 4</th>
                                    <th>Thứ 5</th>
                                    <th>Thứ 6</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>7:30 - 9:30</td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS101, Phòng A303">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS101, Phòng A303">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                </tr>

                                <tr>
                                    <td>9:45 - 11:45</td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS305, Phòng B401">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS305, Phòng B401">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                </tr>

                                <tr>
                                    <td>13:00 - 15:00</td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office" selected>Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="Phòng 305">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office" selected>Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="Phòng 305">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS501, Phòng C202">
                                    </td>
                                </tr>

                                <tr>
                                    <td>15:15 - 17:15</td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office" selected>Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="Phòng 305">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="" selected>Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class">Lớp học</option>
                                            <option value="office" selected>Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="Phòng 305">
                                    </td>
                                    <td>
                                        <select class="form-control schedule-type">
                                            <option value="">Trống</option>
                                            <option value="class" selected>Lớp học</option>
                                            <option value="office">Giờ làm việc</option>
                                        </select>
                                        <input type="text" class="form-control schedule-details" placeholder="Chi tiết" value="CS501, Phòng C202">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button class="btn btn-primary" id="saveSchedule">Lưu lịch</button>
                </section>

                <!-- Cài đặt tài khoản -->
                <section id="account" class="tab-content">
                    <h2 class="section-title">Cài đặt tài khoản</h2>

                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" class="form-control" value="nguyenvanan" disabled>
                    </div>

                    <div class="form-group">
                        <label for="current-password">Mật khẩu hiện tại</label>
                        <input type="password" id="current-password" class="form-control">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="new-password">Mật khẩu mới</label>
                            <input type="password" id="new-password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirm-password" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Cài đặt riêng tư</label>
                        <div class="checkbox-container">
                            <div class="checkbox-item">
                                <input type="checkbox" id="show-email" checked>
                                <label for="show-email">Hiển thị email</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="show-phone" checked>
                                <label for="show-phone">Hiển thị số điện thoại</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="show-schedule" checked>
                                <label for="show-schedule">Hiển thị lịch làm việc</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="saveAccount">Lưu thay đổi</button>
                </section>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="container">
            <p>&copy; 2024 Quản trị Hồ sơ Giảng viên</p>
        </div>
    </footer>

    <div class="modal-overlay hidden" id="modal-overlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>















































