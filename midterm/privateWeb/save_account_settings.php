<?php
include 'db_connect.php';

// Get data from POST request
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$showEmail = isset($_POST['showEmail']) ? 1 : 0;
$showPhone = isset($_POST['showPhone']) ? 1 : 0;
$showSchedule = isset($_POST['showSchedule']) ? 1 : 0;

// Assuming you have a user_id in session
session_start();
$user_id = $_SESSION['user_id']; // Replace with your actual session variable

// Verify current password
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (password_verify($currentPassword, $user['password'])) {
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare and execute SQL query to update account settings
    $sql = "UPDATE users SET password=?, show_email=?, show_phone=?, show_schedule=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiii", $hashedPassword, $showEmail, $showPhone, $showSchedule, $user_id);

    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => $stmt->error];
    }
} else {
    $response = ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng.'];
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>