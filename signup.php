<?php
include('config/constants.php');
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    $user_mobile = trim($_POST['user_mobile'] ?? '');
    $user_pw = trim($_POST['user_pw'] ?? '');

    if (empty($user_name) || empty($user_email) || empty($user_pw)) {
        $errors[] = 'All fields marked * are required.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } elseif (strlen($user_pw) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
        $user_email = mysqli_real_escape_string($conn, $user_email);
        $check = mysqli_query($conn, "SELECT * FROM user_acc WHERE user_email = '$user_email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email already exists.';
        } else {
            $sql = "INSERT INTO user_acc (user_name, user_email, user_mobile, user_pw)
                    VALUES ('$user_name', '$user_email', '$user_mobile', '$user_pw')";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                $_SESSION['signup-success'] = "<div class='success'>Account created successfully.</div>";
                header('Location: login.php');
                exit();
            } else {
                $errors[] = 'Something went wrong. Please try again.';
            }
        }
    }
}
if ($_POST['user_pw'] !== $_POST['confirm_pw']) {
    $errors[] = "Password and Confirm Password must match.";
}
?>

<html>
<head>
    <title>Sign Up - Fender Eats</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .error {
            text-align: center;
            margin: 0 auto;
            width: 100%;
            max-width: 400px;
            padding: 10px;
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 5px;
            color: #c62828;
        }
        .error p { margin: 5px 0; }
        .login { width: 40%; }
        .password-toggle-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 55%;
            margin: 0 auto;
        }
        .password-toggle-container input {
            width: 55%;
            margin-right: 8px;
        }
    </style>
    </style>
</head>
<body>
    <div class="login">
        <h1 class="text-center">Sign Up</h1>
        <h1 class="text-center">Fender Eats</h1>
        <br><br>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
            <br>
        <?php endif; ?>

        <form method="POST" class="text-center">
            Name*: <br>
            <input type="text" name="user_name" placeholder="John Doe" 
                   value="<?= htmlspecialchars($old_input['user_name'] ?? '') ?>" autocomplete="off">
            <br><br>

            Email*: <br>
            <input type="email" name="user_email" placeholder="example@mail.com" 
                   value="<?= htmlspecialchars($old_input['user_email'] ?? '') ?>" autocomplete="off">
            <br><br>

            Mobile: <br>
            <input type="text" name="user_mobile" placeholder="+60 12 345 6789" 
                   value="<?= htmlspecialchars($old_input['user_mobile'] ?? '') ?>" autocomplete="off">
            <br><br>

            Password*: <br>
            <div class="password-toggle-container">
                <input type="password" name="user_pw" id="user_pw" placeholder="********" autocomplete="off">
                <button type="button" class="toggle-password" onclick="togglePassword('user_pw')" aria-label="Show password">
                    👁️
                </button>
            </div>
            <br><br>

            Confirm Password*: <br>
            <div class="password-toggle-container">
                <input type="password" name="confirm_pw" id="confirm_pw" placeholder="********" autocomplete="off">
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_pw')" aria-label="Show password">
                    👁️
                </button>
            </div>
            <br><br>

            <input type="submit" value="Create Account" class="btn-primary">
        </form>
        <br>
        <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
        <br>
        <p class="text-center">© 2025 Perniagaan Fender Maju Jaya. All rights reserved.</p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const pwInput = document.getElementById(fieldId);
            const toggleBtn = pwInput.nextElementSibling;
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                toggleBtn.textContent = '－';
            } else {
                pwInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }
    </script>
</body>
</html>