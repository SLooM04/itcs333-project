<?php
session_start(); // Ensure session is started at the top of the file
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
  <main class="container">
    <!-- Display Error Message -->
    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['login_error']) . "</p>";
        unset($_SESSION['login_error']); // Clear the message after displaying it
    }
    if (isset($_SESSION['login_success'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_SESSION['login_success']) . "</p>";
        unset($_SESSION['login_success']);
    }
    ?>

    <!-- Login Form -->
    <section class="login-section">
      <h1>Login</h1>
      <form action="login-process.php" method="POST" id="login-form" class="login-form">
        <!-- Email -->
        <div class="form-group" id="email-container">
          <label for="login-email">UoB Email</label>
          <input type="email" id="login-email" name="email" placeholder="Enter your email" required>
        </div>

        <!-- Password -->
        <div class="form-group" id="password-container">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
        </div>

        <!-- Submit Button -->
        <div id="submit-container">
          <button type="submit" class="contrast">Login</button>
        </div>
      </form>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </section>
  </main>
  <!-- Footer Section -->
  <footer class="container">
    <hr>
    <p>&copy; <?php echo date("Y"); ?> ITCS333 Project | All rights reserved.</p>
    <ul>
      <li><a href="#privacy-policy">Privacy Policy</a></li>
      <li><a href="#terms-of-service">Terms of Service</a></li>
      <li><a href="#contact">Contact Us</a></li>
    </ul>
  </footer>
</body>
</html>