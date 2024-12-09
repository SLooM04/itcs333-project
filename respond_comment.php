<?php
session_start();
require 'db.php'; // Ensure the database connection is established

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("You are not logged in or authorized to perform this action.");
}

// Get the comment ID and the response
$comment_id = $_POST['comment_id'];
$response = htmlspecialchars($_POST['response']);

// Update the comment with the admin's response
$stmt = $pdo->prepare("UPDATE comments SET admin_response = :response WHERE comment_id = :comment_id");
$stmt->execute([':response' => $response, ':comment_id' => $comment_id]);

// Optionally, set a success message in the session (to be displayed on the next page)
$_SESSION['success_message'] = 'Response added successfully!';

// Redirect back to the feedback management page
header("Location: Feedback_Managment.php");
exit();
?>
