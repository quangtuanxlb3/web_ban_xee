<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /BloomWebsite/tai-khoan/login');
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header('Location: /BloomWebsite/profile/' . $user_id . '?tab=orders');
    exit;
}

$order_query = "SELECT * FROM donhang WHERE id = $order_id AND user_id = $user_id";
$order_result = $conn->query($order_query);

if ($order_result->num_rows === 0) {
    header('Location: /BloomWebsite/profile/' . $user_id . '?tab=orders');
    exit;
}

$order = $order_result->fetch_assoc();

$details_query = "SELECT dhct.*, sp.image_url FROM donhang_chitiet dhct 
                 LEFT JOIN sanpham sp ON dhct.product_id = sp.id
                 WHERE dhct.order_id = $order_id";
$details_result = $conn->query($details_query);

$success_message = '';
$error_message = '';

if (isset($_POST['cancel_order']) && $order['status'] === 'pending') {
    $cancel_reason = isset($_POST['cancel_reason']) ? $conn->real_escape_string($_POST['cancel_reason']) : '';
    
    $update_query = "UPDATE donhang SET 
                     status = 'cancelled', 
                     note = CONCAT(note, ' | Lý do hủy: $cancel_reason'), 
                     updated_at = NOW() 
                     WHERE id = $order_id AND user_id = $user_id";
    
    if ($conn->query($update_query)) {
        $success_message = "Đơn hàng đã được hủy thành công!";
        
        $order_result = $conn->query($order_query);
        $order = $order_result->fetch_assoc();
    } else {
        $error_message = "Có lỗi xảy ra khi hủy đơn hàng!";
    }
}

$order_statuses = [
    'pending' => 'Chờ xác nhận',
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao hàng',
    'completed' => 'Đã hoàn thành',
    'cancelled' => 'Đã hủy'
];

$payment_methods = [
    'cod' => 'Thanh toán khi nhận hàng (COD)',
    'bank_transfer' => 'Chuyển khoản ngân hàng',
    'online_payment' => 'Thanh toán trực tuyến'
];

