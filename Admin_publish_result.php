<?php
session_start();
require 'create_connection.php';

// Handle publish request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish_subject'])) {
    $subject = $_POST['publish_subject'];
    $stmt = $con->prepare("UPDATE results SET is_published = 1 WHERE subject = ?");
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $msg = "✅ Results for '$subject' have been published.";
}

// Fetch all subjects
$subjects = [];
$result = $con->query("SELECT DISTINCT subject FROM results");
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin: Publish Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background: #f0f2f5;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f97316;
      color: white;
    }
    form {
      display: inline;
    }
    .btn {
      background: #10b981;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn:hover {
      background: #059669;
    }
    .msg {
      text-align: center;
      color: green;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>🧑‍🏫 Admin Panel – Publish Student Results</h2>

  <?php if (isset($msg)) echo "<p class='msg'>$msg</p>"; ?>

  <?php foreach ($subjects as $subject): ?>
    <h3>📘 Subject: <?= strtoupper($subject) ?></h3>
    <table>
      <tr>
        <th>Roll No</th>
        <th>Name</th>
        <th>Score</th>
        <th>Total</th>
        <th>Published</th>
      </tr>

      <?php
      $stmt = $con->prepare("SELECT * FROM results WHERE subject = ?");
      $stmt->bind_param("s", $subject);
      $stmt->execute();
      $res = $stmt->get_result();

      while ($row = $res->fetch_assoc()):
      ?>
        <tr>
          <td><?= $row['roll_no'] ?></td>
          <td><?= $row['name'] ?></td>
          <td><?= $row['score'] ?></td>
          <td><?= $row['total'] ?></td>
          <td><?= $row['is_published'] ? '✅ Yes' : '❌ No' ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <?php
    // Show Publish button only if not published yet
    $check = $con->prepare("SELECT COUNT(*) as cnt FROM results WHERE subject = ? AND is_published = 0");
    $check->bind_param("s", $subject);
    $check->execute();
    $countRes = $check->get_result()->fetch_assoc();

    if ($countRes['cnt'] > 0):
    ?>
      <form method="POST">
        <input type="hidden" name="publish_subject" value="<?= $subject ?>">
        <button type="submit" class="btn">📢 Publish Result</button>
      </form>
    <?php else: ?>
      <p style="color:green; font-weight:500;">All results for <?= strtoupper($subject) ?> are published ✅</p>
    <?php endif; ?>
    <hr><br>
  <?php endforeach; ?>
</div>

</body>
</html>
