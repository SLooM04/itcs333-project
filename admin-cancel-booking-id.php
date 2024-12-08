<?php
session_start();
require 'db.php';

// Handling the search request
$bookings = [];
$message = '';

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    $user_type = $_GET['role'];

    // Query to fetch bookings based on user type and ID
    if ($user_type == 'teacher') {
        $query = "SELECT * FROM bookings WHERE teacher_id = :user_id AND start_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC";
    } elseif ($user_type == 'student') {
        $query = "SELECT * FROM bookings WHERE teacher_id = :user_id AND start_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC";
    } else {
        $message = "Please select a valid user type.";
    }

    if (empty($message)) {
        // Prepare the query
        $stmt = $pdo->prepare($query);

        // Bind the user_id parameter using PDO
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch all results
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the statement
        $stmt->closeCursor();
    }

}

else if (isset($_POST['search'])) {
    $user_type = $_POST['user_type'];
    $user_id = $_POST['user_id'];

    // Query to fetch bookings based on user type and ID
    if ($user_type == 'teacher') {
        $query = "SELECT * FROM bookings WHERE teacher_id = :user_id AND start_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC";
    } elseif ($user_type == 'student') {
        $query = "SELECT * FROM bookings WHERE teacher_id = :user_id AND start_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC";
    } else {
        $message = "Please select a valid user type.";
    }

    if (empty($message)) {
        // Prepare the query
        $stmt = $pdo->prepare($query);

        // Bind the user_id parameter using PDO
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch all results
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the statement
        $stmt->closeCursor();
    }
}

// Cancel booking
// if (isset($_GET['cancel_booking_id'])) {
//     $cancel_booking_id = $_GET['cancel_booking_id'];

//     $delete_query = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :id";
//     $stmt = $pdo->prepare($delete_query);

//     // Bind the booking_id parameter using PDO
//     $stmt->bindValue(':id', $cancel_booking_id, PDO::PARAM_INT);

//     if ($stmt->execute()) {
//         $message = "Booking canceled successfully.";
//     } else {
//         $message = "Error canceling booking.";
//     }

//     // Close the statement
//     $stmt->closeCursor();
// }
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

        <!-- Display Error or Success Message -->
        <?php if (!empty($message)) { echo "<p class='error-message'>$message</p>"; } ?>

        <!-- Display Bookings Table if there are results -->
        <?php if (!empty($bookings)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Room ID</th>
                        <th>Room Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
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
                            <td><?php echo $booking['status']; ?></td>
                            <td class="cancel" onclick="confirmCancel(<?= $booking['booking_id']; ?>, <?= $user_id; ?>, '<?= $user_type; ?>')">Cancel</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <script>
                function confirmCancel(bookingId,userId,role) {
                    // Ask for confirmation
                    if (confirm("Are you sure you want to cancel this booking?")) {
                        // Send a request to cancel the booking
                        window.location.href = "cancel_booking_admin.php?booking_id=" + bookingId + "&user_id=" + userId + "&role=" + role;
                    }
                }
            </script>
        <?php } ?>

    </div>

</body>
</html>
