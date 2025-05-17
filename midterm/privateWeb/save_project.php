<?php
include 'db_connect.php';

$title = $_POST['title'];
$description = $_POST['description'];
$link_text = $_POST['link_text'];

// File upload handling
$targetDir = "uploads/"; // Directory where you want to save the files
$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 5000000) { // 5MB
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        // File uploaded successfully, now save data to database
        $sql = "INSERT INTO projects (title, description, file_link, link_text) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $description, $targetFile, $link_text);

        if ($stmt->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => $stmt->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'Sorry, there was an error uploading your file.'];
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>