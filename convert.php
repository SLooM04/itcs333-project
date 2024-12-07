<?php
// Start the session
session_start();

// Include the database connection file
require 'db.php'; // This includes the db.php file

// Check if room_type and room_number are provided in the URL
if (isset($_GET['room_type'], $_GET['room_number'])) {
    $roomType = $_GET['room_type'];
    $roomNumber = $_GET['room_number'];

    // Construct the room_name (e.g., "Room 1048" or "Lab 1048")
    $roomName = $roomType . ' ' . $roomNumber;

    // Prepare the SQL query to search for the room by room_name
    $sql = "SELECT id FROM rooms WHERE room_name = ? LIMIT 1";
    
    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$roomName]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // Get the room ID
        $roomId = $room['id'];

        // Determine the source based on HTTP_REFERER
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referrer = $_SERVER['HTTP_REFERER'];
            
            if (strpos($referrer, '/rooms.php') !== false) {
                // Redirect to room_details.php
                header("Location: room_details.php?id=" . $roomId);
            } elseif (strpos($referrer, 'adminrooms.php') !== false) {
                // Redirect to admin-room_details.php
                header("Location: admin-room_details.php?id=" . $roomId);
            } else {
                echo '<script>alert("Invalid referrer."); 
                window.history.back(); </script>';
            }
        } else {
            echo '<script>alert("Referrer not detected."); 
            window.history.back(); </script>';
        }
        exit();
    } else {
        // If no room is found, show an alert and go back
        echo '<script>alert("No room found matching that criteria."); 
        window.history.back(); </script>';
    }
} else {
    // If required parameters are missing, display an error
    echo "Room type or room number not provided.";
}
?>
