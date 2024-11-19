
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
<?php
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
        unset($_SESSION['login_error']);
    }
    if (isset($_SESSION['registration_success'])) {
        echo "<p style='color: green;'>" . $_SESSION['registration_success'] . "</p>";
        unset($_SESSION['registration_success']);
    }
    ?> 

  <main class="container">
    <section class="login-section">
      <h1>Login</h1>
      <form action = "login-process.php"method = "Post" id="login-form" class="login-form">
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
    </section>
  </main>
</body>
</html>