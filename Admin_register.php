<?php
include('create_connection.php');
session_start();

$serverA = 'localhost';
$usernameA = 'root';
$passwordA = '';
$databaseA = 'Main_Database';

$con = mysqli_connect($serverA, $usernameA, $passwordA);
if (!$con) die("Connection failed: " . mysqli_connect_error());

$sqlA = "CREATE DATABASE IF NOT EXISTS $databaseA";
if (!mysqli_query($con, $sqlA)) die("Error creating database: " . mysqli_error($con));

mysqli_select_db($con, $databaseA);

// $createTableSQL = "
// CREATE TABLE IF NOT EXISTS admin_details (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // A_name VARCHAR(100) NOT NULL,
    // A_email VARCHAR(100) NOT NULL UNIQUE,
    // A_mobile_number VARCHAR(15) NOT NULL,
    // A_password VARCHAR(255) NOT NULL,
    // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// )";
// if (!mysqli_query($con, $createTableSQL)) die("Error creating table: " . mysqli_error($con));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile_number = trim($_POST['mobile_number'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($mobile_number) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href = '../Admin_login.html';</script>";
        exit;
    }

    // ✅ Check if email already exists
    $checkEmail = $con->prepare("SELECT id FROM admin_details WHERE A_email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already registered. Please login.'); window.location.href = '../Admin_login_register.html?flip=true';</script>";
        exit;
    }

    $checkEmail->close();

    // ✅ Proceed with registration
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $con->prepare("INSERT INTO admin_details (A_name, A_email, A_mobile_number, A_password) VALUES (?, ?, ?, ?)");
    if (!$stmt) die("Prepare failed: " . mysqli_error($con));

    $stmt->bind_param("ssss", $name, $email, $mobile_number, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful! Please login.');
            window.location.href = '../Admin_login.html?flip=true';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed. Try again.');
            window.location.href = '../Admin_login.html';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Invalid request method.');
        window.location.href = '../Admin_login.html';
    </script>";
}

mysqli_close($con);
?>
