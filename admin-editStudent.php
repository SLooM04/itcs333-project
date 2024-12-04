<?php
session_start();
require 'db.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if (!$student) {
        $error = "Student not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $major = $_POST['major'];
    $mobile = $_POST['mobile'];
    $year_joined = $_POST['year_joined'];

   
    $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, username = ?, major = ?, mobile = ?, year_joined = ?, updated_at = NOW() WHERE student_id = ?");
    $stmt->execute([$first_name, $last_name, $email, $username, $major, $mobile, $year_joined, $student_id]);

    $success = "Student updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
            color: #1a3d7c;
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
            background-color: #1a3d7c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #134a7f;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>

       
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

       
        <form method="POST">
            <label for="student_id">Enter Student ID:</label>
            <input type="number" id="student_id" name="student_id" required>
            <button type="submit">Find Student</button>
        </form>

        <?php if (isset($student)): ?>
            <form method="POST">
                <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= $student['first_name'] ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= $student['last_name'] ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $student['email'] ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= $student['username'] ?>" required>

                <label for="major">Major:</label>
                <input type="text" id="major" name="major" value="<?= $student['major'] ?>" required>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?= $student['mobile'] ?>" required>

                <label for="year_joined">Year Joined:</label>
                <input type="number" id="year_joined" name="year_joined" value="<?= $student['year_joined'] ?>" required>

                <button type="submit" name="update">Update Student</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
