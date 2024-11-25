<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

        // Check if the user is a teacher
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacher) {
            $user = $teacher;
            $user['role'] = 'teacher';
        } else {
            // If not a teacher, check if the user is a student
            $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $user = $student;
                $user['role'] = 'student';
            } else {
                // No user found
                $user = null;
            }
        }

 

        // Verify the password
        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['user_id'] = $student['student_id'];
            $_SESSION['username'] = $student['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION["login_success"] = "Welcome, " . $student['username'] . "!";
            sleep(seconds: 2);
            header("Location: homelog.php");
            exit();
        }
    

 

        // Verify the password
        elseif ($teacher && password_verify($password, $teacher['password'])) {
            $_SESSION['user_id'] = $teacher['teacher_id'];
            $_SESSION['username'] = $teacher['username'];
            $_SESSION['role'] = $user['role'];
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