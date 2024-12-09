<?php
session_start();
require 'db.php'; 

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? null;
    $email = $_POST['email'] ?? null;

    if ($first_name && $email) {
        $stmt = $pdo->prepare("DELETE FROM teachers WHERE first_name = ? AND email = ?");
        $stmt->execute([$first_name, $email]);

        if ($stmt->rowCount() > 0) {
            $message = "Teacher '$first_name' with email '$email' was deleted successfully.";
        } else {
            $message = "No teacher found with the given name and email.";
        }
    } else {
        $message = "Please provide both the teacher's first name and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Teacher</title>
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
            color: #4a90e2; /* Updated color */
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
            outline-color: #4a90e2; /* Updated color */
            border-color: #4a90e2; /* Updated color */
        }
        button {
            background-color: #4a90e2; /* Updated color */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
        }
        button:hover {
            background-color: #357ab7; /* Updated hover color */
        }
        .message {
            font-size: 1em;
            color: red;
            margin-top: 20px;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Teacher</h1>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter the teacher's first name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter the teacher's email" required>
            
            <button type="submit">Delete Teacher</button>
        </form>

        <!-- Display messages -->
        <?php if (!empty($message)) { ?>
            <p class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>
        <button style="margin-top:10px; padding:10px 20px;background-color:#b9c6d6;color:white;border:none;border-radius:5px;cursor:pointer;font-size:16px;" onclick="window.history.back()">Go Back</button>

    </div>
</body>
</html>
