<?php
session_start();
require 'db.php';

$bookings = []; // To store the booking results

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $room_id = $_POST['room_id'] ?? null;

    // Query to fetch bookings based on the room ID
    if ($room_id) {
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE room_id = ?");
        $stmt->execute([$room_id]);
        $bookings = $stmt->fetchAll();

        // Check if there are no bookings for the room
        if (count($bookings) == 0) {
            $message = "No bookings found for this room.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Room Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #1a3d7c;
        }
        form label, form input, table {
            display: block;
            margin-bottom: 10px;
        }
        form input[type="number"] {
            padding: 10px;
            width: 100%;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            background-color: #1a3d7c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #134a7f;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cancel Room Booking</h1>
        <form method="POST" action="">
            <label for="room_id">Room ID:</label>
            <input type="number" id="room_id" name="room_id" required>

            <button type="submit" name="search">Search Bookings</button>
        </form>

        <?php if (isset($message)) { echo "<p style='color: red;'>$message</p>"; } ?>

        <?php if (!empty($bookings)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Booked By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking) { ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo $booking['room_id']; ?></td>
                            <td><?php echo $booking['start_time']; ?></td>
                            <td><?php echo $booking['end_time']; ?></td>
                            <td><?php echo $booking['student_id'] ? 'Student ' . $booking['student_id'] : 'Teacher ' . $booking['teacher_id']; ?></td>
                            <td>
                                <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

    </div>

</body>
</html>
