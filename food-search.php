<?php

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include menu.php with proper path
require_once($_SERVER['DOCUMENT_ROOT'] . '/fender_eats/partials-front/menu.php');

// Check if search term is submitted
if (isset($_POST['search'])) {
    // Sanitize the search term
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    
    // Store search term in session if needed
    $_SESSION['search'] = $search;
} elseif (isset($_SESSION['search'])) {
    $search = $_SESSION['search'];
} else {
    // Use output buffering to prevent header errors
    ob_start();
    header('location:'.SITEURL);
    ob_end_flush();
    exit();
}

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

<!-- Food Search Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <h2>Foods on Your Search: <a href="#" class="text-white">"<?php echo htmlspecialchars($search); ?>"</a></h2>
        
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <input type="search" name="search" placeholder="Search again..." 
                   value="<?php echo htmlspecialchars($search); ?>" required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>

<!-- Food Menu Section -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <div class="food-menu-container">
            <?php
            // SQL to find foods matching the search term
            $sql = "SELECT * FROM food_food 
                    WHERE active='Yes' 
                    AND (title LIKE '%$search%' OR description LIKE '%$search%')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
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
                echo '<div class="error text-center">No Food Found.</div>';
            }
            ?>
        </div>

        <div class="clearfix"></div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>