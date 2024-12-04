<?php
session_start();
require 'db.php'; 

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
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadDir . $profile_picture);
    }

    if ($first_name && $last_name && $email && $username && $password && $department && $mobile) {
        $stmt = $pdo->prepare("INSERT INTO teachers (first_name, last_name, email, username, password, department, mobile, created_at, updated_at, profile_picture) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $username, password_hash($password, PASSWORD_DEFAULT), $department, $mobile, $created_at, $updated_at, $profile_picture]);

        header("Location: admin-dashboard.php?success=teacher_added");
        exit();
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="form-container">
        <h1>Add Teacher</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Department:</label>
                <input type="text" name="department" required>
            </div>
            <div class="form-group">
                <label>Mobile:</label>
                <input type="text" name="mobile" required>
            </div>
            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" name="profile_picture">
            </div>
            <button type="submit" class="btn">Add Teacher</button>
        </form>
    </main>
</body>
</html>
