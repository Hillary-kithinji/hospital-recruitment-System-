<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

$message = "";

/* ====================== HANDLE EDIT ====================== */
if (isset($_POST['update'])) {
    $id = intval($_POST['job_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    $deadline = $_POST['deadline'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE jobs SET 
            title = '$title',
            department = '$department',
            description = '$description',
            requirements = '$requirements',
            deadline = '$deadline',
            status = '$status'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Job Updated Successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

/* ====================== HANDLE DELETE ====================== */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
    header("Location: admin_jobs.php");
    exit();
}

/* ====================== FETCH JOB FOR EDIT ====================== */
$edit_job = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $edit_id");
    $edit_job = mysqli_fetch_assoc($result);
}

$all_jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - Admin</title>
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

        /* Container */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 { text-align: center; color: #0056b3; margin-bottom: 30px; }

        .message {
            padding: 12px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }
        .success { background: #d4edda; color: #155724; }
        .error   { background: #f8d7da; color: #721c24; }

        /* Edit Form */
        .edit-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }

        /* Job Cards */
        .job-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .open   { background: #d4edda; color: #155724; }
        .closed { background: #f8d7da; color: #721c24; }

        .btn {
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin: 5px;
            display: inline-block;
            font-size: 0.95rem;
        }
        .btn-edit   { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; }

        label { display: block; margin: 15px 0 8px; font-weight: 600; }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea { min-height: 100px; resize: vertical; }

        button {
            background: #0056b3;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover { background: #003d80; }
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
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <h1>Manage Jobs</h1>

        <?php if (!empty($message)): ?>
            <p class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message; ?>
            </p>
        <?php endif; ?>

        <!-- ====================== EDIT FORM ====================== -->
        <?php if ($edit_job): ?>
            <div class="edit-form">
                <h2>Edit Job: <?= htmlspecialchars($edit_job['title']); ?></h2>
                <form method="POST">
                    <input type="hidden" name="job_id" value="<?= $edit_job['id']; ?>">

                    <label>Job Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($edit_job['title']); ?>" required>

                    <label>Department</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($edit_job['department']); ?>" required>

                    <label>Description</label>
                    <textarea name="description" required><?= htmlspecialchars($edit_job['description']); ?></textarea>

                    <label>Requirements</label>
                    <textarea name="requirements" required><?= htmlspecialchars($edit_job['requirements']); ?></textarea>

                    <label>Deadline</label>
                    <input type="date" name="deadline" value="<?= $edit_job['deadline']; ?>" required>

                    <label>Status</label>
                    <select name="status">
                        <option value="Open"   <?= $edit_job['status']=='Open'   ? 'selected' : '' ?>>Open</option>
                        <option value="Closed" <?= $edit_job['status']=='Closed' ? 'selected' : '' ?>>Closed</option>
                    </select>

                    <br><br>
                    <button type="submit" name="update">Save Changes</button>
                    <a href="admin_jobs.php" class="btn" style="background:#6c757d; color:white;">Cancel</a>
                </form>
            </div>
        <?php endif; ?>

        <!-- ====================== JOBS LIST ====================== -->
        <h2>All Jobs</h2>

        <?php if (mysqli_num_rows($all_jobs) == 0): ?>
            <p style="text-align:center; padding:50px;">No jobs found.</p>
        <?php else: ?>
            <?php while($job = mysqli_fetch_assoc($all_jobs)): ?>
            <div class="job-card">
                <div class="job-header">
                    <h3><?= htmlspecialchars($job['title']); ?></h3>
                    <span class="status <?= $job['status']=='Open' ? 'open' : 'closed' ?>">
                        <?= htmlspecialchars($job['status']); ?>
                    </span>
                </div>
                <p><strong>Department:</strong> <?= htmlspecialchars($job['department']); ?></p>
                <p><strong>Deadline:</strong> <?= htmlspecialchars($job['deadline']); ?></p>

                <div style="margin-top: 20px;">
                    <a class="btn btn-edit" href="?edit=<?= $job['id']; ?>">✏️ Edit</a>
                    <a class="btn btn-delete"
                       href="?delete=<?= $job['id']; ?>"
                       onclick="return confirm('Delete this job permanently?')">
                        🗑️ Delete
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

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