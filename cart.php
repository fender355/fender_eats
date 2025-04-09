<?php include('partials-front/menu.php'); ?>

<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Your Cart</h2>

        <?php
        if(isset($_SESSION['success'])) {
            echo '<div class="success">'.$_SESSION['success'].'</div>';
            unset($_SESSION['success']);
        }
        if(isset($_SESSION['error'])) {
            echo '<div class="error">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="update_cart.php" method="POST">
            <table class="tbl-30">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>

                <?php
                $grand_total = 0;
                if(!empty($_SESSION['cart'])) :
                    foreach($_SESSION['cart'] as $key => $item) :
                        $item_total = $item['price'] * $item['quantity'];
                        $grand_total += $item_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td>RM <?= number_format($item['price'], 2) ?></td>
                    <td>
                        <input type="number" name="quantities[<?= $key ?>]" 
                               value="<?= $item['quantity'] ?>" min="1" class="input-responsive">
                    </td>
                    <td>RM <?= number_format($item_total, 2) ?></td>
                    <td><a href="remove_from_cart.php?key=<?= $key ?>" class="btn btn-danger">Remove</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                    <td>RM <?= number_format($grand_total, 2) ?></td>
                    <td></td>
                </tr>
                <?php else : ?>
                <tr>
                    <td colspan="5" class="error">Your cart is empty</td>
                </tr>
                <?php endif; ?>
            </table>

            <?php if(!empty($_SESSION['cart'])) : ?>
            <div class="text-center" style="margin-top: 20px;">
                <input type="submit" name="update" value="Update Cart" class="btn btn-primary">
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>