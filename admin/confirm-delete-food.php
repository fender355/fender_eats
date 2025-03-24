<?php 
include('../config/constants.php');
include('partials/menu.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM food_food WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    
    if($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $image_name = $row['image_name'];
        $description = $row['description'];
        $price = $row['price'];
        $category_id = $row['category_id'];
        $featured = $row['featured'];
        $active = $row['active'];
    } else {
        $_SESSION['delete'] = "<div class='error'>Food item not found.</div>";
        header('location:'.SITEURL.'admin/food-manage.php');
        exit();
    }
} else {
    header('location:'.SITEURL.'admin/food-manage.php');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Delete Food: <?php echo $title; ?>?</h1>
        <br>
        
        <div class="text-margin">
            <p>Are you sure you want to delete this food item?</p>
            <br>
            
            <div class="text-margin">
                <h3>Food Details:</h3>
                <p>Title: <?php echo $title; ?></p>
                <p>Description: <?php echo $description; ?></p>
                <p>Price: RM <?php echo $price; ?></p>
                <?php if($image_name != ""): ?>
                <p>Image: <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" width="100px"></p>
                <?php endif; ?>
                <p>Category ID: <?php echo $category_id; ?></p>
                <p>Featured: <?php echo $featured; ?></p>
                <p>Active: <?php echo $active; ?></p>
            </div>
            <br>
            
            <form action="<?php echo SITEURL; ?>admin/delete-food.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-buttons">
                    <input type="submit" name="delete" value="Confirm Delete" class="btn-danger">
                    <a href="<?php echo SITEURL; ?>admin/food-manage.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>