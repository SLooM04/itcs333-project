<?php
session_start();
require 'db.php'; // Include the DB connection file

// Function to fetch rooms from the database based on department
function fetchRooms($department = null) {
    global $pdo;
    
    // If department is provided, fetch rooms by department
    if ($department) {
        $sql = "SELECT * FROM rooms WHERE department = :department";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['department' => $department]);
    } else {
        // Fetch all rooms if no department is specified
        $sql = "SELECT * FROM rooms";
        $stmt = $pdo->query($sql);
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch rooms if a department is selected
$rooms = [];
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $rooms = fetchRooms($department); // Fetch rooms by department
} else {
    $rooms = fetchRooms(); // Fetch all rooms
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>
    <style>
        /* Basic Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin-top: 10%;
            padding: 0;
            text-align: center;
            position: relative;
        }

        .container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .department {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            width: 200px;
            background-color: #004488;
            color: white;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .department:hover {
            background-color: #0055a5;
        }

        .rooms {
            display: block;
            margin-top: 30px;
            text-align: center;
        }

        .room-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px auto;
            max-width: 800px;
        }

        .room {
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .room figure {
            margin: 0;
        }

        .room img {
            width: 100%;
            height: auto;
            display: block;
        }

        .room figcaption {
            padding: 15px;
            text-align: left;
        }

        .room h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .room p {
            margin: 5px 0;
            font-size: 1em;
            color: #555;
        }

    </style>
</head>

<body>

    <!-- Department Selection -->
    <div class="container">
        <div class="department" onclick="showRooms('Information Systems')">Information Systems</div>
        <div class="department" onclick="showRooms('Computer Science')">Computer Science</div>
        <div class="department" onclick="showRooms('Network Engineering')">Network Engineering</div>
    </div>

    <!-- Room Selection (Dynamic Content) -->
    <div id="rooms" class="rooms">
        <div id="floors" class="floor"></div>
        <div id="roomSelection" class="room-gallery">
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <figure>
                            <!-- Display image if available -->
                            <?php if ($room['S44-106-1.jpg']): ?>
                                <img src="images/<?php echo htmlspecialchars($room['S44-106-1.jpg']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                            <?php else: ?>
                                <img src="images/default.jpg" alt="Room Image">
                            <?php endif; ?>
                            <figcaption>
                                <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                                <p><strong>Available Timeslot:</strong> <?php echo htmlspecialchars($room['available_timeslot']); ?></p>
                                <p><strong>Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($room['department']); ?></p>
                            </figcaption>
                        </figure>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected department.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showRooms(department) {
            // Fetch rooms data based on the selected department
            window.location.href = '?department=' + department;
        }
    </script>

</body>

</html>
