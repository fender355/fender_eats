<?php
session_start();
include('../config/constants.php');

// Retrieve errors and old input from session
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

// Clear the session data after retrieval to prevent them from persisting on refresh
unset($_SESSION['errors'], $_SESSION['old_input']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $admin_mail = trim($_POST['admin_mail'] ?? '');
    $admin_pw = trim($_POST['admin_pw'] ?? '');

    // Validate inputs
    if (empty($admin_mail)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($admin_mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    
    if (empty($admin_pw)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($admin_pw) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
        // Sanitize email input
        $admin_mail = mysqli_real_escape_string($conn, $admin_mail);

        // Check if user exists
        $sql = "SELECT * FROM admin_acc WHERE admin_mail = '$admin_mail'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            
            // Verify password (plaintext comparison - not recommended for production)
            if ($admin_pw === $row['admin_pw']) {
                // Set session variables
                $_SESSION['admin'] = [
                    'id' => $row['id'],
                    'name' => $row['admin_name'],
                    'email' => $row['admin_mail']
                ];
                $_SESSION['login'] = "<div class='success'> Login Successful. </div>";
                $_SESSION['user'] = $admin_mail;
                
                // Redirect to admin panel
                header('Location: ' . SITEURL . 'admin/');
                exit();
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }

    // Store errors and input in session for redirect
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = ['admin_mail' => $admin_mail];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<html>
    <head>
        <title>Login - Fender Eats Admin Panel</title>
        <link rel="stylesheet" href="../css/admin.css">
        <style>
            .password-toggle-container {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                margin: 0 auto;
            }
            .toggle-password {
                background: #f0f0f0;
                border: 1px solid #ccc;
                border-radius: 3px;
                padding: 4px 8px;
                margin-left: 5px;
                cursor: pointer;
                transition: all 0.3s;
            }

            .toggle-password:hover{
                background: #e0e0e0;
            }

            /* Center the error messages */
            .error {
                text-align: center;
                margin: 0 auto;
                width: 100%;
                max-width: 400px; /* Adjust as needed */
                padding: 10px;
                background-color: #ffebee; /* Light red background */
                border: 1px solid #ffcdd2; /* Light red border */
                border-radius: 5px;
                color: #c62828; /* Dark red text */
            }

            .error p {
                margin: 5px 0; /* Add spacing between error messages */
            }
        </style>
    </head>

    <body>
        <div class="login">
            <h1 class="text-center">Login</h1>
            <h1 class="text-center">Fender Eats Admin Panel</h1>
            <br><br>

            <?php 
            if(isset($_SESSION['no-login-message'])) {
                echo $_SESSION['no-login-message'];
                unset($_SESSION['no-login-message']);
                echo "<br>";
            }
            ?>

            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
                <br>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="" method="POST" class="text-center">
                Email: <br>
                <input type="email" name="admin_mail" placeholder="vladimir1922@mail.ru" 
                       value="<?= htmlspecialchars($old_input['admin_mail'] ?? '') ?>" autocomplete="off">
                <br><br>

                Password: <br>
                <div class="password-toggle-container">
                    <p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p>
                    <input type="password" name="admin_pw" id="admin_pw" placeholder="********" autocomplete="off">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Show password">
                        👁️
                    </button>
                </div>
                <br><br>

                <input type="submit" name="submit" value="Login" class="btn-primary">
            </form>
            <br><br>
            <p class="text-center">© 2025 Perniagaan Fender Maju Jaya. All rights reserved. </p>
        </div>

        <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('admin_pw');
            const toggleButton = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerHTML = '－';
                toggleButton.setAttribute('aria-label', 'Hide password');
            } else {
                passwordInput.type = 'password';
                toggleButton.innerHTML = '👁️';
                toggleButton.setAttribute('aria-label', 'Show password');
            }
        }
        </script>
    </body>
</html>