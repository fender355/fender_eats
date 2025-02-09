<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <!DOCTYPE html>
        <div class = "main-content">
            <div class = "wrapper">
                <br><br>
                <h1 class = "text-margin"> Manage Administrators </h1>

                <br>

                <?php 

                    if(isset($_SESSION['add'])) {

                        echo '<span class = "text-margin">' . $_SESSION['add']; // Display Session Message
                        unset($_SESSION['add']); // Removing Session Message

                    }
                
                ?>

                <br><br>

                <!-- Button to Add Admin-->
                <a href="add-admin.php" class = "btn-primary">Add Admin</a>

                <br>
                <br>
                
                <table class = "text-margin">
                    
                        <tr>
                            <th>ID.</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    
                        <?php 

                            //Query to Get all Admin
                            $sql = "SELECT * FROM admin_acc";

                            //Execute the Query 
                            $res = mysqli_query($conn, $sql);

                            //Check whether the Query is Executed or Not
                            if($res == TRUE) {

                                //Count Rows to Check Data Quantity
                                $count = mysqli_num_rows($res); //Function to Get All Rows in DB

                                $admin_id = 1; 

                                //Check the Num of Rows
                                if($count > 0) {

                                    // We have data in db
                                    while($rows = mysqli_fetch_assoc($res)) {

                                        // Get All Data from db, run as long as we have data in db

                                        // Get individual data
                                        $admin_acc_id = $rows['admin_acc_id'];
                                        $admin_name = $rows['admin_name'];
                                        $admin_mail = $rows['admin_mail'];
                                        $admin_pw = $rows['admin_pw'];

                                        // Display values in table
                                        ?> 

                                        <tr>
                                            <td><?php echo $admin_id++; ?>. </td>
                                            <td><?php echo $admin_name; ?></td>
                                            <td><?php echo $admin_mail; ?></td>
                                            <td><?php echo $admin_pw; ?></td>
                                            <td>
                                                <a href="#" class="btn-secondary">Update Admin</a>
                                                <a href="#" class="btn-tertiary">Delete Admin</a>
                                            </td>
                                        </tr> 

                                        <?php

                                    }

                                }
                                else {

                                    // We dont have data in db

                                }

                            }

                        ?>
                    
                </table>

                <div></div>
                <br><br>
            </div>
        </div>
        <!-- Main Content Section Ends --> 

<?php include('partials/footer.php'); ?>