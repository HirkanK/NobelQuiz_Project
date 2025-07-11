<?php
session_start();
require 'create_connection.php';

$subject = 'trade';
$roll = $_SESSION['roll_no'] ?? 'ROLL001';
$name = $_SESSION['name'] ?? 'Student';

// Check if blocked
$check = $con->prepare("SELECT * FROM blocked_students WHERE roll_no = ?");
$check->bind_param("s", $roll);
$check->execute();
$block_result = $check->get_result();

if ($block_result->num_rows > 0) {
    echo "<h3 style='text-align:center; color:red;'>You are blocked from taking this quiz. Contact Admin.</h3>";
    exit();
}

// Fetch questions
$stmt = $con->prepare("SELECT DISTINCT question, opt1, opt2, opt3, opt4, answer FROM questions WHERE LOWER(subject) = LOWER(?)");
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$total = count($questions);
if ($total == 0) {
    echo "<h3 style='text-align:center;'>❌ No questions found for subject '$subject'</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?= strtoupper($subject) ?> Quiz</title>
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 0;
    }
    .quiz-container {
      background: #ffffff;
      padding: 30px 40px;
      width: 100%;
      max-width: 650px;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      font-size: 32px;
      font-weight: bold;
    }
    h3 {
      text-align: center;
      margin-bottom: 30px;
      color: #555;
      font-weight: 500;
    }
    .question-box {
      display: none;
    }
    .question-box.active {
      display: block;
    }
    .option-block {
      display: block;
      background-color: #f1f5f9;
      margin: 10px 0;
      padding: 12px 18px;
      border-radius: 10px;
      border: 2px solid transparent;
      transition: 0.2s;
      cursor: pointer;
    }

    .option-block input[type="radio"]:checked + span {
    background-color: #dbeafe;
    border: 2px solid #2563eb;
    padding: 10px;
    border-radius: 10px;
    display: block;
    }
    .option-block:hover {
      border-color: #3b82f6;
      background-color: #e0ecff;
    }
    input[type="radio"] {
      display: none;
    }
    .btn {
      background-color: #f97316;
      color: white;
      padding: 10px 25px;
      margin: 10px 5px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }
    .btn:hover {
      background-color: #ea580c;
    }
    .button-group {
      text-align: center;
    }
  </style>
</head>
<body>

<div class="quiz-container">
  <h1>Twistters!</h1>
  <h3>Answer the questions below</h3>

  <form id="quizForm" method="POST" action="submit_quiz.php">
    <input type="hidden" name="subject" value="<?= $subject ?>">
    <input type="hidden" name="total" value="<?= $total ?>">

    <?php foreach ($questions as $i => $q): ?>
      <div class="question-box <?= $i === 0 ? 'active' : '' ?>" id="q<?= $i ?>">
        <p><strong><?= $i + 1 ?>. <?= htmlspecialchars($q['question']) ?></strong></p>

        <?php foreach (['opt1', 'opt2', 'opt3', 'opt4'] as $opt): ?>
            <label class="option-block">
            <input type="radio" name="ans<?= $i ?>" value="<?= $q[$opt] ?>" required>
            <span><?= $q[$opt] ?></span>
        </label>
        <?php endforeach; ?>

        <div class="button-group">
          <?php if ($i > 0): ?>
            <button type="button" class="btn" onclick="prevQuestion(<?= $i ?>)">Previous</button>
          <?php endif; ?>

          <?php if ($i < $total - 1): ?>
            <button type="button" class="btn" onclick="nextQuestion(<?= $i ?>)">Next</button>
          <?php else: ?>
            <button type="submit" class="btn">Submit</button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </form>
</div>

<script>
function nextQuestion(i) {
  document.getElementById('q' + i).classList.remove('active');
  document.getElementById('q' + (i + 1)).classList.add('active');
}
function prevQuestion(i) {
  document.getElementById('q' + i).classList.remove('active');
  document.getElementById('q' + (i - 1)).classList.add('active');
}

// Tab switch monitor
window.addEventListener("blur", () => {
  fetch("Record_violation.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `roll_no=<?= $roll ?>&name=<?= $name ?>&reason=Tab switch`
  })
  .then(res => res.text())
  .then(response => {
    if (response === 'blocked') {
      alert("⚠ You switched tabs 3 times. Quiz will now be submitted.");
      document.getElementById('quizForm').submit();
    }
  });
});
</script>

</body>
</html>
