<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

 

        // Verify the password
        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['user_id'] = $student['student_id'];
            $_SESSION['username'] = $student['username'];
            $_SESSION["login_success"] = "Welcome, " . $student['username'] . "!";
            sleep(seconds: 2);
            header("Location: homelog.php");
            exit();
        }
    

 

        // Verify the password
        elseif ($teacher && password_verify($password, $teacher['password'])) {
            $_SESSION['user_id'] = $teacher['teacher_id'];
            $_SESSION['username'] = $teacher['username'];
            $_SESSION["login_success"] = "Welcome, " . $teacher['username'] . "!";
            sleep(seconds: 2);
            header("Location: homelog.php");
            exit();
        }
    
    
    // Authentication failed
    $_SESSION['login_error'] = "Invalid username or password";
    sleep(2);
    header(header: "Location: combined_login.php");
    exit();
} else {
    header("Location: combined_login.php");
    exit();
}


?>