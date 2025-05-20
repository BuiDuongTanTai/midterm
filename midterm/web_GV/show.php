<?php
require 'connect.php';

$stmt = $pdo->query("SELECT * FROM files ORDER BY upload_date DESC");
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($files);
