<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class = "main-content">
            <div class = "wrapper">
                <br><br>
                <h1 class = "text-margin"> Manage Users </h1>

                <br>

                <?php 

                    if(isset($_SESSION['add'])) {

                        echo $_SESSION['add']; // Display Session Message
                        unset($_SESSION['add']); // Removing Session Message

                    }

                    if(isset($_SESSION['delete'])) {

                        echo $_SESSION['delete']; //~
                        unset($_SESSION['delete']); //~

                    }

                ?>

                <br><br>

                <!-- Button to Add User -->
                <span class = "text-margin">
                <a href="add-user.php" class = "btn-primary">Add User</a>
                </span>

                <br><br>

                <table class = "text-margin-15">
                    
                        <tr>
                            <th>ID.</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Actions</th>
                        </tr>
                    
                        <?php 

                            // Query to Get all Users
                            $sql = "SELECT * FROM user_acc";

                            // Execute the Query 
                            $res = mysqli_query($conn, $sql);

                            // Check whether the Query is Executed or Not
                            if($res == TRUE) {

                                // Count Rows to Check Data Quantity
                                $count = mysqli_num_rows($res);

                                $user_id = 1; 

                                // Check the Num of Rows
                                if($count > 0) {

                                    // We have data in db
                                    while($rows = mysqli_fetch_assoc($res)) {

                                        // Get individual data
                                        $user_acc_id = $rows['user_acc_id'];
                                        $user_name = $rows['user_name'];
                                        $user_email = $rows['user_email'];
                                        $user_mobile = $rows['user_mobile'];
                                        $user_pw = $rows=['user_pw'];

                                        // Display values in table
                                        ?> 

                                        <tr>
                                            <td><?php echo $user_id++; ?>. </td>
                                            <td><?php echo htmlspecialchars($user_name); ?></td>
                                            <td><?php echo htmlspecialchars($user_email); ?></td>
                                            <td><?php echo htmlspecialchars($user_mobile); ?></td>
                                            <td>
                                                <a href="update-user.php?user_acc_id=<?php echo $user_acc_id; ?>" class="btn-secondary">Update User</a>
                                                <a href="update-password-user.php?user_acc_id=<?php echo $user_acc_id; ?>" class="btn-primary">Change Password</a>
                                                <a href="confirm-delete-user.php?user_acc_id=<?php echo $user_acc_id; ?>" class="btn-danger">Delete User</a>
                                            </td>
                                        </tr> 

                                        <?php

                                    }

                                } else {

                                    ?> 
                                    <tr>
                                        <td colspan="5">No users found.</td>
                                    </tr>
                                    <?php

                                }

                            }

                        ?>
                    
                </table>
                <br><br>
                <!-- Button to Admin Page-->
                <span class = "text-margin">
                <a href="admin-manage.php" class = "btn-secondary">Manage Admin...</a>
                </span>
                <br><br><br>
            </div>
        </div>
        <!-- Main Content Section Ends --> 

<?php include('partials/footer.php'); ?>