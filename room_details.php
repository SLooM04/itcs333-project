<?php
session_start();
require 'db.php'; // Include the DB connection file

// Check if room ID is provided in the URL
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Fetch room details from the database
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "Room not found.";
        exit();
    }
} else {
    echo "No room selected.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        main {
            display: grid;
            min-height: 100vh;
            padding: 80px 20px 20px 20px;
        }

        nav {
            background-color: #003366;
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            margin: 0 15px;
            border-radius: 5px;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 10px 20px;
            position: relative;
            z-index: 10;
            font-size: 1em;
        }

        .room-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .room-details h2 {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<nav class="menu">
    <a href="Home.php" class="button">Home</a>
    <a href="rooms.php" class="button">Rooms</a>
    <a href="profile.php" class="button">Profile</a>
    <a href="logout.php" class="button">Logout</a>
</nav>

<main>
    <div class="room-details">
        <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
        <p><strong>Available Timeslot:</strong> <?php echo htmlspecialchars($room['available_timeslot']); ?></p>
        <p><strong>Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>
    </div>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> ITCS333 Project | All rights reserved.</p>
    <div>
        <a href="https://www.facebook.com/uobedubh/" target="_blank">Facebook</a>
        <a href="https://x.com/uobedubh" target="_blank">Twitter</a>
        <a href="https://www.instagram.com/uobedubh/" target="_blank">Instagram</a>
    </div>
</footer>

</body>
</html>
