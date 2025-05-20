<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bắt đầu session
session_start();

// Include database connection
include 'connect.php';

// Kiểm tra nếu chưa đăng nhập hoặc thiếu ID
if (!isset($_SESSION['sdt'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin giảng viên']);
    exit;
}

// Lấy dữ liệu từ POST
$full_name       = $_POST['full_name'] ?? '';
$position      = $_POST['position'] ?? '';
$university     = $_POST['university'] ?? '';
$degree         = $_POST['degree'] ?? '';
$specialization  = $_POST['specialization'] ?? '';
$introduction   = $_POST['introduction'] ?? '';
$email          = $_POST['email'] ?? '';
$phone          = $_POST['phone'] ?? '';
$office         = $_POST['office'] ?? '';
$scholar_link        = $_POST['scholar'] ?? '';
$research_gate_link   = $_POST['researchgate'] ?? '';
$professor_id   = $_SESSION['professor_id'];

// Cập nhật bảng bios (giới thiệu)

// Xóa tất cả dữ liệu cũ
$sql_delete = "DELETE FROM bios"; // Hoặc sử dụng TRUNCATE TABLE bios
$conn->query($sql_delete);
// Chèn dữ liệu mới vào bảng bios
$sql_bio = "INSERT INTO bios (content) VALUES (?)
            ON DUPLICATE KEY UPDATE content = VALUES(content)";
$stmt_bio = $conn->prepare($sql_bio);

if ($stmt_bio === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare bios: ' . $conn->error]);
    exit;
}

$stmt_bio->bind_param("s", $introduction);

if (!$stmt_bio->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi execute bios: ' . $stmt_bio->error]);
    exit;
}

$stmt_bio->close();

// Chuẩn bị câu lệnh UPDATE
$sql = "UPDATE professors 
        SET full_name = ?, position = ?, university = ?, degree = ?, specialization = ?, email = ?, phone = ?, office = ?, scholar_link = ?, research_gate_link = ?, last_updated = NOW()
        WHERE id = ?";

$stmt = $conn->prepare($sql);

// Kiểm tra prepare
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare: ' . $conn->error]);
    exit;
}

// Gán giá trị và thực thi
$stmt->bind_param("ssssssssssi", $full_name, $position, $university, $degree, $specialization, $email, $phone, $office, $scholar_link, $research_gate_link, $professor_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi execute: ' . $stmt->error]);
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
