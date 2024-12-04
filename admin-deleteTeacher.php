<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? null;
    $email = $_POST['email'] ?? null;

    if ($first_name && $email) {
        $stmt = $pdo->prepare("DELETE FROM teachers WHERE first_name = ? AND email = ?");
        $stmt->execute([$first_name, $email]);

        if ($stmt->rowCount() > 0) {
            echo "Teacher deleted successfully!";
        } else {
            echo "Teacher not found.";
        }
    } else {
        echo "Please provide the teacher's first name and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Teacher</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>
    <main class="container">
        <h1>Delete Teacher</h1>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <button type="submit" class="primary">Delete Teacher</button>
        </form>
    </main>
</body>
</html>
