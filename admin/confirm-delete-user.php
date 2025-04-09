<?php 
include('../config/constants.php');
include('partials/menu.php');

if (isset($_GET['user_acc_id'])) {
    $user_acc_id = $_GET['user_acc_id'];

    $sql = "SELECT * FROM user_acc WHERE user_acc_id = $user_acc_id";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $name = $row['user_name'];
        $email = $row['user_email'];
        $mobile = $row['user_mobile'];
    } else {
        $_SESSION['delete'] = "<div class='error'>User not found.</div>";
        header("Location: " . SITEURL . "admin/user-manage.php");
        exit();
    }
} else {
    header("Location: " . SITEURL . "admin/user-manage.php");
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Delete <?php echo $name; ?>?</h1>
        <br>
        <p class="text-margin">Are you sure you want to delete this user?</p>
        <br>
        <p class="text-margin"><strong>Name:</strong> <?= $name ?><br><strong>Email:</strong> <?= $email ?><br><strong>Mobile:</strong> <?= $mobile ?></p>
        <br>
        <form class="text-margin" action="delete-user.php" method="POST">
            <input type="hidden" name="user_acc_id" value="<?= $user_acc_id ?>">
            <input type="submit" name="delete" value="Confirm Delete" class="btn-danger">
            <a href="<?= SITEURL ?>admin/user-manage.php" class="btn-secondary">Cancel</a>
        </form>
        <br>
    </div>
</div>

<?php include('partials/footer.php'); ?>
