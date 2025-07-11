<?php
include('create_connection.php');

// Drop table if exists
$drop_sql = "DROP TABLE IF EXISTS Student_details";
if (mysqli_query($con, $drop_sql)) {
    echo "Old table dropped successfully.<br>";
} else {
    echo "Error dropping table: " . mysqli_error($con) . "<br>";
}

// Create table with correct SQL
$table_sql = "
CREATE TABLE Student_details (
    sno INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
	rollno VARCHAR(200) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if (mysqli_query($con, $table_sql)) {
    echo "Table 'Student_details' successfully created.";
} else {
    echo "Error creating table: " . mysqli_error($con);
}
?>
