<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to view your profile.");
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

if ($userRole == 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
}
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Welcome, <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?></h1>
            <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'uploads/Temp-user-face.jpg') ?>" alt="Profile Picture" class="profile-image">
        </div>

        <div class="profile-info">
            <p><img src="https://img.icons8.com/ios-filled/50/000000/username.png" class="icon"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><img src="https://img.icons8.com/ios-filled/50/000000/email.png" class="icon"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><img src="https://img.icons8.com/ios-filled/50/000000/work.png" class="icon"><strong>Role:</strong> <?= $userRole == 'student' ? 'Student' : 'Teacher' ?></p>
            <?php if ($userRole == 'student'): ?>
                <p><img src="https://img.icons8.com/ios-filled/50/000000/graduation-cap.png" class="icon"><strong>Major:</strong> <?= htmlspecialchars($user['major']) ?></p>
                <p><img src="https://img.icons8.com/ios-filled/50/000000/education.png" class="icon"><strong>Level:</strong> <?= htmlspecialchars($user['level']) ?></p>
            <?php else: ?>
                <p><img src="https://img.icons8.com/ios-filled/50/000000/department.png" class="icon"><strong>Department:</strong> <?= htmlspecialchars($user['department']) ?></p>
            <?php endif; ?>
            <a href="edit_profile.php" class="button edit-btn">Edit Profile</a>
        </div>

        <a href="HomeLog.php" class="button back-home-btn">Back to Home</a>
    </div>
</body>
</html>
