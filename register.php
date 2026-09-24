<?php
include "config/db.php";

$message = "";

if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($fullname) || empty($email) || empty($password)) {
        $message = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (fullname, email, password, role) 
                VALUES ('$fullname', '$email', '$password_hash', 'user')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            $message = "Error: Email may already be registered.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hospital Recruitment</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0056b3, #00b248);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }
        .logo { text-align: center; margin-bottom: 20px; font-size: 3rem; }
        h2 { text-align: center; color: #0056b3; margin-bottom: 30px; }

        .message {
            text-align: center;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        .error { background: #f8d7da; color: #721c24; }

        label { display: block; margin: 18px 0 8px; font-weight: 600; color: #444; }

        .input-group {
            position: relative;
        }
        .input-group input {
            width: 100%;
            padding: 14px 45px 14px 45px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .input-group input:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 0 3px rgba(0,86,179,0.2);
        }
        .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.3rem;
            color: #666;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.4rem;
            color: #666;
        }

        button {
            width: 100%;
            padding: 15px;
            margin-top: 25px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #003d80; transform: translateY(-2px); }

        .login-link { text-align: center; margin-top: 25px; }
        .login-link a { color: #0056b3; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏥</div>
        <h2>Create New Account</h2>

        <?php if (!empty($message)): ?>
            <p class="message error"><?= $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Full Name</label>
            <div class="input-group">
                <span class="icon">👤</span>
                <input type="text" name="fullname" placeholder="Enter your full name" required>
            </div>

            <label>Email Address</label>
            <div class="input-group">
                <span class="icon">✉️</span>
                <input type="email" name="email" placeholder="your@email.com" required>
            </div>

            <label>Password</label>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="password" placeholder="Create password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
            </div>

            <label>Confirm Password</label>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁️</span>
            </div>

            <button type="submit" name="register">Register Now</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login Here</a>
        </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            const field = document.getElementById(id);
            if (field.type === "password") {
                field.type = "text";
                icon.textContent = "🙈";
            } else {
                field.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>