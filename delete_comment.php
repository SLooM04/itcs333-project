<?php
session_start();
require 'db.php'; // Database connection

// Ensure the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// Check if comment_id is provided
if (isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];

    // Delete the comment from the database
    $stmt = $pdo->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
    $stmt->execute([':comment_id' => $comment_id]);

    // Redirect back to the admin dashboard with a success message
    $_SESSION['success_message'] = "Feedback deleted successfully.";
    header("Location: admin-dashboard.php");
    exit();
} else {
    die("Invalid request.");
}
?>
