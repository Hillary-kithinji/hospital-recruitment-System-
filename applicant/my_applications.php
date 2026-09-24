<?php
session_start();
include "../config/db.php";

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch applications
$result = mysqli_query($conn, "
    SELECT applications.*, 
           jobs.title 
    FROM applications 
    JOIN jobs ON applications.job_id = jobs.id 
    WHERE applications.user_id = '$user_id' 
    ORDER BY applications.id DESC
");

$has_applications = mysqli_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
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
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 40px;
            font-size: 2.2rem;
        }

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }

        .card h3 {
            color: #0056b3;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .status {
            font-weight: bold;
            color: #007bff;
            font-size: 1.1rem;
        }

        .no-applications {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            max-width: 700px;
            margin: 40px auto;
        }

        .no-applications h2 {
            color: #0056b3;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .no-applications p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .browse-btn {
            display: inline-block;
            background: #0056b3;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.3s;
        }

        .browse-btn:hover {
            background: #003d80;
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
            <a href="my_applications.php">My Applications</a>
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
            <a href="../index.php">Home</a>
            <a href="../jobs.php">Browse Jobs</a>
            <a href="my_applications.php">My Applications</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <h1>My Applications</h1>

        <?php if ($has_applications): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Status:</strong>
                        <span class="status"><?= htmlspecialchars($row['status']); ?></span>
                    </p>
                    <p><strong>Applied On:</strong> <?= date('d M, Y', strtotime($row['applied_at'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-applications">
                <h2>Welcome! 👋</h2>
                <p>You haven't applied to any jobs yet.<br>
                   Start exploring exciting opportunities and take the next step in your career.</p>
                <a href="../jobs.php" class="browse-btn">Browse Available Jobs</a>
            </div>
        <?php endif; ?>
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