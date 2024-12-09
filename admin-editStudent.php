<?php
session_start();
require 'db.php';

$error = '';
$success = '';
$student = null;

// Handle finding student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if (!$student) {
        $error = "Student not found.";
    }
}

// Handle updating student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $major = $_POST['major'];
    $mobile = $_POST['mobile'];
    $level = $_POST['level'];

    $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, username = ?, major = ?, mobile = ?, level = ?, updated_at = NOW() WHERE student_id = ?");
    $stmt->execute([$first_name, $last_name, $email, $username, $major, $mobile, $level, $student_id]);

    $success = "Student updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #0073e6;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
            text-align: left;
            display: block;
        }
        form input, button {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        form input:focus, form select:focus {
            border-color: #0073e6;
            outline-color: #0073e6;
        }
        button {
            background-color: #0073e6;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            padding: 15px;
        }
        button:hover {
            background-color: #005bb5;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
        .success {
            color: green;
            margin-top: 20px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-group label {
            width: 45%;
        }
        .form-group input,
        .form-group select {
            width: 50%;
        }
        .back-button, .dashboard-button {
            background-color: #28a745; /* Green background for dashboard button */
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .back-button:hover, .dashboard-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Edit Student</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Form to find student -->
        <form method="POST">
            <label for="student_id">Enter Student ID:</label>
            <input type="number" id="student_id" name="student_id" placeholder="Enter student ID" required>
            <button type="submit">Find Student</button>
            <?php if (!$student): ?>
                <button type="button" class="back-button" onclick="window.history.back()">Back to Dashboard</button>
            <?php endif; ?>
        </form>

        <!-- Form to edit student -->
        <?php if ($student): ?>
            <form method="POST">
                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($student['username']) ?>" required>

                <div class="form-group">
                    <div>
                        <label for="level">Level</label>
                        <select id="level" name="level" required>
                            <option value="Freshman">Freshman (1st Year)</option>
                            <option value="Sophomore">Sophomore (2nd Year)</option>
                            <option value="Junior">Junior (3rd Year)</option>
                            <option value="Senior">Senior (last Year)</option>
                            <option value="Post">Postgraduate</option>
                        </select>
                    </div>

                    <div>
                        <label for="major">Major</label>
                        <select id="major" name="major" required>
                            <option value="CY">Cybersecurity (CY)</option>
                            <option value="CS">Computer Science (CS)</option>
                            <option value="NE">Network Engineering (NE)</option>
                            <option value="CE">Computer Engineering (CE)</option>
                            <option value="SE">Software Engineering (SE)</option>
                            <option value="IS">Information Systems (IS)</option>
                            <option value="CC">Cloud Computing (CC)</option>
                        </select>
                    </div>
                </div>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($student['mobile']) ?>" required>

                <button type="submit" name="update">Update Student</button>
                <a href="dashboard.php" class="dashboard-button">Back to Dashboard</a>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>