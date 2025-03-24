<?php
include('../config/constants.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    // Get food data first
    $sql = "SELECT * FROM food_food WHERE id = $id";
    $res = mysqli_query($conn, $sql);

    if($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $image_name = $row['image_name'];
        
        // Delete image if exists
        if(!empty($image_name)) {
            $path = "../images/food/".$image_name;
            $remove = unlink($path);
            
            if(!$remove) {
                $_SESSION['delete'] = "<div class='error'>Failed to remove image file.</div>";
                header('location:'.SITEURL.'admin/food-manage.php');
                die();
            }
        }

        // Delete from database
        $sql_delete = "DELETE FROM food_food WHERE id = $id";
        $res_delete = mysqli_query($conn, $sql_delete);

        if($res_delete) {
            $_SESSION['delete'] = "<div class='success'>Food deleted successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Database deletion failed: ".mysqli_error($conn)."</div>";
        }
    } else {
        $_SESSION['delete'] = "<div class='error'>Food item not found.</div>";
    }
    
    header('location:'.SITEURL.'admin/food-manage.php');
} else {
    $_SESSION['delete'] = "<div class='error'>Invalid request.</div>";
    header('location:'.SITEURL.'admin/food-manage.php');
}
?>