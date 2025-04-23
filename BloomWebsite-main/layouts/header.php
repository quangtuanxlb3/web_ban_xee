<header class="header">
    <div class="header-title">
        <a href="/BloomWebsite">
            <img src="/BloomWebsite/assets/logo/logo.png" alt="Hoa Xinh Logo" class="logo" width="60px">
        </a>
        <a href="/BloomWebsite">
            <h1 class="title">Bloom Webiste</h1>
        </a>
    </div>
    <div class="right-side">
        <div class="search">
            <input id="searchInput" type="text" placeholder="Tìm kiếm...">
            <i id="searchIcon" class="fas fa-search"></i>
        </div>
        <nav class="header-actions">
            <ul class="navbar">
                <li><a href="/BloomWebsite" class="<?= $_SERVER['REQUEST_URI'] === '/BloomWebsite/' ? 'active' : '' ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
                <li><a href="/BloomWebsite/tai-khoan"  class="<?= str_starts_with($_SERVER['REQUEST_URI'], '/BloomWebsite/tai-khoan') || str_starts_with($_SERVER['REQUEST_URI'], '/BloomWebsite/profile') ? 'active' : '' ?>"><i class="fas fa-user"></i> Tài Khoản</a></li>
                <li><a href="/BloomWebsite/gio-hang" class="<?= $_SERVER['REQUEST_URI'] === '/BloomWebsite/gio-hang' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a></li>
                <li><a href="/BloomWebsite/thanh-toan" class="<?= $_SERVER['REQUEST_URI'] === '/BloomWebsite/thanh-toan' ? 'active' : '' ?>"><i class="fas fa-credit-card"></i> Thanh toán</a></li>
            </ul>
        </nav>
    </div>
</header>