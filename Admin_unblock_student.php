<?php
require 'create_connection.php';

if (isset($_GET['unblock'])) {
    $roll = $_GET['unblock'];
    $stmt = $con->prepare("DELETE FROM blocked_students WHERE roll_no = ?");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    echo "<script>alert('Unblocked successfully');window.location='admin_unblock.php';</script>";
}

$result = $con->query("SELECT * FROM blocked_students");
?>

<h2>Blocked Students</h2>
<table border="1" cellpadding="8">
    <tr><th>Roll No</th><th>Name</th><th>Status</th><th>Action</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['roll_no'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><a href="?unblock=<?= $row['roll_no'] ?>">Unblock</a></td>
    </tr>
    <?php endwhile; ?>
</table>
