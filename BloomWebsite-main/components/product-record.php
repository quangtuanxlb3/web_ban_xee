<tr>
    <td><?php echo $id ?></td>
    <td><img src="<?php echo $image_url?>" alt="<?php echo $name ?>" class="image-preview" /></td>
    <td><?php echo $name ?></td>
    <td><?php echo $type ?></td>
    <td><?php echo number_format($price * 1000) ?></td>
    <td><?php echo $discount ?>%</td>
    <td><?php echo $quantity ?></td>
    <td>
        <div class="action-buttons">
            <button class="action-btn edit-btn" data-id="<?php echo $id ?>" data-name="<?php echo htmlspecialchars($name) ?>" data-price="<?php echo $price ?>" data-discount="<?php echo $discount ?>" data-quantity="<?php echo $quantity ?>" data-type="<?php echo $type ?>" data-image="<?php echo $image_url ?>" data-description="<?php echo htmlspecialchars($description ?? '') ?>"><i class="fa-solid fa-edit"></i></button>
            <form method="POST" action="/BloomWebsite/composables/products/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                <input type="hidden" name="product_id" value="<?php echo $id ?>">
                <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    </td>
</tr>