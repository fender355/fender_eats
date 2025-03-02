<?php 

include('../config/constants.php'); 
include('login-check.php'); 

?>

<html>
    <head>

        <title>Fender Eats - Admin Home Page</title>

        <link rel="stylesheet" href="../css/admin.css">

    </head>

    <body>

        <!-- Menu Section Starts --> 
        <div class = "menu">
            <div class = "wrapper">
                <ul>
                    <h3 class = "admin-panel-text"> Fender Eats Admin Panel </h3>
                    <li><a href = "index.php">ADMIN HOME</a></li>
                    <li><a href = "admin-manage.php">ADMIN MANAGER</a></li>
                    <li><a href = "category-manage.php">CATEGORY</a></li>
                    <li><a href = "food-manage.php">FOOD</a></li>
                    <li><a href = "order-manage.php">ORDER</a></li>
                    <li><a href = "logout.php">LOGOUT</a></li>
                </ul>
            </div>
        </div>
        <!-- Menu Section Ends -->