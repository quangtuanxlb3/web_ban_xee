<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /BloomWebsite/tai-khoan/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /BloomWebsite/thanh-toan');
    exit;
}

$user_id = $_SESSION['user_id'];

$shipping_name = $conn->real_escape_string($_POST['shipping_name']);
$shipping_phone = $conn->real_escape_string($_POST['shipping_phone']);
$shipping_address = $conn->real_escape_string($_POST['shipping_address']);
$payment_method = $conn->real_escape_string($_POST['payment_method']);
$note = isset($_POST['note']) ? $conn->real_escape_string($_POST['note']) : '';

if (empty($shipping_name) || empty($shipping_phone) || empty($shipping_address) || empty($payment_method)) {
    $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin!";
    header('Location: /BloomWebsite/thanh-toan');
    exit;
}

$cart_query = "SELECT ghct.*, sp.name, sp.price, sp.discount, sp.quantity as stock_quantity 
              FROM giohang_chitiet ghct 
              JOIN sanpham sp ON ghct.product_id = sp.id 
              WHERE ghct.cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)";
$cart_result = $conn->query($cart_query);

if ($cart_result->num_rows === 0) {
    $_SESSION['error_message'] = "Giỏ hàng của bạn đang trống!";
    header('Location: /BloomWebsite/gio-hang');
    exit;
}

$total_price = 0;
$cart_items = [];

while ($row = $cart_result->fetch_assoc()) {
    if ($row['quantity'] > $row['stock_quantity']) {
        $_SESSION['error_message'] = "Sản phẩm '{$row['name']}' chỉ còn {$row['stock_quantity']} trong kho!";
        
        header('Location: /BloomWebsite/thanh-toan');
        exit;
    }
    
    $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
    $row['item_price'] = $discounted_price;
    $row['item_total'] = $discounted_price * $row['quantity'];
    $total_price += $row['item_total'];
    $cart_items[] = $row;
}

$payment_status = ($payment_method === 'cod') ? 'unpaid' : 'pending';

try {
    $conn->begin_transaction();
    
    $order_query = "INSERT INTO donhang (user_id, total_price, shipping_fee, status, payment_method, payment_status, 
                   shipping_address, shipping_name, shipping_phone, note, created_at, updated_at) 
                   VALUES ($user_id, $total_price, 0, 'pending', '$payment_method', '$payment_status', 
                   '$shipping_address', '$shipping_name', '$shipping_phone', '$note', NOW(), NOW())";
    
    if (!$conn->query($order_query)) {
        throw new Exception("Không thể tạo đơn hàng: " . $conn->error);
    }
    
    $order_id = $conn->insert_id;
    
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $product_name = $conn->real_escape_string($item['name']);
        $quantity = $item['quantity'];
        $price = $item['price'];
        $discount = $item['discount'];
        
        $detail_query = "INSERT INTO donhang_chitiet (order_id, product_id, product_name, quantity, price, discount, created_at) 
                        VALUES ($order_id, $product_id, '$product_name', $quantity, $price, $discount, NOW())";
        
        if (!$conn->query($detail_query)) {
            throw new Exception("Không thể thêm chi tiết đơn hàng: " . $conn->error);
        }
        
        $new_stock = $item['stock_quantity'] - $quantity;
        $update_stock = "UPDATE sanpham SET quantity = $new_stock WHERE id = $product_id";
        
        if (!$conn->query($update_stock)) {
            throw new Exception("Không thể cập nhật số lượng tồn kho: " . $conn->error);
        }
    }
    
    $delete_cart = "DELETE FROM giohang_chitiet WHERE cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)";
    
    if (!$conn->query($delete_cart)) {
        throw new Exception("Không thể xóa giỏ hàng: " . $conn->error);
    }
    
    $conn->commit();
    
    $_SESSION['order_success'] = true;
    $_SESSION['order_id'] = $order_id;
    header('Location: /BloomWebsite/xac-nhan-don-hang');
    exit;
    
} catch (Exception $e) {
    $conn->rollback();
    
    $_SESSION['error_message'] = "Có lỗi xảy ra: " . $e->getMessage();
    header('Location: /BloomWebsite/thanh-toan');
    exit;
} 