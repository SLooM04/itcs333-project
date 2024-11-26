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

    // Verify the password for student
    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['user_id'] = $student['student_id'];
        $_SESSION['username'] = $student['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['level'] = $student['level'];
        $_SESSION["login_success"] = "Welcome, " . $student['username'] . "!";

        // Set cookies for the student
        setcookie("user_id", $student['student_id'], time() + (86400 * 30), "/"); // 30 days
        setcookie("username", $student['username'], time() + (86400 * 30), "/");
        setcookie("role", $user['role'], time() + (86400 * 30), "/");
        setcookie("level", $student['level'], time() + (86400 * 30), "/");

        sleep(2);
        header("Location: homelog.php");
        exit();
    }

    // Verify the password for teacher
    elseif ($teacher && password_verify($password, $teacher['password'])) {
        $_SESSION['user_id'] = $teacher['teacher_id'];
        $_SESSION['username'] = $teacher['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION["login_success"] = "Welcome, " . $teacher['username'] . "!";

        // Set cookies for the teacher
        setcookie("user_id", $teacher['teacher_id'], time() + (86400 * 30), "/"); // 30 days
        setcookie("username", $teacher['username'], time() + (86400 * 30), "/");
        setcookie("role", $user['role'], time() + (86400 * 30), "/");

        sleep(2);
        header("Location: homelog.php");
        exit();
    }
    // Authentication failed
    $_SESSION['login_error'] = "Invalid username or password";
    sleep(2);
    header("Location: combined_login.php");
    exit();
} else {
    header("Location: combined_login.php");
    exit();
}
?>
