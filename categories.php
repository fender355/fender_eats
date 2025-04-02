<?php include('partials-front/menu.php'); ?>

    <!-- Categories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <?php 
            // Get categories from database that are active
            $sql = "SELECT * FROM food_category WHERE active='Yes'";
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

<?php include('partials-front/footer.php'); ?>