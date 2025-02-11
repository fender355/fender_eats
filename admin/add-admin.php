<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">

        <br>

        <h1 class="text-margin">Add An Administrator</h1>

        <br>

        <?php

            if(isset($_SESSION['add'])) { //Check Session Set or Not

                echo '<span class = "text-margin">' . $_SESSION['add']; // Display Session Message if Set
                unset($_SESSION['add']); //Remove Session Message

            }

        ?>

        <br>

        <form method = "POST" action = "">

            <table class = "tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td><input type="text" class="form-control is-invalid" name="admin_name" id="admin_name" placeholder="Ninzin" value="" autocomplete="off"></td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td><input type="text" class="form-control is-invalid" name="admin_mail" id="admin_mail" placeholder="ninzin00@outlook.jp" value="" autocomplete="off"></td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td><input type="password" class="form-control is-invalid" name="admin_pw" id="admin_pw" placeholder="********" value="" autocomplete="off"></td>
                </tr>

                <tr>
                    <td colspan = "3" class = "form-buttons">
                        <br>
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/admin-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>

        </form>

    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php

    if(isset($_POST['submit'])) {

        // Get data from Form
        $admin_name = $_POST['admin_name'];
        $admin_mail = $_POST['admin_mail'];
        $admin_pw = $_POST['admin_pw'];

        //SQL Query to Save Data into Database
        $sql = "INSERT INTO admin_acc SET
            admin_name = '$admin_name',
            admin_mail = '$admin_mail',
            admin_pw = '$admin_pw'
        ";
        
        //Executing Query and Saving Data into Database 
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

        //Check whether the (Query is Executed) Data is Inserted or Not, And Display Appropriate Message
        if($res == TRUE) {

            //Data Inserted
            //echo "Data Inserted";

            //Create A Session Variable to Display Message
            $_SESSION['add'] = "<div class='success'>Administrator Added Successfully. </div>"; 

            //Redirect Page to Manage Administrators
            header("location: ".SITEURL.'admin/admin-manage.php');

        }

        else {

            //Failed to Insert Data
            //echo "Failed to Insert Data";

            //Create A Session Variable to Display Message
            $_SESSION['add'] = "<div class='error>Failed to Add This Administrator. </div>"; 

            //Redirect Page to Add Administrator 
            header("location: ".SITEURL.'admin/add-admin.php');

        }

    }

?>