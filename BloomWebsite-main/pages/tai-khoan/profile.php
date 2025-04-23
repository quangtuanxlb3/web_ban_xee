<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: /BloomWebsite/tai-khoan/login");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $id = isset($_GET['id']) ? $_GET['id'] : $user_id;
    
    if ($user_id != $id) {
        header("Location: /BloomWebsite/profile/$user_id");
        exit();
    }
    
    $user_query = "SELECT * FROM nguoidung WHERE id = $user_id";
    $user_result = $conn->query($user_query);
    $user = $user_result->fetch_assoc();
    
    $orders_query = "SELECT * FROM donhang WHERE user_id = $user_id ORDER BY created_at DESC";
    $orders_result = $conn->query($orders_query);
    
    $wishlist_query = "SELECT y.*, s.name, s.price, s.discount, s.image_url 
                      FROM yeuthich y 
                      JOIN sanpham s ON y.product_id = s.id 
                      WHERE y.user_id = $user_id";
    $wishlist_result = $conn->query($wishlist_query);
    
    $success_message = '';
    $error_message = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        
        $check_email = "SELECT id FROM nguoidung WHERE email = '$email' AND id != $user_id";
        $email_result = $conn->query($check_email);
        
        if ($email_result->num_rows > 0) {
            $error_message = "Email đã được sử dụng bởi tài khoản khác!";
        } else {
            $update_query = "UPDATE nguoidung SET 
                            name = '$name',
                            email = '$email',
                            phone = '$phone',
                            address = '$address',
                            updated_at = NOW()
                            WHERE id = $user_id";
                            
            if ($conn->query($update_query)) {
                $success_message = "Cập nhật thông tin thành công!";
                $user_result = $conn->query($user_query);
                $user = $user_result->fetch_assoc();
            } else {
                $error_message = "Có lỗi xảy ra: " . $conn->error;
            }
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pwd_query = "UPDATE nguoidung SET 
                                    password = '$hashed_password',
                                    updated_at = NOW()
                                    WHERE id = $user_id";
                                    
                if ($conn->query($update_pwd_query)) {
                    $success_message = "Thay đổi mật khẩu thành công!";
                } else {
                    $error_message = "Có lỗi xảy ra: " . $conn->error;
                }
            } else {
                $error_message = "Mật khẩu mới và mật khẩu xác nhận không khớp!";
            }
        } else {
            $error_message = "Mật khẩu hiện tại không đúng!";
        }
    }
    
    if (isset($_GET['remove_wishlist']) && !empty($_GET['remove_wishlist'])) {
        $wishlist_id = (int)$_GET['remove_wishlist'];
        $remove_query = "DELETE FROM yeuthich WHERE id = $wishlist_id AND user_id = $user_id";
        
        if ($conn->query($remove_query)) {
            header("Location: /BloomWebsite/profile/$user_id?tab=wishlist");
            exit();
        }
    }
    
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Tài khoản - <?php echo htmlspecialchars($user['name']); ?></title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>

    <div class="profile-container">
        <div class="profile-header">
            <h1>Tài khoản của tôi</h1>
            <p class="welcome-text">Xin chào, <span><?php echo htmlspecialchars($user['name']); ?></span>!</p>
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
        
        <div class="profile-content">
            <div class="profile-sidebar">
                <ul class="profile-menu">
                    <li class="<?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                        <a href="?tab=profile"><i class="fas fa-user"></i> Thông tin tài khoản</a>
                    </li>
                    <li class="<?php echo $active_tab === 'password' ? 'active' : ''; ?>">
                        <a href="?tab=password"><i class="fas fa-lock"></i> Đổi mật khẩu</a>
                    </li>
                    <li class="<?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
                        <a href="?tab=orders"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</a>
                    </li>
                    <li class="<?php echo $active_tab === 'wishlist' ? 'active' : ''; ?>">
                        <a href="?tab=wishlist"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a>
                    </li>
                    <li>
                        <a href="/BloomWebsite/composables/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </li>
                </ul>
            </div>
            
            <div class="profile-main">
                <?php if ($active_tab === 'profile'): ?>
                    <div class="profile-section">
                        <h2>Thông tin cá nhân</h2>
                        <form action="" method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="name">Họ và tên</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <textarea id="address" name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" name="update_profile" class="btn primary-btn">Cập nhật thông tin</button>
                        </form>
                    </div>
                <?php elseif ($active_tab === 'password'): ?>
                    <div class="profile-section">
                        <h2>Thay đổi mật khẩu</h2>
                        <form action="" method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">Mật khẩu mới</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <button type="submit" name="change_password" class="btn primary-btn">Thay đổi mật khẩu</button>
                        </form>
                    </div>
                <?php elseif ($active_tab === 'orders'): ?>
                    <div class="profile-section">
                        <h2>Đơn hàng của tôi</h2>
                        <?php if ($orders_result->num_rows > 0): ?>
                            <div class="orders-list">
                                <?php while ($order = $orders_result->fetch_assoc()): ?>
                                    <div class="order-item">
                                        <div class="order-header">
                                            <div class="order-id">
                                                <span>Mã đơn hàng:</span> #<?php echo $order['id']; ?>
                                            </div>
                                            <div class="order-date">
                                                <span>Ngày đặt:</span> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                            </div>
                                            <div class="order-status <?php echo strtolower($order['status']); ?>">
                                                <?php 
                                                    $status_text = '';
                                                    switch ($order['status']) {
                                                        case 'pending':
                                                            $status_text = 'Chờ xác nhận';
                                                            break;
                                                        case 'processing':
                                                            $status_text = 'Đang xử lý';
                                                            break;
                                                        case 'shipping':
                                                            $status_text = 'Đang giao hàng';
                                                            break;
                                                        case 'completed':
                                                            $status_text = 'Đã hoàn thành';
                                                            break;
                                                        case 'cancelled':
                                                            $status_text = 'Đã hủy';
                                                            break;
                                                        default:
                                                            $status_text = $order['status'];
                                                    }
                                                    echo $status_text;
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <?php 
                                            $order_id = $order['id'];
                                            $order_details_query = "SELECT * FROM donhang_chitiet WHERE order_id = $order_id";
                                            $order_details_result = $conn->query($order_details_query);
                                        ?>
                                        
                                        <div class="order-products">
                                            <?php while ($item = $order_details_result->fetch_assoc()): ?>
                                                <div class="order-product">
                                                    <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                    <div class="product-quantity">x<?php echo $item['quantity']; ?></div>
                                                    <div class="product-price"><?php echo number_format($item['price'] * (1 - $item['discount'] / 100), 0, ',', '.'); ?> VND</div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                        
                                        <div class="order-footer">
                                            <div class="order-total">
                                                <span>Tổng cộng:</span> <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND
                                            </div>
                                            <div class="order-actions">
                                                <a href="/BloomWebsite/chi-tiet-don-hang/<?php echo $order['id']; ?>" class="btn secondary-btn">Xem chi tiết</a>
                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <a href="/BloomWebsite/huy-don-hang/<?php echo $order['id']; ?>" class="btn cancel-btn">Hủy đơn hàng</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Bạn chưa có đơn hàng nào.</p>
                                <a href="/BloomWebsite/" class="btn primary-btn">Mua sắm ngay</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($active_tab === 'wishlist'): ?>
                    <div class="profile-section">
                        <h2>Sản phẩm yêu thích</h2>
                        <?php if ($wishlist_result->num_rows > 0): ?>
                            <div class="wishlist-grid">
                                <?php while ($item = $wishlist_result->fetch_assoc()): ?>
                                    <div class="wishlist-item">
                                        <a href="/BloomWebsite/san-pham/<?php echo $item['product_id']; ?>" class="wishlist-image">
                                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        </a>
                                        <div class="wishlist-info">
                                            <h3><a href="/BloomWebsite/san-pham/<?php echo $item['product_id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                            <div class="wishlist-price">
                                                <span class="current-price"><?php echo number_format($item['price'] * (1 - $item['discount'] / 100), 0, ',', '.'); ?> VND</span>
                                                <?php if ($item['discount'] > 0): ?>
                                                    <span class="original-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="wishlist-actions">
                                                <a href="/BloomWebsite/san-pham/<?php echo $item['product_id']; ?>" class="btn primary-btn">Xem chi tiết</a>
                                                <a href="?tab=wishlist&remove_wishlist=<?php echo $item['id']; ?>" class="btn secondary-btn"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-heart"></i>
                                <p>Bạn chưa có sản phẩm yêu thích nào.</p>
                                <a href="/BloomWebsite/" class="btn primary-btn">Khám phá sản phẩm</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script type="module" src="/BloomWebsite/scripts/search.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
</body>
</html>