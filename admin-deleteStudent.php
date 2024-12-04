<?php
session_start();
require 'db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);

    echo "Student deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <style>
        /* استخدام نفس التصميم */
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Student</h1>
        <form method="POST">
            <label for="student_id">Student ID:</label>
            <input type="number" id="student_id" name="student_id" required>
            <button type="submit">Delete Student</button>
        </form>
    </div>
</body>
</html>
