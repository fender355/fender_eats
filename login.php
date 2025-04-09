<?php
session_start();
include('config/constants.php');

$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pw = trim($_POST['password'] ?? '');

    if (empty($email) || empty($pw)) {
        $errors[] = 'Email and password required.';
    } else {
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM user_acc WHERE user_email = '$email'";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            if ($pw === $row['user_pw']) {
                $_SESSION['user'] = [
                    'id' => $row['user_acc_id'],
                    'name' => $row['user_name'],
                    'email' => $row['user_email']
                ];
                header('Location: index.php');
                exit();
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>

<html>
<head>
    <title>Login - Fender Eats</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Add the same custom styles as admin login */
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
        .toggle-password:hover { background: #e0e0e0; }
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
        .success {
            text-align: center;
            margin: 0 auto;
            width: 100%;
            max-width: 400px;
            padding: 10px;
            background-color: #ebffee;
            border: 1px solid #caffdd;
            border-radius: 5px;
            color:rgb(8, 174, 66);
        }
        .success p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="login">
        <h1 class="text-center">Login</h1>
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

        <?php

        if(isset($_SESSION['no-login-message'])) {

            echo $_SESSION['no-login-message']; // Display Session Message
            unset($_SESSION['no-login-message']); // Removing Session Message

        }

        if(isset($_SESSION['signup-success'])) {

            echo $_SESSION['signup-success']; // Display Session Message
            unset($_SESSION['signup-success']); // Removing Session Message

        }


        ?>

        <br>

        <form action="" method="POST" class="text-center">
            Email: <br>
            <input type="email" name="email" placeholder="example@mail.com" 
                   value="<?= htmlspecialchars($old_input['email'] ?? '') ?>" autocomplete="off">
            <br><br>

            Password: <br>
            <div class="password-toggle-container">
                <p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p>
                <input type="password" name="password" id="password" placeholder="********" autocomplete="off">
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Show password">
                    👁️
                </button>
            </div>
            <br><br>

            <input type="submit" value="Login" class="btn-primary">
        </form>
        <br>
        <p class="text-center">Don't have an account? <a href="signup.php">Sign Up</a></p>
        <br>
        <p class="text-center">© 2025 Perniagaan Fender Maju Jaya. All rights reserved.</p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerHTML = '－';
            } else {
                passwordInput.type = 'password';
                toggleButton.innerHTML = '👁️';
            }
        }
    </script>
</body>
</html>