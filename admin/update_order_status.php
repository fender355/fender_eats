<?php
include('../config/constants.php');
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    
    if($order_id && $status) {
        $stmt = $conn->prepare("UPDATE tbl_order_header SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        
        if($stmt->execute()) {
            $_SESSION['update'] = "<div class='success'>Order status updated successfully!</div>";
        } else {
            $_SESSION['update'] = "<div class='failed'>Failed to update status!</div>";
        }
    } else {
        $_SESSION['update'] = "Invalid request!";
    }
}

header("Location: order-manage.php");
exit();
?>