<?php
session_start();
require 'create_connection.php';

$roll = $_SESSION['roll_no'];

$stmt = $con->prepare("SELECT * FROM results WHERE roll_no = ? AND is_published = 1");
$stmt->bind_param("s", $roll);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    echo "<h3>Subject: {$row['subject']}</h3>";
    echo "<p>Score: {$row['score']} / {$row['total']}</p>";
  }
} else {
  echo "<h3>Result not published yet. Please check later.</h3>";
}
?>
