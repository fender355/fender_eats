<?php
include('../config/constants.php');
include('partials/menu.php');

// Process file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Please select a file to upload.';
    } else {
        $file = $_FILES['import_file'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Only allow certain file types
        if (!in_array($file_ext, ['txt', 'doc', 'docx', 'csv'])) {
            $errors[] = 'Only TXT, DOC, DOCX, and CSV files are allowed.';
        } else {
            // Process the file based on type
            $content = '';
            
            if ($file_ext === 'txt' || $file_ext === 'csv') {
                $content = file_get_contents($file['tmp_name']);
            } elseif ($file_ext === 'doc' || $file_ext === 'docx') {
                // For DOC/DOCX files, we'll need a library to read them
                // You'll need to install phpword (composer require phpoffice/phpword)
                try {
                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($file['tmp_name']);
                    $content = '';
                    foreach ($phpWord->getSections() as $section) {
                        foreach ($section->getElements() as $element) {
                            if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                foreach ($element->getElements() as $text) {
                                    if ($text instanceof \PhpOffice\PhpWord\Element\Text) {
                                        $content .= $text->getText();
                                    }
                                }
                            } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                                $content .= $element->getText();
                            }
                        }
                    }
                } catch (Exception $e) {
                    $errors[] = 'Error reading Word document: ' . $e->getMessage();
                }
            }
            
            if (!empty($content)) {
                // Parse the content into food items
                $imported_foods = parseFoodContent($content, $file_ext);
                
                // Validate and save each food item
                $success_count = 0;
                $error_count = 0;
                $error_messages = [];
                
                foreach ($imported_foods as $food) {
                    $validation_errors = validateFoodItem($food);
                    
                    if (empty($validation_errors)) {
                        // Save to database
                        $save_result = saveFoodItem($food, $conn);
                        
                        if ($save_result) {
                            $success_count++;
                        } else {
                            $error_count++;
                            $error_messages[] = "Failed to save: {$food['title']} - " . mysqli_error($conn);
                        }
                    } else {
                        $error_count++;
                        }
                }
                
                $_SESSION['import_result'] = [
                    'success' => $success_count,
                    'errors' => $error_count,
                    'messages' => $error_messages
                ];
                
                header("Location: " . SITEURL . 'admin/food-manage.php');
                exit();
            }
        }
    }
}

function parseFoodContent($content, $file_type) {
    $foods = [];
    
    if ($file_type === 'csv') {
        // Parse CSV format
        $lines = explode("\n", $content);
        $headers = str_getcsv(array_shift($lines));
        
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $values = str_getcsv($line);
                $food = array_combine($headers, $values);
                $foods[] = [
                    'title' => $food['title'] ?? '',
                    'description' => $food['description'] ?? '',
                    'price' => $food['price'] ?? 0,
                    'category' => $food['category'] ?? 1,
                    'featured' => $food['featured'] ?? 'No',
                    'active' => $food['active'] ?? 'Yes',
                    'image' => $food['image'] ?? ''
                ];
            }
        }
    } else {
        // Parse text-based formats (TXT, DOC, DOCX)
        // Assuming format: Title|Description|Price|Category|Featured|Active|Image
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $parts = explode('|', $line);
                if (count($parts) >= 6) {
                    $foods[] = [
                        'title' => $parts[0] ?? '',
                        'description' => $parts[1] ?? '',
                        'price' => $parts[2] ?? 0,
                        'category' => $parts[3] ?? 1,
                        'featured' => $parts[4] ?? 'No',
                        'active' => $parts[5] ?? 'Yes',
                        'image' => $parts[6] ?? ''
                    ];
                }
            }
        }
    }
    
    return $foods;
}

function validateFoodItem($food) {
    $errors = [];
    
    if (empty($food['title'])) {
        $errors[] = 'Title is required';
    }
    
    if (empty($food['description'])) {
        $errors[] = 'Description is required';
    }
    
    if (!is_numeric($food['price']) || $food['price'] <= 0) {
        $errors[] = 'Price must be a positive number';
    }
    
    if (!is_numeric($food['category'])) {
        $errors[] = 'Invalid category';
    }
    
    if (!in_array($food['featured'], ['Yes', 'No'])) {
        $errors[] = 'Featured must be Yes or No';
    }
    
    if (!in_array($food['active'], ['Yes', 'No'])) {
        $errors[] = 'Active must be Yes or No';
    }
    
    return $errors;
}

function saveFoodItem($food, $conn) {
    $title = mysqli_real_escape_string($conn, $food['title']);
    $description = mysqli_real_escape_string($conn, $food['description']);
    $price = mysqli_real_escape_string($conn, $food['price']);
    $category_id = mysqli_real_escape_string($conn, $food['category']);
    $featured = mysqli_real_escape_string($conn, $food['featured']);
    $active = mysqli_real_escape_string($conn, $food['active']);
    
    // Handle image - you might want to implement file upload here
    $image_name = '';
    if (!empty($food['image'])) {
        $image_name = 'Food_Image_' . uniqid() . '.jpg'; // Adjust as needed
        // You would need to implement the actual image handling
    }
    
    $sql = "INSERT INTO `food_food` SET
        title = '$title',
        description = '$description',
        price = '$price',
        image_name = '$image_name',
        category_id = '$category_id',
        featured = '$featured',
        active = '$active'
    ";
    
    return mysqli_query($conn, $sql);
}
?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Import Food Items</h1>
        <br>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Select File:</td>
                    <td>
                        <input type="file" name="import_file" accept=".txt,.doc,.docx,.csv" required>
                        <p class="help-text">Accepted formats: TXT, DOC, DOCX, CSV</p>
                    </td>
                </tr>
                <tr>
                    <td>File Format:</td>
                    <td>
                        <br>
                        <p>For CSV: title,description,price,category,featured,active,image</p>
                        <p>For TXT/DOC: title|description|price|category|featured|active|image</p>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Import" class="btn-secondary">
                        <a href="<?= SITEURL ?>admin/food-manage.php" class="btn-danger">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>