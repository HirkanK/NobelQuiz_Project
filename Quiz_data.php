/* Saving score*/
CREATE TABLE quiz_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    correct_answers INT NOT NULL,
    incorrect_answers INT NOT NULL,
    quiz_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

/* insert the performance data into the database*/
<?php
include('session_top.php');
include('create_connection.php');


$drop_sql = "DROP TABLE IF EXISTS Student_Scores";
if (mysqli_query($con, $drop_sql)) {
    echo "Old table dropped successfully.<br>";
} else {
    echo "Error dropping table: " . mysqli_error($con) . "<br>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    $quiz_id = $_POST['quiz_id'] ?? null;
    $score = $_POST['score'] ?? 0;
    $total = $_POST['total_questions'] ?? 0;
    $correct = $_POST['correct'] ?? 0;
    $incorrect = $_POST['incorrect'] ?? 0;

    if ($student_id && $quiz_id) {
        $sql = "INSERT INTO quiz_scores (student_id, quiz_id, score, total_questions, correct_answers, incorrect_answers) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiiiii", $student_id, $quiz_id, $score, $total, $correct, $incorrect);

        if ($stmt->execute()) {
            echo "Score saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing student or quiz ID.";
    }

    $con->close();
} else {
    echo "Invalid request.";
}
?>


function saveScoreToDatabase(studentId, quizId, score, total, correct, incorrect) {
  fetch('save_score.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      student_id: studentId,
      quiz_id: quizId,
      score: score,
      total_questions: total,
      correct: correct,
      incorrect: incorrect
    })
  })
  .then(response => response.text())
  .then(result => {
    console.log("Server response:", result);
  })
  .catch(error => {
    console.error("Error submitting score:", error);
  });
}



<?php
include('create_connection.php');
$student_id = 1;

$sql = "SELECT * FROM quiz_scores WHERE student_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "Quiz ID: {$row['quiz_id']} | Score: {$row['score']}/{$row['total_questions']} | Correct: {$row['correct_answers']} | Date: {$row['quiz_date']}<br>";
}

$stmt->close();
$con->close();
?>
