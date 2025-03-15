<?php include('partials/menu.php');?>

<div class="main-content">
    <div class="wrapper">
        <br><br>
        <h1 class="text-margin">Manage Categories</h1>
        <br>

        <?php
        // Session messages handling (same as before)
        ?>

        <br>

        <!-- Add Category Button (same as before) -->
        
        <br>
        
        <table class="text-margin">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php
            $sql = "SELECT * FROM food_category";
            $res = mysqli_query($conn, $sql);
            
            if($res == TRUE) {
                $count = mysqli_num_rows($res);
                $sn = 1;

                if($count > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $category_id = $row['category_id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        $featured = $row['featured'];
                        $active = $row['active'];
                        ?>
                        
                        <tr>
                            <td><?php echo $sn++; ?>.</td>
                            <td><?php echo $title; ?></td>
                            <td>
                                <?php 
                                if($image_name != "") {
                                    // Display the Image
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" class="category-image">
                                    <?php
                                } else {
                                    echo "<div class='error'>No Image Added</div>";
                                }
                                ?>
                            </td>
                            <td><?php echo $featured; ?></td>
                            <td><?php echo $active; ?></td>
                            <td class="actions-column">
                                <a href="<?php echo SITEURL; ?>admin/update-category.php?category_id=<?php echo $category_id; ?>" class="btn-secondary">Update Category</a>
                                <a href="<?php echo SITEURL; ?>admin/confirm-delete-category.php?category_id=<?php echo $category_id; ?>" class="btn-danger">Delete Category</a>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    // No categories found message
                }
            } else {
                // Database error message
            }
            ?>
        </table>

    </div>
</div>

<?php include('partials/footer.php'); ?>