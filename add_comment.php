<?php
session_start();
require 'db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to comment.");
}


$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; 

// Ensure the user exists in the respective table
if ($user_role == 'student') {
    // Check if the student exists in the students table
    $stmt = $pdo->prepare("SELECT student_id FROM students WHERE student_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    if ($stmt->rowCount() == 0) {
        die("Invalid student ID.");
    }
} elseif ($user_role == 'teacher') {
    // Check if the teacher exists in the teachers table
    $stmt = $pdo->prepare("SELECT teacher_id FROM teachers WHERE teacher_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    if ($stmt->rowCount() == 0) {
        die("Invalid teacher ID.");
    }
} else {
    die("Invalid user role.");
}

// Now insert the comment into the comments table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $comment_text = htmlspecialchars($_POST['comment_text']);
    $rating = (int)$_POST['rating'];  // Get the rating from the form

    // Make sure the rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        die("Invalid rating. Please select a rating between 1 and 5 stars.");
    }

    // Insert comment and rating into the database
    $stmt = $pdo->prepare("INSERT INTO comments (room_id, user_id, user_role, comment_text, rating) VALUES (:room_id, :user_id, :user_role, :comment_text, :rating)");
    $stmt->execute([
        ':room_id' => $room_id,
        ':user_id' => $user_id,
        ':user_role' => $user_role,
        ':comment_text' => $comment_text,
        ':rating' => $rating
    ]);

    // Redirect back to room details page
    $_SESSION['success_message'] = "Your comment has been submitted.";
    header("Location: room_details.php?id=$room_id");
    exit();
}
?>
