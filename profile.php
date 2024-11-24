<?php
session_start();
require 'db.php'; // Ensure session and database connection

// Check if the student is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to view your profile.");
}

$userId = $_SESSION['user_id']; // Get logged-in student's ID
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
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
            <h1>Welcome, <?= htmlspecialchars($student['first_name']) ?> <?= htmlspecialchars($student['last_name']) ?></h1>
            <img src="<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="profile-image">
        </div>

        <div class="profile-info">
            <p><strong>Username:</strong> <?= htmlspecialchars($student['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
            <p><strong>Major:</strong> <?= htmlspecialchars($student['major']) ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($student['mobile']) ?></p>
            <p><strong>Year Joined:</strong> <?= htmlspecialchars($student['year_joined']) ?></p>
            <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        </div>

        <!-- Add back to Home button -->
        <a href="HomeLog.php" class="back-home-btn">Back to Home</a>
    </div>

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
