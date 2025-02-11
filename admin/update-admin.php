<?php 
include('partials/menu.php'); 
include('../config/constants.php');

// Get ID from GET request
if(isset($_GET['admin_acc_id'])) {
    $admin_acc_id = $_GET['admin_acc_id'];
    
    // Get admin details
    $sql = "SELECT * FROM admin_acc WHERE admin_acc_id=$admin_acc_id";
    $res = mysqli_query($conn, $sql);
    
    if($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $name = $row['admin_name'];
        $email = $row['admin_mail'];
    } else {
        $_SESSION['delete'] = "<div class='error'>Admin not found. </div>";
        header('location:'.SITEURL.'admin/admin-manage.php');
        exit();
    }
} else {
    header('location:'.SITEURL.'admin/admin-manage.php');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class = "text-margin">Update <?php echo $name; ?></h1>

        <br>

        <form action="" method="POST">

            <table class = "tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="admin_name" value="<?php echo $name; ?>" placeholder="<?php echo $name; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="admin_mail" value="<?php echo $email; ?>" placeholder="<?php echo $email; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan = "3" class = "text-margin">
                        <br>
                        <input type="hidden" name="admin_acc_id" value="<?php echo $admin_acc_id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class = "btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/admin-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>

            </table>

        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php

    if(isset($_POST['submit'])) {

        // Get data from Form
        $admin_acc_id = $_POST['admin_acc_id'];
        $admin_name = $_POST['admin_name'];
        $admin_mail = $_POST['admin_mail'];

        //SQL Query to Save Data into Database
        $sql = "UPDATE admin_acc SET
            admin_name = '$admin_name',
            admin_mail = '$admin_mail'
            WHERE admin_acc_id = '$admin_acc_id'
        ";
        
        //Executing Query and Saving Data into Database 
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

        //Check whether the (Query is Executed) Data is Inserted or Not, And Display Appropriate Message
        if($res == TRUE) {

            //Create A Session Variable to Display Message
            $_SESSION['update'] = "<div class='success'>Admin updated successfully. </div>"; 

            //Redirect Page to Manage Administrators
            header("location: ".SITEURL.'admin/admin-manage.php');

        }

        else {

            //Failed to Insert Data
            //echo "Failed to Insert Data";

            //Create A Session Variable to Display Message
            $_SESSION['update'] = "<div class='error>Failed to update this admin. </div>"; 

            //Redirect Page to Add Administrator 
            header("location: ".SITEURL.'admin/add-admin.php');

        }

    }

?>