<?php
// delete_comment.php

session_start();
require 'db.php';  // Include the DB connection file

// Ensure the user is logged in and is an admin
if ($_SESSION['role'] != 'admin' && !isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Check if comment_id is set and delete the comment
if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    // Prepare the delete query
    $stmt = $pdo->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->execute([$comment_id]);

    // Optionally, set a success message to show after deletion
    $_SESSION['success_message'] = "Comment deleted successfully.";

    // Redirect back to feedback management page
    header("Location: Feedback_Managment.php");
    exit();
} else {
    // If no comment_id was provided, redirect to the feedback management page
    header("Location: Feedback_Managment.php");
    exit();
}
?>
