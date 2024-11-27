<?php
session_start();
include('db.php'); // Database connection





// Fetch the room ID from the URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

// If room does not exist, show an error message
if (!$room) {
    die("Invalid room ID");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Default to Guest if not logged in
    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
    $booking_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : '';

    // Validate that all fields are filled out
    if ($contact_number && $booking_date && $duration && $time_slot) {
        $start_time = $booking_date . ' ' . $time_slot;
        $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));

        // Insert the booking into the database
        $stmt = $pdo->prepare("INSERT INTO bookings (username, RoomID, StartTime, EndTime, contact_number) 
                               VALUES (:username, :room_id, :start_time, :end_time, :contact_number)");
        $stmt->execute([
            'username' => $username,
            'room_id' => $room_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'contact_number' => $contact_number
        ]);

        // Redirect to a success page after booking
        header('Location: success.php'); // Replace with your success page
        exit();
    } else {
        $error_message = "Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            text-align: center;
            color: #0066cc;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"],
        input[type="date"],
        input[type="tel"],
        select,
        button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #005bb5;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></h1>
        <p>Capacity: <?php echo htmlspecialchars($room['capacity']); ?> persons</p>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- You don't need the hidden input for room_id anymore, it's fetched directly -->
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" pattern="^\+973\d{8}$" placeholder="Enter your contact number" required>
                <small>Example: +973 33311222</small>
            </div>

            <div class="form-group">
                <label for="booking_date">Booking Date:</label>
                <input type="date" id="booking_date" name="booking_date" required>
            </div>

            <div class="form-group">
                <label for="duration">Booking Duration:</label>
                <select id="duration" name="duration" required>
                    <option value="1">60 minutes</option>
                    <option value="1.5">90 minutes</option>
                </select>
            </div>

            <div class="form-group">
                <label for="time_slot">Available Time Slots:</label>
                <select id="time_slot" name="time_slot" required>
                    <option value="" disabled selected>Select a time slot</option>
                    <option value="09:00:00">09:00 AM</option>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="11:00:00">11:00 AM</option>
                    <option value="12:00:00">12:00 PM</option>
                </select>
            </div>

            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
