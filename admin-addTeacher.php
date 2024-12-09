<?php
session_start();
require 'db.php';

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $department = $_POST['department'] ?? null;
    $mobile = $_POST['mobile'] ?? null;
    $profile_picture = $_FILES['profile_picture']['name'] ?? null;

    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $uploadDir = "uploads/teachers/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($profile_picture) {
        $target_file = $uploadDir . basename($profile_picture);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_type, $allowed_types)) {
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    if ($first_name && $last_name && $email && $username && $password && $department && $mobile) {
        try {
            $stmt = $pdo->prepare("INSERT INTO teachers (first_name, last_name, email, username, password, department, mobile, created_at, updated_at, profile_picture) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $first_name,
                $last_name,
                $email,
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $department,
                $mobile,
                $created_at,
                $updated_at,
                $profile_picture
            ]);
            $message = "Teacher added successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = $message ?: "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #007bff; 
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3; 
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
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
            margin: 20px auto; /* Centers the button horizontally */
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1>Add Teacher</h1>
    
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>
        </div>
        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture">
        </div>
        <button type="submit" class="btn">Add Teacher</button>
    </form>
    
    <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
</main>

</body>
</html>