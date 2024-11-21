<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $teachers = $stmt->fetch(PDO::FETCH_ASSOC);

 

        // Verify the password
        if ($student && password_verify($password, $teachers['password'])) {
            $_SESSION['user_id'] = $teachers['teacher_id'];
            $_SESSION['username'] = $teachers['username'];
            $_SESSION["login_success"] = "Welcome, " . $teachers['username'] . "!";
            sleep(seconds: 2);
            header("Location: test.php");
            exit();
        }
    
    
    // Authentication failed
    $_SESSION['login_error'] = "Invalid username or password";
    sleep(2);
    header(header: "Location: teacher-login.php");
    exit();
} else {
    header("Location: teacher-login.php");
    exit();
}


?>