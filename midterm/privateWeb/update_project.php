<?php
include 'connect.php';

// Lấy dữ liệu POST
$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$link_text = $_POST['button_text'] ?? '';

// Kiểm tra dữ liệu đầu vào
if (empty($id) || empty($title) || empty($description) || empty($link_text)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết.']);
    exit;
}

$hasFile = isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK;
$uploadSuccess = true;
$targetFile = null;

if ($hasFile) {
    $targetDir = "uploads/";
    $originalName = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . uniqid() . '_' . $originalName; // tránh trùng tên
    $fileSize = $_FILES["file"]["size"];
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Giới hạn kích thước file (5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File quá lớn. Tối đa 5MB.']);
        exit;
    }

    // Upload file
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo json_encode(['success' => false, 'message' => 'Không thể tải lên file.']);
        exit;
    }
}

// Chuẩn bị câu lệnh SQL
if ($hasFile) {
    $sql = "UPDATE projects SET title = ?, description = ?, file_link = ?, link_text = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $targetFile, $link_text, $id);
} else {
    $sql = "UPDATE projects SET title = ?, description = ?, link_text = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $link_text, $id);
}

// Thực thi và trả kết quả
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
