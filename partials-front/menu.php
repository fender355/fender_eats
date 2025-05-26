<?php 
session_start();
include('config/constants.php'); 
include('partials-front/login-check.php'); 

// Initialize font settings if not set
if (!isset($_SESSION['font_size'])) {
    $_SESSION['font_size'] = 'medium';
}
if (!isset($_SESSION['font_color'])) {
    $_SESSION['font_color'] = '#000000';
}

// Handle font changes if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['font_size'])) {
        $_SESSION['font_size'] = $_POST['font_size'];
    }
    if (isset($_POST['font_color'])) {
        $_SESSION['font_color'] = $_POST['font_color'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Font size classes */
        .font-small {
            font-size: 0.8em;
        }
        .font-medium {
            font-size: 1em;
        }
        .font-large {
            font-size: 1.2em;
        }
        .font-xlarge {
            font-size: 1.5em;
        }
        
        /* Apply the selected font size to the body */
        body {
            font-size: <?php 
                switch($_SESSION['font_size']) {
                    case 'small': echo '0.8em'; break;
                    case 'medium': echo '1em'; break;
                    case 'large': echo '1.2em'; break;
                    case 'xlarge': echo '1.5em'; break;
                    default: echo '1em';
                }
            ?>;
            color: <?php echo $_SESSION['font_color']; ?>;
            padding-top: 70px; /* Add padding to body to account for fixed navbar */
        }
        
        /* Make navbar sticky/fixed */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            padding-top: 0;
            padding-bottom: 0;
        }
        
        /* Dropdown styles - now wider but shorter */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 250px; /* Increased width */
            max-height: 300px; /* Limit height */
            overflow-y: auto; /* Add scroll if needed */
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            padding: 10px;
            border-radius: 5px;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        /* Make dropdown button match other nav buttons */
        .dropdown-btn {
            background: none;
            color: #ff6b81;
            padding: 12px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: inherit;
            font-family: inherit;
        }
        
        .dropdown-btn:hover {
            color: #ff4757;
        }
        
        /* More compact options layout */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns */
            gap: 5px;
        }
        
        .font-options, .color-options {
            display: contents; /* Children will participate in grid */
        }
        
        .font-option, .color-option {
            padding: 3px 5px;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .font-option:hover, .color-option:hover {
            background-color: #f1f1f1;
        }
        
        .color-preview {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 8px;
            vertical-align: middle;
            border: 1px solid #ddd;
        }
        
        /* Compact form styling */
        .dropdown-content h4 {
            margin: 5px 0;
            grid-column: span 2; /* Make headers span both columns */
        }
        
        .dropdown-content .btn {
            grid-column: span 2;
            margin-top: 8px;
            padding: 5px;
        }
        
        /* Make sure menu items align properly */
        .menu ul li {
            display: inline-block;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo SITEURL; ?>" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="<?php echo SITEURL; ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                    <li class="cart-link">
                        <a href="cart.php">
                            Cart <?= (!empty($_SESSION['cart'])) ? '('.count($_SESSION['cart']).')' : '' ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>order-history.php">History</a>
                    </li>
                    <li>
                        <div class="dropdown">
                            <a class="dropdown-btn">Display Options</a>
                            <div class="dropdown-content">
                                <form method="post" action="">
                                    <div class="options-grid">
                                        <h4>Font Size</h4>
                                        <h4>Font Color</h4>
                                        
                                        <div class="font-options">
                                            <div class="font-option">
                                                <input type="radio" id="small" name="font_size" value="small" <?= ($_SESSION['font_size'] == 'small') ? 'checked' : '' ?>>
                                                <label for="small">Small</label>
                                            </div>
                                            <div class="font-option">
                                                <input type="radio" id="medium" name="font_size" value="medium" <?= ($_SESSION['font_size'] == 'medium') ? 'checked' : '' ?>>
                                                <label for="medium">Medium</label>
                                            </div>
                                            <div class="font-option">
                                                <input type="radio" id="large" name="font_size" value="large" <?= ($_SESSION['font_size'] == 'large') ? 'checked' : '' ?>>
                                                <label for="large">Large</label>
                                            </div>
                                            <div class="font-option">
                                                <input type="radio" id="xlarge" name="font_size" value="xlarge" <?= ($_SESSION['font_size'] == 'xlarge') ? 'checked' : '' ?>>
                                                <label for="xlarge">X-Large</label>
                                            </div>
                                        </div>
                                        
                                        <div class="color-options">
                                            <div class="color-option">
                                                <div class="color-preview" style="background-color: #000000;"></div>
                                                <input type="radio" id="black" name="font_color" value="#000000" <?= ($_SESSION['font_color'] == '#000000') ? 'checked' : '' ?>>
                                                <label for="black">Black</label>
                                            </div>
                                            <div class="color-option">
                                                <div class="color-preview" style="background-color: #3333cc;"></div>
                                                <input type="radio" id="blue" name="font_color" value="#3333cc" <?= ($_SESSION['font_color'] == '#3333cc') ? 'checked' : '' ?>>
                                                <label for="blue">Blue</label>
                                            </div>
                                            <div class="color-option">
                                                <div class="color-preview" style="background-color: #990000;"></div>
                                                <input type="radio" id="red" name="font_color" value="#990000" <?= ($_SESSION['font_color'] == '#990000') ? 'checked' : '' ?>>
                                                <label for="red">Red</label>
                                            </div>
                                            <div class="color-option">
                                                <div class="color-preview" style="background-color: #006600;"></div>
                                                <input type="radio" id="green" name="font_color" value="#006600" <?= ($_SESSION['font_color'] == '#006600') ? 'checked' : '' ?>>
                                                <label for="green">Green</label>
                                            </div>
                                        </div>
                                        
                                        <input type="submit" value="Apply" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->