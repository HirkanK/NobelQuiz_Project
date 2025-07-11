<?php
$targetDir = "uploads/";
$unit = $_POST['name'];
$topic = $_POST['topic'];
$year = $_POST['year'];

if (isset($_FILES["myFile"]) && $_FILES["myFile"]["error"] == 0) {
    $fileName = basename($_FILES["myFile"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($_FILES["myFile"]["tmp_name"], $targetFilePath);

    // Save to DB
    $conn = new mysqli("localhost", "root", "", "project_db");
    if ($conn->connect_error) {
        die("DB connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO upload_table (unit, topic, year, file_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $unit, $topic, $year, $fileName);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Uploaded";
} else {
    echo "Error uploading file.";
}
?>
