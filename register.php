<?php
session_start();
require 'db.php';

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

    if (
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $email === $confirmEmail &&
        $password === $confirmPassword &&
        !empty($companyTitle) &&
        !empty($contactName) &&
        !empty($jobTitle) &&
        !empty($officePhone) &&
        !empty($mobileNumber) &&
        filter_var($contactEmail, FILTER_VALIDATE_EMAIL)
    ) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, username, password, company_title, contact_name, job_title, office_phone, mobile_number, contact_email)
            VALUES (:first_name, :last_name, :email, :username, :password, :company_title, :contact_name, :job_title, :office_phone, :mobile_number, :contact_email)
        ");
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':username' => $username,
            ':password' => $hashed_password,
            ':company_title' => $companyTitle,
            ':contact_name' => $contactName,
            ':job_title' => $jobTitle,
            ':office_phone' => $officePhone,
            ':mobile_number' => $mobileNumber,
            ':contact_email' => $contactEmail,
        ]);

        $_SESSION['registration_success'] = "Registration successful. You can now log in.";
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
  <style>
  body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    padding: 0;
    color: #333;
    font-size: 16px; /* A good base size for readability */
    line-height: 1.6; /* Add spacing between lines for easier reading */
  }

  main {
    display: grid;
    place-items: center;
    min-height: 100vh;
    padding: 20px;
  }

  .form-container {
    width: 100%;
    max-width: 900px;
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  h1 {
    font-size: 3em;
    color: #0061f2;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
  }

  fieldset {
    border: none;
    padding: 0;
    margin: 0 0 20px 0;
  }

  legend {
    font-size: 1.6em;
    color: #0061f2;
    font-weight: 700;
    margin-bottom: 10px;
  }

  .form-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .form-group {
    flex: 1;
    margin-right: 20px;
  }

  .form-group:last-child {
    margin-right: 0;
  }

  .form-group label {
    font-size: 1.1em;
    color: #555;
    display: block;
    margin-bottom: 8px;
  }

  .form-group input {
    padding: 12px;
    font-size: 1.2em;
    border: 2px solid #ddd;
    border-radius: 8px;
    width: 100%;
    background-color: #f9f9f9;
    color: #333;
    transition: all 0.3s;
  }

  .form-group input:focus {
    border-color: #0061f2;
    background-color: #f1faff;
    outline: none;
  }

  button {
    padding: 18px;
    font-size: 1.3em ;
    font-weight: bold;
    font-family: 'Roboto', sans-serif;
    background-color: #0061f2;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
  }

  button:hover {
    background-color: #004bb5;
  }

  p {
    text-align: center;
    color: #555;
  }

  p a {
    color: #0061f2;
    text-decoration: none;
    font-weight: bold;
  }

  p a:hover {
    text-decoration: underline;
  }

  footer {
    margin-top: 40px;
    text-align: center;
    font-size: 1em;
    color: #888;
  }

  footer ul {
    list-style: none;
    padding: 0;
    margin-top: 10px;
  }

  footer ul li {
    display: inline-block;
    margin-right: 15px;
  }

  footer ul li a {
    color: #0061f2;
    text-decoration: none;
  }

  footer ul li a:hover {
    text-decoration: underline;
  }

  /* Additional error/success message styles */

  p.invalid {
    background-color: #ffe6e6;
    color: red;
    border: 1px solid red;
  }

  .success-message {
    background-color: #e6ffe6;
    color: green;
    border: 1px solid green;
  }
  /* Style the form legends */
.form-legend {
  font-weight: bold;
  font-size: 18px; /* Slightly larger for emphasis */
  color: #333; /* A dark color for clarity */
}

/* Section separator line */
.section-separator {
  border-top: 1px solid #ddd; /* Light gray line for separation */
  margin: 20px 0; /* Add some spacing above and below the line */
}



/* Padding & Margin Adjustments */
.form-container {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.form-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
}

.form-group {
  flex: 1;
  margin-right: 20px;
}

.form-group:last-child {
  margin-right: 0;
}


</style>
</head>
<body>
  <main class="container">
  <div class="form-container">
    <h1>Registration</h1>
    <form action="register.php" method="POST">
      <fieldset>
        <legend class="form-legend">Registration Details</legend>

        <!-- First Name & Last Name -->
        <div class="form-row">
          <div id="registration-first-name" class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
          </div>
          <div id="registration-last-name" class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
          </div>
        </div>

        <!-- Email & Confirm Email -->
        <div class="form-row">
          <div id="registration-email" class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <div id="registration-confirm-email" class="form-group">
            <label for="confirm_email">Confirm Email</label>
            <input type="email" id="confirm_email" name="confirm_email" placeholder="Re-enter your email" required>
          </div>
        </div>

        <!-- Username -->
        <div id="registration-username" class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required>
        </div>

        <!-- Password & Confirm Password -->
        <div class="form-row">
          <div id="registration-password" class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>
          </div>
          <div id="registration-confirm-password" class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
          </div>
        </div>
      </fieldset>

      <div class="section-separator"></div> <!-- Separator Line -->

      <fieldset>
        <legend class="form-legend">Contact Details</legend>

        <!-- Company Title & Contact Name -->
        <div class="form-row">
          <div id="contact-company-title" class="form-group">
            <label for="company_title">Company Title</label>
            <input type="text" id="company_title" name="company_title" placeholder="Enter your company title" required>
          </div>
          <div id="contact-name" class="form-group">
            <label for="contact_name">Contact Name</label>
            <input type="text" id="contact_name" name="contact_name" placeholder="Enter contact name" required>
          </div>
        </div>

        <!-- Job Title & Office Phone -->
        <div class="form-row">
          <div id="contact-job-title" class="form-group">
            <label for="job_title">Contact Job Title</label>
            <input type="text" id="job_title" name="job_title" placeholder="Enter job title" required>
          </div>
          <div id="contact-office-phone" class="form-group">
            <label for="office_phone">Office Phone</label>
            <input type="text" id="office_phone" name="office_phone" placeholder="Enter office phone" required>
          </div>
        </div>

        <!-- Mobile Number & Contact Email -->
        <div class="form-row">
          <div id="contact-mobile-number" class="form-group">
            <label for="mobile_number">Mobile Number</label>
            <input type="text" id="mobile_number" name="mobile_number" placeholder="Enter mobile number" required>
          </div>
          <div id="contact-email" class="form-group">
            <label for="contact_email">Contact Email</label>
            <input type="email" id="contact_email" name="contact_email" placeholder="Enter contact email" required>
          </div>
        </div>
      </fieldset>

      <div id="form-submit" class="form-group">
        <button type="submit" id="submit" class="contrast">Register</button>
      </div>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
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
