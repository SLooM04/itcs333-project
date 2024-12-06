<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
}
$sql = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $booking_id]);



// Redirect back to the page displaying bookings
header("Location: upcoming_bookings.php");
exit();
?>