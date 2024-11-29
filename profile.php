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

<style>
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');

/* BASIC STYLES */

body, html {
  margin: 5px;
  padding: 0;
  font-family: 'Montserrat', sans-serif;
  background: linear-gradient(90deg, #42566b , #7693a3 , #93a4b5 , #AAB7B7 , #93a4b5 , #7693a3 , #42566b );
  display:flex;
  justify-content: center;
  align-items: center;
  height: 90%;
}

a {
  color: #046cdb;
  text-decoration: none;
  font-weight: 500;
}

h1 {
  margin: 0;
}

h2 {
  text-align: center;
  font-size: 18px;
  font-weight: 600;
  color: #333;
  text-transform: uppercase;
}

/* PROFILE CONTAINER */

.profile-container {
  background-color: #f5fafc;
  padding: 60px;
  width: 100%;
  height: 79%;
  max-width: 400px;
  border-radius: 15px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
  text-align: center;
  transition: all 0.4s ease-in-out;
  
}

.profile-container:hover {
  transform: scale(1.02);
}

/* PROFILE HEADER */

.profile-header {
  margin-bottom: 30px;
}

.profile-header h1 {
  font-size: 26px;
  color: #36789c;
  margin-bottom: 10px;
}

.profile-image {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 5px solid #abbac9;
  object-fit: cover;
}

/* PROFILE INFO */

.profile-info {
  text-align: left;
  margin-top: 20px;
  font-size: 16px;
  color: #618bb8;
}

.profile-info p {
  margin-bottom: 12px;
}

.profile-info p strong {
  color: #666;
}

.icon {
  width: 20px;
  height: 20px;
  margin-right: 10px;
  vertical-align: middle;
}

/* BUTTONS */

.button {
  display: inline-block;
  padding: 12px 24px;
  background-color: #618bb8;
  color: white;
  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s ease;
  border: none;
  cursor: pointer;
  margin-top: 0px;
  margin-bottom: 15px;
}

.button:hover {
  background-color: #034f9f;
}

.back-home-btn {
  background-color: #abbac9;
  width: 300px;
}

.back-home-btn:hover {
  background-color: #42566b;
}

@media (max-width: 768px) {
  .body, html{
    margin-top: 60px;
    height: 370px;
  }
  
  .profile-container {
    padding: 20px;
    height: 400px;
  }

  .profile-header h1 {
    font-size: 22px;
  }
  .button{

    margin-bottom: 30px;
  }
  .profile-image {
    width: 100px;
    height: 100px;
  }
}

</style>
</body>
</html>
