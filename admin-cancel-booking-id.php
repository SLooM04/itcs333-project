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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
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
        form input[type="number"], form input[type="text"], form select {
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

        <?php if (!empty($message)) { echo "<p style='color: red;'>$message</p>"; } ?>

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
