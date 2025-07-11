<?php
session_start();
require 'create_connection.php';

// ✅ Only allow admin access
if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}

// 📌 Handle form submission for download
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_csv'])) {
    $subject = $_POST['subject'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=filtered_student_results.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen("php://output", "w");
    fputcsv($output, ['Roll No', 'Name', 'Subject', 'Score', 'Total', 'Submitted At']);

    // Build query
    $query = "SELECT roll_no, name, subject, score, total, submitted_at FROM results WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($subject)) {
        $query .= " AND subject = ?";
        $params[] = $subject;
        $types .= "s";
    }

    if (!empty($from_date)) {
        $query .= " AND submitted_at >= ?";
        $params[] = $from_date . " 00:00:00";
        $types .= "s";
    }

    if (!empty($to_date)) {
        $query .= " AND submitted_at <= ?";
        $params[] = $to_date . " 23:59:59";
        $types .= "s";
    }

    $stmt = $con->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// 🟧 Get distinct subjects for filter dropdown
$subject_result = $con->query("SELECT DISTINCT subject FROM results");
$subjects = [];
while ($row = $subject_result->fetch_assoc()) {
    $subjects[] = $row['subject'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Download Student Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    .container {
      max-width: 700px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.08);
    }
    h2 {
      color: #ff6a00;
      margin-bottom: 30px;
      text-align: center;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn-download {
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
    }
    .btn-download:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📥 Download Student Result Report</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="subject" class="form-label">Subject</label>
      <select name="subject" class="form-select">
        <option value="">-- All Subjects --</option>
        <?php foreach ($subjects as $sub): ?>
          <option value="<?= htmlspecialchars($sub) ?>"><?= strtoupper($sub) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">From Date</label>
      <input type="date" name="from_date" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">To Date</label>
      <input type="date" name="to_date" class="form-control">
    </div>

    <button type="submit" name="download_csv" class="btn btn-download">Download CSV</button>
  </form>
</div>

</body>
</html>
