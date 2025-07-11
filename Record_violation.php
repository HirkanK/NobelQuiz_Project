<?php
include('create_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];

    // Insert or update violations
    $check = $con->prepare("SELECT count FROM violations WHERE student_id=?");
    $check->bind_param("i", $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $con->query("UPDATE violations SET count = count + 1 WHERE student_id = $student_id");
    } else {
        $con->query("INSERT INTO violations (student_id, count) VALUES ($student_id, 1)");
    }
}
?>
