<?php
include 'db_connect.php';

// Get JSON data from the request
$jsonData = file_get_contents('php://input');
$scheduleData = json_decode($jsonData, true);

// Assuming you have a professor_id in session
session_start();
$professor_id = $_SESSION['professor_id']; // Replace with your actual session variable

// Delete existing schedule entries for the professor
$sqlDelete = "DELETE FROM schedules WHERE professor_id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $professor_id);
$stmtDelete->execute();
$stmtDelete->close();

// Prepare SQL statement for inserting schedule data
$sql = "INSERT INTO schedules (professor_id, time_slot, weekday, type, details) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $professor_id, $time_slot, $weekday, $type, $details);

// Loop through schedule data and insert into the database
foreach ($scheduleData as $row) {
    $time_slot = $row['time'];
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    foreach ($days as $day) {
        if ($row[$day]['type'] != "") {
            $weekday = $day;
            $type = $row[$day]['type'];
            $details = $row[$day]['details'];

            if ($stmt->execute()) {
                // Success
            } else {
                $response = ['success' => false, 'message' => $stmt->error];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }
    }
}

$response = ['success' => true];
header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>