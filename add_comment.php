<?php
session_start();
require 'db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to comment.");
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
$room_id = $_POST['room_id'];
$comment_text = htmlspecialchars($_POST['comment_text']);
$rating = (int)$_POST['rating'];
$current_time = date('Y-m-d H:i:s'); // Current timestamp

// Check if the user has a past booking for the room
$stmt = $pdo->prepare("
    SELECT * FROM bookings 
    WHERE room_id = :room_id AND 
          (student_id = :user_id OR teacher_id = :user_id) AND 
          end_time < :current_time AND 
          status = 'Confirmed'
");
$stmt->execute([
    ':room_id' => $room_id,
    ':user_id' => $user_id,
    ':current_time' => $current_time
]);

if ($stmt->rowCount() === 0) {
    die("You must complete a booking for this room to leave a comment.");
}

// Insert the comment
$stmt = $pdo->prepare("
    INSERT INTO comments (room_id, user_id, user_role, comment_text, rating) 
    VALUES (:room_id, :user_id, :user_role, :comment_text, :rating)
");
$stmt->execute([
    ':room_id' => $room_id,
    ':user_id' => $user_id,
    ':user_role' => $user_role,
    ':comment_text' => $comment_text,
    ':rating' => $rating
]);

// Redirect back to room details
header("Location: room_details.php?id=$room_id");
exit();
?>
