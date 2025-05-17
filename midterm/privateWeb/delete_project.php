<?php
include 'connect.php';

$id = $_POST['id'];

$sql = "DELETE FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $response = ['success' => true];
} else {
    $response = ['success' => false, 'message' => $stmt->error];
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>