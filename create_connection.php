<?php
  $server = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'Main_Database';
  
  // Step 1: Connect to MySQL server (without specifying database)
  $con = mysqli_connect($server, $username, $password);
  
  if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
  }
  
  // Step 2: Create database if not exists
  $sql = "CREATE DATABASE IF NOT EXISTS $database";
  if (mysqli_query($con, $sql)) {
     // echo "Database '$database' created or already exists.<br>";
  } else {
      die("Error creating database: " . mysqli_error($con));
  }
  
  // Step 3: Select the created database
  mysqli_select_db($con, $database);
  
  // Final confirmation
 // echo "Database connection successfully done.";
?>
