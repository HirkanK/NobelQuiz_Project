<?php
session_start();
include('create_connection.php');

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $duration = intval($_POST['duration']);

    // Reset all to inactive
    $con->query("UPDATE quiz_status SET is_active = 0");

    // Insert or update the selected subject to active with duration
    $stmt = $con->prepare("INSERT INTO quiz_status (subject, is_active, duration_minutes) 
                           VALUES (?, 1, ?)
                           ON DUPLICATE KEY UPDATE is_active = 1, duration_minutes = ?");
    $stmt->bind_param("sii", $subject, $duration, $duration);
    $stmt->execute();

    echo "<script>alert('Quiz for \"$subject\" is now live with a time limit of $duration minutes!');
    window.location.href='AdminDashboard.php';</script>";
}
?>
