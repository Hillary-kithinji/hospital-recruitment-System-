<?php
session_start();
include "../config/db.php";

/* Check admin access */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['save'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO jobs (title, department, description, requirements, deadline) 
            VALUES ('$title', '$department', '$description', '$requirements', '$deadline')";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Job Created Successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Job</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        /* Container */
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 30px;
        }

        .message {
            padding: 12px;
            margin-bottom: 25px;
            border-radius: 6px;
            text-align: center;
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

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        textarea {
            min-height: 120px;
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

        button:hover {
            background: #003d80;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #0056b3;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">🏥 Hospital Recruitment</div>

        <!-- Desktop Links -->
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="applications.php">Applications</a>
            <a href="admin_jobs.php">Manage Jobs</a>
            <a href="../jobs.php">View Jobs</a>
            <a href="../logout.php">Logout</a>
        </div>

        <!-- Hamburger -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Mobile Menu (inside navbar so it drops below correctly) -->
        <div class="mobile-menu" id="mobile-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="applications.php">Applications</a>
            <a href="admin_jobs.php">Manage Jobs</a>
            <a href="../jobs.php">View Jobs</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <!-- FORM -->
    <div class="container">
        <h2>Create New Job Vacancy</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label>Job Title</label>
            <input type="text" name="title" placeholder="e.g. Registered Nurse" required>

            <label>Department</label>
            <input type="text" name="department" placeholder="e.g. Nursing, Pediatrics, Administration" required>

            <label>Job Description</label>
            <textarea name="description" placeholder="Describe the job responsibilities..." required></textarea>

            <label>Requirements</label>
            <textarea name="requirements" placeholder="List qualifications, experience, skills..." required></textarea>

            <label>Application Deadline</label>
            <input type="date" name="deadline" required>

            <button type="submit" name="save">Create Job</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
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