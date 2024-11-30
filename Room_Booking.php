<?php
session_start();
include('db.php'); // Database connection

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if (!$username) {
    die("You must be logged in to book a room.");
}

// Fetch the room ID and room name from the URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
$room_name = isset($room['room_name']) ? $room['room_name'] : null;

// If room doesn't exist, show an error message
if (!$room) {
    die("Invalid room ID");
}

// Initialize message variables
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the user's role
    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

    if (!$user_role) {
        die("Error: You must be logged in to book a room.");
    }

    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
    $booking_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : '';  

    // Validate that all fields are filled
    if ($contact_number && $booking_date && $duration && $time_slot) {
        // Check if the selected date is in the past
        $current_date = date('Y-m-d');
        if ($booking_date < $current_date) {
            $error_message = "The selected booking date cannot be in the past.";
        } else {
            $start_time = $booking_date . ' ' . $time_slot;
            $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));

            // Prepare the query to insert the booking, including room_name
            $stmt = $pdo->prepare("
                INSERT INTO bookings (room_id, room_name, student_id, teacher_id, username, start_time, end_time, contact_number) 
                VALUES (:room_id, :room_name, :student_id, :teacher_id, :username, :start_time, :end_time, :contact_number)
            ");

            // Set the student_id or teacher_id based on the role
            $stmt->execute([
                ':room_id' => $room_id,
                ':room_name' => $room_name,  // Add room_name to the query
                ':student_id' => ($user_role === 'student') ? $_SESSION['user_id'] : null,  // Use student_id if role is student
                ':teacher_id' => ($user_role === 'teacher') ? $_SESSION['user_id'] : null,  // Use teacher_id if role is teacher
                ':username' => $username,  // Add username to the query
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':contact_number' => $contact_number,
            ]);

            // If insertion is successful
            $success_message = "Your booking has been successfully completed!";
        }
    } else {
        $error_message = "Please fill out all fields.";
    }
}

// Fetch already booked time slots for the selected date
$booked_slots = [];
if (isset($_POST['booking_date'])) {
    $booking_date = $_POST['booking_date'];
    $stmt = $pdo->prepare("SELECT start_time, end_time FROM bookings WHERE room_id = :room_id AND DATE(start_time) = :booking_date");
    $stmt->execute(['room_id' => $room_id, 'booking_date' => $booking_date]);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to check if a time slot overlaps with existing bookings
function isTimeSlotBooked($start_time, $end_time, $booked_slots) {
    foreach ($booked_slots as $slot) {
        if (($start_time >= $slot['start_time'] && $start_time < $slot['end_time']) ||
            ($end_time > $slot['start_time'] && $end_time <= $slot['end_time']) ||
            ($start_time <= $slot['start_time'] && $end_time >= $slot['end_time'])) {
            return true;
        }
    }
    return false;
}

// Function to generate available time slots based on the duration
function generateTimeSlots($duration, $booked_slots) {
    $time_slots = [];
    $start_hour = 8;  // Start from 8 AM
    $end_hour = 20;   // Until 8 PM

    if ($duration == '1') {
        // 60 minute time slots
        for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
            $start_time = sprintf("%02d:00", $hour);
            $end_time = sprintf("%02d:00", $hour + 1);
            // Check if this time slot is already booked
            if (!isTimeSlotBooked($start_time, $end_time, $booked_slots)) {
                $time_slots[] = $start_time;
            }
        }
    } elseif ($duration == '1.5') {
        // 90 minute time slots
        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            $start_time = sprintf("%02d:00", $hour);
            $end_time = sprintf("%02d:30", $hour + 1);
            // Check if this time slot is already booked
            if (!isTimeSlotBooked($start_time, $end_time, $booked_slots)) {
                $time_slots[] = $start_time;
            }
            $next_hour = $hour + 1;
            if ($next_hour <= $end_hour) {
                $next_start_time = sprintf("%02d:30", $hour);
                $next_end_time = sprintf("%02d:00", $next_hour + 1);
                if (!isTimeSlotBooked($next_start_time, $next_end_time, $booked_slots)) {
                    $time_slots[] = $next_start_time;
                }
            }
        }
    }
    return $time_slots;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></title>
    <style>
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
        .capacity {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0066cc; 
            color: white;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 100px;
            margin: 10px auto;
            width: 40%; 
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
        .success-message {
            color: green;
            text-align: center;
        }
        .disabled {
            color: #bbb;
            pointer-events: none;
        }
        .past-date {
            background-color: #f0f0f0;
            color: #ccc;
            pointer-events: none;
        }  
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></h1>
        <p class="capacity">Capacity: <?php echo htmlspecialchars($room['capacity']); ?> persons</p>

        <?php if ($success_message): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" 
                       pattern="^(?:\+973\s?)?\d{8}$" 
                       placeholder="Enter your contact number" required>
                <small>Example: +973 33311222 </small>
            </div>

            <div class="form-group">
                <label for="booking_date">Booking Date:</label>
                <input type="date" id="booking_date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>" 
                onkeydown="return false;" 
                onchange="checkWeekday(this)">
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

    <script>
        function checkWeekday(input) {
            var date = new Date(input.value);
            var day = date.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

            if (day === 5 || day === 6) {  // 5 = Friday, 6 = Saturday
                alert("The booking date cannot be Friday or Saturday.");
                input.value = ''; // Clear the input
            }
        }
    </script>
</body>
</html>
