<?php
require_once '../config/connect.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT * FROM sanpham WHERE type_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $type);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data); 