<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <br><br>
        <h1 class="text-margin">Manage Orders</h1><br><br>

        <?php if(isset($_SESSION['update'])): ?>
            <div><?= $_SESSION['update'] ?></div><br>
            <?php unset($_SESSION['update']); ?>
        <?php endif; ?>
        <div class="text-margin">
            <table class="tbl-full">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                
                <?php
                $sql = "SELECT * FROM tbl_order_header ORDER BY order_date DESC";
                $res = $conn->query($sql);
                
                while($row = $res->fetch_assoc()):
                    $status_class = strtolower($row['status']);
                ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td>RM <?= number_format($row['total_price'], 2) ?></td>
                    <td><?= date('M j, Y H:i', strtotime($row['order_date'])) ?></td>
                    <td>
                        <form action="update_order_status.php" method="POST" class="status-form">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" class="status-select <?= $status_class ?>">
                                <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= $row['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="Delivered" <?= $row['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= $row['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-secondary">Update</button>
                        </form>
                    </td>
                    <td>
                        <a href="view_order.php?id=<?= $row['id'] ?>" class="btn-primary">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <br><br>
    </div>
</div>

<?php include('partials/footer.php'); ?>