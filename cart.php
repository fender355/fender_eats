<?php 
session_start();
include('partials-front/menu.php');
?>

<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Your Cart</h2>

        <?php
        if(isset($_SESSION['cart_message'])) {
            echo '<div class="alert">'.$_SESSION['cart_message'].'</div>';
            unset($_SESSION['cart_message']);
        }
        ?>

        <?php if(!empty($_SESSION['cart'])): ?>
        <div class="cart-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($_SESSION['cart'] as $key => $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td>RM <?= number_format($item['price'], 2) ?></td>
                        <td>
                            <form method="POST" action="update_cart_item.php" class="quantity-form">
                                <input type="hidden" name="item_key" value="<?= $key ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input">
                                <button type="submit" class="btn-update">↻</button>
                            </form>
                        </td>
                        <td>RM <?= number_format($itemTotal, 2) ?></td>
                        <td>
                            <a href="remove_from_cart.php?key=<?= $key ?>" class="btn-remove">✕</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Grand Total:</td>
                        <td colspan="2">RM <?= number_format($total, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <a href="<?= SITEURL ?>" class="btn-continue">Continue Shopping</a>
                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        </div>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="<?= SITEURL ?>" class="btn-primary">Browse Foods</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>