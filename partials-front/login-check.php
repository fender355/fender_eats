<?php 

    include('config/constants.php'); 

    //Authorization - Access Control
    if(!isset($_SESSION['user'])) {
        $_SESSION['no-login-message'] = "<div class='error'>Please login first. </div>";
        header('Location: ' . SITEURL . 'login.php'); 
    }

?>