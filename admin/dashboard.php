<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            max-width: 900px;
            margin: 60px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 40px;
            font-size: 2.2rem;
        }

        .welcome {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.1rem;
            color: #555;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.12);
        }

        .menu-card a {
            text-decoration: none;
            color: #0056b3;
            font-size: 1.25rem;
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        .menu-card:hover a {
            color: #003d80;
        }

        .icon {
            font-size: 3rem;
            margin-bottom: 15px;
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
           
            <a href="applications.php">Applications</a>
          
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
             <a href="../index.php">Home</a>
            <a href="applications.php">Applications</a>
            
            <a href="../jobs.php">View Jobs</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p class="welcome">Welcome back, Administrator</p>

        <div class="menu-grid">

            <div class="menu-card">
                <div class="icon">📝</div>
                <h3>Create New Job</h3>
                <a href="create_job.php">Post a New Vacancy →</a>
            </div>

            <div class="menu-card">
                <div class="icon">📋</div>
                <h3>View Applications</h3>
                <a href="applications.php">Manage All Applications →</a>
            </div>

            <div class="menu-card">
                <div class="icon">🔍</div>
                <h3>Browse Jobs</h3>
                <a href="../jobs.php">View All Posted Jobs →</a>
            </div>

            <div class="menu-card">
                <div class="icon">⚙️</div>
                <h3>Manage Jobs</h3>
                <a href="admin_jobs.php">Edit/Delete Jobs →</a>
            </div>

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