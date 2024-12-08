<?php
// Start the session
session_start();

// Include the database connection file
require 'db.php'; // This includes the db.php file

// Check if room_number is provided in the URL
if (isset($_GET['room_number'])) {
    $roomNumber = $_GET['room_number'];

    // Prepare the SQL query to find whether the number corresponds to "Room" or "Lab"
    $sql = "SELECT id, room_name 
            FROM rooms 
            WHERE room_name LIKE ? OR room_name LIKE ? LIMIT 1";
    
    // Execute the query with placeholders for "Room" and "Lab"
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["Room $roomNumber", "Lab $roomNumber"]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // Get the room ID
        $roomId = $room['id'];

        // Determine the source based on HTTP_REFERER
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referrer = $_SERVER['HTTP_REFERER'];
            
            if (strpos($referrer, '/rooms.php') !== false) {
                // Redirect to room_details.php
                header("Location: room_details.php?id=$roomId");
            } elseif (strpos($referrer, '/adminrooms.php') !== false) {
                // Redirect to admin-room_details.php
                header("Location: admin-room_details.php?id=$roomId");
            } else {
                header("Location: error.php?msg=Invalid referrer");
            }
        } else {
            header("Location: error.php?msg=Referrer not detected");
        }
        exit();
    } else {
        // If no room or lab is found, redirect to error page
        header("Location: error.php?msg=No room or lab found with this number");
        exit();
    }
} else {
    // If room_number is missing, redirect to error page
    header("Location: error.php?msg=Room number not provided");
    exit();
}
?>
