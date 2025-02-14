<?php
include('../config/constants.php');
session_start();

// Initialize variables
$name = '';
$admin_acc_id = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get and sanitize inputs
    $admin_acc_id = mysqli_real_escape_string($conn, $_POST['admin_acc_id']);
    $old_pw = mysqli_real_escape_string($conn, $_POST['old_pw']);
    $new_pw = mysqli_real_escape_string($conn, $_POST['new_pw']);
    $cnew_pw = mysqli_real_escape_string($conn, $_POST['cnew_pw']);

    // Verify old password
    $sql = "SELECT * FROM admin_acc WHERE admin_acc_id='$admin_acc_id' AND admin_pw='$old_pw'";
    $res = mysqli_query($conn, $sql);

    // Validate old password
    if (!$res || mysqli_num_rows($res) !== 1) {
        $_SESSION['error'] = "Invalid current password. ";
        header("Location: update-password.php?admin_acc_id=$admin_acc_id");
        exit();
    }

    // Validate new passwords match
    if ($new_pw !== $cnew_pw) {
        $_SESSION['error'] = "New passwords do not match. ";
        header("Location: update-password.php?admin_acc_id=$admin_acc_id");
        exit();
    }

    // Validate password length
    if (strlen($new_pw) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long. ";
        header("Location: update-password.php?admin_acc_id=$admin_acc_id");
        exit();
    }

    // Update password in database
    $update_sql = "UPDATE admin_acc SET admin_pw='$new_pw' WHERE admin_acc_id='$admin_acc_id'";
    $update_res = mysqli_query($conn, $update_sql);

    if ($update_res) {
        $_SESSION['pw'] = "<div class='success'>Password updated successfully. </div>";
        header("Location: ".SITEURL.'admin/admin-manage.php');
        exit();
    } 
    else {
        $_SESSION['pw'] = "<div class='error'>Failed to update password. </div>";
        header("Location: ".SITEURL.'admin/admin-manage.php');
        exit();
    }
}

// Handle GET request
if (isset($_GET['admin_acc_id'])) {
    $admin_acc_id = mysqli_real_escape_string($conn, $_GET['admin_acc_id']);
    $sql = "SELECT * FROM admin_acc WHERE admin_acc_id='$admin_acc_id'";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $name = $row['admin_name'];
    } 
    else {
        $_SESSION['error'] = "Admin not found. ";
        header("Location: admin-manage.php");
        exit();
    }
} 
else {
    header("Location: admin-manage.php");
    exit();
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Change <?php echo htmlspecialchars($name); ?>'s Password</h1>
        <br>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error text-margin">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
            <br>
        <?php endif; ?>

        <form method="POST">
            <table class="tbl-50">
                <tr>
                    <td>Current Password:</td>
                    <td>
                        <div class="password-toggle-container">
                            <input type="password" name="old_pw" id="old_pw" required 
                                   class="form-control" placeholder="********">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('old_pw', this)" aria-label="Show password">👁️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td>
                        <div class="password-toggle-container">
                            <input type="password" name="new_pw" id="new_pw" required
                                   class="form-control" placeholder="********">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_pw', this)" aria-label="Show password">👁️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td>
                        <div class="password-toggle-container">
                            <input type="password" name="cnew_pw" id="cnew_pw" required
                                   class="form-control" placeholder="********">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('cnew_pw', this)" aria-label="Show password">👁️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="admin_acc_id" 
                               value="<?php echo $admin_acc_id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-primary">
                        <a href="admin-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, button) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        button.textContent = '－';
    } 
    else {
        passwordInput.type = 'password';
        button.textContent = '👁️';
    }
}
</script>

<?php include('partials/footer.php'); ?>