<?php include('partials-front/menu.php'); ?>

    <!-- Food Search Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>
        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <!-- Food Menu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <div class="food-menu-container">
                <?php 
                $sql = "SELECT * FROM food_food WHERE active='Yes'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $res = $stmt->get_result();
                
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
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
                                    <div class="image-placeholder">No Image</div>
                                <?php endif; ?>
                            </div>

                            <div class="food-menu-desc">
                                <h4><?= $title ?></h4>
                                <p class="food-price">RM <?= $price ?></p>
                                <p class="food-detail"><?= $description ?></p>
                                <form method="POST" action="add_to_cart.php">
                                    <input type="hidden" name="food_id" value="<?= $id ?>">
                                    <div class="order-label">Quantity</div>
                                    <input type="number" name="quantity" value="1" min="1" class="input-responsive">
                                    <input type="submit" name="submit" value="Add to Cart" class="btn btn-primary">
                                </form>
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