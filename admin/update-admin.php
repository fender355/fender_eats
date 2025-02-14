<?php
include('../config/constants.php');

// Initialize variables
$errors = [];
$name = '';
$email = '';
$admin_acc_id = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $admin_acc_id = trim($_POST['admin_acc_id']);
    $name = trim($_POST['admin_name']);
    $email = trim($_POST['admin_mail']);

    // Validate inputs
    if (empty($name)) {
        $errors['admin_name'] = 'Name is required.';
    }

    if (empty($email)) {
        $errors['admin_mail'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['admin_mail'] = 'Invalid email format.';
    }

    // If no errors, update database
    if (empty($errors)) {
        $sql = "UPDATE admin_acc SET
                admin_name = '$name',
                admin_mail = '$email'
                WHERE admin_acc_id = '$admin_acc_id'";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['update'] = "<div class='success'>Admin updated successfully. </div>";
            header("Location: ".SITEURL.'admin/admin-manage.php');
            exit();
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to update admin. </div>";
            header("Location: ".SITEURL.'admin/admin-manage.php');
            exit();
        }
    }
} else {
    // Handle GET request
    if (isset($_GET['admin_acc_id'])) {
        $admin_acc_id = $_GET['admin_acc_id'];
        $sql = "SELECT * FROM admin_acc WHERE admin_acc_id = $admin_acc_id";
        $res = mysqli_query($conn, $sql);
        
        if ($res && mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $name = $row['admin_name'];
            $email = $row['admin_mail'];
        } else {
            $_SESSION['delete'] = "<div class='error'>Admin not found.</div>";
            header('Location: '.SITEURL.'admin/admin-manage.php');
            exit();
        }
    } else {
        header('Location: '.SITEURL.'admin/admin-manage.php');
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
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="admin_name" 
                               class="form-control <?= isset($errors['admin_name']) ? 'is-invalid' : '' ?>" 
                               value="<?php echo htmlspecialchars($name); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td>
                        <input type="email" name="admin_mail" 
                               class="form-control <?= isset($errors['admin_mail']) ? 'is-invalid' : '' ?>" 
                               value="<?php echo htmlspecialchars($email); ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="hidden" name="admin_acc_id" value="<?php echo $admin_acc_id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/admin-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>