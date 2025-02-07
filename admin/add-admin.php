<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Add Admin</h1>
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
                    <td colspan = "2">
                        <input type="submit" name="submit" value="Add" class="btn-secondary">
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

        //Execute SQL Query and Save Data into Database
        $conn = mysqli_connect('localhost', 'root', '') or die(mysqli_error()); // Init Database Connection
        $db_select = mysqli_select_db($conn, 'fender_eats') or die(mysqli_error()); // Select Database
        
        //$res = mysqli_query($conn, $sql) or die(mysqli_error());

    }

?>