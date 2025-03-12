<?php include('partials/menu.php');?>

<div class = "main-content">
    <div class="wrapper">
        <br><br>
        <h1 class = "text-margin">Manage Categories</h1>
        <br><br><br>

        <?php

        if(isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }

        ?>

                <!-- Button to Add Cat-->
                <span class='text-margin'>
                <a href="<?php echo SITEURL; ?>admin/add-category.php" class = "btn-primary">Add Category</a>
                </span>

                <br><br>
                
                <table class = "text-margin">
                    
                        <tr>
                            <th>S.N.</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    

                   
                        <tr>
                            <td>0001. </td>
                            <td>Brian Samoylov</td>
                            <td>fender355</td>
                            <td>
                                <a href="#" class="btn-secondary">Update Admin</a>
                                <a href="#" class="btn-danger">Delete Admin</a>
                            </td>
                        </tr>

                        <tr>
                            <td>0002. </td>
                            <td>Brian V Samoylov</td>
                            <td>vladimir1922</td>
                            <td>
                                <a href="#" class="btn-secondary">Update Admin</a>
                                <a href="#" class="btn-danger">Delete Admin</a>
                            </td>
                        </tr>

                        <tr>
                            <td>0003. </td>
                            <td>Brian Samoylov</td>
                            <td>fender355</td>
                            <td>
                                <a href="#" class="btn-secondary">Update Admin</a>
                                <a href="#" class="btn-danger">Delete Admin</a>
                            </td>
                        </tr>
                    
                </table>

    </div>
</div>

<?php include('partials/footer.php'); ?>