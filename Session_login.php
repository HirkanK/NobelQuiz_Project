<?php
// File: session_top.php
session_start();

// Redirect to login if session not set
if (!isset($_SESSION['student_id']) || !isset($_SESSION['student_name'])) {
    header("Location: Student_Login_Register.html");
    exit();
}
?>

<!-- Session Info Header -->
<div style="background-color: #f5f5f5; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; font-family: 'Segoe UI'; font-size: 16px;">
  <div>
    👤 <strong><?php echo $_SESSION['student_name']; ?></strong> (Roll No: <?php echo $_SESSION['student_id']; ?>)
  </div>
  <div>
    <form method="POST" action="logout.php" style="margin: 0;">
      <button type="submit" style="background-color: #ff6a00; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer;">Logout</button>
    </form>
  </div>
</div>
