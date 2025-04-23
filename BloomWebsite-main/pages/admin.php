<?php
  include_once $_SERVER['DOCUMENT_ROOT'] . "/BloomWebsite/config/connect.php";

  session_start();
  if (!isset($_SESSION['user_role'])) {
    header("Location: /BloomWebsite/");
    exit();
  }
  
  $sessionMessages = [];
  
  if (isset($_SESSION['success'])) {
    $sessionMessages[] = ['type' => 'success', 'text' => $_SESSION['success']];
    unset($_SESSION['success']);
  }
  
  if (isset($_SESSION['error'])) {
    $sessionMessages[] = ['type' => 'error', 'text' => $_SESSION['error']];
    unset($_SESSION['error']);
  }
?>


<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Quản Lý Website Bán Hoa</title>
    <link rel="shortcut icon" href="/BloomWebsite/public/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/BloomWebsite/styles/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="admin-container">
      <header class="header">
        <div class="left-side">
          <i class="fa-solid fa-bars toggle-btn"></i>
          <a href="/BloomWebsite">
            <h1>Bloom Website</h1>
          </a>
        </div>
        <ul class="right-side">
          <li><a href="/BloomWebsite/"><i class="fas fa-home"></i>Trang chủ</a></li>
          <li><a href="/BloomWebsite/composables/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Đăng xuất</a></li>
        </ul>
      </header>
      <div class="content-wrapper">
        <nav class="sidebar-container">
            <div class="category">
              <h2 class="category-title">Sản Phẩm</h2>
              <ul>
                <li class="menu-item active" data-section="product-list"><i class="fa-solid fa-list-ul"></i><span class="menu-text">Danh sách sản phẩm</span></li>
                <li class="menu-item" data-section="product-add"><i class="fa-solid fa-plus"></i><span class="menu-text">Thêm sản phẩm</span></li>
                <li class="menu-item" data-section="product-categories"><i class="fa-solid fa-tag"></i><span class="menu-text">Phân loại</span></li>
              </ul>
            </div>
            <div class="category">
              <h2 class="category-title">Đơn Hàng</h2>
              <ul>
                <li class="menu-item" data-section="order-list"><i class="fa-solid fa-shopping-cart"></i><span class="menu-text">Đơn hàng mới</span></li>
                <li class="menu-item" data-section="order-processing"><i class="fa-solid fa-box"></i><span class="menu-text">Đang xử lý</span></li>
                <li class="menu-item" data-section="order-history"><i class="fa-solid fa-clock-rotate-left"></i><span class="menu-text">Lịch sử đơn hàng</span></li>
              </ul>
            </div>
            <div class="category">
              <h2 class="category-title">Người Dùng</h2>
              <ul>
                <li class="menu-item" data-section="user-list"><i class="fa-solid fa-users"></i><span class="menu-text">Danh sách người dùng</span></li>
                <li class="menu-item" data-section="user-add"><i class="fa-solid fa-user-plus"></i><span class="menu-text">Thêm người dùng</span></li>
              </ul>
            </div>
            <div class="category">
              <h2 class="category-title">Thống Kê</h2>
              <ul>
                <li class="menu-item" data-section="dashboard"><i class="fa-solid fa-chart-line"></i><span class="menu-text">Tổng quan</span></li>
                <li class="menu-item" data-section="sales-report"><i class="fa-solid fa-file-invoice-dollar"></i><span class="menu-text">Báo cáo doanh thu</span></li>
              </ul>
            </div>
        </nav>
        <main>
          <section id="dashboard" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Tổng Quan</h2>
              <div class="dashboard-actions">
                <button class="btn"><i class="fa-solid fa-download"></i> Xuất Báo Cáo</button>
              </div>
            </div>
            
            <div class="stats-container">
              <div class="stat-card">
                <h3>Tổng Sản Phẩm</h3>
                <div class="stat-value">125</div>
              </div>
              <div class="stat-card">
                <h3>Đơn Hàng Hôm Nay</h3>
                <div class="stat-value">24</div>
              </div>
              <div class="stat-card">
                <h3>Doanh Thu Tháng</h3>
                <div class="stat-value">14.5M đ</div>
              </div>
              <div class="stat-card">
                <h3>Khách Hàng Mới</h3>
                <div class="stat-value">12</div>
              </div>
            </div>
            
            <div class="data-table-container">
              <h3 style="margin-bottom: 15px;">Đơn Hàng Gần Đây</h3>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Sản Phẩm</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#ORD123</td>
                    <td>Nguyễn Văn A</td>
                    <td>Bó Hoa Hồng Đỏ</td>
                    <td>350,000 đ</td>
                    <td>Đã Giao</td>
                    <td>15/04/2025</td>
                  </tr>
                  <tr>
                    <td>#ORD124</td>
                    <td>Trần Thị B</td>
                    <td>Hoa Cúc Vàng (x2)</td>
                    <td>420,000 đ</td>
                    <td>Đang Giao</td>
                    <td>15/04/2025</td>
                  </tr>
                  <tr>
                    <td>#ORD125</td>
                    <td>Lê Văn C</td>
                    <td>Hoa Hướng Dương (x3)</td>
                    <td>580,000 đ</td>
                    <td>Đang Xử Lý</td>
                    <td>16/04/2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
          
          <section id="product-list" class="content-section active">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Danh Sách Sản Phẩm</h2>
              <div class="dashboard-actions">
                <button class="btn" id="add-product-btn"><i class="fa-solid fa-plus"></i> Thêm Sản Phẩm</button>
              </div>
            </div>
            
            <div class="filter-container">
              <div class="search-box">
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button class="btn"><i class="fa-solid fa-search"></i></button>
              </div>
              <div>
                <select class="filter-dropdown">
                  <option value="">Tất cả loại hoa</option>
                  <option value="hoahong">Hoa Hồng</option>
                  <option value="hoacuc">Hoa Cúc</option>
                  <option value="hoahuongduong">Hoa Hướng Dương</option>
                </select>
              </div>
            </div>
            
            <div class="data-table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá Gốc</th>
                    <th>Giảm Giá</th>
                    <th>Số Lượng</th>
                    <th>Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="product-body">

                </tbody>
              </table>
              <div class="pagination" id="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
              </div>
            </div>
          </section>
          
          <section id="product-add" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Thêm Sản Phẩm Mới</h2>
            </div>
            
            <div class="form-container" style="display: block;">
              <form id="add-product-form">
                <div class="form-group">
                  <label for="product-name">Tên Sản Phẩm</label>
                  <input type="text" id="product-name" name="product-name" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="product-type">Loại Hoa</label>
                  <select id="product-type" name="product-type" class="form-control" required>
                    <option value="">-- Chọn loại hoa --</option>
                    <option value="hoahong">Hoa Hồng</option>
                    <option value="hoacuc">Hoa Cúc</option>
                    <option value="hoahuongduong">Hoa Hướng Dương</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="product-discount">Giảm Giá (%)</label>
                  <input type="number" id="product-discount" name="product-discount" class="form-control" min="0" max="100" value="0">
                </div>
                
                <div class="form-group">
                  <label for="product-quantity">Số Lượng</label>
                  <input type="number" id="product-quantity" name="product-quantity" class="form-control" min="0" required>
                </div>
                
                <div class="form-group">
                  <label for="product-image">Hình Ảnh</label>
                  <input type="file" id="product-image" name="product-image" class="form-control">
                </div>
                
                <div class="form-group">
                  <label for="product-description">Mô Tả</label>
                  <textarea id="product-description" name="product-description" class="form-control" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                  <label for="product-price">Giá Bán</label>
                  <input type="number" id="product-price" name="product-price" class="form-control" min="0" required>
                </div>
                
                <div class="form-actions">
                  <button type="button" class="btn btn-secondary" id="cancel-add">Hủy</button>
                  <button type="submit" class="btn">Lưu Sản Phẩm</button>
                </div>
              </form>
            </div>
          </section>
          
          <section id="user-list" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Danh Sách Người Dùng</h2>
              <div class="dashboard-actions">
                <button class="btn" id="add-user-btn"><i class="fa-solid fa-user-plus"></i> Thêm Người Dùng</button>
              </div>
            </div>
            
            <div class="filter-container">
              <div class="search-box">
                <input type="text" placeholder="Tìm kiếm người dùng...">
                <button class="btn"><i class="fa-solid fa-search"></i></button>
              </div>
              <div>
                <select class="filter-dropdown">
                  <option value="">Tất cả vai trò</option>
                  <option value="admin">Admin</option>
                  <option value="customer">Khách hàng</option>
                </select>
              </div>
            </div>
            
            <div class="data-table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Vai Trò</th>
                    <th>Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              
              <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
              </div>
            </div>
          </section>
          
          <section id="order-list" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Đơn Hàng Mới</h2>
            </div>
            
            <div class="filter-container">
              <div class="search-box">
                <input type="text" placeholder="Tìm kiếm đơn hàng...">
                <button class="btn"><i class="fa-solid fa-search"></i></button>
              </div>
              <div>
                <select class="filter-dropdown">
                  <option value="">Tất cả đơn hàng</option>
                  <option value="new" selected>Mới</option>
                  <option value="processing">Đang xử lý</option>
                  <option value="shipped">Đang giao</option>
                  <option value="completed">Đã giao</option>
                </select>
              </div>
            </div>
            
            <div class="data-table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Sản Phẩm</th>
                    <th>Tổng Tiền</th>
                    <th>Ngày Đặt</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#ORD126</td>
                    <td>Hoàng Văn D</td>
                    <td>Hoa Hồng Đỏ (x2)</td>
                    <td>680,000 đ</td>
                    <td>16/04/2025</td>
                    <td>Mới</td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn edit-btn"><i class="fa-solid fa-edit"></i></button>
                        <button class="action-btn" style="background-color: #28a745; color: white;"><i class="fa-solid fa-check"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#ORD127</td>
                    <td>Phạm Thị E</td>
                    <td>Hoa Hướng Dương (x1)</td>
                    <td>320,000 đ</td>
                    <td>16/04/2025</td>
                    <td>Mới</td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn edit-btn"><i class="fa-solid fa-edit"></i></button>
                        <button class="action-btn" style="background-color: #28a745; color: white;"><i class="fa-solid fa-check"></i></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <div class="pagination">
                <button class="active">1</button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
              </div>
            </div>
          </section>
          
          <section id="user-add" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Thêm Người Dùng Mới</h2>
            </div>
            
            <div class="form-container" style="display: block;">
              <form id="add-user-form">
                <div class="form-group">
                  <label for="user-name">Họ và Tên</label>
                  <input type="text" id="user-name" name="user-name" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="username">Tên đăng nhập</label>
                  <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="user-email">Email</label>
                  <input type="email" id="user-email" name="user-email" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="user-password">Mật khẩu</label>
                  <input type="password" id="user-password" name="user-password" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="user-confirm-password">Xác nhận mật khẩu</label>
                  <input type="password" id="user-confirm-password" name="user-confirm-password" class="form-control" required>
                </div>
                
                <div class="form-group">
                  <label for="user-role">Vai trò</label>
                  <select id="user-role" class="form-control" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Khách hàng</option>
                  </select>
                </div>
                
                <div class="form-actions">
                  <button type="button" class="btn btn-secondary" id="cancel-add-user">Hủy</button>
                  <button type="submit" class="btn">Lưu Người Dùng</button>
                </div>
              </form>
            </div>
          </section>
          
          <section id="sales-report" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Báo Cáo Doanh Thu</h2>
              <div class="dashboard-actions">
                <button class="btn"><i class="fa-solid fa-download"></i> Xuất Báo Cáo</button>
              </div>
            </div>
            
            <div class="filter-container">
              <div>
                <select class="filter-dropdown">
                  <option value="day">Hôm nay</option>
                  <option value="week">7 ngày qua</option>
                  <option value="month" selected>Tháng này</option>
                  <option value="year">Năm nay</option>
                </select>
              </div>
            </div>
            
            <div class="stats-container">
              <div class="stat-card">
                <h3>Tổng Doanh Thu</h3>
                <div class="stat-value">14.5M đ</div>
              </div>
              <div class="stat-card">
                <h3>Tổng Đơn Hàng</h3>
                <div class="stat-value">98</div>
              </div>
              <div class="stat-card">
                <h3>Đơn Trung Bình</h3>
                <div class="stat-value">450,000 đ</div>
              </div>
              <div class="stat-card">
                <h3>Sản Phẩm Đã Bán</h3>
                <div class="stat-value">156</div>
              </div>
            </div>
            
            <div class="data-table-container">
              <h3 style="margin-bottom: 15px;">Doanh Thu Theo Loại Sản Phẩm</h3>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Loại Sản Phẩm</th>
                    <th>Số Lượng Bán</th>
                    <th>Doanh Thu</th>
                    <th>Tỷ Lệ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Hoa Hồng</td>
                    <td>75</td>
                    <td>7.2M đ</td>
                    <td>49.6%</td>
                  </tr>
                  <tr>
                    <td>Hoa Cúc</td>
                    <td>42</td>
                    <td>3.8M đ</td>
                    <td>26.2%</td>
                  </tr>
                  <tr>
                    <td>Hoa Hướng Dương</td>
                    <td>39</td>
                    <td>3.5M đ</td>
                    <td>24.2%</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
          
          <section id="product-categories" class="content-section">
            <div class="dashboard-header">
              <h2 class="dashboard-title">Phân Loại Sản Phẩm</h2>
              <div class="dashboard-actions">
                <button class="btn" id="add-category-btn"><i class="fa-solid fa-plus"></i> Thêm Loại</button>
              </div>
            </div>
            
            <div class="data-table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Tên Loại</th>
                    <th>Mã Loại</th>
                    <th>Số Sản Phẩm</th>
                    <th>Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Hoa Hồng</td>
                    <td>hoahong</td>
                    <td>42</td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn edit-btn"><i class="fa-solid fa-edit"></i></button>
                        <button class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Hoa Cúc</td>
                    <td>hoacuc</td>
                    <td>35</td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn edit-btn"><i class="fa-solid fa-edit"></i></button>
                        <button class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Hoa Hướng Dương</td>
                    <td>hoahuongduong</td>
                    <td>28</td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn edit-btn"><i class="fa-solid fa-edit"></i></button>
                        <button class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </main>
      </div>
    </div>
    
    <div id="edit-product-modal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Chỉnh Sửa Sản Phẩm</h2>
          <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
          <form id="edit-product-form">
            <input type="hidden" id="edit-product-id" name="product_id">
            
            <div class="form-group">
              <label for="edit-product-name">Tên Sản Phẩm</label>
              <input type="text" id="edit-product-name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
              <label for="edit-product-type">Loại Hoa</label>
              <select id="edit-product-type" name="type" class="form-control" required>
                <option value="">-- Chọn loại hoa --</option>
                <?php
                  $sql_loai = "SELECT * FROM loaisanpham";
                  $result_loai = $conn->query($sql_loai);
                  if ($result_loai->num_rows > 0) {
                    while($row_loai = $result_loai->fetch_assoc()) {
                      echo '<option value="'.$row_loai["id"].'">'.$row_loai["name_type"].'</option>';
                    }
                  }
                ?>
              </select>
            </div>
            
            <div class="form-group">
              <label for="edit-product-price">Giá Bán</label>
              <input type="number" id="edit-product-price" name="price" class="form-control" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="edit-product-discount">Giảm Giá (%)</label>
              <input type="number" id="edit-product-discount" name="discount" class="form-control" min="0" max="100" value="0">
            </div>
            
            <div class="form-group">
              <label for="edit-product-quantity">Số Lượng</label>
              <input type="number" id="edit-product-quantity" name="quantity" class="form-control" min="0" required>
            </div>
            
            <div class="form-group">
              <label>Hình Ảnh Hiện Tại</label>
              <div class="image-preview-container">
                <img id="current-product-image" class="edit-image-preview" src="" alt="Hình ảnh sản phẩm">
              </div>
            </div>
            
            <div class="form-group">
              <label for="edit-product-image">Thay Đổi Hình Ảnh</label>
              <input type="file" id="edit-product-image" name="image" class="form-control">
            </div>
            
            <div class="form-group">
              <label for="edit-product-description">Mô Tả</label>
              <textarea id="edit-product-description" name="description" class="form-control" rows="4"></textarea>
            </div>
            
            <div class="form-actions">
              <button type="button" class="btn btn-secondary close-modal">Hủy</button>
              <button type="submit" class="btn">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <script>
      const sessionMessages = <?php echo json_encode($sessionMessages); ?>;
    </script>
    
    <?php $conn->close()  ?>
    <script type="module" src="/BloomWebsite/scripts/admin.js"></script>
  </body>
</html>