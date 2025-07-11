<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name']) || !isset($_SESSION['admin_email'])) {
    header("Location: http://localhost/Nobel_Quiz/Project_website/HTML_pages/Admin_login_register.html");
    exit();
}

// Now you can use:
$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_email = $_SESSION['admin_email'];
?>
