<?php
session_start();

if(isset($_GET['key'])) {
    $key = filter_input(INPUT_GET, 'key', FILTER_VALIDATE_INT);
    
    if($key !== false && isset($_SESSION['cart'][$key])) {
        array_splice($_SESSION['cart'], $key, 1);
        $_SESSION['cart_message'] = "Item removed from cart!";
    } else {
        $_SESSION['cart_message'] = "Invalid item!";
    }
}

header("Location: cart.php");
exit();
?>