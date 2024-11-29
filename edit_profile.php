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
    <style> 
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');

        body, html {
          font-family: 'Montserrat', sans-serif;
          background: linear-gradient(90deg, #42566b , #7693a3 , #93a4b5 , #AAB7B7 , #93a4b5 , #7693a3 , #42566b );
          height: 100%;
          display:flex;
          justify-content: center;
          align-items: flex-start;
          padding-top: 0px;
          
        }

        .edit-profile-container {
          background-color: #f5fafc;
          padding: 40px;
          width: 100%;
          max-width: 500px;
          border-radius: 15px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
          text-align: center;
          transition: all 0.3s ease-in-out;
          padding-right: 63px;
        }

        .form-group {
          text-align: left;
          margin-top: 20px;
        }

        .form-group input {
          width: 100%;
          padding: 10px;
          font-size: 16px;
          border-radius: 5px;
          border: 2px solid #ddd;
          margin-top: 5px;
        }

        .submit-btn {
          display: inline-block;
          padding: 12px 190px;
          background-color: #618bb8;
          color: white;
          border: none;
          border-radius: 25px;
          text-decoration: none;
          font-weight: bold;
          transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
          background-color: #034f9f;
        }

        .delete-btn {
          display: inline-block;
          padding: 12px 24px;
          background-color: #e74c3c;
          color: white;
          border: none;
          border-radius: 25px;
          font-weight: bold;
          cursor: pointer;
          transition: background-color 0.3s ease;
          margin-right: 10px;
        }

        .delete-btn:hover {
          background-color: #c0392b;
        }

        .error-message {

            color: #e74c3c;
        }

        /* Responsive design */
        @media (max-width: 768px) {
          .submit-btn {
            padding: 12px 130px;
          }

          .edit-profile-container {
            padding: 40px;
            max-width: 350px;
          }

          .form-group input {
            font-size: 14px;
          }
        }

    </style>
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
                <label for="delete_picture">Delete Picture: </label>
                <button type="button" class="delete-btn" onclick="document.getElementById('delete_picture').value = '1';">Delete Current Picture</button>
                <input type="hidden" name="delete_picture" id="delete_picture" value="0">
            </div>

            <?php if ($userRole == 'teacher'): ?>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($user['department']) ?>" required>
                </div>
            <?php endif; ?>

            </br>
            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    </div>

    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete the current picture?")) {
                document.getElementById('delete_picture').value = '1';
                document.querySelector('form').submit();
            }
        }
    </script>
</body>
</html>

