<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';

if (!isset($_SESSION['order_success']) || $_SESSION['order_success'] !== true || !isset($_SESSION['order_id'])) {
    header('Location: /BloomWebsite/');
    exit;
}

$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];

$order_query = "SELECT * FROM donhang WHERE id = $order_id AND user_id = $user_id";
$order_result = $conn->query($order_query);

if ($order_result->num_rows === 0) {
    header('Location: /BloomWebsite/');
    exit;
}

$order = $order_result->fetch_assoc();

$details_query = "SELECT * FROM donhang_chitiet WHERE order_id = $order_id";
$details_result = $conn->query($details_query);

unset($_SESSION['order_success']);
unset($_SESSION['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/order-confirm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Đặt Hàng Thành Công - Bloom Website</title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>

    <div class="confirm-container">
        <div class="confirm-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Đặt Hàng Thành Công!</h1>
            <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
        </div>
        
        <div class="order-info">
            <div class="order-summary">
                <h2>Thông tin đơn hàng #<?php echo $order_id; ?></h2>
                <div class="order-detail">
                    <div class="detail-item">
                        <span class="detail-label">Ngày đặt hàng:</span>
                        <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Người nhận:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($order['shipping_name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Số điện thoại:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($order['shipping_phone']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Địa chỉ giao hàng:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($order['shipping_address']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phương thức thanh toán:</span>
                        <span class="detail-value">
                            <?php 
                                switch ($order['payment_method']) {
                                    case 'cod':
                                        echo 'Thanh toán khi nhận hàng (COD)';
                                        break;
                                    case 'bank_transfer':
                                        echo 'Chuyển khoản ngân hàng';
                                        break;
                                    case 'online_payment':
                                        echo 'Thanh toán trực tuyến';
                                        break;
                                    default:
                                        echo $order['payment_method'];
                                }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="order-products">
                <h2>Sản phẩm đã đặt</h2>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $details_result->fetch_assoc()): ?>
                            <tr>
                                <td class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td class="product-price">
                                    <?php echo number_format($item['price'] * (1 - $item['discount'] / 100) * 1000, 0, ',', '.'); ?> VND
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="original-price"><?php echo number_format($item['price'] * 1000, 0, ',', '.'); ?> VND</span>
                                    <?php endif; ?>
                                </td>
                                <td class="product-quantity"><?php echo $item['quantity']; ?></td>
                                <td class="product-total"><?php echo number_format($item['price'] * (1 - $item['discount'] / 100) * $item['quantity'] * 1000, 0, ',', '.'); ?> VND</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total-label">Tổng cộng:</td>
                            <td class="order-total"><?php echo number_format($order['total_price'] * 1000, 0, ',', '.'); ?> VND</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="order-notes">
                <h3>Lưu ý:</h3>
                <ul>
                    <li>Đơn hàng của bạn sẽ được xử lý trong vòng 24 giờ.</li>
                    <li>Bạn sẽ nhận được email xác nhận đơn hàng trong thời gian sớm nhất.</li>
                    <li>Theo dõi trạng thái đơn hàng trong mục "Đơn hàng của tôi" ở trang tài khoản cá nhân.</li>
                    <li>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua hotline: 1900 633 045.</li>
                </ul>
            </div>
            
            <div class="order-actions">
                <a href="/BloomWebsite/" class="btn primary-btn">Tiếp tục mua sắm</a>
                <a href="/BloomWebsite/profile/<?php echo $_SESSION["user_id"]?>?tab=orders" class="btn secondary-btn">Xem đơn hàng của tôi</a>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
</body>
</html> 