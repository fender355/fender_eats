<?php include('partials-front/menu.php'); ?>

    <!-- Food Search Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food..." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <!-- Categories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <?php 
            // Get categories from database that are active
            $sql = "SELECT * FROM food_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
            $res = mysqli_query($conn, $sql);

            // Check if categories exist
            if(mysqli_num_rows($res) > 0) {
                // Loop through categories
                while($row = mysqli_fetch_assoc($res)) {
                    $category_id = $row['category_id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    ?>
                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $category_id; ?>">
                        <div class="box-3 float-container">
                            <div class="category-image-wrapper">
                                <?php 
                                // Check if image is available
                                if($image_name != "") {
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" 
                                        alt="<?php echo $title; ?>" 
                                        class="img-responsive img-curve">
                                    <?php
                                } else {
                                    // Display placeholder if image missing
                                    echo "<div class='image-placeholder'>No Image</div>";
                                }
                                ?>
                            </div>
                            <h3 class="float-text text-white"><?php echo $title; ?></h3>
                        </div>
                    </a>
                    <?php
                }
            } else {
                // No categories found
                echo "<div class='error'>No Categories Available</div>";
            }
            ?>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->

    <!-- Food Menu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <div class="food-menu-container">
                <?php 
                $sql = "SELECT * FROM food_food WHERE active='Yes' AND featured='Yes' LIMIT 6";
                $res = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $title = htmlspecialchars($row['title']);
                        $price = number_format($row['price'], 2);
                        $description = htmlspecialchars($row['description']);
                        $image_name = $row['image_name'];
                        ?>
                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php if($image_name != ""): ?>
                                    <img src="<?= SITEURL ?>images/food/<?= $image_name ?>" 
                                         alt="<?= $title ?>" 
                                         class="img-responsive img-curve">
                                <?php else: ?>
                                    <div class="error">Image Not Available</div>
                                <?php endif; ?>
                            </div>

                            <div class="food-menu-desc">
                                <h4><?= $title ?></h4>
                                <p class="food-price">RM <?= $price ?></p>
                                <p class="food-detail"><?= $description ?></p>
                                <a href="<?= SITEURL ?>order.php?food_id=<?= $id ?>" 
                                   class="btn btn-primary">Order Now</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='error text-center'>No Food Available</div>";
                }
                ?>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Food Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>