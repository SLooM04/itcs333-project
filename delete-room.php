<?php
session_start();
require 'db.php'; // Database connection file

// Success/error message
$message = '';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the room name is provided
    if (!empty($_POST['room_name'])) {
        $room_name = $_POST['room_name'];

        try {
            // Prepare the delete query
            $query = "DELETE FROM rooms WHERE room_name = :room_name";
            $stmt = $pdo->prepare($query);

            // Bind the room name
            $stmt->bindParam(':room_name', $room_name, PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Check if a row was deleted
            if ($stmt->rowCount() > 0) {
                $message = "Room '$room_name' has been deleted successfully!";
            } else {
                $message = "No room found with the name '$room_name'.";
            }
        } catch (PDOException $e) {
            $message = "Error while deleting the room: " . $e->getMessage();
        }
    } else {
        $message = "Please enter the room name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Room</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Light blue */
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007BFF; /* Blue */
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            background: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
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
            margin: 20px auto; /* Centers the button horizontally */
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function confirmDeletion() {
            return confirm('Are you sure you want to delete this room?');
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Delete Room</h1>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST" onsubmit="return confirmDeletion()">
        <input type="text" name="room_name" placeholder="Enter Room Name" required>
        <button type="submit">Delete Room</button>
    </form>

    <!-- Back to Dashboard button -->
    <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>