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
    // Get the current datetime
    $current_datetime = new DateTime();
    $start_datetime = new DateTime("$booking_date $time_slot"); 

    // Check if the selected time is too close to the current time
    $interval = $current_datetime->diff($start_datetime);

    // Ensure the booking time is true ( not in the past )
    if ($start_datetime < $current_datetime || $interval->h <= 0 && $interval->invert === 0) {
        $error_message = "The selected time cannot be in the past.";
    } else {
        // Proceed with the booking logic (existing code)
        if ($duration == '1.5') {
            $end_time = date('Y-m-d H:i:s', strtotime($start_datetime->format('Y-m-d H:i:s') . ' +90 minutes'));
        } else {
            $end_time = date('Y-m-d H:i:s', strtotime($start_datetime->format('Y-m-d H:i:s') . ' +60 minutes'));
        }

    
        // Check for conflicting bookings
        $stmt = $pdo->prepare("
            SELECT * FROM bookings
            WHERE room_id = :room_id
            AND (
                (:start_time < end_time AND :end_time > start_time)
            )
            AND status != 'Cancelled'
        ");
        $stmt->execute([
            'room_id' => $room_id,
            'start_time' => $start_datetime->format('Y-m-d H:i:s'),
            'end_time' => $end_time
        ]);
        $existing_booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_booking) {
            $error_message = "The selected time slot is already booked for this room.";
        } else {
            // Proceed with the booking
            $stmt = $pdo->prepare("
                INSERT INTO bookings (room_id, room_name, student_id, teacher_id, username, start_time, end_time, contact_number) 
                VALUES (:room_id, :room_name, :student_id, :teacher_id, :username, :start_time, :end_time, :contact_number)
            ");
            $stmt->execute([
                ':room_id' => $room_id,
                ':room_name' => $room_name,
                ':student_id' => ($user_role === 'student') ? $_SESSION['user_id'] : null,
                ':teacher_id' => ($user_role === 'teacher') ? $_SESSION['user_id'] : null,
                ':username' => $username,
                ':start_time' => $start_datetime->format('Y-m-d H:i:s'),
                ':end_time' => $end_time,
                ':contact_number' => $contact_number,
            ]);
            $success_message = "Your booking has been successfully completed!";
            }
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
    function isTimeSlotBooked($start_time, $end_time, $booked_slots)
    {
        foreach ($booked_slots as $slot) {
            if (
                ($start_time >= $slot['start_time'] && $start_time < $slot['end_time']) ||
                ($end_time > $slot['start_time'] && $end_time <= $slot['end_time']) ||
                ($start_time <= $slot['start_time'] && $end_time >= $slot['end_time'])
            ) {
                return true;
            }
        }
        return false;
    }

    // Function to generate available time slots based on the duration
// Function to generate available time slots based on the duration
    function generateTimeSlots($duration)
    {
        $time_slots = [];
        $start_hour = 8;  // Start from 8 AM
        $end_hour = 22;   // Until 10 PM

        if ($duration == '1') {
            // 60 minute time slots (every hour)
            for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                $start_time = sprintf("%02d:00", $hour);
                $end_time = sprintf("%02d:00", $hour + 1);
                $time_slots[] = ['start' => $start_time, 'end' => $end_time];
            }
        } elseif ($duration == '1.5') {
            // 90 minute time slots (every 1.5 hours)
            for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                // Create time slot from hour and half-hour
                $start_time = sprintf("%02d:00", $hour);
                $end_time = sprintf("%02d:30", $hour + 1);
                $time_slots[] = ['start' => $start_time, 'end' => $end_time];

                // Next slot (1.5 hours later)
                $next_hour = $hour + 1;
                if ($next_hour <= $end_hour) {
                    $next_start_time = sprintf("%02d:30", $hour);
                    $next_end_time = sprintf("%02d:00", $next_hour + 1);
                    $time_slots[] = ['start' => $next_start_time, 'end' => $next_end_time];
                }
            }
        }

        return $time_slots;
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
       
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');


body {
    font-family: 'Poppins', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}


.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 90%; 
    max-width: 600px; 
    box-sizing: border-box;
    margin: 10px auto; 
}


h1 {
    text-align: center;
    color: #0066cc;
    margin-bottom: 20px;
    font-size: 1.8rem;
}


label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
    font-size: 1rem;
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
    font-size: 1rem;
    margin-bottom: 20px;
    box-sizing: border-box;
}

/* Button styles */
button {
    background-color: #0066cc;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    padding: 10px 20px;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #005bb5;
}


.error-message {
    color: red;
    text-align: center;
    font-size: 1rem;
}

.success-message {
    color: green;
    text-align: center;
    font-size: 1rem;
}


