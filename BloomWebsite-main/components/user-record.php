<tr>
    <td><?php echo $id ?></td>
    <td><?php echo $name ?></td>
    <td><?php echo $username ?></td>
    <td><?php echo $email ?></td>
    <td><?php echo $role ?></td>
    <td>
    <div class="action-buttons">
        <button class="action-btn edit-btn" data-id="<?php echo $id ?>" data-name="<?php echo htmlspecialchars($name) ?>" data-username="<?php echo htmlspecialchars($username) ?>" data-email="<?php echo htmlspecialchars($email) ?>" data-role="<?php echo $role ?>"><i class="fa-solid fa-edit"></i></button>
        <form method="POST" action="/BloomWebsite/composables/users/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
            <input type="hidden" name="user_id" value="<?php echo $id ?>">
            <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
        </form>
    </div>
    </td>
</tr>