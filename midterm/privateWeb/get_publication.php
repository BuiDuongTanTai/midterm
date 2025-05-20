<?php
include 'db_connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM publications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$publication = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($publication);

$stmt->close();
$conn->close();
?>