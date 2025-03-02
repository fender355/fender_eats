<?php 

    //Authorization - Access Control
    if(!isset($_SESSION['user'])) {
        $_SESSION['no-login-message'] = "<div class='error'>Please login to access the Admin Panel. </div>";
        header('Location: ' . SITEURL . 'admin/login.php'); 
    }

?>