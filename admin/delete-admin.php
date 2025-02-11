<?php
include('../config/constants.php');

if(isset($_POST['admin_acc_id'])) {
    $admin_acc_id = $_POST['admin_acc_id'];

    // SQL Delete
    $sql = "DELETE FROM admin_acc WHERE admin_acc_id=$admin_acc_id";
    $res = mysqli_query($conn, $sql);

    if($res) {
        $_SESSION['delete'] = "<div class='success'>This administrator is deleted successfully. </div>";
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to delete this administrator.</div>";
    }
    
    header('location:'.SITEURL.'admin/admin-manage.php');
} else {
    $_SESSION['delete'] = "<div class='error'>Unauthorized access. </div>";
    header('location:'.SITEURL.'admin/admin-manage.php');
}
?>