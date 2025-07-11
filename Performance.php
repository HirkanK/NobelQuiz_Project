<?php

include('create_connection.php'); // This should define $conn
include('session_top.php');
header('Content-Type: application/json');


// Fallback in case the included file doesn't define $conn
if (!isset($con)) {
    $host = 'localhost';
    $db = 'Quiz_Database';
    $user = 'root';
    $pass = '';

    $con = new mysqli($host, $user, $pass, $db);
    if ($con->connect_error) {
        die(json_encode(["error" => "Database connection failed."]));
    }
}

$student_id = $_GET['student_id'] ?? '';
if (!$student_id || !is_numeric($student_id)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT score, total_questions, correct AS correct_answers, time_taken, taken_at AS quiz_date
        FROM scores
        WHERE student_id = ?
        ORDER BY taken_at ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
