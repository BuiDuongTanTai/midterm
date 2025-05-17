<?php
include 'db_connect.php';

$title = $_POST['title'];
$authors = $_POST['authors'];
$journal = $_POST['journal'];

// File upload handling
$targetDir = "uploads/"; // Directory where you want to save the files
$targetFile = $targetDir . basename($_FILES["pdf"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["pdf"]["size"] > 5000000) { // 5MB
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($fileType != "pdf") {
    echo "Sorry, only PDF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFile)) {
        // File uploaded successfully, now save data to database
        $sql = "INSERT INTO publications (title, authors, journal, pdf_link) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $authors, $journal, $targetFile);

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