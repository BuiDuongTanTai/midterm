<?php
include 'connect.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu ID']);
    exit;
}

// Gỡ lỗi: kiểm tra ID và kết nối
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Lỗi kết nối: ' . mysqli_connect_error()]);
    exit;
}

$sql = "SELECT id, title, description, link, button_text FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);

// Gỡ lỗi: nếu prepare() bị lỗi
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Lỗi prepare: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($project);

$stmt->close();
$conn->close();
