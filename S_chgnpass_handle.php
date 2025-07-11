<?php
// DB connection
$con = new mysqli("localhost", "root", "", "Student_Database");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch input
$mobile = $_POST['mobile'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit;
}

// Check if student with this mobile exists
$stmt = $con->prepare("SELECT * FROM students WHERE mobile = ?");
$stmt->bind_param("s", $mobile);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('No student found with this mobile number!'); window.history.back();</script>";
    exit;
}

// Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password
$update = $con->prepare("UPDATE students SET password = ? WHERE mobile = ?");
$update->bind_param("ss", $hashed_password, $mobile);
if ($update->execute()) {
    echo "<script>alert('Password updated successfully!'); window.location='Student_Login_Register.html';</script>";
} else {
    echo "<script>alert('Error updating password.'); window.history.back();</script>";
}
?>
