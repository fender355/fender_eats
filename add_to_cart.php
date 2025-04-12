<?php
session_start();
include('config/constants.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = filter_input(INPUT_POST, 'food_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if($food_id && $quantity && $quantity > 0) {
        $stmt = $conn->prepare("SELECT id, title, price FROM food_food WHERE id = ? AND active='Yes'");
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows === 1) {
            $food = $result->fetch_assoc();
            
            // Initialize cart if not exists
            if(!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Check if item already in cart
            $found = false;
            foreach($_SESSION['cart'] as &$item) {
                if($item['id'] === $food_id) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            if(!$found) {
                $_SESSION['cart'][] = [
                    'id' => $food_id,
                    'title' => $food['title'],
                    'price' => $food['price'],
                    'quantity' => $quantity
                ];
            }
            
            $_SESSION['cart_message'] = "Item added to cart!";
        } else {
            $_SESSION['cart_message'] = "Item not found!";
        }
    } else {
        $_SESSION['cart_message'] = "Invalid quantity!";
    }
}

header("Location: cart.php");
exit();
?>