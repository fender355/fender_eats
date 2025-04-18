<?php
include('../config/constants.php');

// Initialize variables
$errors = [];
$order_id = '';
$user_name = '';
$user_email = '';
$user_contact = '';
$user_address = '';
$status = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $order_id = trim($_POST['order_id']);
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_contact = trim($_POST['user_contact']);
    $user_address = trim($_POST['user_address']);
    $status = trim($_POST['status']);

    // Validate inputs
    if (empty($user_name)) {
        $errors['user_name'] = 'Customer name is required.';
    }

    if (empty($user_email)) {
        $errors['user_email'] = 'Email is required.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $errors['user_email'] = 'Invalid email format.';
    }

    if (empty($user_contact)) {
        $errors['user_contact'] = 'Phone number is required.';
    }

    if (empty($user_address)) {
        $errors['user_address'] = 'Address is required.';
    }

    // If no errors, update database
    if (empty($errors)) {
        $sql = "UPDATE tbl_order_header SET
                user_name = '$user_name',
                user_email = '$user_email',
                user_contact = '$user_contact',
                user_address = '$user_address',
                status = '$status'
                WHERE id = '$order_id'";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['update'] = "<div class='success'>Order updated successfully.</div>";
            header("Location: ".SITEURL.'admin/order-manage.php');
            exit();
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to update order.</div>";
            header("Location: ".SITEURL.'admin/order-manage.php');
            exit();
        }
    }
} else {
    // Handle GET request
    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];
        $sql = "SELECT * FROM tbl_order_header WHERE id = $order_id";
        $res = mysqli_query($conn, $sql);
        
        if ($res && mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $user_name = $row['user_name'];
            $user_email = $row['user_email'];
            $user_contact = $row['user_contact'];
            $user_address = $row['user_address'];
            $status = $row['status'];
        } else {
            $_SESSION['delete'] = "<div class='error'>Order not found.</div>";
            header('Location: '.SITEURL.'admin/order-manage.php');
            exit();
        }
    } else {
        header('Location: '.SITEURL.'admin/order-manage.php');
        exit();
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Update Order #<?php echo htmlspecialchars($order_id); ?></h1>

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
                    <td>Customer Name: </td>
                    <td>
                        <input type="text" name="user_name" 
                               class="form-control <?= isset($errors['user_name']) ? 'is-invalid' : '' ?>" 
                               value="<?php echo htmlspecialchars($user_name); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td>
                        <input type="email" name="user_email" 
                               class="form-control <?= isset($errors['user_email']) ? 'is-invalid' : '' ?>" 
                               value="<?php echo htmlspecialchars($user_email); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Phone: </td>
                    <td>
                        <input type="text" name="user_contact" 
                               class="form-control <?= isset($errors['user_contact']) ? 'is-invalid' : '' ?>" 
                               value="<?php echo htmlspecialchars($user_contact); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Address: </td>
                    <td>
                        <textarea name="user_address" 
                                  class="form-control <?= isset($errors['user_address']) ? 'is-invalid' : '' ?>"><?php echo htmlspecialchars($user_address); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Status: </td>
                    <td>
                        <select name="status" class="form-control">
                            <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Processing" <?= $status === 'Processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="Delivered" <?= $status === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="Cancelled" <?= $status === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <input type="submit" name="submit" value="Update Order" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/order-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>