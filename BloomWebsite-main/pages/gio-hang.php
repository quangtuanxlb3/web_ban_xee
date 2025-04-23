<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /BloomWebsite/tai-khoan/login');
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $success_message = '';
    $error_message = '';

    if (isset($_SESSION['error_message'])) {
        $error_message = htmlspecialchars($_SESSION['error_message']);
        unset($_SESSION['error_message']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $item_id => $quantity) {
            $item_id = (int)$item_id;
            $quantity = (int)$quantity;
            
            if ($quantity <= 0) {
                $conn->query("DELETE FROM giohang_chitiet WHERE id = $item_id AND cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)");
            } else {
                $conn->query("UPDATE giohang_chitiet SET quantity = $quantity, updated_at = NOW() WHERE id = $item_id AND cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)");
            }
        }
        
        $conn->query("UPDATE giohang SET updated_at = NOW() WHERE user_id = $user_id");
        
        $success_message = "Giỏ hàng đã được cập nhật!";
    }

    if (isset($_GET['remove']) && !empty($_GET['remove'])) {
        $item_id = (int)$_GET['remove'];
        $conn->query("DELETE FROM giohang_chitiet WHERE id = $item_id AND cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)");
        
        $conn->query("UPDATE giohang SET updated_at = NOW() WHERE user_id = $user_id");
        
        $success_message = "Sản phẩm đã được xóa khỏi giỏ hàng!";
    }

    $cart_query = "SELECT ghct.*, sp.name, sp.price, sp.discount, sp.image_url, sp.quantity as available_quantity 
                  FROM giohang_chitiet ghct 
                  JOIN sanpham sp ON ghct.product_id = sp.id 
                  WHERE ghct.cart_id = (SELECT id FROM giohang WHERE user_id = $user_id)";
    $cart_result = $conn->query($cart_query);

    $total_price = 0;
    $cart_items = [];

    while ($row = $cart_result->fetch_assoc()) {
        $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
        $row['item_price'] = $discounted_price;
        $row['item_total'] = $discounted_price * $row['quantity'];
        $total_price += $row['item_total'];
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
    <link rel="stylesheet" href="/BloomWebsite/styles/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Giỏ Hàng - Bloom Website</title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/navbar.php'; ?>

    <div class="cart-container">
        <h1>Giỏ hàng của bạn</h1>
        
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
        
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Giỏ hàng của bạn hiện đang trống!</p>
                <a href="/BloomWebsite/" class="btn primary-btn">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <form method="POST" action="" class="cart-form">
                <div class="cart-header">
                    <div class="cart-product">Sản phẩm</div>
                    <div class="cart-price">Đơn giá</div>
                    <div class="cart-quantity">Số lượng</div>
                    <div class="cart-total">Thành tiền</div>
                    <div class="cart-action">Thao tác</div>
                </div>
                
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="cart-product">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="product-info">
                                    <h3><a href="/BloomWebsite/san-pham/<?php echo $item['product_id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                    <?php if ($item['discount'] > 0): ?>
                                        <div class="discount-badge"><?php echo $item['discount']; ?>% giảm</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="cart-price">
                                <div class="current-price"><?php echo number_format($item['item_price'] * 1000, 0, ',', '.'); ?> VND</div>
                                <?php if ($item['discount'] > 0): ?>
                                    <div class="original-price"><?php echo number_format($item['price'] * 1000, 0, ',', '.'); ?> VND</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cart-quantity">
                                <div class="quantity-control">
                                    <button type="button" class="decrease-btn"><i class="fas fa-minus"></i></button>
                                    <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['available_quantity']; ?>">
                                    <button type="button" class="increase-btn"><i class="fas fa-plus"></i></button>
                                </div>
                                <?php if ($item['quantity'] > $item['available_quantity']): ?>
                                    <div class="stock-warning">Chỉ còn <?php echo $item['available_quantity']; ?> sản phẩm</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cart-total">
                                <?php echo number_format($item['item_total'] * 1000, 0, ',', '.'); ?> VND
                            </div>
                            
                            <div class="cart-action">
                                <a href="?remove=<?php echo $item['id']; ?>" class="remove-btn" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-footer">
                    <div class="cart-summary">
                        <div class="subtotal">
                            <span>Tổng tiền:</span>
                            <span class="price"><?php echo number_format($total_price * 1000, 0, ',', '.'); ?> VND</span>
                        </div>
                        
                        <div class="cart-actions">
                            <a href="/BloomWebsite/" class="btn secondary-btn">Tiếp tục mua sắm</a>
                            <button type="submit" name="update_cart" class="btn update-btn">Cập nhật giỏ hàng</button>
                            <a href="/BloomWebsite/thanh-toan" class="btn primary-btn">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtns = document.querySelectorAll('.decrease-btn');
            const increaseBtns = document.querySelectorAll('.increase-btn');
            
            decreaseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });
            });
            
            increaseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    let value = parseInt(input.value);
                    const max = parseInt(input.getAttribute('max'));
                    if (value < max) {
                        input.value = value + 1;
                    }
                });
            });
        });
    </script>
</body>
</html>