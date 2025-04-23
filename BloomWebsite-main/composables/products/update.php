<?php
session_start();
require_once '../../config/connect.php';

// Kiểm tra phương thức request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $product_id = $_POST['product_id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $price = $_POST['price'] ?? 0;
    $discount = $_POST['discount'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $description = $_POST['description'] ?? '';
    $response = ['success' => false, 'message' => ''];
    
    if (empty($product_id) || empty($name) || empty($type) || $price <= 0 || $quantity < 0) {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin sản phẩm!';
        echo json_encode($response);
        exit;
    }
    
    $check_product = $conn->prepare("SELECT id FROM sanpham WHERE id = ?");
    $check_product->bind_param("i", $product_id);
    $check_product->execute();
    $result = $check_product->get_result();
    if ($result->num_rows === 0) {
        $response['message'] = 'Sản phẩm không tồn tại!';
        echo json_encode($response);
        exit;
    }
    
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../../public/products/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $filename = time() . '_' . $_FILES['image']['name'];
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = '/BloomWebsite/public/products/' . $filename;
        } else {
            $response['message'] = 'Có lỗi khi tải lên hình ảnh!';
            echo json_encode($response);
            exit;
        }
    }
    
    if ($image_url) {
        $stmt = $conn->prepare("UPDATE sanpham SET name = ?, type_id = ?, price = ?, discount = ?, quantity = ?, description = ?, image_url = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssddissi", $name, $type, $price, $discount, $quantity, $description, $image_url, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE sanpham SET name = ?, type_id = ?, price = ?, discount = ?, quantity = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssddisi", $name, $type, $price, $discount, $quantity, $description, $product_id);
    }
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Cập nhật sản phẩm thành công!';
    } else {
        $response['message'] = 'Lỗi khi cập nhật sản phẩm: ' . $conn->error;
    }
    
    echo json_encode($response);
}