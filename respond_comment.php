<?php
session_start();
require 'db.php'; // Include your database connection

// Ensure the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("You must be an admin to respond.");
}

// Check if the comment ID and response are provided
if (isset($_POST['comment_id']) && isset($_POST['response'])) {
    $comment_id = $_POST['comment_id'];
    $response = htmlspecialchars($_POST['response']); // Sanitize the response

    // Update the comment with the admin response
    $stmt = $pdo->prepare("UPDATE comments SET admin_response = :response WHERE comment_id = :comment_id");
    $stmt->execute([
        ':response' => $response,
        ':comment_id' => $comment_id
    ]);

    // Redirect back to the admin dashboard after responding
    $_SESSION['success_message'] = "Your response has been submitted.";
    header("Location: admin_dashboard.php");
    exit();
} else {
    die("Invalid request.");
}
