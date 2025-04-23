<?php
require_once '../config/connect.php';

$sql = "SELECT * FROM nguoidung";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data); 