<?php
session_start();
include('config/constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    try {
        $conn->begin_transaction();

        // 1. Validate delivery info
        $required_fields = ['full_name', 'contact', 'email', 'address'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all delivery information!");
            }
        }

        // 2. Validate and sanitize inputs
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        // 3. Validate cart items
        $valid_items = [];
        $total_price = 0;
        
        foreach ($_SESSION['cart'] as $item) {
            if (!isset($item['id']) || !is_numeric($item['id']) || $item['id'] <= 0) {
                throw new Exception("Invalid food ID in cart!");
            }
            
            // Verify food exists in database
            $stmt = $conn->prepare("SELECT id, price FROM food_food WHERE id = ? AND active='Yes'");
            $stmt->bind_param("i", $item['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception("Food item ID {$item['id']} not found or unavailable!");
            }
            
            $db_item = $result->fetch_assoc();
            $valid_items[] = [
                'id' => $db_item['id'],
                'price' => $db_item['price'],
                'quantity' => min(100, max(1, (int)$item['quantity']))
            ];
            
            $total_price += $db_item['price'] * $valid_items[count($valid_items)-1]['quantity'];
        }

        // 4. Insert order header
        $stmt = $conn->prepare("INSERT INTO tbl_order_header 
            (user_name, user_contact, user_email, user_address, total_price, order_date, status)
            VALUES (?, ?, ?, ?, ?, NOW(), 'Pending')");
        $stmt->bind_param("ssssd", $full_name, $contact, $email, $address, $total_price);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create order!");
        }
        
        $order_id = $conn->insert_id;

        // 5. Insert order items
        $stmt = $conn->prepare("INSERT INTO tbl_order_items 
            (order_id, food_id, quantity, price, total)
            VALUES (?, ?, ?, ?, ?)");
            
        foreach ($valid_items as $item) {
            $total = $item['price'] * $item['quantity'];
            $stmt->bind_param("iiidd", $order_id, $item['id'], $item['quantity'], $item['price'], $total);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to add order items!");
            }
        }

        $conn->commit();
        
        // Clear cart and redirect to success page
        unset($_SESSION['cart']);
        $_SESSION['order_success'] = true;
        header("Location: order_success.php?id=$order_id");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['checkout_error'] = "Order failed: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}

// If cart is empty or not POST request
$_SESSION['checkout_error'] = "Invalid order request!";
header("Location: cart.php");
exit();
?>