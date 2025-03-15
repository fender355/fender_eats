<?php
include('../config/constants.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "<pre>DEBUG:\n";
print_r($_POST);
echo "</pre>";

if(isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Get category data first
    $sql = "SELECT * FROM food_category WHERE category_id = $category_id";
    $res = mysqli_query($conn, $sql);

    if($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $image_name = $row['image_name'];
        
        // Delete image if exists
        if(!empty($image_name)) {
            $path = "../images/category/".$image_name;
            $remove = unlink($path);
            
            if(!$remove) {
                $_SESSION['delete'] = "<div class='error'>Failed to remove image file.</div>";
                header('location:'.SITEURL.'admin/category-manage.php');
                die();
            }
        }

        // Delete from database
        $sql_delete = "DELETE FROM food_category WHERE category_id = $category_id";
        $res_delete = mysqli_query($conn, $sql_delete);

        if($res_delete) {
            $_SESSION['delete'] = "<div class='success'>Category deleted successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Database deletion failed: ".mysqli_error($conn)."</div>";
        }
    } else {
        $_SESSION['delete'] = "<div class='error'>Category not found.</div>";
    }
    
    header('location:'.SITEURL.'admin/category-manage.php');
} else {
    $_SESSION['delete'] = "<div class='error'>Invalid request.</div>";
    header('location:'.SITEURL.'admin/category-manage.php');
}
?>