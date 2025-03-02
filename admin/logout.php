<?php 

    include('../config/constants.php');

    session_destroy(); //Unset $_SESSION['user']

    header('Location: ' . SITEURL . 'admin/login.php');
    
?>