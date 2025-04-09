<?php
include('partials-front/menu.php');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach($_POST['quantities'] as $key => $quantity) {
        if(isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] = max(1, intval($quantity));
        }
    }
    $_SESSION['success'] = "Cart updated!";
}

header("Location: cart.php");
exit();
?>