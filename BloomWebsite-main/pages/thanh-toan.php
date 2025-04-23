<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /BloomWebsite/tai-khoan/login');
    exit;
}

    $error_message = '';

    if (isset($_SESSION['error_message'])) {
        $error_message = htmlspecialchars($_SESSION['error_message']);
        unset($_SESSION['error_message']);
    }

$user_id = $_SESSION['user_id'];

$cart_query = "SELECT ghct.*, sp.name, sp.price, sp.discount, sp.image_url FROM giohang_chitiet ghct JOIN sanpham sp ON ghct.product_id = sp.id WHERE ghct.cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)";
$cart_result = $conn->query($cart_query);

$total_price = 0;
$cart_items = [];

while ($row = $cart_result->fetch_assoc()) {
    $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
    $total_price += $discounted_price * $row['quantity'];
    $cart_items[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Thanh Toán Sản Phẩm</title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/navbar.php'; ?>

    <div class="checkout-container">
        <h1>Thanh Toán</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="/BloomWebsite/pages/thanh-toan-xu-ly.php" method="POST" class="checkout-form">
            <div class="form-section">
                <h2>Thông tin người nhận</h2>
                <label for="shipping_name">Họ và tên:</label>
                <input type="text" id="shipping_name" name="shipping_name" required>

                <label for="shipping_phone">Số điện thoại:</label>
                <input type="text" id="shipping_phone" name="shipping_phone" required>

                <label for="shipping_address">Địa chỉ:</label>
                <textarea id="shipping_address" name="shipping_address" required></textarea>
            </div>

            <div class="form-section">
                <h2>Phương thức thanh toán</h2>
                <label for="payment_method">Chọn phương thức thanh toán:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                    <option value="online_payment">Thanh toán trực tuyến</option>
                </select>
            </div>

            <div class="form-section">
                <h2>Đơn hàng của bạn</h2>
                <ul class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <li>
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                <span class="item-quantity">Số lượng: <?php echo $item['quantity']; ?></span>
                                <span class="item-price">Giá: <?php echo number_format($item['price'] * (1 - $item['discount'] / 100) * 1000, 0, ',', '.'); ?> VND</span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="total-price">
                    <span>Tổng cộng: <?php echo number_format($total_price * 1000, 0, ',', '.'); ?> VND</span>
                </div>
            </div>

            <button type="submit" class="checkout-btn">Đặt hàng</button>
        </form>
    </div>
    
    <script type="module" src="/BloomWebsite/scripts/search.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
</body>
</html>