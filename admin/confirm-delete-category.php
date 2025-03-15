<?php 
include('../config/constants.php');
include('partials/menu.php');

if(isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    
    $sql = "SELECT * FROM food_category WHERE category_id=$category_id";
    $res = mysqli_query($conn, $sql);
    
    if($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $image_name = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
    } else {
        $_SESSION['delete'] = "<div class='error'>Category not found.</div>";
        header('location:'.SITEURL.'admin/category-manage.php');
        exit();
    }
} else {
    header('location:'.SITEURL.'admin/category-manage.php');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Delete Category: <?php echo $title; ?>?</h1>
        <br>
        
        <div class="text-margin">
            <p>Are you sure you want to delete this category?</p>
            <br>
            
            <div class="text-margin">
                <h3>Category Details:</h3>
                <p>Title: <?php echo $title; ?></p>
                <?php if($image_name != ""): ?>
                <p>Image: <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" width="100px"></p>
                <?php endif; ?>
                <p>Featured: <?php echo $featured; ?></p>
                <p>Active: <?php echo $active; ?></p>
            </div>
            <br>
            
            <form action="<?php echo SITEURL; ?>admin/delete-category.php" method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                <div class="form-buttons">
                    <input type="submit" name="delete" value="Confirm Delete" class="btn-danger">
                    <a href="<?php echo SITEURL; ?>admin/category-manage.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>