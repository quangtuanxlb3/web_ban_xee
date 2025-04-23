<?php
session_start();
require_once '../../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product-name'] ?? '';
    $type = $_POST['product-type'] ?? '';
    $price = $_POST['product-price'] ?? 0;
    $discount = $_POST['product-discount'] ?? 0;
    $quantity = $_POST['product-quantity'] ?? 0;
    $description = $_POST['product-description'] ?? '';
    $response = ['success' => false, 'message' => ''];
    
    if (empty($name) || empty($type) || $price <= 0 || $quantity < 0) {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin sản phẩm!';
        echo json_encode($response);
        exit;
    }
    
    $image_url = '';
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] == 0) {
        $cloudinary_cloud_name = 'dxd95jc8f';
        $cloudinary_api_key = '357358282261638';
        $cloudinary_api_secret = 'u44xMbSN9-87gzIcS0aZysGeMgU';
        
        $cloudinary_url = "https://api.cloudinary.com/v1_1/$cloudinary_cloud_name/image/upload";
        
        $file_path = $_FILES['product-image']['tmp_name'];
        $file_name = $_FILES['product-image']['name'];
        
        $timestamp = time();
        $signature = sha1("timestamp=$timestamp$cloudinary_api_secret");
        
        $post_data = [
            'api_key' => $cloudinary_api_key,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'file' => new CURLFile($file_path, $_FILES['product-image']['type'], $file_name)
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $cloudinary_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_data
        ]);
        
        $result = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $response['message'] = 'Lỗi khi upload ảnh lên Cloudinary: ' . curl_error($curl);
            echo json_encode($response);
            curl_close($curl);
            exit;
        }
        
        curl_close($curl);
        
        $cloudinary_result = json_decode($result, true);
        
        if (isset($cloudinary_result['secure_url'])) {
            $image_url = $cloudinary_result['secure_url'];
        } else {
            $response['message'] = 'Có lỗi khi tải lên hình ảnh lên Cloudinary!';
            echo json_encode($response);
            exit;
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO sanpham (name, price, discount, quantity, image_url, type_id, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddisss", $name, $price, $discount, $quantity, $image_url, $type, $description);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Thêm sản phẩm thành công!';
    } else {
        $response['message'] = 'Lỗi khi thêm sản phẩm: ' . $conn->error;
    }
    
    echo json_encode($response);
}