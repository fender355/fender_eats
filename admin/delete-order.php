<?php
include('../config/constants.php');

if(isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // First delete order items
    $sql_items = "DELETE FROM tbl_order_items WHERE order_id=$order_id";
    $res_items = mysqli_query($conn, $sql_items);

    // Then delete order header
    $sql_header = "DELETE FROM tbl_order_header WHERE id=$order_id";
    $res_header = mysqli_query($conn, $sql_header);

    if($res_items && $res_header) {
        $_SESSION['delete'] = "<div class='success'>Order and its items deleted successfully.</div>";
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to delete order.</div>";
    }
    
    header('location:'.SITEURL.'admin/order-manage.php');
} else {
    $_SESSION['delete'] = "<div class='error'>Unauthorized access.</div>";
    header('location:'.SITEURL.'admin/order-manage.php');
}
?>