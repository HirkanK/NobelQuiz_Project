<?php
session_start();
if (!isset($_SESSION['student_id']) || !isset($_SESSION['student_name'])) {
    header("Location: Student_Login_Register.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quiz Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .quiz-container {
      width: 90%;
      max-width: 600px;
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .question-box { display: none; }
    .question-box.active { display: block; }
    .options label {
      display: block;
      background: #f5f7fa;
      border: 1px solid #ddd;
      padding: 0.8rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .options label:hover { background: #e8efff; }
    .options input { margin-right: 0.8rem; }
    .quiz-controls {
      text-align: center;
      margin-top: 2rem;
    }
    .quiz-controls button {
      background-color: #ff6a00;
      color: white;
      border: none;
      padding: 0.8rem 1.5rem;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      margin: 0 0.5rem;
      transition: background 0.3s;
    }
    .quiz-controls button:hover { background-color: #ee0979; }
  </style>
</head>
<body>
  <div class="quiz-container">
    <header class="text-center mb-4">
      <h1>Twistters!</h1>
      <p>Answer the questions below</p>
    </header>

    <!-- Single form wrapping all questions -->
    <form id="quizForm">
      <!-- Questions -->
      <div class="question-box active">
        <h2></h2>
        <div class="options">
          <label><input type="radio" name="q1" value="A"> A) </label>
          <label><input type="radio" name="q1" value="B"> B) </label>
          <label><input type="radio" name="q1" value="C"> C) </label>
          <label><input type="radio" name="q1" value="D"> D) </label>
        </div>
      </div>

      <!-- Nav buttons -->
      <div class="quiz-controls">
        <button type="button" id="prevBtn">Previous</button>
        <button type="button" id="nextBtn">Next</button>
        <button type="submit" id="submitBtn" style="display:none;">Submit</button>
      </div>
    </form>
  </div>

  <script>
    // 🔐 Violation tracking logic
    let violations = 0;
    const studentId = <?php echo json_encode($_SESSION['student_id']); ?>;
  
    window.onblur = () => {
      violations++;
  
      fetch('PHP_pages/record_violation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ student_id: studentId })
      });
  
      if (violations >= 3) {
        fetch('PHP_pages/force_submit.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ student_id: studentId })
        }).then(() => {
          alert('You are blocked. Admin will review.');
          window.location.href = 'Blocked.html';
        });
      }
    };
  
    // ✅ Quiz navigation + scoring logic
    const correctAnswers = { };
    const boxes = document.querySelectorAll('.question-box');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let idx = 0;
  
    // Save quiz start time
    if (!localStorage.getItem("quizStartTime")) {
      localStorage.setItem("quizStartTime", new Date().toISOString());
    }
  
    function render() {
      boxes.forEach((box, i) => box.classList.toggle('active', i === idx));
      prevBtn.style.display = idx === 0 ? 'none' : 'inline-block';
      nextBtn.style.display = idx === boxes.length - 1 ? 'none' : 'inline-block';
      submitBtn.style.display = idx === boxes.length - 1 ? 'inline-block' : 'none';
    }
  
    prevBtn.onclick = () => { if (idx > 0) idx--; render(); };
    nextBtn.onclick = () => { if (idx < boxes.length - 1) idx++; render(); };
  
    document.getElementById("quizForm").onsubmit = function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      let score = 0;
      let total = Object.keys(correctAnswers).length;
      for (let key in correctAnswers) {
        if (formData.get(key) === correctAnswers[key]) score++;
      }
      window.location.href = `result.html?score=${score}&total=${total}`;
    };
  
    render();
  </script>
  

</body>
</html>
