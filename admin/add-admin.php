<?php
include('../config/constants.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get data from Form
    $admin_name = trim($_POST['admin_name'] ?? '');
    $admin_mail = trim($_POST['admin_mail'] ?? '');
    $admin_pw = trim($_POST['admin_pw'] ?? '');

    // Initialize errors array
    $errors = [];

    // Validate name
    if (empty($admin_name)) {
        $errors['admin_name'] = 'Name is required.';
    }

    // Validate email
    if (empty($admin_mail)) {
        $errors['admin_mail'] = 'Email is required.';
    } elseif (!filter_var($admin_mail, FILTER_VALIDATE_EMAIL)) {
        $errors['admin_mail'] = 'Invalid email format.';
    }

    // Validate password
    if (empty($admin_pw)) {
        $errors['admin_pw'] = 'Password is required.';
    } elseif (strlen($admin_pw) < 8) {
        $errors['admin_pw'] = 'Password must be at least 8 characters.';
    }

    // If no errors, proceed to insert
    if (empty($errors)) {
        // SQL Query (Consider using prepared statements for security)
        $sql = "INSERT INTO admin_acc SET
            admin_name = '$admin_name',
            admin_mail = '$admin_mail',
            admin_pw = '$admin_pw'";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['add'] = "<div class='success'>This administrator is added successfully.</div>";
            header("Location: " . SITEURL . 'admin/admin-manage.php');
            exit();
        } else {
            $_SESSION['add'] = "<div class='error'>Failed to Add This Administrator.</div>";
            header("Location: " . SITEURL . 'admin/add-admin.php');
            exit();
        }
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Add An Administrator</h1>
        <br>

        <?php
        // Display session messages
        if (isset($_SESSION['add'])) {
            echo '<div class="text-margin">' . $_SESSION['add'] . '</div>';
            unset($_SESSION['add']);
        }

        // Display validation errors
        if (!empty($errors)) {
            echo '<div class="error text-margin"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul></div>';
        }
        ?>

        <form method="POST" action="">
            <table class="tbl-30">
                <tr>
                    <br>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" class="form-control <?= isset($errors['admin_name']) ? 'is-invalid' : '' ?>" 
                               name="admin_name" id="admin_name" placeholder="Vladimir Vladimirovich" 
                               value="<?= htmlspecialchars($admin_name ?? '') ?>" autocomplete="off">
                    </td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td>
                        <input type="email" class="form-control <?= isset($errors['admin_mail']) ? 'is-invalid' : '' ?>" 
                               name="admin_mail" id="admin_mail" placeholder="vladimir@mail.com" 
                               value="<?= htmlspecialchars($admin_mail ?? '') ?>" autocomplete="off">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" class="form-control <?= isset($errors['admin_pw']) ? 'is-invalid' : '' ?>" 
                               name="admin_pw" id="admin_pw" placeholder="********" autocomplete="off">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                        <a href="<?= SITEURL ?>admin/admin-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

