<?php
session_start();

// Validate cart contents before showing checkout page
if (!empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) {
        return isset($item['id']) && is_numeric($item['id']) && $item['id'] > 0;
    });
    
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart_message'] = "Your cart contains invalid items and was cleared!";
        header("Location: cart.php");
        exit();
    }
}
include('partials-front/menu.php');

if(empty($_SESSION['cart'])) {
    $_SESSION['cart_message'] = "Your cart is empty!";
    header("Location: cart.php");
    exit();
}
?>

<section class="checkout-section">
    <div class="container">
        <h2 class="text-center">Checkout</h2>
        
        <div class="checkout-wrapper">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <ul>
                    <?php 
                    $total = 0;
                    foreach($_SESSION['cart'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <li>
                        <span><?= htmlspecialchars($item['title']) ?> x<?= $item['quantity'] ?></span>
                        <span>RM <?= number_format($itemTotal, 2) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="total">
                    <span>Total:</span>
                    <span>RM <?= number_format($total, 2) ?></span>
                </div>
            </div>

            <form class="checkout-form" action="place_order.php" method="POST">
                <h3>Delivery Information</h3>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="tel" name="contact" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <textarea name="address" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Place Order</button>
            </form>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>