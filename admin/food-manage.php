<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <br><br>
        <h1 class="text-margin">Manage Foods</h1>
        <br>

        <?php
        // Session messages handling
        if(isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }

        if(isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']);
        }

        if(isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }

        if(isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
        ?>

        <br><br>

        <!-- Add Food Button -->
        <span class='text-margin'>
            <a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add Food</a>
        </span>

        <br><br>
        
        <table class="text-margin">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th>Category</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php
            $sql = "SELECT f.*, c.title AS category_title 
                    FROM food_food f 
                    LEFT JOIN food_category c ON f.category_id = c.category_id";
            $res = mysqli_query($conn, $sql);
            
            if($res) {
                $count = mysqli_num_rows($res);
                $sn = 1;

                if($count > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $title = $row['title'];
                        $price = $row['price'];
                        $image_name = $row['image_name'];
                        $category = $row['category_title'] ?? 'Uncategorized';
                        $featured = $row['featured'];
                        $active = $row['active'];
                        ?>
                        
                        <tr>
                            <td><?php echo $sn++; ?>.</td>
                            <td><?php echo htmlspecialchars($title); ?></td>
                            <td>RM <?php echo number_format($price, 2); ?></td>
                            <td>
                                <?php if(!empty($image_name)): ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" class="category-image">
                                <?php else: ?>
                                    <div class='error'>No Image Added</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($category); ?></td>
                            <td><?php echo htmlspecialchars($featured); ?></td>
                            <td><?php echo htmlspecialchars($active); ?></td>
                            <td class="actions-column">
                                <a href="<?php echo SITEURL; ?>admin/update-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update Food</a>
                                <a href="<?php echo SITEURL; ?>admin/confirm-delete-food.php?id=<?php echo $id; ?>" class="btn-danger">Delete Food</a>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    echo '<tr><td colspan="8"><div class="error">No Foods Found</div></td></tr>';
                }
            } else {
                echo '<tr><td colspan="8"><div class="error">Database Query Error</div></td></tr>';
            }
            ?>
        </table>

    </div>
</div>

<?php include('partials/footer.php'); ?>