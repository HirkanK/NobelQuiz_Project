<?php
require 'create_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $question = $_POST['question'];
    $opt1 = $_POST['opt1'];
    $opt2 = $_POST['opt2'];
    $opt3 = $_POST['opt3'];
    $opt4 = $_POST['opt4'];
    $answer = $_POST['answer'];

    $stmt = $con->prepare("INSERT INTO practice_questions (subject, question, opt1, opt2, opt3, opt4, answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $subject, $question, $opt1, $opt2, $opt3, $opt4, $answer);

    if ($stmt->execute()) {
        echo "✅ Question uploaded successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
