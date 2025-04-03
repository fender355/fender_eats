<?php include('partials-front/menu.php'); ?>

<?php
// Check if category_id is set
if(isset($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];

    // Get category title and validate existence
    $sql_category = "SELECT title FROM food_category WHERE category_id = $category_id AND active='Yes'";
    $res_category = mysqli_query($conn, $sql_category);

    if($res_category && mysqli_num_rows($res_category) == 1) {
        $row_category = mysqli_fetch_assoc($res_category);
        $category_title = $row_category['title'];
    } else {
        $_SESSION['error'] = "Category not found or unavailable.";
        header('location:'.SITEURL.'categories.php');
        exit();
    }
} else {
    header('location:'.SITEURL.'categories.php');
    exit();
}
?>

    <!-- Food Search Section Starts Here -->
    <section class="food-search">
        <div class="container">
            <div class="category-header">
                <a href="<?php echo SITEURL; ?>categories.php" class="btn-back">
                    ← Back to Categories
                </a>
                <h2 class="text-center">Foods in <span class="text-white">"<?php echo htmlspecialchars($category_title); ?>"</span></h2>
            </div>
        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <!-- Food Menu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <div class="food-menu-container">
                <?php 
                // Get foods for the selected category
                $sql_foods = "SELECT * FROM food_food 
                             WHERE category_id = $category_id 
                             AND active='Yes'";
                $res_foods = mysqli_query($conn, $sql_foods);

                if($res_foods) {
                    if(mysqli_num_rows($res_foods) > 0) {
                        while($row_food = mysqli_fetch_assoc($res_foods)) {
                            $id = $row_food['id'];
                            $title = htmlspecialchars($row_food['title']);
                            $price = number_format($row_food['price'], 2);
                            $description = htmlspecialchars($row_food['description']);
                            $image_name = $row_food['image_name'];
                            ?>
                            <div class="food-menu-box">
                                <div class="food-menu-img">
                                    <?php if(!empty($image_name)): ?>
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" 
                                             alt="<?php echo $title; ?>" 
                                             class="img-responsive img-curve">
                                    <?php else: ?>
                                        <div class="error">Image Not Available</div>
                                    <?php endif; ?>
                                </div>

                                <div class="food-menu-desc">
                                    <h4><?php echo $title; ?></h4>
                                    <p class="food-price">RM <?php echo $price; ?></p>
                                    <p class="food-detail"><?php echo $description; ?></p>
                                    <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" 
                                       class="btn btn-primary">Order Now</a>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='error text-center'>No foods available in this category.</div>";
                    }
                } else {
                    echo "<div class='error text-center'>Database query error.</div>";
                }
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <!-- Food Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>