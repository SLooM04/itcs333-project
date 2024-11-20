<?php
session_start();

// Simple in-memory storage (replace with a database in real-world scenarios)
if (!isset($_SESSION["users"])) {
    $_SESSION["users"] = [];
}

$users = &$_SESSION["users"]; // Reference users array in session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $confirmEmail = $_POST['confirm_email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $companyTitle = $_POST['company_title'];
    $contactName = $_POST['contact_name'];
    $jobTitle = $_POST['job_title'];
    $officePhone = $_POST['office_phone'];
    $mobileNumber = $_POST['mobile_number'];
    $contactEmail = $_POST['contact_email'];

    // Validation checks
    if (
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $email === $confirmEmail &&
        !empty($username) &&
        $password === $confirmPassword &&
        !empty($companyTitle) &&
        !empty($contactName) &&
        !empty($jobTitle) &&
        !empty($officePhone) &&
        !empty($mobileNumber) &&
        filter_var($contactEmail, FILTER_VALIDATE_EMAIL)
    ) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Store user in session (replace with database storage in real applications)
        $users[$email] = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'password' => $hashed_password,
            'company_title' => $companyTitle,
            'contact_name' => $contactName,
            'job_title' => $jobTitle,
            'office_phone' => $officePhone,
            'mobile_number' => $mobileNumber,
            'contact_email' => $contactEmail,
        ];

        $_SESSION['registration_success'] = "Registration successful. You can now log in.";
        sleep(2);
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['registration_error'] = "Invalid input. Please check all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
</head>
<body>
  <main class="container">
  <h1>Registration</h1>
    <form action="register.php" method="POST">
      <fieldset>
        <legend>Registration Details</legend>

        <!-- First Name -->
        <div id="registration-first-name" class="form-group">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
        </div>

        <!-- Last Name -->
        <div id="registration-last-name" class="form-group">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
        </div>

        <!-- Email -->
        <div id="registration-email" class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <!-- Confirm Email -->
        <div id="registration-confirm-email" class="form-group">
          <label for="confirm_email">Confirm Email</label>
          <input type="email" id="confirm_email" name="confirm_email" placeholder="Re-enter your email" required>
        </div>

        <!-- Username -->
        <div id="registration-username" class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required>
        </div>

        <!-- Password -->
        <div id="registration-password" class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>

        <!-- Confirm Password -->
        <div id="registration-confirm-password" class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
        </div>
      </fieldset>

      <fieldset>
        <legend>Contact Details</legend>

        <!-- Company Title -->
        <div id="contact-company-title" class="form-group">
          <label for="company_title">Company Title</label>
          <input type="text" id="company_title" name="company_title" placeholder="Enter your company title" required>
        </div>

        <!-- Contact Name -->
        <div id="contact-name" class="form-group">
          <label for="contact_name">Contact Name</label>
          <input type="text" id="contact_name" name="contact_name" placeholder="Enter contact name" required>
        </div>

        <!-- Job Title -->
        <div id="contact-job-title" class="form-group">
          <label for="job_title">Contact Job Title</label>
          <input type="text" id="job_title" name="job_title" placeholder="Enter job title" required>
        </div>

        <!-- Office Phone -->
        <div id="contact-office-phone" class="form-group">
          <label for="office_phone">Office Phone</label>
          <input type="text" id="office_phone" name="office_phone" placeholder="Enter office phone" required>
        </div>

        <!-- Mobile Number -->
        <div id="contact-mobile-number" class="form-group">
          <label for="mobile_number">Mobile Number</label>
          <input type="text" id="mobile_number" name="mobile_number" placeholder="Enter mobile number" required>
        </div>

        <!-- Contact Email -->
        <div id="contact-email" class="form-group">
          <label for="contact_email">Contact Email</label>
          <input type="email" id="contact_email" name="contact_email" placeholder="Enter contact email" required>
        </div>
      </fieldset>

      <div id="form-submit" class="form-group">
        <button type="submit" id="submit" class="contrast">Register</button>
      </div>
    </form>
    </form>

    <?php if (isset($_SESSION['registration_error'])){ 
     echo "<p style= color: red;> ". $_SESSION['registration_error'] ."</p>";
       unset($_SESSION['registration_error']); }?>
    
  </main>
</body>
</html>