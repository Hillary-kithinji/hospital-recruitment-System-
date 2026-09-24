<?php
session_start();
include "config/db.php";

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: applicant/my_applications.php");
        }
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital Recruitment</title>
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
        .logo { 
            text-align: center; 
            margin-bottom: 20px; 
            font-size: 3rem; 
        }
        h2 { 
            text-align: center; 
            color: #0056b3; 
            margin-bottom: 30px; 
        }

        .message {
            text-align: center;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        .error { background: #f8d7da; color: #721c24; }

        label { 
            display: block; 
            margin: 18px 0 8px; 
            font-weight: 600; 
            color: #444; 
        }

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
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.2);
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
            transition: all 0.3s;
        }
        button:hover {
            background: #003d80;
            transform: translateY(-2px);
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #0056b3;
            text-decoration: none;
            display: block;
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏥</div>
        <h2>Welcome Back</h2>

        <?php if (!empty($message)): ?>
            <p class="message error"><?= $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Email Address</label>
            <div class="input-group">
                <span class="icon">✉️</span>
                <input type="email" name="email" placeholder="your@email.com" required>
            </div>

            <label>Password</label>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
            </div>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="links">
            <a href="forgot_password.php">Forgot Password?</a>
            <a href="register.php">Don't have an account? Register</a>
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