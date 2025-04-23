<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/config/connect.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/helpers/components.php';

    $raw_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'name_asc';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $items_per_page = 12;
    $offset = ($page - 1) * $items_per_page;

    $query = "SELECT * FROM sanpham WHERE 1=1";
    $count_query = "SELECT COUNT(*) as total FROM sanpham WHERE 1=1";
    
    if (!empty($raw_keyword)) {
        $escaped_keyword = $conn->real_escape_string($raw_keyword);
        $search_keyword = '%' . $escaped_keyword . '%';
        $query .= " AND (NAME LIKE '$search_keyword' OR description LIKE '$search_keyword')";
        $count_query .= " AND (NAME LIKE '$search_keyword' OR description LIKE '$search_keyword')";
    }
    
    if (!empty($category)) {
        $category = $conn->real_escape_string($category);
        $query .= " AND category = '$category'";
        $count_query .= " AND category = '$category'";
    }
    
    switch ($sort) {
        case 'price_asc':
            $query .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY price DESC";
            break;
        case 'name_desc':
            $query .= " ORDER BY name DESC";
            break;
        case 'newest':
            $query .= " ORDER BY id DESC";
            break;
        default:
            $query .= " ORDER BY name ASC";
    }
    
    $query .= " LIMIT $items_per_page OFFSET $offset";
    
    $count_result = $conn->query($count_query);
    $total_items = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_items / $items_per_page);
    
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/BloomWebsite/styles/reset.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/layout.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

    <title>Tìm kiếm - Bloom Website</title>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/navbar.php'; ?>

    <div class="search-container">
        <div class="search-header">
            <h1>Tìm kiếm sản phẩm</h1>
            <form method="GET" action="/BloomWebsite/tim-kiem" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="keyword" value="<?php echo htmlspecialchars($raw_keyword); ?>" placeholder="Nhập từ khóa tìm kiếm...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                
                <div class="filter-group">
                    <select name="category" id="category">
                        <option value="">Tất cả danh mục</option>
                        <option value="hoa-sinh-nhat" <?php echo ($category == 'hoa-sinh-nhat') ? 'selected' : ''; ?>>Hoa sinh nhật</option>
                        <option value="hoa-tuoi" <?php echo ($category == 'hoa-tuoi') ? 'selected' : ''; ?>>Hoa tươi</option>
                        <option value="lan-ho-diep" <?php echo ($category == 'lan-ho-diep') ? 'selected' : ''; ?>>Lan hồ điệp</option>
                        <option value="hoa-khai-truong" <?php echo ($category == 'hoa-khai-truong') ? 'selected' : ''; ?>>Hoa khai trương</option>
                        <option value="thiet-ke" <?php echo ($category == 'thiet-ke') ? 'selected' : ''; ?>>Thiết kế</option>
                    </select>
                    
                    <select name="sort" id="sort">
                        <option value="name_asc" <?php echo ($sort == 'name_asc') ? 'selected' : ''; ?>>Tên A-Z</option>
                        <option value="name_desc" <?php echo ($sort == 'name_desc') ? 'selected' : ''; ?>>Tên Z-A</option>
                        <option value="price_asc" <?php echo ($sort == 'price_asc') ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?php echo ($sort == 'price_desc') ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                        <option value="newest" <?php echo ($sort == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="search-results">
            <?php if (!empty($raw_keyword)) { ?>
                <h2 class="results-title">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($raw_keyword); ?>"</h2>
            <?php } ?>
            
            <p class="results-count">Tìm thấy <?php echo $total_items; ?> sản phẩm</p>
            
            <?php if ($result->num_rows > 0) { ?>
                <div class="flower-grid">
                    <?php while ($row = $result->fetch_assoc()) {
                        flower_card($row["id"], $row["image_url"], $row["name"], $row["price"], $row["discount"]);
                    } ?>
                </div>
                
                <?php if ($total_pages > 1) { ?>
                    <div class="pagination">
                        <?php if ($page > 1) { ?>
                            <a href="?keyword=<?php echo urlencode($raw_keyword); ?>&category=<?php echo urlencode($category); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page-1; ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php } ?>
                        
                        <?php 
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++) { ?>
                            <a href="?keyword=<?php echo urlencode($raw_keyword); ?>&category=<?php echo urlencode($category); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?>" 
                               class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php } ?>
                        
                        <?php if ($page < $total_pages) { ?>
                            <a href="?keyword=<?php echo urlencode($raw_keyword); ?>&category=<?php echo urlencode($category); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page+1; ?>" class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
                
            <?php } else { ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>Không tìm thấy sản phẩm nào phù hợp</p>
                    <a href="/BloomWebsite/" class="back-home">Quay về trang chủ</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php 
        include $_SERVER['DOCUMENT_ROOT'] . '/BloomWebsite/layouts/footer.php'; 
        $conn->close();
    ?>
    <script>
        document.getElementById('category').addEventListener('change', () => {
            document.querySelector('.search-form').submit();
        });
        
        document.getElementById('sort').addEventListener('change', () => {
            document.querySelector('.search-form').submit();
        });
    </script>
</body>
</html>