<?php
include('create_connection.php');
session_start();
include('create_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL to fetch hashed password and ID for given email
    $sql = "SELECT password FROM student_details WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($sno, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['student_id'] = $sno;

            // Redirect to dashboard page
            header("Location: Performance.html");  // Change to your actual dashboard path
            exit;
        } else {
            echo "<script>alert('Invalid Password'); window.location.href='Student_Login_Registration.html';</script>";
        }
    } else {
        echo "<script>alert('Email not registered'); window.location.href='Student_Login_Registration.html';</script>";
    }

    $stmt->close();
    $con->close();
} else {
    echo "Please submit the form.";
}
?>
