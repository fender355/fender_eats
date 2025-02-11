<?php 
include('../config/constants.php');
include('partials/menu.php');

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
        $_SESSION['delete'] = "<div class='error'>Admin not found.</div>";
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
        <h1 class = "text-margin">Delete <?php echo $name; ?>?</h1>
        <br>
        
        <div class="text-margin">
            <p>Are you sure you want to delete this administrator?</p>
            <br>
            
            <div class="text-margin">
                <h3>Admin Details:</h3>
                <p>Name: <?php echo $name; ?></p>
                <p>Email: <?php echo $email; ?></p>
            </div>
            <br>
            
            <form action="delete-admin.php" method="POST">
                <input type="hidden" name="admin_acc_id" value="<?php echo $admin_acc_id; ?>">
                <div class="form-buttons">
                    <input type="submit" name="delete" value="Confirm Delete" class="btn-danger">
                    <a href="<?php echo SITEURL; ?>admin/admin-manage.php" class="btn-secondary">Cancel</a>
                </div>
            </form>

            <br>

        </div>
        
    </div>
</div>

<?php include('partials/footer.php'); ?>