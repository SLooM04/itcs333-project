<?php
session_start();
require 'db.php';
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
            gap: 20px;
            align-items: center;
        }

        .nav-item {
            text-decoration: none;
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
            border: 2px solid #0051b5;
            transition: transform 0.8s;
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

    </style>
</head>
<body>
<header>
        <!-- Logo Section -->
        <a href="homelog.php" class="logo">
            <img src="uploads/UOB-Colleges-new-logo.png" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->

        <nav class="nav-links">
            <a href="homelog.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'homelog.php' ? 'active' : ''; ?>">Home</a>
            <a href="rooms.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">Rooms</a>
            <a href="reporting.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : ''; ?>">My Reservations</a>
            <a href="supportFAQ.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>">Support</a>
        </nav>



        <!-- User Profile Section -->
        <div class="user-profile dropdown">
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
            <span> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <div class="dropdown-content">
                <a href="profile.php">My Profile</a>
                <a href="settings.php">Settings</a>
                <a id="themeToggle">Dark Mode</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
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
                        <p>Visit the registration page, fill out the required fields, and ensure you use your UoB email for validation. A confirmation email will be sent upon successful registration.</p>

                        <p><strong>I forgot my password. What should I do?</strong></p>
                        <p>Use the "Forgot Password" link on the login page to reset your password. Follow the instructions sent to your registered email.</p>
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
                        <p>Yes, you can cancel a booking from your user dashboard under "Upcoming Bookings". Please note that cancellations must be made at least 24 hours in advance.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
   

        // Event listener for theme toggle
        themeToggle.addEventListener('click', function () {
            if (body.classList.contains('dark-mode')) {
                body.classList.remove('dark-mode');
                themeToggle.textContent = 'Dark Mode';
                video.src = 'uploads/homelog.mp4'; // Light mode video
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark-mode');
                themeToggle.textContent = 'Light Mode';
                video.src = 'uploads/homelogDARK.mp4'; // Dark mode video
                localStorage.setItem('theme', 'dark');
            }
        });

       
   

    </script>
</body>
</html>
