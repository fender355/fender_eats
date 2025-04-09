<?php
include('../config/constants.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_mobile = trim($_POST['user_mobile']);
    $user_pw = trim($_POST['user_pw']);

    $errors = [];

    if (empty($user_name)) $errors[] = "Name is required.";
    if (empty($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($user_mobile)) $errors[] = "Mobile number is required.";
    if (empty($user_pw) || strlen($user_pw) < 8) $errors[] = "Password must be at least 8 characters.";

    if (empty($errors)) {
        $sql = "INSERT INTO user_acc SET
                user_name='$user_name',
                user_email='$user_email',
                user_mobile='$user_mobile',
                user_pw='$user_pw'";

        $res = mysqli_query($conn, $sql);

        $_SESSION['add'] = $res ? "<div class='success'>User added successfully.</div>" : "<div class='error'>Failed to add user.</div>";
        header("Location: " . SITEURL . "admin/user-manage.php");
        exit();
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Add User</h1>
        <br>

        <?php 
        if (!empty($errors)) {
            echo "<div class='error'><ul>";
            foreach ($errors as $err) echo "<li>$err</li>";
            echo "</ul></div>";
        }
        ?>

        <form method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Full Name:</td>
                    <td><input type="text" name="user_name" value="<?= htmlspecialchars($user_name ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="user_email" value="<?= htmlspecialchars($user_email ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>Mobile:</td>
                    <td><input type="text" name="user_mobile" value="<?= htmlspecialchars($user_mobile ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <div class="password-toggle-container">
                            <input type="password" class="form-control <?= isset($errors['user_pw']) ? 'is-invalid' : '' ?>" 
                                   name="user_pw" id="user_pw" placeholder="********" autocomplete="off">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Show password">
                                👁️
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="submit" value="Add User" class="btn-secondary">
                        <a href="<?= SITEURL ?>admin/user-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('admin_pw');
    const toggleButton = document.querySelector('.toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = '－';
        toggleButton.setAttribute('aria-label', 'Hide password');
    } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = '👁️';
        toggleButton.setAttribute('aria-label', 'Show password');
    }
}
</script>

<?php include('partials/footer.php'); ?>
