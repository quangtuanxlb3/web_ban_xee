<?php
    session_start();
    $id = $_GET['id'] ?? null;

    if ($id === null) {
        echo "Không tìm thấy sản phẩm!";
        exit;
    }

    require_once "../config/connect.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/helpers/components.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        $buy_now = isset($_POST['buy_now']) ? (int)$_POST['buy_now'] : 0;

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['redirect_after_login'] = "/BloomWebsite/san-pham/$product_id";
            header('Location: /BloomWebsite/tai-khoan/login');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        
        $cart_query = "SELECT id FROM giohang WHERE user_id = $user_id";
        $cart_result = $conn->query($cart_query);
        
        if ($cart_result->num_rows === 0) {
            $conn->query("INSERT INTO giohang (user_id, created_at, updated_at) VALUES ($user_id, NOW(), NOW())");
            $cart_id = $conn->insert_id;
        } else {
            $cart_id = $cart_result->fetch_assoc()['id'];
        }
        
        $check_query = "SELECT * FROM giohang_chitiet WHERE cart_id = $cart_id AND product_id = $product_id";
        $check_result = $conn->query($check_query);
        
        if ($check_result->num_rows > 0) {
            $item = $check_result->fetch_assoc();
            $new_quantity = $item['quantity'] + $quantity;
            $conn->query("UPDATE giohang_chitiet SET quantity = $new_quantity, updated_at = NOW() WHERE id = {$item['id']}");
        } else {
            $conn->query("INSERT INTO giohang_chitiet (cart_id, product_id, quantity, created_at, updated_at) 
                        VALUES ($cart_id, $product_id, $quantity, NOW(), NOW())");
        }
        
        $conn->query("UPDATE giohang SET updated_at = NOW() WHERE id = $cart_id");
        
        if ($buy_now === 1) {
            header('Location: /BloomWebsite/thanh-toan');
        } else {
            header('Location: /BloomWebsite/gio-hang');
        }
        exit;
    }

    $result_sanpham = $conn->query("SELECT * FROM sanpham WHERE id = $id");

    if ($result_sanpham->num_rows === 0) {
        echo "Sản phẩm không tồn tại.";
        exit;
    }

    $flower = $result_sanpham->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title><?php echo $flower["name"]; ?></title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/navbar.php'; ?>

    <h2 class="order-title">ĐẶT HOA ONLINE - GIAO MIỄN PHÍ TP HCM & HÀ NỘI - GỌI NGAY 1900 000 001 HOẶC 0442 567 321</h2>


    <div class="cart-wraper">
        <div class="cart-container">
            <img width="456" height="567" class="product-image" src="<?php echo $flower['image_url']; ?>" alt="ảnh bị lỗi ..">
            <div class="product-detail">
                <h1 class="product-title"><?php echo htmlspecialchars($flower['name']); ?></h1>                
                <div class="product-price">
                    <h2 class="product-after-price"><?php echo number_format($flower['price'] * 1000, 0, ',', ',') ?>VND</h2>
                    <?php if (!empty($flower['discount']) && $flower['discount'] > 0): ?>
                        <h2 class="product-origin-price">
                            <?php echo number_format($flower['price'] * 1000 * (1 - $flower['discount'] / 100), 0, ',', '.') . ' VND'; ?>
                        </h2>
                    <?php endif; ?>
                    <div class="discount-value">
                        <span><?php echo $flower['discount'] ?> % GIẢM</span>  
                    </div>
                </div>
                <div class="promotion">
                    <span class="title">Khuyến mãi:</span>
                    <div class="promotion-list">
                        <span class="discount-label">Giảm 50k</span>
                        <span class="discount-label">Giảm 25k</span>
                        <span class="discount-label">Giảm 10%</span>
                    </div>
                </div>
                <div class="call-now">
                    <span class="title">Gọi ngay:</span>
                    <span class="number-phone">1900 633 045</span>
                </div>
                <div class="chat-now">
                    <span class="title">Chat ngay:</span>
                    <div class="contact-list">
                        <a href="https://www.messenger.com/t/100015444163739"><img src="/BloomWebsite/assets/icon/ms.png" alt="Messenger Icon"></a>
                        <a href="https://www.messenger.com/t/100015444163739"><img src="/BloomWebsite/assets/icon/zalo.png" alt="Zalo Icon"></a>
                        <a href="https://www.messenger.com/t/100015444163739"><img src="/BloomWebsite/assets/icon/zalo.png" alt="Zalo Icon"></a>
                        <a href="https://www.messenger.com/t/100015444163739"><img src="/BloomWebsite/assets/icon/zalo.png" alt="Zalo Icon"></a>
                    </div>       
                </div>
                <div class="product-transport">
                    <div class="main-title">
                        <span class="title">Vận chuyển:</span>
                        <p>Miễn phí giao hoa khu vực nội thành TP.HCM & Hà Nội</p>
                    </div>
                    <div class="select-location">
                    <select id="city-select">
                        <option value="">-- Chọn thành phố --</option>
                        <option value="HANOI">Hà Nội</option>
                        <option value="HCM">Hồ Chí Minh</option>
                    </select>

                    <select id="district-select">
                        <option value="">-- Chọn quận/huyện --</option>
                    </select>
                    </div>
                    <p class="ship-fee">Phí giao hàng: Miễn phí!</p>
                </div>
                <div class="ship-notifcation">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="title">Sản phẩm này không hỗ trợ giao vào ngày: 08-03-2025</p>
                </div>
                <div class="quantity">
                    <span class="title">Số lượng</span>
                    <form method="POST" class="order-actions">
                        <input type="hidden" name="product_id" value="<?php echo $flower['id']; ?>">
                        <input type="text" name="quantity" value="1">
                        <button type="submit" name="add_to_cart" class="order">Thêm vào giỏ</button>
                        <button type="submit" name="add_to_cart" class="order-now" onclick="document.querySelector('input[name=buy_now]').value = '1'">Mua ngay</button>
                        <input type="hidden" name="buy_now" value="0">
                    </form>
                </div>
            </div>
        </div>
        <div class="separator"></div>
        <div class="product-information element">
            <h3 class="product-title">Mô Tả Sản Phẩm</h3>
            <p class="product-description">
                <?php if ($flower["description"]): ?>
                    <?php echo htmlspecialchars($flower["description"]); ?>
                <?php else: ?>
                    <p class='no-product'>Không có mô tả cho sản phẩm này.</p>
                <?php endif; ?>
            </p>
            <div class="product-note">
                <span class="product-note__title">Lưu ý:</span>
                <p class="product-note__content">Do được làm thủ công, nên sản phẩm ngoài thực tế sẽ có đôi chút khác biệt so với hình ảnh trên website. Tuy nhiên, BloomWebsite cam kết hoa sẽ giống khoảng 80% so với hình ảnh.</p>
                <p class="product-note__content">Vì các loại hoa lá phụ sẽ có tùy vào thời điểm trong năm, BloomWebsite đảm bảo các loại hoa chính, các loại hoa lá phụ sẽ thay đổi phù hợp giá cả và thiết kế sản phẩm.</p>
            </div>
        </div>
        <div class="separator"></div>
        <div class="product-related element">
            <h3 class="product-title">Sản phẩm liên quan</h3>
            <?php 
                $stmt = $conn->prepare("SELECT sp.*
                    FROM sanpham_lienquan splq
                    JOIN sanpham sp ON splq.sanpham_lienquan_id = sp.id
                    WHERE splq.sanpham_id = ?
                    ORDER BY sp.id ASC
                    LIMIT 8
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result_sanphamlienquan = $stmt->get_result();
            ?>
            <div class="flower-grid">
                <?php
                    while ($row = $result_sanphamlienquan->fetch_assoc()) {
                        flower_card($row["id"], $row["image_url"], $row["name"],  $row["price"], $row["discount"]);
                    }
                    ?>
            </div>
            <?php 
                if ($result_sanphamlienquan->num_rows == 0) {
                    echo "<p class='no-product'>Không có sản phẩm liên quan nào!</p>";
                }
                $stmt->close();
            ?>
        </div>
    </div>

    <?php 
        include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; 
        $conn->close();
    ?>
    <script type="module" src="/BloomWebsite/scripts/product.js"></script>
    <script type="module" src="/BloomWebsite/scripts/script.js"></script>
</body>
</html>