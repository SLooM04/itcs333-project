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
        }
        form input:focus {
            border-color: #4a90e2; /* Updated color */
            outline-color: #4a90e2; /* Updated color */
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4a90e2; /* Updated color */
            color: white;
        }
        a {
            color: #4a90e2; /* Updated color */
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #357ab7; /* Updated hover color */
        }
        .message {
            font-size: 1em;
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cancel Room Booking</h1>
        <form method="POST" action="">
            <label for="room_id">Room ID:</label>
            <input type="number" id="room_id" name="room_id" placeholder="Enter Room ID" required>

            <button type="submit" name="search">Search Bookings</button>
        </form>

        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

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
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                            <td><?php echo $booking['student_id'] 
                                ? 'Student ' . htmlspecialchars($booking['student_id']) 
                                : 'Teacher ' . htmlspecialchars($booking['teacher_id']); ?></td>
                            <td>
                                <a href="cancel_booking.php?id=<?php echo htmlspecialchars($booking['id']); ?>" 
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

    </div>

</body>
</html>
