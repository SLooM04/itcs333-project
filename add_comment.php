<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to leave feedback.");
}

$user_id = $_SESSION['user_id'];
$room_id = $_POST['room_id'];
$comment_text = htmlspecialchars($_POST['comment_text']);
$rating = (int)$_POST['rating'];

// Check if the user has a valid past booking
$stmt = $pdo->prepare("
    SELECT * FROM bookings 
    WHERE room_id = :room_id 
      AND (student_id = :user_id OR teacher_id = :user_id) 
      AND end_time < NOW() 
      AND status IN ('Confirmed', 'Successful')
");
$stmt->execute([
    ':room_id' => $room_id,
    ':user_id' => $user_id
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
    ':user_role' => $_SESSION['role'], // Ensure the session has the correct role
    ':comment_text' => $comment_text,
    ':rating' => $rating
]);


header("Location: room_details.php?id=$room_id");  // Redirect back to room details page
exit();
?>
