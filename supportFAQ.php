<?php
session_start();
require 'db.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Get user details from session
$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

if ($userRole == 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
}
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support FAQ - Room Booking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333;
            z-index: 1;
        }
        body.dark-mode {
            background-color: #2e4156;
            color: white;
            z-index: 1000000000;

        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode p,
        body.dark-mode a {
            color: white;
        }

        body.dark-mode footer {
            background: linear-gradient(1deg, #000724, #111d4d);  
            color: #d1d1d1;
        }

        body.dark-mode .accordion-item {
            background: #2b2b3b;
        }

        body.dark-mode .accordion-button {
            background: #2b2b3b;
            color: white;
            border: white;
        }

        body.dark-mode .dropdown-content a {
            color: #000;
        }

        body.dark-mode .dropdown-content a:hover{
            background-color: #2e4156;
        }

        body.dark-mode .recommendation-card {
            background-color: #2e344e;
        }

        body.dark-mode .recommendations h2 {
            color: white;
        }

        body.dark-mode  .action-buttons a{
            background-color: #0b2445;
        }
        

        body.dark-mode header {
            background: linear-gradient(1deg, #172047, #34417d);  
            color: #d1d1d1;
        }

        body.dark-mode nav a {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-content {
            background-color: #2b2b3b;
        }

        body.dark-mode .dropdown-content a {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-content a {
            color: #edf4fa;
        }
        /* Header Styles */
        header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 10px 30px;
            background: linear-gradient(1deg, #1a73e8, #004db3 );  
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1000;
            
        
        }

         /* Navigation Links */
         .nav-links {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            align-items: center;
        }

        .nav-item {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            color: white;
            font-size: 1em;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.3s, border 0.3s;
            position: relative;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid #ffffff;
            border-radius: 8px;
        }

        .active {
            border: 2px solid #f0f0f0;
            background-color: rgba(255, 255, 255, 0.2);
        }

         /* Logo Styles */
         .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: white;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logo img {
            width: 100px;
            border-radius: 20%;
            transition: transform 0.8s;
        }

        @media (min-width: 801px) and (max-width: 1000px) {
            .logo img {
                width: 3rem;
            }
        }






        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-profile span {
            font-size: 1em;
            white-space: nowrap;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            color: #222;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 2000;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #222;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }





        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-profile span {
            font-size: 1em;
            white-space: nowrap;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            color: #222;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 2000;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #222;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
         @media (max-width: 480px) {
            header {
                flex-direction: column;
                align-items: center;
            }

            .logo img {
                width: 60px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .user-profile {
                flex-direction: column;
                align-items: center;
            }}

            /* Footer styles */
        footer {
            color: white;
            background: linear-gradient(1deg, #024ba9, #96c3ff);  
            text-align: center;
            padding: 1rem 1rem;
            margin-top: 0rem;
            font-size: 0.9rem;
            z-index: 1;
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            z-index: 1;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: left;
            z-index: 1;
        }

        footer .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        footer .footer-section ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            text-decoration: underline;
        }
        /* Responsive design for the footer */
        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem; 
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }}

            @media (max-width: 800px) {
            header {
                display: flex;
                justify-content: space-evenly;
                height: auto;
                flex-direction: row;
                font-size: 0.8rem;
            }

            .logo{
                size: 0.8rem;
            }
            @media (min-width: 801px) and (max-width: 1000px) {
            .logo img {
                width: 3rem;
            }
        }
       
           

        .nav-item {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            gap: 40px;
            color: white;
            font-size: 0.8em;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.3s, border 0.3s;
            position: relative;
        }
            
            .nav-item:hover,
        .nav-item.active {
            border: none;
            border-radius: 8px;
            box-sizing: content-box;
            
        }

            .logo img {
                width: 40px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .user-profile {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .action-card {
                font-size: 0.9rem;
                padding: 10px;
            }
        }

              /* From Uiverse.io by Galahhad */ 
.theme-switch {
    
    --toggle-size: 10px;
    /* the size is adjusted using font-size,
       this is not transform scale,
       so you can choose any size */
    --container-width: 5.625em;
    --container-height: 2.5em;
    --container-radius: 6.25em;
    /* radius 0 - minecraft mode :) */
    --container-light-bg: #3D7EAE;
    --container-night-bg: #1D1F2C;
    --circle-container-diameter: 3.375em;
    --sun-moon-diameter: 2.125em;
    --sun-bg: #ECCA2F;
    --moon-bg: #C4C9D1;
    --spot-color: #959DB1;
    --circle-container-offset: calc((var(--circle-container-diameter) - var(--container-height)) / 2 * -1);
    --stars-color: #fff;
    --clouds-color: #F3FDFF;
    --back-clouds-color: #AACADF;
    --transition: .5s cubic-bezier(0, -0.02, 0.4, 1.25);
    --circle-transition: .3s cubic-bezier(0, -0.02, 0.35, 1.17);
  }
  
  .theme-switch, .theme-switch *, .theme-switch *::before, .theme-switch *::after {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-size: var(--toggle-size);
  }
  
  .theme-switch__container {
    width: var(--container-width);
    height: var(--container-height);
    background-color: var(--container-light-bg);
    border-radius: var(--container-radius);
    overflow: hidden;
    cursor: pointer;
    -webkit-box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
    box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
    -webkit-transition: var(--transition);
    -o-transition: var(--transition);
    transition: var(--transition);
    position: relative;
  }
  
  .theme-switch__container::before {
    content: "";
    position: absolute;
    z-index: 1;
    inset: 0;
    -webkit-box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
    box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
    border-radius: var(--container-radius)
  }
  
  .theme-switch__checkbox {
    display: none;
  }
  
  .theme-switch__circle-container {
    width: var(--circle-container-diameter);
    height: var(--circle-container-diameter);
    background-color: rgba(255, 255, 255, 0.1);
    position: absolute;
    left: var(--circle-container-offset);
    top: var(--circle-container-offset);
    border-radius: var(--container-radius);
    -webkit-box-shadow: inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), 0 0 0 0.625em rgba(255, 255, 255, 0.1), 0 0 0 1.25em rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), 0 0 0 0.625em rgba(255, 255, 255, 0.1), 0 0 0 1.25em rgba(255, 255, 255, 0.1);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-transition: var(--circle-transition);
    -o-transition: var(--circle-transition);
    transition: var(--circle-transition);
    pointer-events: none;
  }
  
  .theme-switch__sun-moon-container {
    pointer-events: auto;
    position: relative;
    z-index: 2;
    width: var(--sun-moon-diameter);
    height: var(--sun-moon-diameter);
    margin: auto;
    border-radius: var(--container-radius);
    background-color: var(--sun-bg);
    -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
    box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
    -webkit-filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
    filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
    overflow: hidden;
    -webkit-transition: var(--transition);
    -o-transition: var(--transition);
    transition: var(--transition);
  }
  
  .theme-switch__moon {
    -webkit-transform: translateX(100%);
    -ms-transform: translateX(100%);
    transform: translateX(100%);
    width: 100%;
    height: 100%;
    background-color: var(--moon-bg);
    border-radius: inherit;
    -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
    box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
    -webkit-transition: var(--transition);
    -o-transition: var(--transition);
    transition: var(--transition);
    position: relative;
  }
  
  .theme-switch__spot {
    position: absolute;
    top: 0.75em;
    left: 0.312em;
    width: 0.75em;
    height: 0.75em;
    border-radius: var(--container-radius);
    background-color: var(--spot-color);
    -webkit-box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
    box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
  }
  
  .theme-switch__spot:nth-of-type(2) {
    width: 0.375em;
    height: 0.375em;
    top: 0.937em;
    left: 1.375em;
  }
  
  .theme-switch__spot:nth-last-of-type(3) {
    width: 0.25em;
    height: 0.25em;
    top: 0.312em;
    left: 0.812em;
  }
  
  .theme-switch__clouds {
    width: 1.25em;
    height: 1.25em;
    background-color: var(--clouds-color);
    border-radius: var(--container-radius);
    position: absolute;
    bottom: -0.625em;
    left: 0.312em;
    -webkit-box-shadow: 0.937em 0.312em var(--clouds-color), -0.312em -0.312em var(--back-clouds-color), 1.437em 0.375em var(--clouds-color), 0.5em -0.125em var(--back-clouds-color), 2.187em 0 var(--clouds-color), 1.25em -0.062em var(--back-clouds-color), 2.937em 0.312em var(--clouds-color), 2em -0.312em var(--back-clouds-color), 3.625em -0.062em var(--clouds-color), 2.625em 0em var(--back-clouds-color), 4.5em -0.312em var(--clouds-color), 3.375em -0.437em var(--back-clouds-color), 4.625em -1.75em 0 0.437em var(--clouds-color), 4em -0.625em var(--back-clouds-color), 4.125em -2.125em 0 0.437em var(--back-clouds-color);
    box-shadow: 0.937em 0.312em var(--clouds-color), -0.312em -0.312em var(--back-clouds-color), 1.437em 0.375em var(--clouds-color), 0.5em -0.125em var(--back-clouds-color), 2.187em 0 var(--clouds-color), 1.25em -0.062em var(--back-clouds-color), 2.937em 0.312em var(--clouds-color), 2em -0.312em var(--back-clouds-color), 3.625em -0.062em var(--clouds-color), 2.625em 0em var(--back-clouds-color), 4.5em -0.312em var(--clouds-color), 3.375em -0.437em var(--back-clouds-color), 4.625em -1.75em 0 0.437em var(--clouds-color), 4em -0.625em var(--back-clouds-color), 4.125em -2.125em 0 0.437em var(--back-clouds-color);
    -webkit-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
    -o-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
    transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
  }
  
  .theme-switch__stars-container {
    position: absolute;
    color: var(--stars-color);
    top: -100%;
    left: 0.312em;
    width: 2.75em;
    height: auto;
    -webkit-transition: var(--transition);
    -o-transition: var(--transition);
    transition: var(--transition);
  }
  
  /* actions */
  
  .theme-switch__checkbox:checked + .theme-switch__container {
    background-color: var(--container-night-bg);
  }
  
  .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container {
    left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter));
  }
  
  .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
    left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.187em)
  }
  
  .theme-switch__circle-container:hover {
    left: calc(var(--circle-container-offset) + 0.187em);
  }
  
  .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon {
    -webkit-transform: translate(0);
    -ms-transform: translate(0);
    transform: translate(0);
  }
  
  .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds {
    bottom: -4.062em;
  }
  
  .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
  }


    </style>
</head>
<body>
<header>
        <!-- Logo Section -->
        <a href="Home.php" class="logo">
            <img src="uploads/UOB-Colleges-new-logo.png" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->

        <nav class="nav-links">
            <a href="homelog.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'homelog.php' ? 'active' : ''; ?>">Home</a>
            <a href="rooms.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">Rooms</a>
            <a href="upcoming_bookings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : ''; ?>">My Reservations</a>
            <a href="supportFAQ.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'supportFAQ.php' ? 'active' : ''; ?>">Support</a>
        </nav>



        <!-- User Profile Section -->
        <div class="user-profile dropdown">
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
            <span> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <div class="dropdown-content">
                <a href="profile.php">My Profile</a>
                <a href="past_bookings.php">Past Bookings</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>

                <label class="theme-switch">
  <input id="themeToggle" type="checkbox" class="theme-switch__checkbox">
  <div class="theme-switch__container">
    <div class="theme-switch__clouds"></div>
    <div class="theme-switch__stars-container">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path>
      </svg>
    </div>
    <div class="theme-switch__circle-container">
      <div class="theme-switch__sun-moon-container">
        <div class="theme-switch__moon">
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
        </div>
      </div>
    </div>
  </div>
</label> 
            </div>
        </div>
    </header>
    <div class="container my-5">
        <h1 class="text-center mb-4">Support FAQ</h1>

        <div class="accordion" id="faqAccordion">
            <!-- General Questions -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingGeneral">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral" aria-expanded="true" aria-controls="collapseGeneral">
                        General Questions
                    </button>
                </h2>
                <div id="collapseGeneral" class="accordion-collapse collapse show" aria-labelledby="headingGeneral" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p><strong>What is this system?</strong></p>
                        <p>This is a responsive web-based room booking system designed specifically for the IT College. It allows users to browse rooms, view details, and make bookings efficiently.</p>

                        <p><strong>Who can use the system?</strong></p>
                        <p>The system is accessible to students, faculty, and administrators of the IT College, with different functionalities based on user roles.</p>
                    </div>
                </div>
            </div>

            <!-- User Account Questions -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAccount">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                        User Account Questions
                    </button>
                </h2>
                <div id="collapseAccount" class="accordion-collapse collapse" aria-labelledby="headingAccount" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p><strong>How do I register?</strong></p>
                        <p>Visit the registration page, fill out the required fields, and ensure you use your UoB email for validation.</p>

                    </div>
                </div>
            </div>

            <!-- Room Booking Questions -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBooking">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBooking" aria-expanded="false" aria-controls="collapseBooking">
                        Room Booking Questions
                    </button>
                </h2>
                <div id="collapseBooking" class="accordion-collapse collapse" aria-labelledby="headingBooking" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p><strong>How do I book a room?</strong></p>
                        <p>Navigate to the room browsing page, select a room, choose an available timeslot, and confirm your booking. You will receive a confirmation notification.</p>
                        
                        <p><strong>Can I cancel a booking?</strong></p>
                        <p>Yes, you can cancel a booking from your user dashboard under "Upcoming Bookings".</p>
                    </div>
                </div>
            </div>

            <!-- Admin Questions -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdmin">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                        Admin Questions
                    </button>
                </h2>
                <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p><strong>How do I add or manage rooms?</strong></p>
                        <p>Admins can access the admin dashboard, navigate to the room management section, and use the options to add, edit, or delete room details.</p>

                        <p><strong>How can I view reports?</strong></p>
                        <p>Reporting tools are available in the admin dashboard, providing insights into room usage, booking trends, and user activity.</p>
                    </div>
                </div>
            </div>

            <!-- Comment and Feedback Questions -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingComments">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComments" aria-expanded="false" aria-controls="collapseComments">
                        Comment and Feedback Questions
                    </button>
                </h2>
                <div id="collapseComments" class="accordion-collapse collapse" aria-labelledby="headingComments" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p><strong>How can I leave feedback?</strong></p>
                        <p>Users can leave comments on the room details page. Feedback helps improve the system and room facilities.</p>

                        <p><strong>How do admins handle feedback?</strong></p>
                        <p>Admins can view and respond to feedback through the admin dashboard. Notifications will be sent for new comments.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        // Handle theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check for saved theme in localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.textContent = 'Light Mode';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');

            // Update button text and save preference
            if (body.classList.contains('dark-mode')) {
                themeToggle.textContent = 'Light Mode';
                localStorage.setItem('theme', 'dark');
            } else {
                themeToggle.textContent = 'Dark Mode';
                localStorage.setItem('theme', 'light');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
  const themeSwitch = document.querySelector('.theme-switch__checkbox');

  // Load saved state from localStorage
  const savedState = localStorage.getItem('theme-switch-state');
  if (savedState === 'off') {
    themeSwitch.checked = true; // Reversed: "off" means checkbox is checked
    document.body.classList.add('dark-mode'); // Apply dark mode if reversed
  } else {
    themeSwitch.checked = false;
    document.body.classList.remove('dark-mode');
  }

  // Listen for state change
  themeSwitch.addEventListener('change', () => {
    if (themeSwitch.checked) {
      // Checkbox is checked -> Should turn off
      localStorage.setItem('theme-switch-state', 'off');
      document.body.classList.add('dark-mode'); // Apply dark mode if reversed
    } else {
      // Checkbox is unchecked -> Should turn on
      localStorage.setItem('theme-switch-state', 'on');
      document.body.classList.remove('dark-mode'); // Remove dark mode
    }
  });
});

    </script>

      <!-- Footer -->
      <footer>
        <div class="footer-container">
            <!-- University Info -->
            <div class="footer-section">
                <h3>University Info</h3>
                <ul>
                    <li><a href="https://www.uob.edu.bh/about/our-leadership/">About Us</a></li>
                    <li><a href="https://www.uob.edu.bh/locations">Campus Locations</a></li>
                    <li><a href="#events">Upcoming Events</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="https://www.uob.edu.bh/admission-requirements">Join UOB</a></li>
                    <li><a href="https://www.uob.edu.bh/deanship-of-graduate-studies-scientific-research">Research</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li>Email: <a href="mailto:info@university.com">info@university.com</a></li>
                    <li>Phone: +123 456 789</li>
                    <li>Address: Sakhir – Kingdom of Bahrain <br>1017 Road 5418 <br>Zallaq 1054</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p style="color:white;">&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy" style="color : white;">Privacy Policy | </a>
                <a href="https://www.uob.edu.bh/terms-and-conditions" style="color : white;">Terms of Service</a>
            </p>
        </div>
    </footer>
</body>
</html>
