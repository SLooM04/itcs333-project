<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Function to fetch rooms from the database based on department
function fetchRooms($department = null)
{
    global $pdo;

    // If department is provided, fetch rooms by department
    if ($department) {
        $sql = "SELECT * FROM rooms WHERE department = :department";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['department' => $department]);
    } else {
        // Fetch all rooms if no department is specified
        $sql = "SELECT * FROM rooms";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch rooms if a department is selected
$rooms = [];
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $rooms = fetchRooms($department); // Fetch rooms by department
} else {
    $rooms = fetchRooms(); // Fetch all rooms
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
 <style>
     
        body {
      margin: 0; /* Remove default body margins */
      padding: 0;
      background-image: url('uploads/network-engineering.jpg'); /* Path to your background image */
      background-size: contain; /* Ensures the entire image is visible */
      background-repeat: no-repeat; /* Prevents repeating */
      background-position: center; /* Centers the image */
      width: 100%; /* Sets the width to the viewport width */
      height: 100%; /* Sets the height to the viewport height */
      overflow: hidden; /* Prevents scrolling if content overflows */
      margin-top: 0px;
      padding-top: 0px;
      }
 /* Container for buttons */
 .button-container {
            position: relative;
            width: 100%; /* Full width */
            height: 100vh; /* Full height */
        }

        /* Transparent button style */
        .transparent-button {
            position: absolute;
            width: 70px;
            height: 85px;
            font-size: 16px;
            color: black; /* Text color */
            background-color: rgba(0, 0, 0, 0.0); /* Transparent background */
            border: 1px solid rgba(255, 255, 255, 0.0); /* Border */
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-align: center;
           
        }

        a {
       color: black; 
       font-weight: bold;
      }

        /* Hover effect */
        .transparent-button:hover {
            background-color: rgba(0, 255, 0, 0.4); /* Opaque background */
            color: white; /* Text color on hover */
        }

        .button {

            padding: 0px;
        }


        /* Positioning buttons in two per line */
        .transparent-button:nth-child(1) { top: 1%; left: 18.8%; }
        .transparent-button:nth-child(2) { top: 1%; left: 40%; }
        .transparent-button:nth-child(3) { top: 7.3%; left: 18.8%; }
        .transparent-button:nth-child(4) { top: 7.3%; left: 40%; }
        .transparent-button:nth-child(5) { top: 11.4%; left: 18.9%; }
        .transparent-button:nth-child(6) { top: 11.4%; left: 40.6%; }
        .transparent-button:nth-child(7) { top: 17.0%; left: 18.9%; }
        .transparent-button:nth-child(8) { top: 17.1%; left: 40.6%; }
        .transparent-button:nth-child(9) { top: 34.1%; left: 19%; }
        .transparent-button:nth-child(10) { top: 34.1%; left: 40.6%; }
        .transparent-button:nth-child(11) { top: 45%; left: 19.4%; }
        .transparent-button:nth-child(12) { top: 45%; left: 40.8%; }
        .transparent-button:nth-child(13) { top: 50.7%; left: 19.4%; }
        .transparent-button:nth-child(14) { top: 50.7%; left: 40.8%; }
        .transparent-button:nth-child(15) { top: 67.6%; left: 19.6%; }
        .transparent-button:nth-child(16) { top: 67.6%; left: 41.4%; }
        .transparent-button:nth-child(17) { top: 78.5%; left: 19.3%; }
        .transparent-button:nth-child(18) { top: 78.5%; left: 41.1%; }
        .transparent-button:nth-child(19) { top: 84.3%; left: 19.2%; }
        .transparent-button:nth-child(20) { top: 84.3%; left: 41%; }

 </style>

</head>
<body>
<div class="button-container">
    <a class="transparent-button" href="room_details.php?id=35" target="_blank"><br>2086</a>
    <a class="transparent-button" href="room_details.php?id=36" target="_blank"><br>2087</a>
    <a class="transparent-button" href="room_details.php?id=34" target="_blank"><br>2084</a>
    <a class="transparent-button" href="room_details.php?id=37" target="_blank"><br>2088</a>
    <a class="transparent-button" href="room_details.php?id=33" target="_blank"><br>2083</a>
    <a class="transparent-button" href="room_details.php?id=38" target="_blank"><br>2089</a>
    <a class="transparent-button" href="room_details.php?id=39" target="_blank"><br>2081</a>
    <a class="transparent-button" href="room_details.php?id=51" target="_blank"><br>2091</a>
    <a class="transparent-button" href="room_details.php?id=29" target="_blank"><br>1085</a>
    <a class="transparent-button" href="room_details.php?id=30" target="_blank"><br>1086</a>
    <a class="transparent-button" href="room_details.php?id=28" target="_blank"><br>1083</a>
    <a class="transparent-button" href="room_details.php?id=31" target="_blank"><br>1087</a>
    <a class="transparent-button" href="room_details.php?id=27" target="_blank"><br>1081</a>
    <a class="transparent-button" href="room_details.php?id=32" target="_blank"><br>1089</a>
    <a class="transparent-button" href="room_details.php?id=22" target="_blank"><br>084</a>
    <a class="transparent-button" href="room_details.php?id=23" target="_blank"><br>085</a>
    <a class="transparent-button" href="room_details.php?id=24" target="_blank"><br>097</a>
    <a class="transparent-button" href="room_details.php?id=25" target="_blank"><br>086</a>
    <a class="transparent-button" href="room_details.php?id=21" target="_blank"><br>077</a>
    <a class="transparent-button" href="room_details.php?id=26" target="_blank"><br>088</a>
</div>

    

</body>
</html>