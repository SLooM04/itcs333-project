<?php
session_start();
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
    $stmt->execute([$teacher_id]);
    $teacher = $stmt->fetch();

    if (!$teacher) {
        $error = "Teacher not found.";
    }
}


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
        <h1>Edit Teacher</h1>

        
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        
        <form method="POST">
            <label for="teacher_id">Enter Teacher ID:</label>
            <input type="number" id="teacher_id" name="teacher_id" required>
            <button type="submit">Find Teacher</button>
        </form>

        <?php if (isset($teacher)): ?>
            <form method="POST">
                <input type="hidden" name="teacher_id" value="<?= $teacher['teacher_id'] ?>">

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= $teacher['first_name'] ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= $teacher['last_name'] ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $teacher['email'] ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= $teacher['username'] ?>" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= $teacher['department'] ?>" required>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?= $teacher['mobile'] ?>" required>

                <button type="submit" name="update">Update Teacher</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
