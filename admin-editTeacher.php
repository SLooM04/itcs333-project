<?php
session_start();
require 'db.php';

$error = '';
$success = '';
$teacher = null;

// Handle finding teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
    $stmt->execute([$teacher_id]);
    $teacher = $stmt->fetch();

    if (!$teacher) {
        $error = "Teacher not found.";
    }
}

// Handle updating teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $teacher_id = $_POST['teacher_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];

    $stmt = $pdo->prepare("UPDATE teachers SET first_name = ?, last_name = ?, email = ?, username = ?, department = ?, mobile = ?, updated_at = NOW() WHERE teacher_id = ?");
    $stmt->execute([$first_name, $last_name, $email, $username, $department, $mobile, $teacher_id]);

    $success = "Teacher updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4a90e2;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
            color: #555;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #357ab7;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        .back-button {
            display: block;
            width: 200px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Teacher</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Form to find teacher -->
        <form method="POST">
            <label for="teacher_id">Enter Teacher ID:</label>
            <input type="number" id="teacher_id" name="teacher_id" placeholder="Enter teacher ID" required>
            <button type="submit">Find Teacher</button>
        </form>

        <!-- Form to edit teacher -->
        <?php if ($teacher): ?>
            <form method="POST">
                <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher['teacher_id']) ?>">

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($teacher['first_name']) ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($teacher['last_name']) ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($teacher['username']) ?>" required>
                 
                <label for="department">Department:</label>
                <select id="department" name="department" value="<?= htmlspecialchars($teacher['department']) ?>" required>
                    <option value="Information Systems">Information Systems</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Computer Engineering">Computer Engineering</option>
                </select>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($teacher['mobile']) ?>" required>

                <button type="submit" name="update">Update Teacher</button>
            </form>
        <?php endif; ?>

        <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>