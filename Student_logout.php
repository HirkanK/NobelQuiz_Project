<?php
// File: logout.php

session_start();         // Start session to access session variables
session_unset();         // Clear all session data
session_destroy();       // Destroy the session completely

// Redirect to login/register page
header("Location: Home_page.html");
exit();
?>
