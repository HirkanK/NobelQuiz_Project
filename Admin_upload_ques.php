<?php
session_start();
include('create_connection.php');

if ($_SERVER['REQUEST_METHOD']==='POST' && is_uploaded_file($_FILES['csv']['tmp_name'])) {
  $file = fopen($_FILES['csv']['tmp_name'], 'r');
  while (($row = fgetcsv($file)) !== FALSE) {
    $stmt = $con->prepare("INSERT INTO questions (subject, question, opt1,opt2,opt3,opt4,answer) VALUES (?, ?, ?,?,?,?,?)");
    $stmt->bind_param("sssssss", ...$row);
    $stmt->execute();
  }
  header("Location: AdminDashboard.php");
exit();
}
?>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="csv" accept=".csv">
  <button type="submit">Upload Questions</button>
</form>
