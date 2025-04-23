<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/assets/logo/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Đăng ký tài khoản</title>
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>

    <div class="register-wrap">
        <div class="register-container">
            <div class="register-header">
                <h1>Đăng ký tài khoản</h1>
                <p>Vui lòng điền thông tin để tạo tài khoản mới</p>
            </div>

            <?php if(isset($_SESSION['register_errors'])): ?>
                <div class="error-message" id="error-box">
                    <button class="close-btn">×</button>
                    <ul>
                        <?php 
                            foreach($_SESSION['register_errors'] as $error) {
                                echo "<li>$error</li>";
                            }
                            unset($_SESSION['register_errors']);
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/BloomWebsite/composables/register_process.php">
                <div class="row">
                    <div class="input-group">
                        <label for="firstname">Họ và tên</label>
                        <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
                </div>
                
                <div class="input-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Tạo tên đăng nhập" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Tạo mật khẩu" required>
                    <ul class="password-requirements">
                        <li>Ít nhất 8 ký tự</li>
                        <li>Bao gồm chữ hoa và chữ thường</li>
                        <li>Ít nhất một số và một ký tự đặc biệt</li>
                    </ul>
                </div>
                
                <div class="input-group">
                    <label for="confirm-password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Nhập lại mật khẩu" required>
                </div>
                
                <div class="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Tôi đã đọc và đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a> của trang web</label>
                </div>
                
                <button type="submit" class="register-button">Đăng ký</button>
            </form>
            
            <div class="login-link">
                Đã có tài khoản? <a href="/BloomWebsite/tai-khoan/login">Đăng nhập</a>
            </div>
        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; ?>
    <script type="module" src="/BloomWebsite/scripts/search.js"></script>
    <script type="module" src="/BloomWebsite/scripts/notification.js"></script>
</body>