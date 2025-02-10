<?php

    include('../config/constants.php');

    //Get Admin ID to Delete
    $admin_acc_id = $_GET['admin_acc_id'];

    //SQL Query to Delete
    $sql = "DELETE FROM admin_acc WHERE admin_acc_id=$admin_acc_id";

    //Execute
    $res = mysqli_query($conn, $sql);

    if($res == TRUE) {
        echo "This Administrator is Deleted Successfully. "; 
    }
    else {
        echo "Failed to Delete This Administrator Successfully. ";
    }

    //Redirect to Manage Admin (Success or Not)

?>