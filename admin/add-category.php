<?php
include('../config/constants.php');

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

    // Process image upload
    $image_name = '';
    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['image'] = 'Please select an image.';
        } else {
            $errors['image'] = 'Error code: ' . $file['error'] . ' during upload.';
        }
    } else {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed)) {
            $errors['image'] = 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF.';
        }

        if ($file_size > 5 * 1024 * 1024) {
            $errors['image'] = 'File size exceeds 5MB.';
        }

        if (!isset($errors['image'])) {
            $image_name = 'Category_Image_' . uniqid() . '.' . $file_ext;
            $upload_path = '../images/category/' . $image_name;

            // Verify/Create directory
            $dir_path = dirname($upload_path);
            if (!is_dir($dir_path)) {
                if (!mkdir($dir_path, 0755, true)) {
                    $errors['image'] = 'Failed to create upload directory.';
                }
            }

            // Attempt to move the file
            if (empty($errors)) {
                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $errors['image'] = 'Failed to upload image. Check permissions for: ' . $upload_path;
                    error_log("Upload Failed: " . print_r(error_get_last(), true));
                }
            }
        }
    }    

    // If there are errors, store them in session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'title' => $title,
            'featured' => $featured,
            'active' => $active
        ];
        header("Location: " . SITEURL . 'admin/add-category.php');
        exit();
    }

    // If no errors, proceed to insert
    if (empty($errors)) {
        // Sanitize data
        $title = mysqli_real_escape_string($conn, $title);
        $featured = mysqli_real_escape_string($conn, $featured);
        $active = mysqli_real_escape_string($conn, $active);
        $image_name = mysqli_real_escape_string($conn, $image_name);

        // SQL Query (Consider using prepared statements)
        $sql = "INSERT INTO food_category SET
            title = '$title',
            image_name = '$image_name',
            featured = '$featured',
            active = '$active'
        ";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['add'] = "<div class='success'>Category added successfully.</div>";
            // Clear form data from session
            unset($_SESSION['form_data']);
        } else {
            // Remove uploaded image if query failed
            if (file_exists($upload_path)) {
                unlink($upload_path);
            }
            $_SESSION['add'] = "<div class='error'>Failed to add category.</div>";
        }
        header("Location: " . SITEURL . 'admin/category-manage.php');
        exit();
    }
}

include('partials/menu.php');

// Retrieve form data and errors from session
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?>

<!-- HTML Form (Updated to use $formData) -->
<div class="main-content">
    <div class="wrapper">
        <br>
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

        <br>

        <form method="POST" action="" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Russian Cuisine"
                               value="<?= htmlspecialchars($formData['title'] ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image" id="imageInput">
                    </td>
                </tr>
                <tr id="imagePreviewRow" style="display: none;">
                    <td>Preview: </td>
                    <td>
                        <img id="imagePreview" src="#" alt="Image Preview" 
                             style="max-width: 200px; max-height: 200px; padding: 5px; border: 1px solid #ddd;">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" 
                            <?= ($formData['featured'] ?? '') === 'Yes' ? 'checked' : '' ?>> Yes
                        <input type="radio" name="featured" value="No" 
                            <?= ($formData['featured'] ?? '') === 'No' ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" 
                            <?= ($formData['active'] ?? '') === 'Yes' ? 'checked' : '' ?>> Yes
                        <input type="radio" name="active" value="No" 
                            <?= ($formData['active'] ?? '') === 'No' ? 'checked' : '' ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                        <a href="<?= SITEURL ?>admin/category-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
// Add image preview functionality
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewRow = document.getElementById('imagePreviewRow');
    const previewImg = document.getElementById('imagePreview');
    
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