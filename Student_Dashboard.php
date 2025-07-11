<?php
include('create_connection.php');
session_start();

// Login check
if (!isset($_SESSION['roll_no']) || !isset($_SESSION['name'])) {
  header("Location: ../HTML_pages/Student_Login_Register.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7fc;
      color: #333;
    }
    .dashboard-container { display: flex; height: 100vh; }
    .sidebar {
      width: 220px;
      background-color: #ff6a00;
      color: white;
      padding: 2rem 1rem;
    }
    .sidebar h2 { margin-bottom: 2rem; text-align: center; }
    .sidebar ul { list-style: none; }
    .sidebar ul li { margin: 1rem 0; }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      display: block;
      padding: 0.5rem;
      border-radius: 5px;
    }
    .sidebar ul li a:hover { background-color: #ee0979; }
    .main-content { flex-grow: 1; padding: 2rem; }
    header h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      color: #ee0979;
    }
    header p { margin-bottom: 2rem; }
    .cards {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
    }
    .card {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      flex: 1;
      min-width: 250px;
      transition: transform 0.3s ease;
    }
    .card:hover { transform: translateY(-5px); }
    .card h3 { color: #ee0979; margin-bottom: 0.5rem; }
    .card p { margin-bottom: 1rem; }
    .card a {
      background-color: #ff6a00;
      color: white;
      padding: 0.5rem 1rem;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 500;
    }
    .card a:hover { background-color: #ee0979; }
    .user-info {
      position: absolute;
      top: 10px;
      right: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: white;
      padding: 8px 16px;
      border-radius: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .user-info img {
      height: 40px;
      width: 40px;
      border-radius: 50%;
    }
    .user-info span {
      font-size: 14px;
      color: #333;
      font-weight: 500;
      line-height: 1.2;
    }
  </style>
</head>
<body>
  <div class="user-info">
    <img src="https://cdn-icons-png.flaticon.com/512/4333/4333609.png" alt="Profile" />
    <span>
      <?= htmlspecialchars($_SESSION['name']); ?><br>
      (<?= htmlspecialchars($_SESSION['roll_no']); ?>)
    </span>
  </div>

  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>NobelQuiz</h2>
      <nav>
        <ul>
          <li><a href="../Home_page.html">🏠 Home</a></li>
          <li><a href="../HTML_pages/Categories.html">? Start Quiz</a></li>
          <li><a href="Student_upload_question.php">📋 Instructions</a></li>
          <li><a href="Student_Performance.php">📊 My Scores</a></li>
          <li><a href="Student_result.php">📜 My Results</a></li>
          <li><a href="Student_change_password.php">🔒 Change Password</a></li>
          <li><a href="Student_logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header>
        <h1>Welcome, Trainee!</h1>
        <p>Track your progress and take on new challenges.</p>
      </header>

      <section class="cards">
        <div class="card">
          <h3>Start New Quiz</h3>
          <p>Challenge yourself with a new set of questions.</p>
          <a href="../Categories.html">Start</a>
        </div>

        <div class="card">
          <h3>My Performance</h3>
          <p>Analyze your quiz history and progress.</p>
          <a href="Student_Performance.php">Check</a>
        </div>

        <div class="card">
          <h3>My Results</h3>
          <p>Check your scores after result is published.</p>
          <a href="Student_result.php">View</a>
        </div>

        <div class="card">
          <h3>Change Password</h3>
          <p>Update your login credentials securely.</p>
          <a href="Student_change_password.php">Update</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
