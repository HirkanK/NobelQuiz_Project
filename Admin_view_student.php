<?php
// File: admin_view_students.php
session_start();

// ✅ Database Connection
$con = new mysqli("localhost", "root", "", "Main_Database");
if ($con->connect_error) die("Connection failed: " . $con->connect_error);

// ✅ Create 'students' table if not exists
// $con->query("
  // CREATE TABLE IF NOT EXISTS students (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // name VARCHAR(100) NOT NULL,
    // email VARCHAR(100) NOT NULL UNIQUE,
    // roll_no VARCHAR(50),
    // password VARCHAR(255),
    // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  // )
// ");

// ✅ Create 'quiz_scores' table if not exists
// $con->query("
  // CREATE TABLE IF NOT EXISTS quiz_scores (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // student_id INT,
    // quiz_name VARCHAR(100),
    // score INT,
    // time_taken VARCHAR(20),
    // taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    // FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
  // )
// ");

// ✅ Fetch student performance data
$res = $con->query("
  SELECT s.name, s.email, q.quiz_name, q.score, q.time_taken, q.taken_at
  FROM students_details s
  JOIN quiz_scores q ON s.id = q.student_id
  ORDER BY q.taken_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>All Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2 class="text-center text-primary">All Student Performances</h2>

  <table class="table table-bordered table-striped mt-4">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Quiz</th>
        <th>Score</th>
        <th>Time Taken</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['quiz_name']) ?></td>
          <td><?= htmlspecialchars($row['score']) ?></td>
          <td><?= htmlspecialchars($row['time_taken']) ?></td>
          <td><?= htmlspecialchars($row['taken_at']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="AdminDashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
