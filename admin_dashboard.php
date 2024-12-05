<?php
session_start();
require 'db.php'; // Include your database connection

// Ensure the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("You must be an admin to access this page.");
}

// Fetch all comments from the database
$stmt = $pdo->prepare("
    SELECT c.*, 
           CASE 
               WHEN c.user_role = 'student' THEN s.username 
               WHEN c.user_role = 'teacher' THEN t.username 
           END AS username
    FROM comments c
    LEFT JOIN students s ON c.user_id = s.student_id AND c.user_role = 'student'
    LEFT JOIN teachers t ON c.user_id = t.teacher_id AND c.user_role = 'teacher'
    ORDER BY c.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Manage Comments</h2>

    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
            <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
            <p><strong>Rating:</strong>
                <?php 
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $comment['rating'] ? '★' : '☆'; 
                    }
                ?> /5
            </p>
            <p><em>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></em></p>

            <!-- Admin Response Form -->
            <?php if (empty($comment['admin_response'])): ?>
                <form action="respond_comment.php" method="POST">
                    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                    <textarea name="response" placeholder="Enter your response..." required></textarea>
                    <button type="submit">Respond</button>
                </form>
            <?php else: ?>
                <p><strong>Admin Response:</strong> <?php echo htmlspecialchars($comment['admin_response']); ?></p>
            <?php endif; ?>

            <hr>
        </div>
    <?php endforeach; ?>
</body>
</html>
