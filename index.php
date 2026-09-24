<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Recruitment System</title>
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
            overflow-x: hidden;
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

        /* Mobile Menu - inside navbar, wraps below */
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

        /* Hero */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(138, 154, 170, 0.75), rgba(0, 86, 179, 0.75)),
                        url('https://images.unsplash.com/photo-1551076805-e1869033e561?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #0fb317;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            margin: 8px;
            transition: all 0.4s;
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.05);
            background: #00b248;
        }

        .section {
            padding: 60px 20px;
        }

        .partners {
            background: white;
        }

        .partner-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
        }

        .partner-grid img {
            width: 140px;
            height: 100px;
            object-fit: contain;
            padding: 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        footer {
            text-align: center;
            padding: 25px;
            background: #222;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .hamburger {
                display: flex;
            }
            .hero h1 {
                font-size: 2.3rem;
            }
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
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">🏥HOSPITAL</div>
        <icon></icon>

        <!-- Desktop Links -->
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="jobs.php">Jobs</a>
            <a href="#contact">Contact Us</a>

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

        <!-- Mobile Menu (inside navbar so it drops below correctly) -->
        <div class="mobile-menu" id="mobile-menu">
            <a href="index.php">Home</a>
            <a href="jobs.php">Jobs</a>
            <a href="#contact">Contact Us</a>

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

    <!-- HERO -->
    <div class="hero">
        <div class="hero-content">
            <h1>Caring for the Future</h1>
            <p>Discover meaningful healthcare careers and join our mission to save lives</p>
            <a class="btn" href="jobs.php">Browse Open Positions</a>
            <a class="btn" href="register.php">Join Our Team</a>
        </div>
    </div>

    <!-- PARTNERS -->
    <div class="section partners" id="partners">
        <h2 style="text-align:center; margin-bottom:20px; color:#0056b3;">Our Trusted Partners</h2>
        <div class="partner-grid">
            <img src="uploads/partners/couty.jpg" alt="Partner 1">
            <img src="uploads/partners/download.png" alt="Partner 2">
            <img src="uploads/partners/SHA.JPG" alt="Partner 3">
            <img src="uploads/partners/un.png" alt="Partner 4">
        </div>
    </div>

    <!-- CONTACT -->
    <div class="section" id="contact" style="background: #085fb6; color: white; text-align:center;">
        <h2>Contact Us</h2>
        <p style="font-size:1.2rem; margin:15px 0;">
        &#9993; Email:
         <a href="mailto:info@hospitaljobs.com">info@hospitaljobs.com</a>
</p>

        <p style="font-size:1.2rem;">
        &#9742; Phone:
         <a href="tel:+254700000000">+254 700 000 000</a>
</p>
    </div>
    

    <footer>
        © <?= date("Y"); ?> Hospital Recruitment System • All Rights Reserved
    </footer>

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

        // Scroll animations
        function animateOnScroll() {
            document.querySelectorAll('.section').forEach(section => {
                if (section.getBoundingClientRect().top < window.innerHeight * 0.85) {
                    section.classList.add('visible');
                }
            });
        }

        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);
    </script>
</body>
</html>