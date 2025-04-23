<?php
require_once '../config/connect.php';

$sql = "SELECT COUNT(*) AS total FROM sanpham";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode(['total' => $row['total']]);
