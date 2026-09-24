<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

$message = "";

/* DELETE APPLICATION */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $fileQuery = mysqli_query($conn, "SELECT cv_file FROM applications WHERE id = $id");
    $fileData = mysqli_fetch_assoc($fileQuery);

    if ($fileData && !empty($fileData['cv_file'])) {
        $filePath = __DIR__ . "/../uploads/cvs/" . $fileData['cv_file'];
        if (file_exists($filePath)) unlink($filePath);
    }

    mysqli_query($conn, "DELETE FROM applications WHERE id = $id");
    header("Location: applications.php");
    exit();
}

/* UPDATE STATUS */
if (isset($_POST['update_status'])) {
    $app_id = intval($_POST['app_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE applications SET status = '$status' WHERE id = $app_id");
    $message = "Status updated successfully!";
}

$result = mysqli_query($conn, "
    SELECT applications.*, jobs.title, users.fullname 
    FROM applications 
    JOIN jobs ON applications.job_id = jobs.id 
    JOIN users ON applications.user_id = users.id 
    ORDER BY applications.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Applications</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
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

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .row { margin: 12px 0; line-height: 1.6; }

        .file-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            word-break: break-all;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            margin: 5px 5px 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .btn:hover { background: #0056b3; }
        .btn.danger { background: #dc3545; }
        .btn.danger:hover { background: #c82333; }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 1000px;
            height: 90vh;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }
        .modal-header {
            padding: 15px 20px;
            background: #0056b3;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close {
            font-size: 28px;
            cursor: pointer;
        }
        iframe {
            width: 100%;
            height: calc(100% - 60px);
            border: none;
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
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <h1>All Job Applications</h1>

        <?php if ($message): ?>
            <p style="text-align:center; color:green; font-weight:bold;"><?= $message; ?></p>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <div class="row"><strong>Name:</strong> <?= htmlspecialchars($row['fullname']); ?></div>
            <div class="row"><strong>Email:</strong> <?= htmlspecialchars($row['email']); ?></div>
            <div class="row"><strong>Job:</strong> <?= htmlspecialchars($row['title']); ?></div>
            <div class="row"><strong>Phone:</strong> <?= htmlspecialchars($row['phone']); ?></div>
            <div class="row"><strong>Qualification:</strong> <?= htmlspecialchars($row['qualification']); ?></div>

            <div class="row">
                <strong>Status:</strong>
                <span style="color:green; font-weight:bold;"><?= htmlspecialchars($row['status'] ?: 'Pending'); ?></span>
            </div>

            <div class="row">
                <strong>Document:</strong><br><br>
                <div class="file-box"><?= htmlspecialchars($row['cv_file']); ?></div><br><br>

                <button class="btn" onclick="viewDocument('<?= urlencode($row['cv_file']); ?>', '<?= htmlspecialchars($row['fullname']); ?>')">
                    👁️ View Online
                </button>

                <a class="btn" href="../uploads/cvs/<?= urlencode($row['cv_file']); ?>?download=1" download>
                    ⬇️ Download File
                </a>
            </div>

            <form method="POST" style="margin-top: 20px;">
                <input type="hidden" name="app_id" value="<?= $row['id']; ?>">
                <select name="status">
                    <option value="Pending"     <?= $row['status']=='Pending'     ? 'selected' : '' ?>>Pending</option>
                    <option value="Shortlisted" <?= $row['status']=='Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                    <option value="Interview"   <?= $row['status']=='Interview'   ? 'selected' : '' ?>>Interview</option>
                    <option value="Rejected"    <?= $row['status']=='Rejected'    ? 'selected' : '' ?>>Rejected</option>
                    <option value="Hired"       <?= $row['status']=='Hired'       ? 'selected' : '' ?>>Hired</option>
                </select>
                <button type="submit" name="update_status" class="btn">Update Status</button>

                <a class="btn danger" href="?delete=<?= $row['id']; ?>"
                   onclick="return confirm('Delete this application?')">Delete</a>
            </form>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Document Popup Modal -->
    <div id="docModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <strong id="modalTitle">Viewing Document</strong>
                <span class="close" onclick="closeModal()">×</span>
            </div>
            <iframe id="docFrame" src=""></iframe>
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

        function viewDocument(filename, applicantName) {
            document.getElementById('modalTitle').textContent = "Document - " + applicantName;
            document.getElementById('docFrame').src = "view_cv.php?file=" + filename;
            document.getElementById('docModal').style.display = "flex";
        }

        function closeModal() {
            document.getElementById('docModal').style.display = "none";
            document.getElementById('docFrame').src = "";
        }

        window.onclick = function(event) {
            const modal = document.getElementById('docModal');
            if (event.target === modal) closeModal();
        }
    </script>
</body>
</html>