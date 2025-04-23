
<div class="flower-card">
    <a href="/BloomWebsite/san-pham/<?php echo $id; ?>" class="flower-link">
        <img src="<?php echo $image_url; ?>" alt="Flower Image">
        <?php if ($discount > 0):?>
            <div class="discount-mark">
                <p class="discount-number">
                    <?php echo($discount . '%' . ' GIẢM') ?>
                </p>
            </div>
        <?php endif ?>
    </a>
    <div class="information"> 
        <h3 class="title"><?php echo $name; ?></h3>
        <div class="price">
            <span class="after-price">
                <?php echo number_format($price * 1000 * (1 - $discount / 100), 0, ',', '.') . ' VND'; ?>
            </span>
            <?php if (!empty($discount) && $discount > 0): ?>
                <span class="original-price">
                    <?php echo number_format($price * 1000, 0, ',', '.') . ' VND'; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <a class="purchase-btn" href="/BloomWebsite/san-pham/<?php echo $id; ?>">Đặt hàng</a>
</div>