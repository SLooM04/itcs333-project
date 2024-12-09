<?php
session_start();
require 'db.php';

$bookings = [];
$message = '';

// Handle search and cancel booking actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $user_type = $_POST['user_type'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    // Fetch bookings based on user type and ID
    if ($user_type && $user_id) {
        $column = ($user_type === 'teacher') ? 'teacher_id' : 'student_id';
        $stmt = $pdo->prepare("SELECT b.id AS booking_id, r.id AS room_id, r.room_name, b.start_time, b.end_time
                               FROM bookings b
                               JOIN rooms r ON b.room_id = r.id
                               WHERE b.$column = ?");
        $stmt->execute([$user_id]);
        $bookings = $stmt->fetchAll();

        if (count($bookings) == 0) {
            $message = "This $user_type with ID $user_id has no bookings.";
        }
    }
}

// Handle cancel booking action (after confirmation)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['cancel_booking_id'])) {
    $booking_id = $_GET['cancel_booking_id'];

    // Delete the booking from the database
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);

    // Redirect after cancellation
    header('Location: cancel_booking.php');
    exit();
}

// Handle unblocking room action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unblock_room'])) {
    $room_id = $_POST['room_id'] ?? null;
    
    if ($room_id) {
        // Remove the block from the room
        $stmt = $pdo->prepare("DELETE FROM room_blocks WHERE room_id = ?");
        $stmt->execute([$room_id]);
        $message = "The block has been removed for room ID $room_id.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #1a3d7c;
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        h2 {
            color: #4a90e2;
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        form input[type="number"],
        form input[type="text"],
        form select,
        button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        form input[type="number"],
        form input[type="text"],
        form select {
            background-color: #f0f2f5;
        }
        button {
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #357ab7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        table, th, td {
            border: 1px solid #e0e0e0;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4a90e2;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        .action-link {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }
        .action-link:hover {
            color: #c9302c;
        }
        .message {
            padding: 12px;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            margin-top: 20px;
            color: #ff0000;
            font-size: 1.2em;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cancel Booking</h1>

        <!-- Search Form for Teacher/Student -->
        <form method="POST" action="">
            <label for="user_type">Select User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="">Choose User Type</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>

            <label for="user_id">Enter ID:</label>
            <input type="number" id="user_id" name="user_id" required>

            <button type="submit" name="search">Search Bookings</button>
        </form>

        <!-- Message Display -->
        <?php if (!empty($message)) { echo "<div class='message'>$message</div>"; } ?>

        <!-- Display Bookings Table if there are results -->
        <?php if (!empty($bookings)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Room ID</th>
                        <th>Room Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking) { ?>
                        <tr>
                            <td><?php echo $booking['room_id']; ?></td>
                            <td><?php echo $booking['room_name']; ?></td>
                            <td><?php echo $booking['start_time']; ?></td>
                            <td><?php echo $booking['end_time']; ?></td>
                            <td>
                                <!-- Link to cancel booking -->
                                <a href="cancel_booking.php?cancel_booking_id=<?php echo $booking['booking_id']; ?>" 
                                   onclick="return confirm('Are you sure you want to cancel this booking?')" class="action-link">Cancel Booking</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <h2>Unblock Room</h2>

        <!-- Form for unblocking room by Room ID -->
        <form method="POST" action="">
            <label for="room_id">Enter Room ID to Unblock:</label>
            <input type="number" id="room_id" name="room_id" required>

            <button type="submit" name="unblock_room">Unblock Room</button>
        </form>
        <button style="margin-top:10px; padding:10px 20px;background-color:#b9c6d6;color:white;border:none;border-radius:5px;cursor:pointer;font-size:16px;" onclick="window.history.back()">Go Back</button>

    </div>

</body>
</html>
