<?php
include('../config/constants.php');

// Initialize variables
$errors = [];
$id = '';
$title = '';
$description = '';
$price = '';
$current_image = '';
$category_id = '';
$featured = '';
$active = '';

// Process GET request to load food details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM food_food WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $title         = $row['title'];
        $description   = $row['description'];
        $price         = $row['price'];
        $current_image = $row['image_name'];
        $category_id   = $row['category_id'];
        $featured      = $row['featured'];
        $active        = $row['active'];
    } else {
        $_SESSION['error'] = "<div class='error'>Food not found.</div>";
        header('Location: '.SITEURL.'admin/food-manage.php');
        exit();
    }
} else {
    header('Location: '.SITEURL.'admin/food-manage.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data and trim
    $id = $_POST['id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = trim($_POST['category'] ?? '');
    $featured = trim($_POST['featured'] ?? '');
    $active = trim($_POST['active'] ?? '');
    $current_image = $_POST['current_image'] ?? '';

    // Validate title
    if (empty($title)) {
        $errors['title'] = 'Title is required.';
    }
    
    // Validate description
    if (empty($description)) {
        $errors['description'] = 'Description is required.';
    }
    
    // Validate price
    if (!is_numeric($price) || $price <= 0) {
        $errors['price'] = 'Price must be a valid positive number.';
    }
    
    // Validate category
    if (!is_numeric($category_id)) {
        $errors['category'] = 'Invalid category selected.';
    } else {
        $category_id = (int)$category_id;
        $sql2 = "SELECT category_id FROM food_category WHERE category_id = $category_id AND active='Yes'";
        $res2 = mysqli_query($conn, $sql2);
        if (!$res2 || mysqli_num_rows($res2) === 0) {
            $errors['category'] = 'Invalid category selected.';
        }
    }
    
    // Validate featured and active statuses
    if (!in_array($featured, ['Yes', 'No'])) {
        $errors['featured'] = 'Invalid featured status.';
    }
    if (!in_array($active, ['Yes', 'No'])) {
        $errors['active'] = 'Invalid active status.';
    }
    
    // Process image removal and upload
    $image_name = $current_image;
    $remove_image = isset($_POST['remove_image']);
    
    // Handle image removal if requested
    if ($remove_image && !empty($current_image)) {
        $old_path = "../images/food/$current_image";
        if (file_exists($old_path)) {
            unlink($old_path);
        }
        $image_name = '';
    }
    
    // Process new image upload if a file is selected
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp  = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed)) {
            $errors['image'] = 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF.';
        } elseif ($file_size > 5 * 1024 * 1024) {
            $errors['image'] = 'File size exceeds 5MB.';
        } else {
            $new_image_name = "Food_Image_" . uniqid() . ".$file_ext";
            $upload_path = "../images/food/$new_image_name";
            
            // Attempt to move uploaded file
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete previous image if exists
                if (!empty($current_image)) {
                    $prev_path = "../images/food/$current_image";
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
    
    // Update the database if there are no errors
    if (empty($errors)) {
        $sql = "UPDATE food_food SET
                    title = '".mysqli_real_escape_string($conn, $title)."',
                    description = '".mysqli_real_escape_string($conn, $description)."',
                    price = '".mysqli_real_escape_string($conn, $price)."',
                    image_name = '".mysqli_real_escape_string($conn, $image_name)."',
                    category_id = '".mysqli_real_escape_string($conn, $category_id)."',
                    featured = '".mysqli_real_escape_string($conn, $featured)."',
                    active = '".mysqli_real_escape_string($conn, $active)."'
                WHERE id = $id";
                
        $res = mysqli_query($conn, $sql);
        
        if ($res) {
            $_SESSION['update'] = "<div class='success'>Food updated successfully.</div>";
            header('Location: '.SITEURL.'admin/food-manage.php');
            exit();
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to update food.</div>";
            header('Location: '.SITEURL.'admin/food-manage.php');
            exit();
        }
    }
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Update Food: <?php echo htmlspecialchars($title); ?></h1>
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
                <!-- Title -->
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    </td>
                </tr>

                <!-- Description -->
                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
                    </td>
                </tr>

                <!-- Price -->
                <tr>
                    <td>Price (RM): </td>
                    <td>
                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>">
                    </td>
                </tr>

                <!-- Current Image -->
                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php if (!empty($current_image)): ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                        <?php else: ?>
                            <span>No image uploaded</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Remove Image Option -->
                <?php if (!empty($current_image)): ?>
                <tr>
                    <td>Remove Image: </td>
                    <td>
                        <input type="checkbox" name="remove_image" value="1">
                        <small>(Check to remove current image)</small>
                    </td>
                </tr>
                <?php endif; ?>

                <!-- New Image Upload -->
                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image" id="imageInput">
                    </td>
                </tr>
                <tr id="newImagePreviewRow" style="display: none;">
                    <td>New Image Preview: </td>
                    <td>
                        <img id="newImagePreview" src="#" alt="New Image Preview" 
                             style="max-width: 200px; max-height: 200px; padding: 5px; border: 1px solid #ddd;">
                    </td>
                </tr>

                <!-- Category Dropdown -->
                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">
                            <?php
                            $sql = "SELECT category_id, title FROM food_category WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);
                            if ($res && mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $selected = ($row['category_id'] == $category_id) ? 'selected' : '';
                                    echo "<option value='{$row['category_id']}' $selected>{$row['title']}</option>";
                                }
                            } else {
                                echo "<option value=''>No Categories Available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <!-- Featured -->
                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php echo ($featured === 'Yes') ? 'checked' : ''; ?>> Yes
                        <input type="radio" name="featured" value="No" <?php echo ($featured === 'No') ? 'checked' : ''; ?>> No
                    </td>
                </tr>

                <!-- Active -->
                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php echo ($active === 'Yes') ? 'checked' : ''; ?>> Yes
                        <input type="radio" name="active" value="No" <?php echo ($active === 'No') ? 'checked' : ''; ?>> No
                    </td>
                </tr>

                <!-- Hidden Fields and Buttons -->
                <tr>
                    <td colspan="2" class="form-buttons">
                        <br>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                        <a href="<?php echo SITEURL; ?>admin/food-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
// New Image Preview
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
