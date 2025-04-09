<?php
include('../config/constants.php');

// Initialize variables
$errors = [];
$name = '';
$email = '';
$user_acc_id = '';
$user_mobile = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $user_acc_id = trim($_POST['user_acc_id']);
    $name = trim($_POST['user_name']);
    $email = trim($_POST['user_mail']);
    $user_mobile = trim($_POST['user_mobile']);

    // Validate inputs
    if (empty($name)) {
        $errors['user_name'] = 'Name is required.';
    }

    if (empty($email)) {
        $errors['user_mail'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['user_mail'] = 'Invalid email format.';
    }

    if (empty($user_mobile)) {
        $errors['user_mobile'] = 'Mobile number is required.';
    }

    // If no errors, update database
    if (empty($errors)) {
        $sql = "UPDATE user_acc SET
                user_name = '$name',
                user_mail = '$email'
                user_mobile = '$user_mobile'
                WHERE user_acc_id = '$user_acc_id'";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['update'] = "<div class='success'>User updated successfully.</div>";
            header("Location: ".SITEURL.'admin/user-manage.php');
            exit();
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to update user.</div>";
            header("Location: ".SITEURL.'admin/user-manage.php');
            exit();
        }
    }
} else {
    // Handle GET request
    if (isset($_GET['user_acc_id'])) {
        $user_acc_id = $_GET['user_acc_id'];
        $sql = "SELECT * FROM user_acc WHERE user_acc_id = $user_acc_id";
        $res = mysqli_query($conn, $sql);
        
        if ($res && mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $name = $row['user_name'];
            $email = $row['user_mail'];
            $user_mobile = $row['user_mobile'];
        } else {
            $_SESSION['delete'] = "<div class='error'>User not found.</div>";
            header('Location: '.SITEURL.'admin/user-manage.php');
            exit();
        }
    } else {
        header('Location: '.SITEURL.'admin/user-manage.php');
        exit();
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Update <?php echo htmlspecialchars($name); ?></h1>

        <br>

        <?php if (!empty($errors)): ?>
            <div class="error text-margin">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <br>
        <?php endif; ?>

        <form method="POST" action="">
            <table class="tbl-30">
                <tr>
                    <td>Full Name:</td>
                    <td>
                        <input type="text" name="user_name"
                               class="form-control <?= isset($errors['user_name']) ? 'is-invalid' : '' ?>"
                               value="<?php echo htmlspecialchars($name); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Email:</td>
                    <td>
                        <input type="email" name="user_mail"
                               class="form-control <?= isset($errors['user_mail']) ? 'is-invalid' : '' ?>"
                               value="<?php echo htmlspecialchars($email); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Mobile No.:</td>
                    <td>
                        <input type="text" name="user_mobile"
                               class="form-control <?= isset($errors['user_mobile']) ? 'is-invalid' : '' ?>"
                               value="<?php echo htmlspecialchars($user_mobile); ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="hidden" name="user_acc_id" value="<?php echo $user_acc_id; ?>">
                        <input type="submit" name="submit" value="Update User" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/user-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
