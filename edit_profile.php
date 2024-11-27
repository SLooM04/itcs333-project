<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to edit your profile.");
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

// Fetch user details
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

// Handle form submission to update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $mobile = trim($_POST['mobile']);
    $profilePicture = $user['profile_picture'] ?? 'uploads/Temp-user-face.jpg';

    $errors = [];

    // Validate unique email
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM students WHERE email = ? AND student_id != ? 
        UNION 
        SELECT COUNT(*) FROM teachers WHERE email = ? AND teacher_id != ?
    ");
    $stmt->execute([$email, $userId, $email, $userId]);
    $emailExists = $stmt->fetchColumn() > 0;

    if ($emailExists) {
        $errors[] = "This email is already in use.";
    }

    // Validate unique username
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM students WHERE username = ? AND student_id != ? 
        UNION 
        SELECT COUNT(*) FROM teachers WHERE username = ? AND teacher_id != ?
    ");
    $stmt->execute([$username, $userId, $username, $userId]);
    $usernameExists = $stmt->fetchColumn() > 0;

    if ($usernameExists) {
        $errors[] = "This username is already in use.";
    }

    if (!empty($password) && $password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Handle profile picture upload or deletion
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
        $profilePicture = $targetFilePath;
    } elseif (isset($_POST['delete_picture']) && $_POST['delete_picture'] == '1') {
        $profilePicture = 'uploads/Temp-user-face.jpg'; // Reset to default image
    }

    if (empty($errors)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

        if ($userRole == 'student') {
            $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, username = ?, password = ?, mobile = ?, profile_picture = ? WHERE student_id = ?");
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $mobile, $profilePicture, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE teachers SET first_name = ?, last_name = ?, email = ?, username = ?, password = ?, mobile = ?, department = ?, profile_picture = ? WHERE teacher_id = ?");
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $mobile, $_POST['department'], $profilePicture, $userId]);
        }

        $_SESSION['profile_update_success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['profile_update_error'] = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Your Profile</h1>
        <?php if (isset($_SESSION['profile_update_error'])): ?>
            <p class="error-message"><?= $_SESSION['profile_update_error']; ?></p>
            <?php unset($_SESSION['profile_update_error']); ?>
        <?php endif; ?>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password">
            </div>

            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>">
            </div>

            <div class="form-group">
                <label for="profile_picture">Change Picture:</label>
                <input type="file" name="profile_picture">
            </div>

            <div class="form-group">
                <label for="delete_picture">Delete Picture:</label>
                <input type="checkbox" name="delete_picture" value="1"> Delete current picture
            </div>

            <?php if ($userRole == 'teacher'): ?>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($user['department']) ?>" required>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    </div>
    
    <style> 
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');

/* BASIC STYLES */

body, html {
  margin: 0;
  padding: 0;
  font-family: 'Montserrat', sans-serif;
  background: linear-gradient(135deg, #1f83ed, #abbac9);
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: flex-start; /* Changed to flex-start */
  padding-top: 40px; /* Added padding to the top */
}

a {
  color: #046cdb;
  text-decoration: none;
  font-weight: 500;
}

h1 {
  margin: 0;
}

/* EDIT PROFILE CONTAINER */

.edit-profile-container {
  background-color: #ffffff;
  padding: 40px;
  width: 100%;
  max-width: 500px;
  border-radius: 15px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: all 0.3s ease-in-out;
}

.edit-profile-container:hover {
  transform: scale(1.03);
}

/* FORM GROUP */

.form-group {
  text-align: left;
  margin-top: 20px;
}

.form-group label {
  font-size: 16px;
  color: #333;
}

.form-group input, 
.form-group select {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 2px solid #ddd;
  margin-top: 5px;
}

/* BUTTONS */

.submit-btn {
  display: inline-block;
  padding: 12px 24px;
  background-color: #046cdb;
  color: white;
  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s ease;
  border: none;
  cursor: pointer;
  margin-top: 20px;
  width: 100%;
}

.submit-btn:hover {
  background-color: #034f9f;
}

.error-message {
  color: red;
  font-size: 16px;
  margin-bottom: 20px;
}

/* MEDIA QUERY */

@media (max-width: 768px) {
  .edit-profile-container {
    padding: 20px;
  }

  .edit-profile-container h1 {
    font-size: 22px;
  }
}

    </style>
</body>
