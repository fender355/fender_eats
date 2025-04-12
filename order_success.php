<?php
session_start();
include('partials-front/menu.php');

if (empty($_GET['id']) || !isset($_SESSION['order_success'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_GET['id'];
unset($_SESSION['order_success']);
?>

<section class="order-success">
    <div class="container">
        <h1>Order Placed Successfully!</h1>
        <p>Your order ID is: <strong>#<?= $order_id ?></strong></p>
        <p>We've received your order and will prepare it soon.</p>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>