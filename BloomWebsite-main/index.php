<?php
    session_start();
    // session_unset();

    include_once $_SERVER['DOCUMENT_ROOT'] . "/BloomWebsite/config/connect.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/helpers/components.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoZone - Cửa hàng xe chất lượng cao</title>
    <link rel="shortcut icon" href="assets/logo/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'layouts/header.php'; ?>
    <?php include_once 'layouts/navbar.php'; ?>

    <h2 class="order-title">ĐẶT MUA XE ONLINE - GIAO MIỄN PHÍ TOÀN QUỐC - GỌI NGAY 1900 000 001 HOẶC 0442 567 321</h2>

    <div class="banner">
        <div class="pre-banner">&lt;</div>
        <div class="banner-slide-wrapper">
            <div class="banner-slide">
                <img src="assets/banner/car1.webp" alt="Xe hơi mẫu 1">
                <img src="assets/banner/car2.webp" alt="Xe hơi mẫu 2">
                <img src="assets/banner/car3.webp" alt="Xe hơi mẫu 3">
            </div>
        </div>
        <div class="next-banner">&gt;</div>
    </div>

    <main class="content">
        <div class="category">
            <h2 class="category-title">XE MỚI ƯU ĐÃI LỚN</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 1 AND 8";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>

        <div class="category">
            <h2 class="category-title">BÁN CHẠY NHẤT</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 9 AND 16";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>

        <div class="category">
            <h2 class="category-title">XE Ô TÔ MỚI RA MẮT</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 17 AND 24";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>

        <div class="category">
            <h2 class="category-title">XE MÁY PHỔ BIẾN</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 25 AND 32";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>

        <div class="category">
            <h2 class="category-title">XE TẢI - XE DU LỊCH</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 9 AND 16";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>

        <div class="category">
            <h2 class="category-title">PHỤ KIỆN & ĐỒ CHƠI XE</h2>
            <div class="flower-grid">
            <?php 
                $sql = "SELECT ID, NAME, PRICE, DISCOUNT, image_url FROM sanpham WHERE ID BETWEEN 9 AND 16";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["NAME"];
                        $price = $row["PRICE"];
                        $discount = $row["DISCOUNT"];
                        $image_url = $row["image_url"];

                        flower_card($id, $image_url, $name, $price, $discount);
                    }
                } else {
                    echo "Không có sản phẩm nào.";
                }
            ?>
        </div>
    </main>

    <?php
        $conn->close(); 
        include_once 'layouts/footer.php'; 
    ?>

    <script type="module" src="scripts/banner-slide.js"></script>
    <script type="module" src="scripts/search.js"></script>
    <script type="module" src="scripts/script.js"></script>
</body>
</html>
