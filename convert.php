<?php
// Start the session
session_start();

// Include the database connection file
require 'db.php'; // This includes the db.php file

// Check if room_type and room_number are provided in the URL
if (isset($_GET['room_type']) && isset($_GET['room_number'])) {
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

    // If a room is found, redirect to admin-room_details.php with the room id in the URL
    if ($room) {
        $roomId = $room['id'];
        header("Location: admin-room_details.php?id=" . $roomId);
        exit();
    } else {
        echo '<script>alert("No room found matching that criteria."); 
        window.history.back(); </script>';

    }
} else { 
    echo "Room type or room number not provided.";

}
?>
