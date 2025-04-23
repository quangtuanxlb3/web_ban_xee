<?php
session_start();
require_once '../../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'] ?? null;

    if ($id) {
        $check_orders = $conn->prepare("SELECT COUNT(*) as count FROM donhang_chitiet WHERE product_id = ?");
        $check_orders->bind_param("i", $id);
        $check_orders->execute();
        $result = $check_orders->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $_SESSION['error'] = "Không thể xóa sản phẩm đã có trong đơn hàng!";
        } else {
            $get_image = $conn->prepare("SELECT image_url FROM sanpham WHERE id = ?");
            $get_image->bind_param("i", $id);
            $get_image->execute();
            $image_result = $get_image->get_result();
            $image_data = $image_result->fetch_assoc();
            
            if (!empty($image_data['image_url'])) {
                $cloudinary_cloud_name = 'dxd95jc8f';
                $cloudinary_api_key = '357358282261638';
                $cloudinary_api_secret = 'u44xMbSN9-87gzIcS0aZysGeMgU';
                

                $image_url = $image_data['image_url'];
                $public_id = "";
                if (preg_match('/\/v\d+\/([^\.]+)/', $image_url, $matches)) {
                    $public_id = $matches[1];
                }
                
                if (!empty($public_id)) {
                    $timestamp = time();
                    $signature = sha1("public_id=$public_id&timestamp=$timestamp$cloudinary_api_secret");
                    
                    $destroy_url = "https://api.cloudinary.com/v1_1/$cloudinary_cloud_name/image/destroy";
                    
                    $post_data = [
                        'api_key' => $cloudinary_api_key,
                        'public_id' => $public_id,
                        'timestamp' => $timestamp,
                        'signature' => $signature
                    ];
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $destroy_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $post_data
                    ]);
                    
                    curl_exec($curl);
                    curl_close($curl);
                }
            }
            
            $delete_related = $conn->prepare("DELETE FROM sanpham_lienquan WHERE sanpham_id = ? OR sanpham_lienquan_id = ?");
            $delete_related->bind_param("ii", $id, $id);
            $delete_related->execute();
            
            $delete_favorites = $conn->prepare("DELETE FROM yeuthich WHERE product_id = ?");
            $delete_favorites->bind_param("i", $id);
            $delete_favorites->execute();
            
            $delete_cart = $conn->prepare("DELETE FROM giohang_chitiet WHERE product_id = ?");
            $delete_cart->bind_param("i", $id);
            $delete_cart->execute();
            
            $delete_reviews = $conn->prepare("DELETE FROM danhgia WHERE product_id = ?");
            $delete_reviews->bind_param("i", $id);
            $delete_reviews->execute();
            
            $stmt = $conn->prepare("DELETE FROM sanpham WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Xóa sản phẩm thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa sản phẩm: " . $conn->error;
            }
        }
    } else {
        $_SESSION['error'] = "Không tìm thấy ID sản phẩm!";
    }
}

header("Location: /BloomWebsite/admin");
exit();
