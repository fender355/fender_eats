<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = filter_input(INPUT_POST, 'item_key', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if($key !== false && $quantity !== false && $quantity > 0 && isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
        $_SESSION['cart_message'] = "Cart updated!";
    } else {
        $_SESSION['cart_message'] = "Invalid update request!";
    }
}

header("Location: cart.php");
exit();
?>