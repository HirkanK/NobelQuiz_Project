<?php

include('create_connection.php');
$serverQ = 'localhost';
$usernameQ = 'root';
$passwordQ = '';
$databaseQ = 'Main_Database';

// Step 1: Connect without selecting database
$conQ = mysqli_connect($serverQ, $usernameQ, $passwordQ);

if (!$conQ) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 2: Create database if not exists
/*$sqlQ = "CREATE DATABASE IF NOT EXISTS $databaseQ";
if (mysqli_query($conQ, $sqlQ)) {
    echo "Database '$databaseQ' created or already exists.<br>";
} else {
    die("Error creating database: " . mysqli_error($conQ));
}*/

// Step 3: Select the newly created database
mysqli_select_db($conQ, $databaseQ);

// Create admin table
$Table_admin = "CREATE TABLE IF NOT EXISTS admin_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    A_name VARCHAR(100) NOT NULL,
    A_email VARCHAR(100) NOT NULL UNIQUE,
    A_mobile_number VARCHAR(15) NOT NULL,
    A_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($con, $Table_admin);

// Step 4: Create students table
// $table_stu = "CREATE TABLE IF NOT EXISTS students (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // name VARCHAR(100) NOT NULL,
    // email VARCHAR(100) UNIQUE,
    // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// )";
// mysqli_query($conQ, $table_stu);

// Step 5: Create student table
$table_stu = "CREATE TABLE IF NOT EXISTS students_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    roll_no VARCHAR(50),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )";
mysqli_query($conQ, $table_stu);

// Create quiz_scores table 
$table_quizscore = "CREATE TABLE IF NOT EXISTS quiz_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    quiz_name VARCHAR(100),
    score INT,
    time_taken VARCHAR(20),
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
  )";
mysqli_query($conQ, $table_quizscore);

// Step 5: Create quizzes table
$table_quiz = "CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    total_questions INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conQ, $table_quiz);


// Step 6: Create blocked_students table
$table_block = "CREATE TABLE IF NOT EXISTS blocked_students (
    roll_no VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100),
    status VARCHAR(20)
)";
mysqli_query($conQ, $table_block);


//Step 7: Create question table
$table_ques = "CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(50),
    question TEXT NOT NULL,
    opt1 TEXT NOT NULL,
    opt2 TEXT NOT NULL,
    opt3 TEXT NOT NULL,
    opt4 TEXT NOT NULL,
    answer TEXT NOT NULL
)";

mysqli_query($conQ, $table_ques);

// Step 8: Create result table
$table_rslt = "CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50),
    name VARCHAR(100),
    subject VARCHAR(50),
    score INT,
    total INT,
    time_taken VARCHAR(20),
    is_published TINYINT(1) DEFAULT 0,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conQ, $table_rslt);




//Step 9: Create quiz status table
$table_stt = "CREATE TABLE IF NOT EXISTS quiz_status (
    duration_minutes INT DEFAULT 60;
    subject VARCHAR(50) PRIMARY KEY,
    is_active BOOLEAN DEFAULT FALSE
)";
mysqli_query($conQ, $table_stt);


//Step 10: Create scores table
$table_scr = "CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    quiz_id INT,
    score INT,
    total_questions INT,
    correct INT,
    incorrect INT,
    time_taken VARCHAR(20),
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
)";
mysqli_query($conQ, $table_scr);

echo "All tables created successfully.";
?>