a {
    color: #0066cc;
    text-decoration: underline;
    font-size: 0.9rem;
}


@media screen and (min-width: 768px) {
    body {
        padding: 20px;
    }

    .container {
        padding: 30px;
        max-width: 700px;
    }

    h1 {
        font-size: 2rem;
    }

    label {
        font-size: 1.1rem;
    }

    button {
        font-size: 1.1rem;
        padding: 12px 24px;
    }

    a {
        font-size: 1rem;
    }
}
    </style>
</head>

<body>

    <div class="container">
        <h1>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></h1>
        <p style="text-align: center; margin-top: 10px;">
    <a href="room_details.php?id=<?php echo $room_id; ?>" style="color: #0066cc; text-decoration: underline; font-size: 18px;">Room Details</a>
</p>

        <?php if ($success_message): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" pattern="^(?:\+973\s?)?\d{8}$"
                    placeholder="Enter your contact number" required>
                <small>Example: +973 33311222 </small>
            </div>

            <div class="form-group">
                <label for="booking_date">Booking Date:</label>
                <input type="date" id="booking_date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>"
                    onkeydown="return false;" onchange="checkWeekday(this)">
            </div>

            <div class="form-group">
                <label for="duration">Booking Duration:</label>
                <select id="duration" name="duration" required onchange="updateTimeSlots()">
                    <option value="1">60 minutes</option>
                    <option value="1.5">90 minutes</option>
                </select>
            </div>

            <div class="form-group">
                <label for="time_slot">Available Time Slots:</label>
                <select id="time_slot" name="time_slot" required>
                    <option value="" disabled selected>Select a time slot</option>
                </select>
            </div>

            <script>
                // Function to update time slots based on the selected duration
                function updateTimeSlots() {
                    var duration = document.getElementById("duration").value;
                    var timeSlotSelect = document.getElementById("time_slot");

                    // Clear the existing options in the time slots dropdown
                    timeSlotSelect.innerHTML = '<option value="" disabled selected>Select a time slot</option>';

                    if (duration == "1") {
                        // If 60 minutes is selected, show hourly slots from 8 AM to 10 PM
                        for (var hour = 8; hour <= 22; hour++) {
                            var startHour = hour;
                            var endHour = hour + 1; // End time is the next hour
                            var startTime = (startHour < 12 ? (startHour < 10 ? "0" + startHour : startHour) + ":00 AM" : (startHour - 12) + ":00 PM");
                            var endTime = (endHour < 12 ? (endHour < 10 ? "0" + endHour : endHour) + ":00 AM" : (endHour - 12) + ":00 PM");

                            var option = document.createElement("option");
                            option.value = startHour + ":00:00";
                            option.textContent = startTime + " - " + endTime;
                            timeSlotSelect.appendChild(option);
                        }
                    } else if (duration == "1.5") {
                        for (var hour = 8; hour < 22; hour++) {
                            var startHour = hour;
                            var endHour = hour + 1.5; // End time is 1.5 hours later

                            // Format start time (e.g., 8:00 AM, 9:30 AM)
                            var startTime = (startHour < 12 ? (startHour < 10 ? "0" + startHour : startHour) + ":00 AM" : (startHour - 12) + ":00 PM");
                            var endTime = ((endHour % 1 === 0.5) ? (Math.floor(endHour) + ":30") : (Math.floor(endHour)) + ":00");
                            endTime = (endHour < 12 ? endTime + " AM" : (Math.floor(endHour) - 12) + ":30 PM");

                            var option = document.createElement("option");
                            option.value = startHour + ":00:00";
                            option.textContent = startTime + " - " + endTime;
                            timeSlotSelect.appendChild(option);
                        }
                    }
                }

                // Call the function once to populate the time slots when the page is loaded
                window.onload = function () {
                    updateTimeSlots();
                };
            </script>

            <button type="submit">Confirm Booking</button>
            <p style="text-align: center; margin-top: 10px;">
                <a href="javascript:history.back()" style="color: #0066cc; text-decoration: underline; font-size: 14px;">Back
                    to Previous Page</a>
            </p>
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
        // Handle theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check for saved theme in localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.textContent = 'Light Mode';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');

            // Update button text and save preference
            if (body.classList.contains('dark-mode')) {
                themeToggle.textContent = 'Light Mode';
                localStorage.setItem('theme', 'dark');
            } else {
                themeToggle.textContent = 'Dark Mode';
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
    <script>
    function openRoomDetails() {
        const roomId = "<?php echo $room_id; ?>"; // Pass the room ID dynamically 1
        const roomDetailsUrl = `room_details.php?id=${roomId}`;
        window.open(roomDetailsUrl, '_blank'); // Open in a new tab
    }
</script>
</body>

</html>