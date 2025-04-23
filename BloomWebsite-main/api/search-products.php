<?php
require_once '../config/connect.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT * FROM sanpham WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data); 