<!DOCTYPE html>
<html lang="en">
<head>
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

            <div class="form-section">
                <label for="start_date">Select Date:</label>
                <input type="date" id="start_date" name="start_date" onchange="toggleTimeSelection()" required>
            </div>

            <div class="form-section" id="duration_container" style="display:none;">
                <label for="duration">Select Duration:</label>
                <select id="duration" name="duration" onchange="toggleStartHour()" required>
                    <option value="">-- Select Duration --</option>
                    <option value="60">60 Minutes</option>
                    <option value="90">90 Minutes</option>
                </select>
            </div>

            <div class="form-section" id="start_hour_container" style="display:none;">
                <label for="start_hour">Select Start Hour:</label>
                <input type="time" id="start_hour" name="start_hour" required>
            </div>

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
        </form>
    </main>
</body>
</html>
