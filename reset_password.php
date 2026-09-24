<?php
include "config/db.php";

$message = "";

// 1. Get and clean token
$token = trim($_GET['token'] ?? '');

if (empty($token)) {
    die("<h2 style='color:red; text-align:center; margin-top:100px;'>❌ Invalid reset link.</h2>");
}

// 2. Secure query (FIXED)
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 3. Validate token exists
if (!$user) {
    die("<h2 style='color:red; text-align:center; margin-top:100px;'>❌ This reset link is invalid or has been used.</h2>");
}

// 4. Check expiry safely
if (strtotime($user['reset_expiry']) < time()) {
    die("<h2 style='color:red; text-align:center; margin-top:100px;'>❌ This reset link has expired.<br><a href='forgot_password.php'>Request a new link</a></h2>");
}

// 5. Handle password reset
if (isset($_POST['reset'])) {

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "❌ Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $message = "❌ Password must be at least 6 characters!";
    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update password + clear token (SECURE)
        $stmt = $conn->prepare("
            UPDATE users 
            SET password = ?, reset_token = NULL, reset_expiry = NULL 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $password_hash, $user['id']);
        $stmt->execute();

        header("Location: login.php?success=reset");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #0056b3, #00b248);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 400px;
        }

        h2 { text-align: center; color: #0056b3; }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>

    <?php if (!empty($message)): ?>
        <div class="error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>

</body>
</html>