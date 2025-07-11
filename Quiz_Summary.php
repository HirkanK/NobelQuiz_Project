<?php
// File: Quiz_Summary.php
session_start();
include('create_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../HTML_pages/Admin.html");
    exit();
}

// ✅ Create student_login table first
$createLoginTable = "
CREATE TABLE IF NOT EXISTS student_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    roll_no VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255)
)";
if (!mysqli_query($con, $createLoginTable)) {
    die("Error creating student_login table: " . mysqli_error($con));
}

// ✅ Now create student_result with valid foreign key
$createResultTable = "
CREATE TABLE IF NOT EXISTS student_result (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    correct_answers INT NOT NULL,
    wrong_answers INT NOT NULL,
    time_taken INT NOT NULL,
    attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_login(id) ON DELETE CASCADE
)";
if (!mysqli_query($con, $createResultTable)) {
    die("Error creating student_result table: " . mysqli_error($con));
}

// ✅ Fetch report
$sql = "SELECT s.name, s.roll_no, r.subject, r.score, r.correct_answers, r.wrong_answers, r.time_taken, r.attempted_at 
        FROM student_result r
        JOIN student_login s ON s.id = r.student_id
        ORDER BY r.attempted_at DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Quiz Summary Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      padding: 2rem;
      font-family: Arial, sans-serif;
    }
    h2 {
      margin-bottom: 1rem;
      color: #ff6a00;
    }
    table {
      background: white;
    }
  </style>
</head>
<body>

  <h2 class="text-center">📊 Combined Student Quiz Report</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-dark text-center">
      <tr>
        <th>Student Name</th>
        <th>Roll No</th>
        <th>Subject</th>
        <th>Score</th>
        <th>Correct</th>
        <th>Wrong</th>
        <th>Time Taken</th>
        <th>Attempted At</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['name']) . "</td>
                      <td>" . htmlspecialchars($row['roll_no']) . "</td>
                      <td>" . htmlspecialchars($row['subject']) . "</td>
                      <td>{$row['score']}</td>
                      <td>{$row['correct_answers']}</td>
                      <td>{$row['wrong_answers']}</td>
                      <td>{$row['time_taken']} sec</td>
                      <td>{$row['attempted_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
