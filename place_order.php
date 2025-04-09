<?php
include('partials-front/menu.php');

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    // Get user details
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Calculate total
    $total_price = 0;
    foreach($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert order header
        $stmt = $conn->prepare("INSERT INTO tbl_order_header 
            (user_name, user_contact, user_email, user_address, total_price, order_date, status)
            VALUES (?, ?, ?, ?, ?, NOW(), 'Ordered')");
        $stmt->bind_param("ssssd", $full_name, $contact, $email, $address, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO tbl_order_items 
            (order_id, food_id, quantity, price, total)
            VALUES (?, ?, ?, ?, ?)");

        foreach($_SESSION['cart'] as $item) {
            $food_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $price * $quantity;

            $stmt->bind_param("iiidd", $order_id, $food_id, $quantity, $price, $total);
            $stmt->execute();
        }

        // Commit transaction
        mysqli_commit($conn);
        
        // Clear cart
        unset($_SESSION['cart']);
        $_SESSION['success'] = "Order placed successfully!";
        header("Location: index.php");
    } catch(Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error placing order: ".$e->getMessage();
        header("Location: checkout.php");
    }
    exit();
}

header("Location: cart.php");
?>