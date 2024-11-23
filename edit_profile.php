<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $bio = trim($_POST['bio']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $profilePicture = $user['profile_picture'];

    if ($_FILES['profile_picture']['name']) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
        $profilePicture = $targetFilePath; // Update with new file path
    }

    $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, bio = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?");
    $stmt->execute([$name, $bio, $email, $hashedPassword, $profilePicture, $userId]);

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