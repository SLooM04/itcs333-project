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
            color: #4a90e2; /* Updated color */
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
            outline-color: #4a90e2; /* Updated color */
            border-color: #4a90e2; /* Updated color */
        }
        button {
            background-color: #4a90e2; /* Updated color */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
        }
        button:hover {
            background-color: #357ab7; /* Updated hover color */
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
            background-color: #4a90e2; /* Updated color */
            color: white;
        }
        a {
            color: #4a90e2; /* Updated color */
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #357ab7; /* Updated hover color */
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
