<?php
session_start();

// Simple in-memory storage (replace with a database in real-world scenarios)//we will update it when we do the database
if (!isset($_SESSION["users"])) {
    $_SESSION["users"] = [];
}

$users = &$_SESSION["users"]; // Reference users array in session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email and password inputs
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Store user in session (replace with database storage in real applications)//we will update it when we do the database
        $users[$email] = $hashed_password;

        // Set a success message and redirect to login
        $_SESSION['registration_success'] = "Registration successful. You can now log in.";
        sleep(3);
        header("Location: login.php" ,);
        exit();
    } else {
        $_SESSION['registration_error'] = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Example</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
  <main class="container">
    <h1>User Registration and Login</h1>
    <form action = "register.php" method = "Post" id="sample-form">
      
      <!-- Email -->
      <div id="email-container" class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <!-- Password -->
      <div id="password-container" class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <!-- Submit Button -->
      <div id="submit-container" class="form-group">
        <button type="submit" class="contrast">Register</button>
      </div>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </main>
</body>
</html>