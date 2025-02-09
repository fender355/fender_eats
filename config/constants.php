<?php 

    //Start Session
    session_start();

    //Create Constants to Store Non Repeating Values
    define('SITEURL', 'http://localhost/fender_eats/');
    define('LOCALHOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'fender_eats');

    //Execute SQL Query and Save Data into Database
    $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die(mysqli_error()); // Init Database Connection
    $db_select = mysqli_select_db($conn, DB_NAME) or die(mysqli_error()); // Select Database

?>