<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $level = $_POST['level'];
    $major = $_POST['major'] ?? null;
    $mobile = $_POST['mobile'] ?? null;
    $profile_picture = $_FILES['profile_picture']['name'] ?? null;

    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Directory for uploading images
    $uploadDir = "uploads/teachers/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // File upload handling
    if ($profile_picture) {
        $target_file = $uploadDir . basename($profile_picture);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_type, $allowed_types)) {
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
        } else {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Validate required fields
    if ($first_name && $last_name && $email && $username && $password && $level && $mobile) {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, username, password, major, mobile, level)
                        VALUES (:first_name, :last_name, :email, :username, :password, :major, :mobile, :level)");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':major' => $major,
            ':mobile' => $mobile,
            ':level' => $level,  // Ensure that $level is being passed correctly
        ]);
        

        header("Location: admin-dashboard.php?success=teacher_added");
        exit();
    } else {
        $error = $error ?? "Please fill all required fields.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4a90e2;
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        form label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form input[type="number"],
        button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form input[type="number"] {
            background-color: #f0f2f5;
        }
        button {
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #357ab7;
        }

         
        .form-group {
            flex: 1;
            margin-right: 24px;
        }

        .form-group:last-child {
            margin-right: 0;
        }

        .form-group label {
            font-size: 1.1em;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            font-size: 0.9em;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 60%;
            background-color: white;
            color: #333;
            transition: all 0.3s;
            margin-bottom: 15px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0061f2;
            background-color: #f1faff;
            outline: none;
        }
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

            <div id="registration-major" class="form-group">
                    <label for="major">Major</label>
                    <select id="major" name="major" required>
                        <option value="CY">Cybersecurity (CY)</option>
                        <option value="CS">Computer Science (CS)</option>
                        <option value="NE">Network Engineering (NE)</option>
                        <option value="CE">Computer Engineering (CE)</option>
                        <option value="SE">Software Engineering (SE)</option>
                        <option value="IS">Information Systems (IS)</option>
                        <option value="CC">Cloud Computing (CC)</option>
                    </select>
            </div>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>
            <div id="registration-level" class="form-group">
                    <label for="year">Level</label>
                    <select id="level" name="level" required>
                        <option value="Freshman">Freshman (1st Year)</option>
                        <option value="Sophomore">Sophomore (2nd Year)</option>
                        <option value="Junior">Junior (3rd Year)</option>
                        <option value="Senior">Senior (last Year)</option>
                        <option value="Post">Postgraduate</option>
                    </select>
            </div>

            <button type="submit">Add Student</button>
        </form>
        <button style="margin-top:10px; padding:10px 20px;background-color:#b9c6d6;color:white;border:none;border-radius:5px;cursor:pointer;font-size:16px;" onclick="window.history.back()">Go Back</button>

    </div>
</body>
</html>
