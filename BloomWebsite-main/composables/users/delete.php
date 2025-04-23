<?php
session_start();
require_once '../../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['user_id'] ?? null;

    if ($id) {
        $check_orders = $conn->prepare("SELECT COUNT(*) as count FROM donhang WHERE user_id = ?");
        $check_orders->bind_param("i", $id);
        $check_orders->execute();
        $result = $check_orders->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $_SESSION['error'] = "Không thể xóa người dùng đã có đơn hàng!";
        } else {
            $delete_cart = $conn->prepare("DELETE FROM giohang WHERE user_id = ?");
            $delete_cart->bind_param("i", $id);
            $delete_cart->execute();
            
            $delete_favorites = $conn->prepare("DELETE FROM yeuthich WHERE user_id = ?");
            $delete_favorites->bind_param("i", $id);
            $delete_favorites->execute();
            
            $delete_reviews = $conn->prepare("DELETE FROM danhgia WHERE user_id = ?");
            $delete_reviews->bind_param("i", $id);
            $delete_reviews->execute();
            
            $stmt = $conn->prepare("DELETE FROM nguoidung WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Xóa người dùng thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa người dùng: " . $conn->error;
            }
        }
    } else {
        $_SESSION['error'] = "Không tìm thấy ID người dùng!";
    }
}

header("Location: /BloomWebsite/admin");
exit();