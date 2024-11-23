<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

 

        // Verify the password
        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['user_id'] = $student['student_id'];
            $_SESSION['username'] = $student['username'];
            $_SESSION["login_success"] = "Welcome, " . $student['username'] . "!";
            sleep(seconds: 2);
            header("Location: rooms.php");
            exit();
        }
    
    
    // Authentication failed
    $_SESSION['login_error'] = "Invalid username or password";
    sleep(2);
    header(header: "Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}


?>