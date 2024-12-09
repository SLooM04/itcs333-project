<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: combined_login.php");
    exit();
}

if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    // Delete the admin response
    $stmt = $pdo->prepare("UPDATE comments SET admin_response = NULL WHERE comment_id = ?");
    $stmt->execute([$comment_id]);

    $_SESSION['success_message'] = "Response deleted successfully!";
    header("Location: Feedback_Managment.php"); // Redirect back to the feedback management page
    exit();
} else {
    die("No comment selected.");
}
