<?php
session_start();
include('create_connection.php');
if (!isset($_SESSION['admin_id'])) header("Location: Admin.html");

$res = $con->query("SELECT * FROM violations WHERE resolved=0");
echo "<h1>Live Violations</h1>";
while($v=$res->fetch_assoc()) {
  echo "Roll {$v['student_id']} has {$v['count']} violations &nbsp;";
  if($v['count']>=3) {
    echo "<form method='post'><button name='unblock' value='{$v['student_id']}'>Unblock</button></form>";
  }
}
if(isset($_POST['unblock'])) {
  $rid = $_POST['unblock'];
  $con->query("DELETE FROM violations WHERE student_id='$rid'");
  $con->query("UPDATE student_sessions SET blocked=0 WHERE student_id='$rid'");
  header("Refresh:0");
}
