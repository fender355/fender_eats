<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class = "main-content">
            <div class = "wrapper">
                <br><br>
                <h1 class = "text-margin"> Dashboard </h1>
                <br>

                <?php 
                    if(isset($_SESSION['login'])) {
                        echo $_SESSION['login'];
                        unset($_SESSION['login']);
                    }
                ?>

                <br>

                <div class = "col-4">
                    <?php 
                        // Get number of active categories
                        $sql = "SELECT COUNT(*) AS count FROM food_category WHERE active='Yes'";
                        $res = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($res);
                        $categories_count = $row['count'];
                    ?>
                    <h1><?php echo $categories_count; ?></h1> 
                    Categories
                </div>

                <div class = "col-4">
                    <?php 
                        // Get number of active foods
                        $sql = "SELECT COUNT(*) AS count FROM food_food WHERE active='Yes'";
                        $res = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($res);
                        $foods_count = $row['count'];
                    ?>
                    <h1><?php echo $foods_count; ?></h1>
                    Foods
                </div>

                <div class = "col-4">
                    <?php 
                        // Get total number of orders
                        $sql = "SELECT COUNT(*) AS count FROM tbl_order_header";
                        $res = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($res);
                        $orders_count = $row['count'];
                    ?>
                    <h1><?php echo $orders_count; ?></h1>
                    Total Orders
                </div>

                <div class = "col-41">
                    <?php 
                        // Get total revenue generated
                        $sql = "SELECT SUM(total_price) AS total FROM tbl_order_header";
                        $res = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($res);
                        $revenue = $row['total'];
                        // Format as currency
                        $revenue_formatted = number_format($revenue, 2);
                    ?>
                    <h1>RM<?php echo $revenue_formatted; ?></h1>
                    Revenue Generated
                </div>

                <div class="clearfix"></div>
                <br><br>
            </div>
        </div>
        <!-- Main Content Section Ends --> 

<?php include('partials/footer.php'); ?>