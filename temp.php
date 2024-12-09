<?php
session_start();
require 'db.php';

$bookings = [];
$message = '';

// Handling search by room name
if (isset($_POST['search'])) {
    $room_name = $_POST['room_name'];

    // Query to fetch bookings based on room name
    $query = "SELECT b.booking_id, b.room_id, b.room_name, b.start_time, b.end_time, b.status, 
                     t.username AS teacher_name, s.username AS student_name 
              FROM bookings b
              LEFT JOIN users t ON b.teacher_id = t.id   -- Linking teacher_id to users.id
              LEFT JOIN users s ON b.student_id = s.id   -- Linking student_id to users.id
              WHERE b.room_name LIKE :room_name AND b.status != 'Cancelled' 
              ORDER BY b.start_time ASC";

    // Prepare the query
    $stmt = $pdo->prepare($query);

    // Bind the room name parameter using PDO
    $stmt->bindValue(':room_name', '%' . $room_name . '%', PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the statement
    $stmt->closeCursor();
}

// Cancel booking
if (isset($_GET['cancel_booking_id'])) {
    $cancel_booking_id = $_GET['cancel_booking_id'];

    $delete_query = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :id";
    $stmt = $pdo->prepare($delete_query);

    // Bind the booking_id parameter using PDO
    $stmt->bindValue(':id', $cancel_booking_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "Booking canceled successfully.";
    } else {
        $message = "Error canceling booking.";
    }

    // Close the statement
    $stmt->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Cancel Booking</title>
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
            color: #4a90e2;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        form label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        form input, form select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input:focus, form select:focus {
            outline-color: #4a90e2;
            border-color: #4a90e2;
        }
        button {
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
        }
        button:hover {
            background-color: #357ab7;
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
            background-color: #4a90e2;
            color: white;
        }
        .cancel {
            color: #4a90e2;
            text-decoration: none;
            font-weight: bold;
        }
        .cancel:hover {
            color: #357ab7;
            cursor: pointer;
        }
        .error-message {
            color: red;
            font-size: 1em;
            margin-bottom: 20px;
        }
        .no-bookings-message {
            color: green;
            font-size: 1.2em;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Search and Cancel Booking</h1>

    <!-- Search Form for Room Name -->
    <form method="POST" action="">
        <label for="room_name">Search Room by Name:</label>
        <input type="text" id="room_name" name="room_name" required>

        <button type="submit" name="search">Search Bookings</button>
    </form>

    <!-- Display Error or Success Message -->
    <?php if (!empty($message)) { echo "<p class='error-message'>$message</p>"; } ?>

    <!-- Display Bookings Table if there are results -->
    <?php if (!empty($bookings)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Teacher</th>
                    <th>Student</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) { ?>
                    <tr>
                        <td><?php echo $booking['room_name']; ?></td>
                        <td><?php echo $booking['start_time']; ?></td>
                        <td><?php echo $booking['end_time']; ?></td>
                        <td><?php echo $booking['status']; ?></td>
                        <td><?php echo $booking['teacher_name']; ?></td>
                        <td><?php echo $booking['student_name']; ?></td>
                        <td class="cancel" onclick="confirmCancel(<?= $booking['booking_id']; ?>)">Cancel</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p class="no-bookings-message">No bookings found for the specified room.</p>
    <?php } ?>

</div>

<script>
    function confirmCancel(bookingId) {
        if (confirm("Are you sure you want to cancel this booking?")) {
            window.location.href = "?cancel_booking_id=" + bookingId;
        }
    }
</script>

</body>
</html>
