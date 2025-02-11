<?php

    include('../config/constants.php');

    //Get Admin ID to Delete
    $admin_acc_id = $_GET['admin_acc_id'];

    //SQL Query to Delete
    $sql = "DELETE FROM admin_acc WHERE admin_acc_id=$admin_acc_id";

    //Execute
    $res = mysqli_query($conn, $sql);

    if($res == TRUE) {

        //echo "This Administrator is Deleted Successfully. "; 

        //Success session Starts
        $_SESSION['delete'] = "<div class='success'>This Administrator is Deleted Successfully. </div>" ; 
        header('location:'.SITEURL.'admin/admin-manage.php');

    }
    else {

        //echo "Failed to Delete This Administrator Successfully. ";

        //~
        $_SESSION['delete'] = "<div class='error'>Failed to Delete This Administrator.  Please try again later. </div>";
        header('location:'.SITEURL.'admin/admin-manage.php');

    }

    //Redirect to Manage Admin (Success or Not)

?>