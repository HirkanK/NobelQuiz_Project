<?php
include('create_connection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? 0;
    $quiz_id = $_POST['quiz_id'] ?? 0;
    $score = $_POST['score'] ?? 0;
    $total = $_POST['total_questions'] ?? 0;
    $correct = $_POST['correct'] ?? 0;
    $incorrect = $_POST['incorrect'] ?? 0;

    $sql = "INSERT INTO quiz_scores (student_id, quiz_id, score, total_questions, correct_answers, incorrect_answers) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiiiii", $student_id, $quiz_id, $score, $total, $correct, $incorrect);

    if ($stmt->execute()) {
        echo "Score saved!";
    } else {
        echo "Failed: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
