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
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        /* General styles */
        main {
            display: grid;
            min-height: 100vh;
            padding: 0 !important;
            /* Explicitly remove padding */
            margin: 0 !important;
            /* Ensure no margin is applied */
            position: relative;
            z-index: 2;
        }
        
        /* Container for video and overlay text */
        .video-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        }
        .background-video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0; /* Ensures the video stays behind */
      }

        /* Background video styling */
        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            /* Ensure the video stays at the background */
        }
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

        body.dark-mode footer {
            background: linear-gradient(1deg, #000724, #151a2d);  
            color: #d1d1d1;
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
            height: 20px;
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

        /* Main content section */
        main {
            display: grid;
            min-height: 100vh;
            padding: 80px 20px 20px 20px;
            position: relative;
            z-index: 1;
        }

        /* Welcome section */
        .welcome-section {
            text-align: center;
            margin: 20px 0;
            color: #003366;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .welcome-section h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #111;
            z-index: 1;
        }

        .welcome-section p {
            font-size: 1.2em;
            color: #111;
            z-index: 1;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            margin: 40px 10px;
            z-index: 1;
        }

        .action-buttons a {
            flex: 1;
            max-width: 300px;
            height: 500px;
            text-decoration: none;
            color: #ffffff;
            font-weight: 500;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #045ed3;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .action-buttons a:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 3px solid #003366;
            animation: glowing 1.5s ease-in-out infinite;
        }

        @keyframes glowing {
            0% {
                border-color: #222;
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
            }

            50% {
                border-color: #222;
                box-shadow: 0 0 10px #222, 0 0 20px #222, 0 0 30px #222;
            }

            100% {
                border-color: darkslategray;
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
            }
        }

        /* Recommendations Section */
        .recommendations {
            text-align: center;
            margin: 20px 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .recommendation-card {
            background: #abcfff;
            border-radius: 170px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.6);
            text-align: center;
            z-index: 1;
        }

        .recommendation-card h3,
        .recommendation-card h2,
        .recommendation-card p,
        .recommendation-card a {
            color: #ffffff;
            z-index: 1;
        }

        .recommendations h2{
            color: black;
            z-index: 1;
        }

        .recommendations a:hover {
            color: #003366;
            font-weight: bold;
            text-decoration: none;
        }

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

        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .action-card {
                font-size: 1rem;
                /* Consistent text size */
                padding: 12px;
                /* Adjusted padding */

            }

            .action-buttons a {
                padding: 80px;
            }



            
        }

        @media (max-width: 480px) {
            header {
                display: flex;
                justify-content: space-evenly;
                height: auto;
                flex-direction: row;
                font-size: 0.8rem;
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
                width: 60px;
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


    <!-- Main Content -->
    <main>

    <section class="video-container">
    <!-- Background Video -->
    <video class="background-video" autoplay muted loop>
        <source src="uploads/homelog.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    
</section>


        <!-- Welcome Section -->
        <section class="welcome-section">
            <?php
            if ($_SESSION['role'] == 'student') {
                echo "<h1>Welcome, " . htmlspecialchars($username) . "!</h1>";
            } else if ($_SESSION['role'] == 'teacher') {
                echo "<h1>Welcome Dr." . htmlspecialchars($username) . "!</h1>";
            }
            ?>
            <p>Your personalized dashboard awaits.</p>
        </section>

        <!-- Action Buttons -->
        <section class="action-buttons">
            <a href="rooms.php"><span> Rooms</span></a>
            <a href="reservations.php"><span>My Reservations</span></a>
            <a href="supportFAQ.php"><span>Support</span></a>
        </section>

        <!-- Recommendations -->
        <section class="recommendations">
            <h2 >Recommended for You</h2>
            <div class="recommendation-card">
                <h3>Room 101</h3>
                <p>Most booked this month. Reserve now!</p>
                <a href="rooms.php">View Details</a>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- University Info -->
            <div class="footer-section">
                <h3>University Info</h3>
                <ul>
                    <li><a href="#about">About Us</a></li>
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
    <script>
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function () {
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const video = document.querySelector('.background-video');

        // Initial theme setup based on localStorage
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.textContent = 'Light Mode';
            video.src = 'uploads/homelogDARK.mp4'; // Dark mode video
        } else {
            themeToggle.textContent = 'Dark Mode';
            video.src = 'uploads/homelog.mp4'; // Light mode video
        }

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

        // Smooth scroll to sections
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            });
        });
    });
</script>

    



</body>

</html>
