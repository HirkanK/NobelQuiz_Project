<?php
session_start();
require 'create_connection.php';

$roll = $_SESSION['roll_no'];
$name = $_SESSION['name'];
$subject = $_POST['subject'];
$total = $_POST['total'];

$score = 0;

for ($i = 0; $i < $total; $i++) {
    $ans = $_POST["ans$i"] ?? '';
    $stmt = $con->prepare("SELECT answer FROM questions WHERE subject = ? LIMIT ?, 1");
    $stmt->bind_param("si", $subject, $i);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res && $ans === $res['answer']) {
        $score++;
    }
}

$stmt = $con->prepare("INSERT INTO results (roll_no, name, subject, score, total) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssii", $roll, $name, $subject, $score, $total);
$stmt->execute();

// Redirect to a “Thank You” or waiting page
header("Location: thank_you.php");
exit();
?>
