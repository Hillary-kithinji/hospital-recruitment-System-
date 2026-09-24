<?php
session_start();
include "config/db.php";

$jobs = mysqli_query($conn, "
    SELECT * FROM jobs 
    WHERE status = 'Open' 
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs - Hospital Careers</title>
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        /* Mobile Menu */
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
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        /* Main content grows to push footer down */
        .main {
            flex: 1;
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
            margin-bottom: 10px;
            font-size: 2.2rem;
        }

        .job-count {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        /* Scrollable job list */
        .job-scroll-wrapper {
            max-height: 780px;
            overflow-y: auto;
            padding-right: 6px;
            scrollbar-width: thin;
            scrollbar-color: #0056b3 #e0e7ef;
        }

        .job-scroll-wrapper::-webkit-scrollbar {
            width: 7px;
        }

        .job-scroll-wrapper::-webkit-scrollbar-track {
            background: #e0e7ef;
            border-radius: 10px;
        }

        .job-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #0056b3;
            border-radius: 10px;
        }

        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 25px;
            padding-bottom: 10px;
        }

        .job-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }

        /* Job title label above the job name */
        .job-title-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }

        .job-card h2 {
            color: #0056b3;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .job-info {
            margin: 12px 0;
            line-height: 1.6;
        }

        .job-info strong {
            color: #444;
        }

        .deadline {
            color: #d32f2f;
            font-weight: bold;
        }

        .apply-btn {
            display: inline-block;
            background: #0056b3;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .apply-btn:hover {
            background: #003d80;
        }

        .no-jobs {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            font-size: 1.2rem;
            color: #666;
        }

        /* Scroll hint */
        .scroll-hint {
            text-align: center;
            color: #0056b3;
            font-size: 0.88rem;
            margin-top: 10px;
            opacity: 0.8;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 25px;
            background: #222;
            color: white;
            font-size: 0.95rem;
            margin-top: auto;
        }

        footer a {
            color: #7eb8ff;
            text-decoration: none;
            margin: 0 8px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .footer-links {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">🏥  HOSPITAL RECRUITMNET</div>

        <!-- Desktop Links -->
        <div class="nav-links">
            <a href="index.php">Home</a>
            
            <a href="index.php#contact">Contact</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="admin/dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a href="applicant/my_applications.php">My Applications</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Signup</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobile-menu">
            <a href="index.php">Home</a>
        
            <a href="index.php#contact">Contact</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="admin/dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a href="applicant/my_applications.php">My Applications</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Signup</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="container">
            <h1>Available Job Vacancies</h1>

            <?php $total = mysqli_num_rows($jobs); ?>

            <?php if($total == 0): ?>
                <div class="no-jobs">
                    <p>No vacancies available at the moment.</p>
                    <p>Please check back later.</p>
                </div>
            <?php else: ?>

                <p class="job-count">
                    <?= $total; ?> open position<?= $total > 1 ? 's' : ''; ?> available
                </p>

                <div class="job-scroll-wrapper">
                    <div class="job-grid">
                        <?php while($job = mysqli_fetch_assoc($jobs)): ?>
                        <div class="job-card">

                            <h2>
                                <span class="job-title-label">Job Title</span>
                                <?= htmlspecialchars($job['title']); ?>
                            </h2>

                            <div class="job-info">
                                <strong>Department:</strong>
                                <?= htmlspecialchars($job['department']); ?>
                            </div>

                            <div class="job-info">
                                <strong>Description:</strong><br>
                                <?= nl2br(htmlspecialchars($job['description'])); ?>
                            </div>

                            <div class="job-info">
                                <strong>Requirements:</strong><br>
                                <?= nl2br(htmlspecialchars($job['requirements'])); ?>
                            </div>

                            <div class="job-info deadline">
                                <strong>Deadline:</strong>
                                <?= htmlspecialchars($job['deadline']); ?>
                            </div>

                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a class="apply-btn"
                                   href="applicant/apply.php?id=<?= $job['id']; ?>">
                                    Apply Now →
                                </a>
                            <?php else: ?>
                                <a class="apply-btn"
                                   href="login.php">
                                    Login to Apply
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <?php if($total > 2): ?>
                    <p class="scroll-hint">↕ Scroll inside the list to see more positions</p>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="jobs.php">Jobs</a>
            <a href="index.php#contact">Contact</a>
        </div>
        © <?= date("Y"); ?> KAGIO HOSPITAL Recruitment System • All Rights Reserved
    </footer>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.style.display = (mobileMenu.style.display === 'flex') ? 'none' : 'flex';
        });

        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.style.display = 'none';
            });
        });
    </script>

</body>
</html>