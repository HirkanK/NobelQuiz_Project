<?php
include('create_connection.php'); // This should define $conn
include('session_top.php');
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: Student_Login_Register.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quiz Result | NobelQuiz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      padding: 2rem;
    }
    .result-container {
      max-width: 600px;
      margin: auto;
      background-color: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    h1 {
      color: #ee0979;
      margin-bottom: 1rem;
    }
    .stat {
      font-size: 1.2rem;
      margin: 1rem 0;
    }
    .feedback {
      font-size: 1.3rem;
      font-weight: bold;
      color: #ff6a00;
      margin-top: 1.5rem;
    }
  </style>
</head>
<body>

<div class="result-container">
  <h1>Your Quiz Results</h1>
  <div class="stat">Score: <span id="scoreText">--</span></div>
  <div class="stat">Correct Answers: <span id="correctCount">--</span></div>
  <div class="stat">Wrong Answers: <span id="wrongCount">--</span></div>
  <div class="stat">Time Taken: <span id="timeTaken">--</span></div>
  <div class="feedback" id="feedbackText"></div>
</div>

<script>
  const params = new URLSearchParams(window.location.search);
  const score = parseInt(params.get("score"));
  const total = parseInt(params.get("total"));
  const quizId = params.get("quiz_id") ?? 1;

  const startTime = new Date(localStorage.getItem("quizStartTime"));
  const endTime = new Date();
  const timeTakenSec = Math.round((endTime - startTime) / 1000);
  const timeDisplay = `${Math.floor(timeTakenSec / 60)}:${String(timeTakenSec % 60).padStart(2, '0')} mins`;

  const wrong = total - score;
  const percent = (score / total) * 100;

  document.getElementById("scoreText").textContent = `${score} / ${total}`;
  document.getElementById("correctCount").textContent = score;
  document.getElementById("wrongCount").textContent = wrong;
  document.getElementById("timeTaken").textContent = timeDisplay;

  let feedback = "";
  if (percent === 100) feedback = "Perfect score! 🎯";
  else if (percent >= 80) feedback = "Excellent work! 🏆";
  else if (percent >= 50) feedback = "Good effort! Keep practicing!";
  else feedback = "Don't worry, try again! 💪";

  document.getElementById("feedbackText").textContent = feedback;

  // ✅ Get student ID from PHP session
  const studentId = <?php echo json_encode($_SESSION['student_id']); ?>;

  // ✅ Save to backend
  fetch("../PHP_pages/Result.php", {
    method: "POST",
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      student_id: studentId,
      quiz_id: quizId,
      score: score,
      total_questions: total,
      correct: score,
      incorrect: wrong,
      time_taken: timeDisplay
    })
  })
  .then(response => response.text())
  .then(result => {
    console.log("Server Response:", result);
  })
  .catch(error => {
    console.error("Error submitting score:", error);
  });

  localStorage.removeItem("quizStartTime");
</script>

</body>
</html>
