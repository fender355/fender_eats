<?php
include('config/constants.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $featured = trim($_POST['featured'] ?? '');
    $active = trim($_POST['active'] ?? '');

    // Initialize errors array
    $errors = [];

    // Validate title
    if (empty($title)) {
        $errors['title'] = 'Title is required.';
    }

    // Validate featured
    if (!in_array($featured, ['Yes', 'No'])) {
        $errors['featured'] = 'Featured status must be Yes or No.';
    }

    // Validate active
    if (!in_array($active, ['Yes', 'No'])) {
        $errors['active'] = 'Active status must be Yes or No.';
    }

    // If no errors, proceed to insert
    if (empty($errors)) {
        // SQL Query (Consider using prepared statements for better security)
        $sql = "INSERT INTO food_category SET
            title = '$title',
            featured = '$featured',
            active = '$active'
        ";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['add'] = "<div class='success'>Category added successfully. </div>";
            header("Location: " . SITEURL . 'admin/category-manage.php');
            exit();
        } else {
            $_SESSION['add'] = "<div class='error'>Failed to add category. </div>";
            header("Location: " . SITEURL . 'admin/category-manage.php');
            exit();
        }
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-margin">Add Category</h1>
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
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Russian Cuisine"
                               value="<?= htmlspecialchars($title ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?= ($featured ?? '') === 'Yes' ? 'checked' : '' ?>> Yes
                        <input type="radio" name="featured" value="No" <?= ($featured ?? '') === 'No' ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?= ($active ?? '') === 'Yes' ? 'checked' : '' ?>> Yes
                        <input type="radio" name="active" value="No" <?= ($active ?? '') === 'No' ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

    </div>
</div>

<?php include('partials/footer.php'); ?>