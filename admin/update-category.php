<?php
include('../config/constants.php');

// Initialize variables
$errors = [];
$title = '';
$featured = '';
$active = '';
$current_image = '';
$category_id = '';

// Process GET request
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql = "SELECT * FROM food_category WHERE category_id = $category_id";
    $res = mysqli_query($conn, $sql);
    
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $current_image = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
    } else {
        $_SESSION['error'] = "<div class='error'>Category not found.</div>";
        header('Location: '.SITEURL.'admin/category-manage.php');
        exit();
    }
} else {
    header('Location: '.SITEURL.'admin/category-manage.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $category_id = $_POST['category_id'];
    $title = trim($_POST['title']);
    $featured = trim($_POST['featured']);
    $active = trim($_POST['active']);
    $current_image = $_POST['current_image'];

    // Validate inputs
    if (empty($title)) {
        $errors['title'] = 'Title is required.';
    }
    if (!in_array($featured, ['Yes', 'No'])) {
        $errors['featured'] = 'Invalid featured status.';
    }
    if (!in_array($active, ['Yes', 'No'])) {
        $errors['active'] = 'Invalid active status.';
    }

    // Process only if there are no errors
    if (empty($errors)) {
        $image_name = $current_image;
        $remove_image = isset($_POST['remove_image']);

        // Handle image removal
        if ($remove_image && !empty($current_image)) {
            $old_path = "../images/category/$current_image";
            if (file_exists($old_path)) {
                unlink($old_path);
            }
            $image_name = '';
        }

        // Handle new image upload
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_ext, $allowed)) {
                $errors['image'] = 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF.';
            } elseif ($file_size > 5 * 1024 * 1024) {
                $errors['image'] = 'File size exceeds 5MB.';
            } else {
                $new_image_name = "Category_Image_" . uniqid() . ".$file_ext";
                $upload_path = "../images/category/$new_image_name";

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete previous image if exists
                    if ($image_name != '') {
                        $prev_path = "../images/category/$image_name";
                        if (file_exists($prev_path)) {
                            unlink($prev_path);
                        }
                    }
                    $image_name = $new_image_name;
                } else {
                    $errors['image'] = 'Failed to upload image. Check permissions.';
                }
            }
        }

        // Update database if no errors
        if (empty($errors)) {
            $sql = "UPDATE food_category SET
                    title = '".mysqli_real_escape_string($conn, $title)."',
                    image_name = '".mysqli_real_escape_string($conn, $image_name)."',
                    featured = '".mysqli_real_escape_string($conn, $featured)."',
                    active = '".mysqli_real_escape_string($conn, $active)."'
                    WHERE category_id = $category_id";

            $res = mysqli_query($conn, $sql);

            if ($res) {
                $_SESSION['update'] = "<div class='success'>Category updated successfully.</div>";
                header('Location: '.SITEURL.'admin/category-manage.php');
                exit();
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to update category.</div>";
                header('Location: '.SITEURL.'admin/category-manage.php');
                exit();
            }
        }
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Update Category: <?php echo $title; ?></h1>

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

        <form method="POST" action="" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php if ($current_image != ''): ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width="150px">
                        <?php else: ?>
                            <span>No image uploaded</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php if ($current_image != ''): ?>
                <tr>
                    <td>Remove Image: </td>
                    <td>
                        <input type="checkbox" name="remove_image" value="1">
                        <small>(Check to remove current image)</small>
                    </td>
                </tr>
                <?php endif; ?>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image" id="imageInput">
                    </td>
                </tr>

                <!-- New Image Preview Row -->
                <tr id="newImagePreviewRow" style="display: none;">
                    <td>New Image Preview: </td>
                    <td>
                        <img id="newImagePreview" src="#" alt="New Image Preview" 
                             style="max-width: 200px; max-height: 200px; padding: 5px; border: 1px solid #ddd;">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?= ($featured === 'Yes') ? 'checked' : '' ?>> Yes
                        <input type="radio" name="featured" value="No" <?= ($featured === 'No') ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?= ($active === 'Yes') ? 'checked' : '' ?>> Yes
                        <input type="radio" name="active" value="No" <?= ($active === 'No') ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/category-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
// Add image preview functionality for new image
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewRow = document.getElementById('newImagePreviewRow');
    const previewImg = document.getElementById('newImagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewRow.style.display = '';
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.src = '#';
        previewRow.style.display = 'none';
    }
});
</script>

<?php include('partials/footer.php'); ?>