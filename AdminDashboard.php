<?php
// File: Admin_dashboard.php
session_start();
include('create_connection.php');

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    header("Location:AdminDashboard.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$admin_email = $_SESSION['admin_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden; /* prevent scrolling */
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7fc;
    }

    .dashboard-container {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 250px;
      background-color: #ff6a00;
      color: white;
      padding: 2rem 1rem;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 2rem;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin: 1rem 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.6rem 1rem;
      border-radius: 6px;
    }

    .sidebar ul li a:hover {
      background-color: #ee0979;
    }

    .main-content {
      flex-grow: 1;
      padding: 1rem 2rem;
      background-color: #fff;
      display: flex;
      flex-direction: column;
    }

    .main-content h1 {
      color: #ee0979;
      margin-bottom: 1rem;
    }

    .card-grid {
      flex-grow: 1;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
      overflow-y: auto;
      padding-right: 8px;
    }

    .card {
      background-color: #f9f9f9;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    .card h3, .card h4 {
      color: #ff6a00;
      margin-bottom: 0.5rem;
    }

    .card p {
      margin-bottom: 1rem;
      font-size: 14px;
    }

    .card a {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      background-color: #ff6a00;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
    }

    .card a:hover {
      background-color: #ee0979;
    }

    .push-form {
      display: flex;
      flex-direction: column;
      gap: 8px;
      font-size: 14px;
    }

    .push-form select,
    .push-form input {
      padding: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .logout-card {
      background-color: #ffe9e0;
      align-self: flex-end;
    }

    .logout-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  <!-- 🔸 Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="Home_page.html">🏠 Home</a></li>
      <li><a href="Quiz_template.php">🧠 Start Quiz</a></li>
      <li><a href="Admin_view_student.php">👥 View All Students</a></li>
      <li><a href="Quiz_Summary.php">📊 Quiz-Wise Performance</a></li>
      <li><a href="admin_publish_results.php">📢 Publish Results</a></li>
      <li><a href="Admin_upload_ques.php">📤 Upload Questions</a></li>
      <li><a href="Download_report.php">📥 Download Reports</a></li>
      <li><a href="../PHP_pages/logout.php">🚪 Logout</a></li>
    </ul>
  </div>

  <!-- 🔹 Main Area -->
  <div class="main-content">
    <h1>Welcome Back, <?= htmlspecialchars($admin_name) ?>!</h1>

    <div class="card-grid">
      <!-- Cards -->
      <div class="card">
        <h3>View All Students</h3>
        <p>Monitor individual student performance</p>
        <a href="Admin_view_student.php">View</a>
      </div>

      <div class="card">
        <h3>Quiz-Wise Analysis</h3>
        <p>Get insights into performance per quiz</p>
        <a href="Quiz_Summary.php">View</a>
      </div>

      <div class="card">
        <h3>Publish Results</h3>
        <p>Release scores to students after review</p>
        <a href="admin_publish_results.php">Publish</a>
      </div>

      <div class="card">
        <h3>Start Quiz</h3>
        <p>Take a quiz as preview or demonstration</p>
        <a href="../Categories.html">Start</a>
      </div>

      <div class="card">
        <h3>Upload Questions</h3>
        <p>Add or update quiz questions by category</p>
        <a href="Admin_upload_ques.php">Upload</a>
      </div>

      <div class="card">
        <h3>Download Reports</h3>
        <p>Export all student data in CSV format</p>
        <a href="Download_report.php">Download</a>
      </div>

      <!-- Push Quiz Card -->
      <div class="card">
        <h4>📢 Push Quiz Live</h4>
        <form method="POST" action="push_quiz_live.php" class="push-form">
          <select name="subject" required>
            <option value="">-- Select Subject --</option>
            <option value="gk">GK</option>
            <option value="maths">Maths</option>
            <option value="reasoning">Reasoning</option>
            <option value="trade theory">Trade Theory</option>
            <option value="training methodology">Training Methodology</option>
          </select>

          <input type="number" name="duration" min="1" max="180" value="60" required placeholder="Duration (min)">

          <button type="submit" class="btn btn-success">Push Quiz</button>
        </form>
      </div>

</body>
</html>
