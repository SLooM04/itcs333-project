<?php
session_start();
require 'db.php';

// Ensure session is active and student_id exists
if (!isset($_SESSION['student_id'])) {
    die("You are not logged in. Please log in first.");
}

$studentId = $_SESSION['student_id'];

// Fetch student details from the database
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
    <main class="container">
        <h1>Welcome, <?= htmlspecialchars($student['first_name'] . " " . $student['last_name']) ?></h1>
        <p><strong>Username:</strong> <?= htmlspecialchars($student['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>Major:</strong> <?= htmlspecialchars($student['major']) ?></p>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($student['mobile']) ?: "N/A" ?></p>
        <p><strong>Year Joined:</strong> <?= htmlspecialchars($student['year_joined']) ?></p>
        <p><strong>Date Joined:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
        <a href="edit_profile.php">Edit Profile</a>
    </main>
</body>
</html>
