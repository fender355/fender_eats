<?php
include('partials-front/menu.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = intval($_POST['food_id']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity
    if($quantity < 1) $quantity = 1;

    $stmt = $conn->prepare("SELECT id, title, price FROM food_food WHERE id=?");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {
        $food = $result->fetch_assoc();
        
        // Initialize cart
        if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        // Check if item exists
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

        $_SESSION['success'] = "Item added to cart!";
    } else {
        $_SESSION['error'] = "Food item not found!";
    }
}

header("Location: cart.php");
exit();
?>