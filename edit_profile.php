<?php
session_start();
require 'db.php'; 

// Check if the student is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to edit your profile.");
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
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
    $major = $_POST['major'];
    $year = $_POST['year'];

    // Validate the inputs
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
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $student['password'];

        $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, username = ?, password = ?, mobile = ?, major = ?, year_joined = ? WHERE student_id = ?");
        $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $mobile, $major, $year, $userId]);

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
        <?php
        if (isset($_SESSION['profile_update_error'])) {
            echo "<p class='error-message'>" . $_SESSION['profile_update_error'] . "</p>";
            unset($_SESSION['profile_update_error']);
        }
        ?>
        
        <form action="edit_profile.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($student['username']) ?>" required>
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
                <input type="text" name="mobile" value="<?= htmlspecialchars($student['mobile']) ?>">
            </div>

            <div class="form-group">
                <label for="major">Major:</label>
                <select name="major" required>
                    <option value="CY" <?= $student['major'] == 'CY' ? 'selected' : '' ?>>Cybersecurity</option>
                    <option value="CS" <?= $student['major'] == 'CS' ? 'selected' : '' ?>>Computer Science</option>
                    <option value="NE" <?= $student['major'] == 'NE' ? 'selected' : '' ?>>Network Engineering</option>
                    <option value="CE" <?= $student['major'] == 'CE' ? 'selected' : '' ?>>Computer Engineering</option>
                    <option value="SE" <?= $student['major'] == 'SE' ? 'selected' : '' ?>>Software Engineering</option>
                    <option value="IS" <?= $student['major'] == 'IS' ? 'selected' : '' ?>>Information Systems</option>
                    <option value="CC" <?= $student['major'] == 'CC' ? 'selected' : '' ?>>Cloud Computing</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year Joined:</label>
                <input type="number" name="year" value="<?= htmlspecialchars($student['year_joined']) ?>" required>
            </div>

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

        @media (max-width: 768px) {
            .edit-profile-container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .form-group input, .form-group select {
                font-size: 14px;
            }
        }
    </style>
</body>
</html>
