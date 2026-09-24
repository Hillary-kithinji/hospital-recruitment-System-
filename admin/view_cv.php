<?php
// view_cv.php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    die("No file specified.");
}

$filename = basename(urldecode($_GET['file']));
$filepath = __DIR__ . "/../uploads/cvs/" . $filename;

if (file_exists($filepath)) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // === STRONG INLINE HEADERS ===
    if ($ext === 'pdf') {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
    } else {
        // For doc/docx - force inline as much as possible
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: inline; filename="' . $filename . '"');
    }

    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Pragma: public');
    header('Expires: 0');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: ALLOWALL');
    header('X-Robots-Tag: noindex');

    // Read file in chunks (helps with large files and some download managers)
    $handle = fopen($filepath, 'rb');
    while (!feof($handle)) {
        echo fread($handle, 8192);
        ob_flush();
        flush();
    }
    fclose($handle);
    exit();
} else {
    echo "<h2 style='color:red; text-align:center; padding:50px;'>File not found on server</h2>";
}
?>