$payment_statuses = [
    'unpaid' => 'Chưa thanh toán',
    'pending' => 'Đang xử lý',
    'paid' => 'Đã thanh toán',
    'refunded' => 'Đã hoàn tiền'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/order-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Chi tiết đơn hàng #<?php echo $order_id; ?> - Bloom Website</title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>

    <div class="order-detail-container">
        <div class="order-header">
            <h1>Chi tiết đơn hàng #<?php echo $order_id; ?></h1>
            <div class="order-status-badge <?php echo strtolower($order['status']); ?>">
                <?php echo $order_statuses[$order['status']] ?? $order['status']; ?>
            </div>
        </div>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="order-content">
            <div class="order-info-grid">
                <div class="order-info-section">
                    <h2>Thông tin đơn hàng</h2>
                    <div class="info-group">
                        <div class="info-item">
                            <span class="label">Mã đơn hàng:</span>
                            <span class="value">#<?php echo $order_id; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Ngày đặt hàng:</span>
                            <span class="value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Trạng thái đơn hàng:</span>
                            <span class="value status-text <?php echo strtolower($order['status']); ?>">
                                <?php echo $order_statuses[$order['status']] ?? $order['status']; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Phương thức thanh toán:</span>
                            <span class="value"><?php echo $payment_methods[$order['payment_method']] ?? $order['payment_method']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Trạng thái thanh toán:</span>
                            <span class="value payment-status <?php echo strtolower($order['payment_status']); ?>">
                                <?php echo $payment_statuses[$order['payment_status']] ?? $order['payment_status']; ?>
                            </span>
                        </div>
                        <?php if (!empty($order['note'])): ?>
                            <div class="info-item">
                                <span class="label">Ghi chú:</span>
                                <span class="value"><?php echo nl2br(htmlspecialchars($order['note'])); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="order-info-section">
                    <h2>Thông tin giao hàng</h2>
                    <div class="info-group">
                        <div class="info-item">
                            <span class="label">Người nhận:</span>
                            <span class="value"><?php echo htmlspecialchars($order['shipping_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Số điện thoại:</span>
                            <span class="value"><?php echo htmlspecialchars($order['shipping_phone']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Địa chỉ giao hàng:</span>
                            <span class="value"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Phí vận chuyển:</span>
                            <span class="value"><?php echo number_format($order['shipping_fee'] * 1000, 0, ',', '.'); ?> VND</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="order-products-section">
                <h2>Sản phẩm đã đặt</h2>
                <div class="products-list">
                    <?php while ($item = $details_result->fetch_assoc()): ?>
                        <div class="product-item">
                            <div class="product-image">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                <?php else: ?>
                                    <div class="no-image"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                <div class="product-price-info">
                                    <span class="product-price"><?php echo number_format($item['price'] * (1 - $item['discount'] / 100) * 1000, 0, ',', '.'); ?> VND</span>
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="discount-badge"><?php echo $item['discount']; ?>% giảm</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-quantity">
                                <span>x<?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="product-total">
                                <?php echo number_format($item['price'] * (1 - $item['discount'] / 100) * $item['quantity'] * 1000, 0, ',', '.'); ?> VND
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="order-summary">
                    <div class="summary-item subtotal">
                        <span class="label">Tổng tiền hàng:</span>
                        <span class="value"><?php echo number_format(($order['total_price'] - $order['shipping_fee']) * 1000, 0, ',', '.'); ?> VND</span>
                    </div>
                    <div class="summary-item shipping">
                        <span class="label">Phí vận chuyển:</span>
                        <span class="value"><?php echo number_format($order['shipping_fee'] * 1000, 0, ',', '.'); ?> VND</span>
                    </div>
                    <div class="summary-item total">
                        <span class="label">Tổng thanh toán:</span>
                        <span class="value"><?php echo number_format($order['total_price'] * 1000, 0, ',', '.'); ?> VND</span>
                    </div>
                </div>
            </div>
            
            <div class="order-actions">
                <?php if ($order['status'] === 'pending'): ?>
                    <button type="button" id="cancel-order-btn" class="btn cancel-btn">Hủy đơn hàng</button>
                <?php endif; ?>
                <a href="/BloomWebsite/profile/<?php echo $user_id; ?>?tab=orders" class="btn back-btn">Quay lại</a>
                <?php if ($order['status'] === 'completed'): ?>
                    <a href="/BloomWebsite/danh-gia-don-hang/<?php echo $order_id; ?>" class="btn review-btn">Đánh giá sản phẩm</a>
                <?php endif; ?>
            </div>
            
            <?php if ($order['status'] === 'pending'): ?>
                <div id="cancel-modal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Hủy đơn hàng</h2>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="cancel_reason">Lý do hủy đơn hàng:</label>
                                <select id="cancel_reason" name="cancel_reason" required>
                                    <option value="">-- Chọn lý do hủy --</option>
                                    <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                                    <option value="Tôi muốn thay đổi phương thức thanh toán">Tôi muốn thay đổi phương thức thanh toán</option>
                                    <option value="Tôi tìm thấy giá tốt hơn ở nơi khác">Tôi tìm thấy giá tốt hơn ở nơi khác</option>
                                    <option value="Tôi đổi ý không muốn mua nữa">Tôi đổi ý không muốn mua nữa</option>
                                    <option value="Tôi đặt nhầm sản phẩm">Tôi đặt nhầm sản phẩm</option>
                                    <option value="Lý do khác">Lý do khác</option>
                                </select>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn secondary-btn close-btn">Đóng</button>
                                <button type="submit" name="cancel_order" class="btn cancel-btn">Xác nhận hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('cancel-modal');
            const cancelBtn = document.getElementById('cancel-order-btn');
            const closeBtn = document.querySelector('.close');
            const closeBtnSecondary = document.querySelector('.close-btn');
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.style.display = 'block';
                });
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }
            
            if (closeBtnSecondary) {
                closeBtnSecondary.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }
            
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 