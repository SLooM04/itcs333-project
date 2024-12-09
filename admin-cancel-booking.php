<?php
session_start();
require 'db.php'; // Database connection file

// Success/error message
$message = '';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cancel booking if booking_id is provided
    if (isset($_POST['cancel_booking_id'])) {
        $bookingId = $_POST['cancel_booking_id'];

        try {
            // Update booking status to 'cancelled'
            $query = "UPDATE bookings SET status = 'cancelled', updated_at = NOW() WHERE booking_id = :booking_id";
            $stmt = $pdo->prepare($query);

            // Bind value
            $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();

            // Check if a row was updated
            if ($stmt->rowCount() > 0) {
                $message = "The booking has been cancelled successfully!";
            } else {
                $message = "No booking found with the provided ID.";
            }
        } catch (PDOException $e) {
            $message = "Error while cancelling the booking: " . $e->getMessage();
        }
    }

    // Fetch bookings if room_name is provided
    if (isset($_POST['room_name'])) {
        $roomName = $_POST['room_name'];

        try {
            // Retrieve current bookings for the room
            $query = "SELECT booking_id, room_name, username, 
                      IFNULL(student_id, teacher_id) AS user_id,
                      CASE 
                          WHEN student_id IS NOT NULL THEN 'Student'
                          WHEN teacher_id IS NOT NULL THEN 'Teacher'
                          ELSE 'Unknown'
                      END AS user_type
                      FROM bookings
                      WHERE room_name = :room_name AND status != 'cancelled'
                      AND status != 'Confirmed'";
            $stmt = $pdo->prepare($query);

            // Bind value
            $stmt->bindParam(':room_name', $roomName, PDO::PARAM_STR);

            // Execute query
            $stmt->execute();

            // Fetch results
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = "Error while fetching bookings: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Light blue */
            color: #333;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #007BFF;
            color: #fff;
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
        function confirmCancellation() {
            return confirm('Are you sure you want to cancel this booking?');
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Manage Bookings</h1>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- Form for searching bookings by room name -->
    <form action="" method="POST">
        <input type="text" name="room_name" placeholder="Enter Room Name" required>
        <button type="submit">Search Bookings</button>
    </form>

    <?php if (isset($bookings) && count($bookings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Username</th>
                    <th>User ID</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_name']) ?></td>
                        <td><?= htmlspecialchars($booking['username']) ?></td>
                        <td><?= htmlspecialchars($booking['user_id']) ?></td>
                        <td><?= htmlspecialchars($booking['user_type']) ?></td>
                        <td>
                            <form action="" method="POST" onsubmit="return confirmCancellation()">
                                <input type="hidden" name="cancel_booking_id" value="<?= $booking['booking_id'] ?>">
                                <button type="submit">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($bookings)): ?>
        <p>No bookings found for this room.</p>
    <?php endif; ?>

    <!-- Back to Dashboard button -->
    <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>