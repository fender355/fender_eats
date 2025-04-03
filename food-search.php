<?php include('partials-front/menu.php'); 

// Check if search term is submitted
if (isset($_POST['search'])) {
    // Sanitize the search term
    $search = mysqli_real_escape_string($conn, $_POST['search']);
} else {
    // Redirect to homepage if search term isn't present
    header('location:'.SITEURL);
    exit();
}
?>

<section class="food-search text-center">
    <div class="container">
        <h2>Foods on Your Search: <a href="#" class="text-white">"<?php echo htmlspecialchars($search); ?>"</a></h2>
        
        <!-- Add search form here -->
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST" class="search-form">
            <input type="search" name="search" placeholder="Search again..." 
                   value="<?php echo htmlspecialchars($search); ?>" required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>

<section class="food-menu">
    <div class="container">
        <br>
        <h2 class="text-center">Food Menu</h2>

        <div class="food-menu-container">
            <?php
            // SQL to find foods matching the search term
            $sql = "SELECT * FROM food_food 
                    WHERE active='Yes' 
                    AND (title LIKE '%$search%' OR description LIKE '%$search%')";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                $count = mysqli_num_rows($res);

                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $title = htmlspecialchars($row['title']);
                        $price = number_format($row['price'], 2);
                        $description = htmlspecialchars($row['description']);
                        $image_name = $row['image_name'];
                        ?>
                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php if (!empty($image_name)) : ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" 
                                         alt="<?php echo $title; ?>" 
                                         class="img-responsive img-curve">
                                <?php else : ?>
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
                    echo '<div class="error text-center">No Food Found.</div>';
                }
            } else {
                echo '<div class="error text-center">Failed to Search Foods.</div>';
            }
            ?>
        </div>

        <div class="clearfix"></div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>