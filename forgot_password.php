<?php
include "config/db.php";

// Correct PHPMailer path
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if (isset($_POST['send_link'])) {
    $email = trim($_POST['email']);
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        mysqli_query($conn, "UPDATE users SET 
            reset_token = '$token', 
            reset_expiry = '$expiry' 
            WHERE email = '$email'");

        $reset_link = "http://localhost/hospital-recruitment/reset_password.php?token=" . urlencode($token);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '';
            $mail->Password   = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('', 'Hospital Recruitment');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request - Hospital Jobs';
            $mail->Body    = "
                <h2>Reset Your Password</h2>
                <p>Click the button below to reset your password:</p>
                <p><a href='$reset_link' style='background:#0056b3;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
                <p><strong>This link will expire in 1 hour.</strong></p>
            ";

            $mail->send();
            $message = "✅ Reset link has been sent to your email!";
        } catch (Exception $e) {
            $message = "❌ Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $message = "❌ No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            cursor: pointer;
        }
        .back { text-align: center; margin-top: 25px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏥</div>
        <h2>Forgot Password</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <button type="submit" name="send_link">Send Reset Link</button>
        </form>

        <div class="back">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
</body>
</html>