<?php
include('create_connection.php');
// File: Detail_Enter.php — for registration
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'main_database';

$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get and sanitize form data
$name = trim($_POST['name']);
$rollno = trim($_POST['rollno']);
$email = strtolower(trim($_POST['email']));
$mobile_number = trim($_POST['mobile_number']);
$password = trim($_POST['password']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ✅ Prepare SQL query with 5 fields
$sql = "INSERT INTO student_details (name, rollno, email, mobile_number, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);

// ✅ Bind 5 values, use 'sssss'
$stmt->bind_param("sssss", $name, $rollno, $email, $mobile_number, $hashed_password);

// ✅ Execute and check result
if ($stmt->execute()) {
    echo "<script>
    alert('Registration successful!');
    document.querySelector('.container').classList.remove('right-panel-active');
</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = '../Student_Login_Register.html';</script>";
}

$stmt->close();
$con->close();
?>
