<?php
include('../config/constants.php');

if (isset($_POST['user_acc_id'])) {
    $user_acc_id = $_POST['user_acc_id'];

    $sql = "DELETE FROM user_acc WHERE user_acc_id=$user_acc_id";
    $res = mysqli_query($conn, $sql);

    $_SESSION['delete'] = $res
        ? "<div class='success'>User deleted successfully.</div>"
        : "<div class='error'>Failed to delete user.</div>";

    header("Location: " . SITEURL . "admin/user-manage.php");
} else {
    $_SESSION['delete'] = "<div class='error'>Unauthorized access.</div>";
    header("Location: " . SITEURL . "admin/user-manage.php");
}
