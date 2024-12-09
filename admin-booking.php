<?php
session_start();
include('db.php'); // Database connection


if (!isset($_SESSION['role'])) {
    die("You must be logged in to book a room.");
}

// Fetch the room ID and room name from the URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
$room_name = isset($room['room_name']) ? $room['room_name'] : null;



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
    $room_id = $_POST['room_id'] ?? null;
    $person_type = $_POST['person_type'] ?? null;
    $person_id = $_POST['person_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? null;
    $duration = $_POST['duration'] ?? null;
    $time_slot = $_POST['time_slot'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;
    $username = $_POST['username'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    $room_name = isset($room['room_name']) ? $room['room_name'] : null;


    // Validate that all fields are filled
    if ($contact_number && $booking_date && $duration && $time_slot) {
        // Get the current datetime
        $current_datetime = new DateTime();
        $start_datetime = new DateTime("$booking_date $time_slot"); 

        // Check if the selected time is too close to the current time
        $interval = $current_datetime->diff($start_datetime);

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
                // Determine student_id and teacher_id based on the person_type
if ($person_type === 'student') {
    $student_id = $person_id; // Assign person_id to student_id
    $teacher_id = null;       // teacher_id is null for students
} elseif ($person_type === 'teacher') {
    $teacher_id = $person_id; // Assign person_id to teacher_id
    $student_id = null;       // student_id is null for teachers
} else {
    $student_id = null;
    $teacher_id = null;
}

// Prepare and execute the query
$stmt = $pdo->prepare("
    INSERT INTO bookings (room_id, room_name, student_id, teacher_id, username, start_time, end_time, contact_number) 
    VALUES (:room_id, :room_name, :student_id, :teacher_id, :username, :start_time, :end_time, :contact_number)
");
$stmt->execute([
    ':room_id' => $room_id,
    ':room_name' => $room_name,
    ':student_id' => $student_id, // Determined earlier
    ':teacher_id' => $teacher_id, // Determined earlier
    ':username' => $_POST['username'], // From form input
    ':start_time' => $start_datetime->format('Y-m-d H:i:s'), // Assuming $start_datetime is a DateTime object
    ':end_time' => $end_time, // Ensure $end_time is properly defined
    ':contact_number' => $_POST['contact_number'], // From form input
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
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
            font-size: 1.8em;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
            color: #555;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="date"],
        form input[type="time"],
        form select,
        form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form select:focus,
        form input[type="time"]:focus,
        form input[type="date"]:focus {
            outline-color: #4a90e2; /* Updated color */
            border-color: #4a90e2; /* Updated color */
        }
        form button {
            background-color: #4a90e2; /* Updated color */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 1.2em;
            border-radius: 5px;
        }
        form button:hover {
            background-color: #357ab7; /* Updated hover color */
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section:last-child {
            margin-bottom: 0;
        }
    </style>
    <script>
        // Toggle visibility of person ID field
        function togglePersonInput() {
            const personType = document.getElementById('person_type').value;
            const personInputSection = document.getElementById('person_input_section');

            if (personType) {
                personInputSection.style.display = 'block';
            } else {
                personInputSection.style.display = 'none';
            }
        }

        // Toggle visibility of the start time and duration fields based on the selected date
        function toggleTimeSelection() {
            const startDate = document.getElementById('start_date').value;
            const durationContainer = document.getElementById('duration_container');
            const startHourContainer = document.getElementById('start_hour_container');

            if (startDate) {
                durationContainer.style.display = "block";
            } else {
                durationContainer.style.display = "none";
                startHourContainer.style.display = "none";
            }
        }

        // Display the start hour selection based on selected duration
        function toggleStartHour() {
            const duration = document.getElementById('duration').value;
            const startHourContainer = document.getElementById('start_hour_container');

            if (duration) {
                startHourContainer.style.display = "block";
            } else {
                startHourContainer.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <main class="container">
        <h1>Book a Room</h1>
        <form method="POST" action="">
            <div class="form-section">
                <label for="room_id">Room ID:</label>
                <input type="number" id="room_id" name="room_id" required>
            </div>

            <div class="form-section">
                <label for="person_type">Select Person Type:</label>
                <select id="person_type" name="person_type" onchange="togglePersonInput()" required>
                    <option value="">-- Select --</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>

            <div class="form-section" id="person_input_section" style="display:none;">
                <label for="person_id">Person ID:</label>
                <input type="number" id="person_id" name="person_id">
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
                        for (var hour = 8; hour < 22; hour++) {
                            var startHour = hour;
                            var endHour = hour + 1; // End time is the next hour
                            var startTime = (startHour < 12 ? (startHour < 10 ? "0" + startHour : startHour) + ":00 AM" : (startHour - 12) + ":00 PM");
                            var endTime = (endHour < 12 ? (endHour <= 10 ? "0" + endHour : endHour) + ":00 AM" : (endHour - 12) + ":00 PM");

                            var option = document.createElement("option");
                            option.value = startHour + ":00:00";
                            option.textContent = startTime + " - " + endTime;
                            timeSlotSelect.appendChild(option);
                        }
                    } else if (duration == "1.5") {
                        for (var hour = 8; hour < 22; hour++) {
                            var startHour = hour;
                            var endHour = hour + 1.5; 

                    
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
    <script>
    function openRoomDetails() {
        const roomId = "<?php echo $room_id; ?>"; // Pass the room ID dynamically 1
        const roomDetailsUrl = `room_details.php?id=${roomId}`;
        window.open(roomDetailsUrl, '_blank'); // Open in a new tab
    }
    </script>

<script>
    <?php if ($success_message): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $success_message; ?>',
            timer:"4000",
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'admin-dashboard.php';      
          });
    <?php elseif ($error_message): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $error_message; ?>',
            timer:"4000",
            confirmButtonText: 'Try Again'
        });
    <?php endif; ?>
    </script>

            <div class="form-section">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>

            <div class="form-section">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-section">
                <button type="submit">Book Now</button>
            </div>
            <button style="margin-top:10px; padding:10px 20px;background-color:#b9c6d6;color:white;border:none;border-radius:5px;cursor:pointer;font-size:16px;" onclick="window.history.back()">Go Back</button>

        </form>
    </main>
</body>
</html>
