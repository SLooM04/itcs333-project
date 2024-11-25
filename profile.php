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
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Welcome, <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?></h1>
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
        </div>

        <div class="profile-info">
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Role:</strong> <?= $userRole == 'student' ? 'Student' : 'Teacher' ?></p>
            <?php if ($userRole == 'student'): ?>
                <p><strong>Year Joined:</strong> <?= htmlspecialchars($user['year_joined']) ?></p>
            <?php else: ?>
                <p><strong>Department:</strong> <?= htmlspecialchars($user['department']) ?></p>
            <?php endif; ?>
            <a href="<?= $userRole == 'student' ? 'STU-edit_profile.php' : 'TECH-edit_profile.php' ?>" class="edit-btn">Edit Profile</a>
        </div>

        <a href="HomeLog.php" class="back-home-btn">Back to Home</a>
    </div>

    <!-- Styling for profile -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 600px;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h1 {
            color: #2863AE;
            font-size: 2.5em;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #2863AE;
            object-fit: cover;
        }

        .profile-info {
            font-size: 1rem;
            color: #333;
            line-height: 1.6;
        }

        .profile-info p {
            margin-bottom: 10px;
            font-weight: 500;
        }

        .edit-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2863AE;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .edit-btn:hover {
            background-color: #1A4A80;
        }

        .back-home-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2863AE;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-home-btn:hover {
            background-color: #1A4A80;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 30px 20px;
            }
            .profile-header h1 {
                font-size: 2em;
            }
            .profile-image {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</body>
</html>
