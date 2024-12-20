<?php
session_start();
require 'db.php';

$bookings = [];
$message = '';
$show_no_bookings_message = false;
$show_message = false;

// Handling search by teacher_id or student_id
if (isset($_POST['search'])) {
    $user_id = $_POST['user_id'];
    $user_type = $_POST['user_type'];

    // Query based on teacher or student ID
    if ($user_type == 'teacher') {
        $query = "SELECT b.booking_id, b.room_id, b.room_name, b.start_time, b.end_time, b.status
                  FROM bookings b
                  LEFT JOIN users t ON b.teacher_id = t.id
                  WHERE b.teacher_id = :user_id AND b.status != 'Cancelled' 
                  ORDER BY b.start_time ASC";
    } elseif ($user_type == 'student') {
        $query = "SELECT b.booking_id, b.room_id, b.room_name, b.start_time, b.end_time, b.status
                  FROM bookings b
                  LEFT JOIN users s ON b.student_id = s.id
                  WHERE b.student_id = :user_id AND b.status != 'Cancelled' 
                  ORDER BY b.start_time ASC";
    } else {
        $message = "Please select a valid user type.";
    }

    if (empty($message)) {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (empty($bookings)) {
            $show_no_bookings_message = true;
        }
    }
}

// Cancel booking
if (isset($_GET['cancel_booking_id'])) {
    $cancel_booking_id = $_GET['cancel_booking_id'];

    $delete_query = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindValue(':id', $cancel_booking_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['cancel_message'] = "Booking canceled successfully.";
    } else {
        $_SESSION['cancel_message'] = "Error canceling booking.";
    }

    $stmt->closeCursor();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['search']) && isset($_SESSION['cancel_message'])) {
    unset($_SESSION['cancel_message']);
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
        .success-message {
            color: green;
            font-size: 1.2em;
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

    <!-- Search Form for Teacher or Student -->
    <form method="POST" action="">
        <label for="user_type">Select User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="">Choose User Type</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <label for="user_id">Enter User ID:</label>
        <input type="number" id="user_id" name="user_id" required>

        <button type="submit" name="search">Search Bookings</button>
    </form>

    <!-- Display Success Message if booking was canceled -->
    <?php if (isset($_SESSION['cancel_message'])) { ?>
        <p class="success-message"><?php echo $_SESSION['cancel_message']; unset($_SESSION['cancel_message']); ?></p>
    <?php } ?>

    <!-- Display Error or Success Message -->
    <?php if (!empty($message) && !isset($_SESSION['cancel_message'])) { echo "<p class='error-message'>$message</p>"; } ?>

    <!-- Display No Bookings Message only after a search -->
    <?php if ($show_no_bookings_message) { ?>
        <p class="no-bookings-message">No bookings found for the specified user.</p>
    <?php } ?>

    <!-- Display Bookings Table if there are results -->
    <?php if (!empty($bookings)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Action</th> <!-- Cancel action -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) { ?>
                    <tr>
                        <td><?php echo $booking['room_name']; ?></td>
                        <td><?php echo $booking['start_time']; ?></td>
                        <td><?php echo $booking['end_time']; ?></td>
                        <td><?php echo $booking['status']; ?></td>
                        <td class="cancel" onclick="confirmCancel(<?= $booking['booking_id']; ?>)">Cancel</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
