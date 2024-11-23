<?php
session_start();
require 'db.php'; // Database connection

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>Welcome,   <?= htmlspecialchars($user['username']) ?></h1>
    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="150">
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Username: <?= htmlspecialchars($user['username']) ?></p>
    <p>Bio: <?= htmlspecialchars($user['bio']) ?></p>

    <a href="edit_profile.php">Edit Profile</a>
</body>
</html>