<?php
// File: PHP_pages/logout.php

session_start();         // Start the session if it’s active
session_unset();         // Clear all session variables
session_destroy();       // Destroy the session completely

// ✅ Redirect to home page instead of login
header("Location: ../HTML_pages/Home_page.html");
exit();
?>
