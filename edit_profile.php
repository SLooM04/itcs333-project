<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to edit your profile.");
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

// Handle form submission to update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $mobile = trim($_POST['mobile']);
    $profilePicture = $user['profile_picture'];

    // Handle profile picture upload or deletion
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
        $profilePicture = $targetFilePath;
    } elseif (isset($_POST['delete_picture']) && $_POST['delete_picture'] == '1') {
        $profilePicture = 'uploads/Temp-user-face.jpg';
    }

    // Validate inputs
    $errors = [];
    if (!preg_match("/^[0-9]{9}@stu\.uob\.edu\.bh$/", $email)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (!empty($password) && $password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

        if ($userRole == 'student') {
            $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, username = ?, password = ?, mobile = ?, year_joined = ?, profile_picture = ? WHERE student_id = ?");
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $mobile, $_POST['year'], $profilePicture, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE teachers SET first_name = ?, last_name = ?, email = ?, username = ?, password = ?, mobile = ?, department = ?, profile_picture = ? WHERE teacher_id = ?");
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $mobile, $_POST['department'], $profilePicture, $userId]);
        }

        $_SESSION['profile_update_success'] = "Profile updated successfully!";
        header("Location: " . ($userRole == 'student' ? 'STU-profile.php' : 'TECH-profile.php'));
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
        <?php
        if (isset($_SESSION['profile_update_error'])) {
            echo "<p class='error-message'>" . $_SESSION['profile_update_error'] . "</p>";
            unset($_SESSION['profile_update_error']);
        }
        ?>

        <form action="<?= $userRole == 'student' ? 'STU-edit_profile.php' : 'TECH-edit_profile.php' ?>" method="POST" enctype="multipart/form-data">
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
                <input type="checkbox" name="delete_picture" value="1">
            </div>

            <?php if ($userRole == 'student'): ?>
                <div class="form-group">
                    <label for="year">Year Joined:</label>
                    <input type="number" name="year" value="<?= htmlspecialchars($user['year_joined']) ?>" required>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($user['department']) ?>" required>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    </div>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .edit-profile-container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0061f2;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 18px;
            color: #333;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        .submit-btn {
            background-color: #0061f2;
            color: #fff;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #004bb5;
        }

        .error-message {
            color: red;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #2863AE;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .edit-profile-container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</body>
</html>
