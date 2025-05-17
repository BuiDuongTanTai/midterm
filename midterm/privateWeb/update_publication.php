<?php
include 'db_connect.php';

$id = $_POST['id'];
$title = $_POST['title'];
$authors = $_POST['authors'];
$journal = $_POST['journal'];

// File upload handling (if a new file is uploaded)
if ($_FILES["pdf"]["name"] != "") {
    $targetDir = "uploads/";
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
            // Update the database with the new file
            $sql = "UPDATE publications SET title=?, authors=?, journal=?, pdf_link=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $title, $authors, $journal, $targetFile, $id);
        } else {
            $response = ['success' => false, 'message' => 'Sorry, there was an error uploading your file.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
} else {
    // No new file uploaded, update other fields
    $sql = "UPDATE publications SET title=?, authors=?, journal=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $authors, $journal, $id);
}

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