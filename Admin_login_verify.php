<?php
include('create_connection.php');
session_start();

// Connect to Student_Database
$con = new mysqli("localhost", "root", "", "Main_Database");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href = '../Admin_login.html';</script>";
        exit;
    }

    $stmt = $con->prepare("SELECT id, A_name, A_password FROM Admin_details WHERE A_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['A_password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = $admin['A_name'];

            echo "<script>
                alert('Login successful!');
                window.location.href = 'AdminDashboard.php';
            </script>";
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href = '../Admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('Admin not found.'); window.location.href = '../Admin_login.html';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href = '../Admin_login.html';</script>";
}

$con->close();
?>
