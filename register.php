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