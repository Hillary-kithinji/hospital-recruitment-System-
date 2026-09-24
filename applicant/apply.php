<?php
session_start();
include "../config/db.php";

$job_id = $_GET['id'] ?? 0;
$message = "";
$success = false;

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);
    $cover_letter = trim($_POST['cover_letter']);

    if (empty($job_id) || empty($_SESSION['user_id'])) {
        $message = "Invalid session or job ID.";
    } elseif (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        
        $upload_dir = __DIR__ . "/../uploads/cvs/";
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $original_name = $_FILES['cv']['name'];
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed_exts = ['pdf', 'doc', 'docx'];

        if (!in_array($ext, $allowed_exts)) {
            $message = "Only PDF, DOC, and DOCX files are allowed.";
        } else {
            $safe_name = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($original_name, PATHINFO_FILENAME));
            $file_name = time() . "_" . $safe_name . "." . $ext;
            $target = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $target)) {
                $sql = "INSERT INTO applications 
                        (user_id, email, job_id, phone, qualification, experience, cv_file, cover_letter) 
                        VALUES 
                        ('{$_SESSION['user_id']}', '$email', '$job_id', '$phone', '$qualification', 
                         '$experience', '$file_name', '$cover_letter')";

                if (mysqli_query($conn, $sql)) {
                    $message = "Application Submitted Successfully ✔";
                    $success = true;
                } else {
                    $message = "Database error: " . mysqli_error($conn);
                }
            } else {
                $message = "File upload failed. Check folder permissions (755).";
            }
        }
    } else {
        $message = "Please upload your CV/Recommendation Letter.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7f9;
            color: #333;
        }

        /* Navbar */
        .navbar {
            background: rgba(14, 79, 143, 0.95);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 1.4rem;
            font-weight: bold;
            color: white;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
        }

        .hamburger span {
            width: 28px;
            height: 3px;
            background: white;
            border-radius: 3px;
            transition: all 0.3s;
        }

        /* Mobile Menu - inside navbar */
        .mobile-menu {
            display: none;
            flex-direction: column;
            background: rgba(14, 79, 143, 0.98);
            width: 100%;
            padding: 10px 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 10px;
        }

        .mobile-menu a {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            font-size: 1.05rem;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .mobile-menu a:last-child {
            border-bottom: none;
        }

        /* Hamburger Animation */
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .hamburger {
                display: flex;
            }
        }

        /* Form Container */
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 25px;
        }

        .msg {
            text-align: center;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .success { background: #d4edda; color: #155724; }
        .error   { background: #f8d7da; color: #721c24; }

        label {
            display: block;
            margin: 15px 0 8px;
            font-weight: 600;
            color: #444;
        }

        input[type="email"],
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 25px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            cursor: pointer;
        }

        button:hover { background: #003d80; }

        .note {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">🏥 Hospital Recruitment</div>

        <!-- Desktop Links -->
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../jobs.php">Browse Jobs</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_applications.php">My Applications</a>
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Mobile Menu (inside navbar so it drops below correctly) -->
        <div class="mobile-menu" id="mobile-menu">
            <a href="../index.php">Home</a>
            <a href="../jobs.php">Browse Jobs</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_applications.php">My Applications</a>
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FORM -->
    <div class="container">
        <h2>Job Application</h2>

        <?php if ($message != ""): ?>
            <p class="msg <?= $success ? 'success' : 'error' ?>"><?= $message; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="your@email.com" required>

            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="+254 700 000 000" required>

            <label>Highest Qualification</label>
            <input type="text" name="qualification" placeholder="e.g. Bachelor of Nursing" required>

            <label>Experience</label>
            <textarea name="experience" placeholder="Briefly describe your relevant experience..."></textarea>

            <label>CV / Recommendation Letter (PDF, DOC, DOCX)</label>
            <input type="file" name="cv" accept=".pdf,.doc,.docx" required>

            <label>Cover Letter</label>
            <textarea name="cover_letter" placeholder="Write your cover letter here..."></textarea>

            <button type="submit" name="submit">Submit Application</button>
        </form>

        <p class="note">Spaces in filenames are automatically cleaned. Supported: PDF, DOC, DOCX.</p>
    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.style.display = (mobileMenu.style.display === 'flex') ? 'none' : 'flex';
        });

        // Close menu when clicking a link
        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.style.display = 'none';
            });
        });
    </script>

</body>
</html>