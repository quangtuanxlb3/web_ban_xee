<?php
require_once '../config/connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 7;
$start = ($page - 1) * $itemsPerPage;

$sql = "SELECT * FROM sanpham LIMIT $itemsPerPage OFFSET $start";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
