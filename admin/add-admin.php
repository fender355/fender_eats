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

session_start(); // Start the session

if (isset($_POST["submit"])) {
    $_SESSION['button_clicked'] = true; // Store click status in session
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid resubmission
    exit();
}

// Display the message based on session status
if (isset($_SESSION['button_clicked']) && $_SESSION['button_clicked'] === true) {
    echo "Button Clicked";
    unset($_SESSION['button_clicked']); // Clear session to reset for next click
} else {
    echo "Button Not Clicked";
}

?>