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
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #1a3d7c;
            margin-bottom: 20px;
            font-size: 1.8em;
        }
        form label {
            display: block;
            margin-bottom: 10px;
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
            padding: 12px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form select:focus,
        form input[type="time"]:focus,
        form input[type="date"]:focus {
            outline-color: #1a3d7c;
            border-color: #1a3d7c;
        }
        form button {
            background-color: #1a3d7c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
        }
        form button:hover {
            background-color: #134a7f;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section:last-child {
            margin-bottom: 0;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .form-group > div {
            flex: 1;
        }
        .form-group label {
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
        }
    </style>
    <script>
        // Function to toggle visibility of the start time and duration fields based on the selected date
        function toggleTimeSelection() {
            const start_date = document.getElementById('start_date').value;
            const durationContainer = document.getElementById('duration_container');
            const start_hourContainer = document.getElementById('start_hour_container');

            if (start_date) {
                durationContainer.style.display = "block";
            } else {
                durationContainer.style.display = "none";
                start_hourContainer.style.display = "none";
            }
        }

        // Function to display the start hour selection based on selected duration
        function toggleStartHour() {
            const duration = document.getElementById('duration').value;
            const start_hourContainer = document.getElementById('start_hour_container');

            if (duration) {
                start_hourContainer.style.display = "block";
            } else {
                start_hourContainer.style.display = "none";
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
                <label id="person_label">Person ID:</label>
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
