<?php
require_once '../config/connect.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT * FROM nguoidung WHERE name LIKE ? OR username LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data); 