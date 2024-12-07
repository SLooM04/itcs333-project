<?php
session_start();
require 'db.php'; // Database connection

// Ensure the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// Check if comment_id and response are provided
if (isset($_POST['comment_id']) && isset($_POST['response'])) {
    $comment_id = (int)$_POST['comment_id'];
    $response = htmlspecialchars($_POST['response']); // Sanitize input

    // Update the admin_response in the database
    $stmt = $pdo->prepare("UPDATE comments SET admin_response = :response, updated_at = NOW() WHERE comment_id = :comment_id");
    $stmt->execute([
        ':response' => $response,
        ':comment_id' => $comment_id
    ]);

    // Redirect back to the admin dashboard with a success message
    $_SESSION['success_message'] = "Response added successfully.";
    header("Location: admin-dashboard.php");
    exit();
} else {
    die("Invalid request.");
}
?>
