<?php
session_start();
require 'db.php';

// Fetch current student details
$studentId = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Define validation patterns
$emailRegex = "/^[a-zA-Z0-9]+@stu\.uob\.edu\.bh$/";
$usernameRegex = "/^[a-zA-Z0-9_]{3,50}$/";
$passRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $major = trim($_POST['major']);
    $mobile = trim($_POST['mobile']);
    $yearJoined = trim($_POST['year_joined']);
    $errors = [];

    // Validation
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }
    if (empty($username) || !preg_match($usernameRegex, $username)) {
        $errors[] = "Username must be alphanumeric, 3-50 characters, and may include underscores.";
    }
    if (empty($email) || !preg_match($emailRegex, $email)) {
        $errors[] = "Invalid email format. Use a valid University of Bahrain email.";
    }
    if (!empty($password) && !preg_match($passRegex, $password)) {
        $errors[] = "Password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a special character.";
    }
    if (!in_array($major, ['CY', 'CS', 'NE', 'CE', 'SE', 'IS', 'CC'])) {
        $errors[] = "Invalid major.";
    }
    if (!is_numeric($yearJoined) || $yearJoined < 2000 || $yearJoined > date("Y")) {
        $errors[] = "Year joined must be a valid year between 2000 and " . date("Y") . ".";
    }

    if (empty($errors)) {
        // Hash password if provided
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $student['password'];

        // Update the database
        $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, username = ?, email = ?, password = ?, major = ?, mobile = ?, year_joined = ? WHERE student_id = ?");
        $stmt->execute([$firstName, $lastName, $username, $email, $hashedPassword, $major, $mobile, $yearJoined, $studentId]);

        $_SESSION['profile_update_success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
    <main class="container">
        <h1>Edit Profile</h1>
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li style="color: red;"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form action="edit_profile.php" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($student['username']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>

            <label for="password">New Password (Leave blank to keep current):</label>
            <input type="password" name="password">

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

            <label for="mobile">Mobile:</label>
            <input type="tel" name="mobile" value="<?= htmlspecialchars($student['mobile']) ?>" required>

            <label for="year_joined">Year Joined:</label>
            <input type="number" name="year_joined" value="<?= htmlspecialchars($student['year_joined']) ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </main>
</body>
</html>
