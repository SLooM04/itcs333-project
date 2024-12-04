<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Check if the room exists
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $room = $stmt->fetch();

        if ($room) {
            // Delete the room from the database
            $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt->execute([$id]);

            $message = "Room with ID $id has been deleted successfully.";
        } else {
            $error = "Room with ID $id does not exist.";
        }
    } else {
        $error = "Please enter a valid Room ID.";
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        main.container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #002244;
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #002244;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #004080;
        }

        .success {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Delete Room</h1>
        <form method="POST">
            <label for="id">Enter Room ID to Delete:</label>
            <input type="number" id="id" name="id" required>
            <button type="submit" class="primary">Delete Room</button>
        </form>

        <?php if (isset($message)): ?>
            <p class="success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
