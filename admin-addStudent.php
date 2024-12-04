<?php
session_start();
require 'db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $major = $_POST['major'];
    $mobile = $_POST['mobile'];
    $year_joined = $_POST['year_joined'];

    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, username, password, major, mobile, year_joined, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$first_name, $last_name, $email, $username, $password, $major, $mobile, $year_joined]);

    echo "Student added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        /* استخدام نفس تصميم صفحة تسجيل الدخول */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #1a3d7c; }
        form label { display: block; margin-bottom: 8px; font-size: 1.1em; color: #555; }
        form input[type="text"], form input[type="email"], form input[type="password"], form input[type="number"], form button { width: 100%; padding: 10px; margin-bottom: 20px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px; }
        form button { background-color: #1a3d7c; color: white; border: none; cursor: pointer; }
        form button:hover { background-color: #134a7f; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Student</h1>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="major">Major:</label>
            <input type="text" id="major" name="major" required>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>

            <label for="year_joined">Year Joined:</label>
            <input type="number" id="year_joined" name="year_joined" required>

            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>
