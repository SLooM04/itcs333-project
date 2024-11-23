<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$emailRegex = "/^[a-zA-Z0-9]+@uob\.edu\.bh$/";  
$usernameRegex = "/^[a-zA-Z0-9_]{3,20}$/";    
$passRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/"; 

// Restrictions
$errors = [];
    
if (empty($name)) {
    $errors[] = "Name is required.";
}

if (empty($username)) {
    $errors[] = "Username is required.";
} elseif (!preg_match($usernameRegex, $username)) {
    $errors[] = "Username must be alphanumeric, 3-20 characters, and may include underscores.";
}

if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!preg_match($emailRegex, $email)) {
    $errors[] = "Invalid email format. Please use a valid University of Bahrain email.";
}

if (!empty($password) && !preg_match($passRegex, $password)) {
    $errors[] = "Password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a special character.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red'>$error</p>";
    }
}

if ($_FILES['profile_picture']['name']) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES['profile_picture']['name']);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
    $profilePicture = $targetFilePath; 
}

 $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

if (empty($errors)) {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, bio = ?, email = ?, username = ?, password = ?, profile_picture = ? WHERE id = ?");
    $stmt->execute([$name, $bio, $email, $username, $hashedPassword, $profilePicture, $userId]);

    $_SESSION['profile_update_success'] = "Profile updated successfully!";
    header("Location: profile.php");
    exit();
}


?>


<!-- HTML for editing the profile -->
 <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label for="bio">Bio:</label>
    <textarea name="bio"><?= htmlspecialchars($user['bio']) ?></textarea>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="password">New Password (Leave blank to keep current):</label>
    <input type="password" name="password">

    <label for="profile_picture">Profile Picture:</label>
    <input type="file" name="profile_picture">

    <button type="submit">Save Changes</button>
</form>