<?php
session_start();
require 'db.php';

$message = '';

// Handle the deletion request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'] ?? null;

    if ($student_id) {
        $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);

        if ($stmt->rowCount() > 0) {
            $message = "Student with ID $student_id was deleted successfully.";
        } else {
            $message = "No student found with ID $student_id.";
        }
    } else {
        $message = "Student ID is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #4a90e2;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            font-size: 1.1em;
            margin-bottom: 8px;
            color: #555;
        }
        form input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form input:focus {
            outline-color: #4a90e2;
            border-color: #4a90e2;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            font-size: 1em;
            margin-top: 20px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .back-button {
            display: block;
            width: 200px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto;
            font-size: 16px;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Student</h1>
        <form method="POST">
            <label for="student_id">Student ID:</label>
            <input type="number" id="student_id" name="student_id" placeholder="Enter Student ID" required>
            <button type="submit">Delete Student</button>
        </form>

        <!-- Display success or error message -->
        <?php if (!empty($message)) { ?>
            <p class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>
        
        <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>