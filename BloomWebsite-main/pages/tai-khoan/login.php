<?php
    session_start(); 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/BloomWebsite/config/connect.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                // phần remember
                // if(isset($_POST['remember']) && $_POST['remember'] == 'on') {
                //     setcookie('remember_user', $username, time() + (86400 * 30), "/");
                // }

                if ($user['role'] === 'ADMIN') {
                    header("Location: /BloomWebsite/admin");
                    exit;
                } else {
                    header("Location: /BloomWebsite/");
                    exit;
                }
            } else {
                $error_message = "Thông tin đăng nhập không chính xác!";
            }
        } else {
            $error_message = "Thông tin đăng nhập không chính xác!";
        }

        $stmt->close();
        $conn->close();
    }
?>

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

    <title>Đăng nhập tài khoản</title>
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>
    <!-- <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/navbar.php';?> -->

    <div class="login-wrap">
        <div class="login-container">
            <div class="login-header">
                <h1>Đăng nhập</h1>
                <p>Vui lòng nhập thông tin đăng nhập của bạn</p>
            </div>

            <form method="POST">
                <div class="input-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập hoặc email" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Quên mật khẩu?</a>
                    </div>
                </div>
                
                <button type="submit" class="login-button">Đăng nhập</button>
            </form>

            <?php if(isset($_SESSION['register_success'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['register_success']; 
                        unset($_SESSION['register_success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout_success'): ?>
                <div class="success-message">
                    <?php
                        echo "<p>Đăng xuất thành công!</p>";
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($error_message)): ?>
                <p class="error_message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <div class="register-link">
                Chưa có tài khoản? <a href="/BloomWebsite/tai-khoan/register">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script type="module" src="/BloomWebsite/scripts/search.js"></script>

    <?php 
        $conn->close(); 
        include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php';
    ?>
</body>