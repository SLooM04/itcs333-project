<?php
session_start();
require 'db.php'; // Replace with your database connection file

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: combined_login.php'); // Redirect to login page
    exit();
}

// Initialize variables
$credits = null;
$message = '';
$isTeacher = false;

// Check if the user is in the teachers table
$userId = $_SESSION['user_id'];
$sql = "SELECT booking_credit FROM teachers WHERE teacher_id = ?";  // Changed 'students' to 'teachers' and 'student_id' to 'teacher_id'
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if ($teacher) {
    $credits = $teacher['booking_credit'];  // Access credits for teachers
    $isTeacher = true;
}

// Restrict access to non-teachers
if (!$isTeacher) {
    header('Location: combined_login.php'); // Redirect to an unauthorized access page
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Status</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f0f8ff, #dcefff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Credit container styling */
        .credit-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 450px;
            width: 100%;
        }

        /* Header styles */
        .credit-container h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #4a90e2;
        }

        /* Status paragraph */
        .credit-container p {
            font-size: 1.2em;
            margin: 10px 0;
            color: #555;
        }

        /* Button styles */
        .btn-book {
            display: inline-block;
            padding: 12px 25px;
            background: #4a90e2;
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(74, 144, 226, 0.5);
            transition: background 0.3s ease;
        }

        .btn-book:hover {
            background: #357ab8;
        }

        .btn-disabled {
            padding: 12px 25px;
            background: #ccc;
            color: #666;
            font-size: 1.1em;
            border-radius: 8px;
            cursor: not-allowed;
            box-shadow: 0 3px 10px rgba(204, 204, 204, 0.5);
        }

        /* Red message for empty credits */
        .credit-container .error-message {
            margin-top: 20px;
            font-weight: bold;
            color: red;
            font-size: 1.1em;
        }

        /* Purchase credits section */
        .purchase-section {
            margin-top: 30px;
        }

        .purchase-section a {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.5);
            transition: background 0.3s ease;
        }

        .purchase-section a:hover {
            background: #218838;
        }
        
        .centered-iframe {
            position: fixed; /* Ensures it stays on top and does not move with scrolling */
            top: 2%; /* Vertically centers the iframe */
            left: 38%; /* Horizontally centers the iframe */
            z-index: 9999; /* Keeps it on top of all other elements */
        }

        .centered-iframeG {
            position: fixed; /* Ensures it stays on top and does not move with scrolling */
            top: 1%; /* Vertically centers the iframe */
            left: 38%; /* Horizontally centers the iframe */
            z-index: 9999; /* Keeps it on top of all other elements */
        }
        
        /* Go Back button styling */
        .go-back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff6347;
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(255, 99, 71, 0.5);
            transition: background 0.3s ease;
        }

        .go-back-btn:hover {
            background: #e55347;
        }
    </style>
</head>

<body>
<?php if ($credits > 0): ?>
        <iframe 
            class="centered-iframe" 
            src="coinStatus.php" 
            width="280" 
            height="210" 
            frameborder="0" 
            scrolling="no" 
            allowfullscreen>
        </iframe>
    <?php else: ?>
        <iframe 
            class="centered-iframeG" 
            src="coin-GrayStatus.php" 
            width="280" 
            height="210" 
            frameborder="0" 
            scrolling="no" 
            allowfullscreen>
        </iframe>
    <?php endif; ?>

    <div class="credit-container">
        <h1>Credit Status</h1>
        <p><strong>Status:</strong> 
            <?php 
            if ($credits > 0) {
                echo "You have " . $credits . " credit(s) available.";
            } else {
                echo "No Credits.";
            }
            ?>
        </p>

        <!-- Display appropriate action based on credits -->
        <?php if ($credits > 0): ?>
            <a href="rooms.php" class="btn-book">Book a Room Now</a>
        <?php else: ?>
            <button type="button" disabled class="btn-disabled">Book Now</button>
            <p class="error-message">Your credits have finished.</p>
        <?php endif; ?>

        <!-- Go Back Button -->
        <a href="javascript:history.back()" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>
