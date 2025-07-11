<?php
// File: Student_login.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('create_connection.php');
include('session_top.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    $query = "SELECT id, name, rollno, password FROM student_details WHERE email = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['rollno'];
            $_SESSION['student_name'] = $user['name'];
            $_SESSION['roll_no'] = $user['rollno'];
            $_SESSION['name'] = $user['name'];

            header("Location: Student_Dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href = '../Student_Login_Register.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.'); window.location.href = '../Student_Login_Register.html';</script>";
    }
	$stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '../Student_Login_Register.html';</script>";
}
$con->close();
?>
