<?php 
include('partials-front/menu.php');

if(empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty!";
    header("Location: cart.php");
    exit();
}
?>

<section class="food-search">
    <div class="container">
        <h2 class="text-center text-white">Checkout</h2>

        <form action="place_order.php" method="POST" class="order">
            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full_name" placeholder="E.g. John Doe" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="E.g. 012-3456789" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="E.g. john@example.com" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="5" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                <input type="submit" name="submit" value="Place Order" class="btn btn-primary">
            </fieldset>
        </form>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>