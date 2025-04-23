<?php
require_once '../config/connect.php';

$sql_products = "SELECT COUNT(*) as total FROM sanpham";
$result_products = $conn->query($sql_products);
$row_products = $result_products->fetch_assoc();
$totalProducts = $row_products['total'];

$today = date('Y-m-d');
$sql_today_orders = "SELECT COUNT(*) as total FROM donhang WHERE DATE(created_at) = '$today'";
$result_today_orders = $conn->query($sql_today_orders);
$row_today_orders = $result_today_orders->fetch_assoc();
$todayOrders = $row_today_orders['total'];

$firstDayOfMonth = date('Y-m-01');
$lastDayOfMonth = date('Y-m-t');
$sql_month_revenue = "SELECT SUM(total_price) as total FROM donhang WHERE created_at BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
$result_month_revenue = $conn->query($sql_month_revenue);
$row_month_revenue = $result_month_revenue->fetch_assoc();
$monthRevenue = $row_month_revenue['total'] ? $row_month_revenue['total'] : 0;

$sql_new_customers = "SELECT COUNT(*) as total FROM nguoidung WHERE role = 'user' AND created_at BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
$result_new_customers = $conn->query($sql_new_customers);
$row_new_customers = $result_new_customers->fetch_assoc();
$newCustomers = $row_new_customers['total'];

$sql_recent_orders = "SELECT d.id, d.total_price, d.status, d.created_at, n.name as userName, s.name as productName 
                      FROM donhang d
                      JOIN nguoidung n ON d.user_id = n.id
                      JOIN donhang_chitiet dc ON d.id = dc.order_id
                      JOIN sanpham s ON dc.product_id = s.id
                      ORDER BY d.created_at DESC
                      LIMIT 5";
$result_recent_orders = $conn->query($sql_recent_orders);
$recentOrders = [];

while ($row = $result_recent_orders->fetch_assoc()) {
    $recentOrders[] = [
        'id' => $row['id'],
        'userName' => $row['userName'],
        'productName' => $row['productName'],
        'total' => $row['total_price'],
        'status' => $row['status'],
        'date' => date('d/m/Y', strtotime($row['created_at']))
    ];
}

$stats = [
    'totalProducts' => $totalProducts,
    'todayOrders' => $todayOrders,
    'monthRevenue' => $monthRevenue,
    'newCustomers' => $newCustomers,
    'recentOrders' => $recentOrders
];

header('Content-Type: application/json');
echo json_encode($stats); 