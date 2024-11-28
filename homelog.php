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
        /* General styles */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to top, #1f2a38, white);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body.dark-mode {
            background: linear-gradient(to top, #0d1117, #1f2a38);            
            color: white;
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

        body.dark-mode .recommendation-card {
            background-color: #2e344e;
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
        body.dark-mode .user-profile span {
            color: white;
        }

        body.dark-mode .action-buttons a{
            color: #000;
        }

        

        /* Header Styles */
        header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 15px 30px;
            font-family: "Libre Baskerville", Garamond, sans-serif;
            font-size: auto;
            margin-bottom: 1rem;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8em;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 20px;
            background: linear-gradient(90deg, #abbac9, #2e4156);
            border-radius: 12px;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
        }

        .logo img {
            width: 200px;
            height: auto;
            border-radius: 10%;
            border: 3px solid ;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.15);
        }

        nav {
            display: flex;
            gap: 40px;
            margin-bottom: 10px;
        }

        nav a {
            color: #222;
            text-decoration: none;
            font-size: 1.1em;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #abbac9;
            color: #222;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #222;
        }

        .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #222;
        }

        .user-profile span {
            font-size: 1em;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
            border-radius: 0;
            z-index: 2000;
        }

        .dropdown-content a {
            color: #222;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #abbac9;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        @media screen and (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 10px;
            }

            .logo {
                font-size: 1.0em;
            }

            .logo img {
                width: 100px;
                height: auto;
            }

            .dropdown-content {
                min-width: 100px;
            }
        }

        /* Main content section */
        main {
            border-top: none; /* Smooth transitions */
            display: grid;
            min-height: 100vh;
            padding: 80px 20px 20px 20px;
            position: relative;
        }

       
        /* Welcome section */
        .welcome-section {
            text-align: center;
            margin: 20px 0;
            color: #003366;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .welcome-section h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: black;
        }

        .welcome-section p {
            font-size: 1.2em;
            color: black;
        }

        /* Container for Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            margin: 40px 10px;
        }

        /* Container for Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            margin: 40px 10px;
        }

        /* Button Styles */
        .action-buttons a {
            flex: 1;
            max-width: 300px;
            height: 500px;
            position: relative;
            text-decoration: none;
            color: #222;
            font-weight: bold;
            font-size: 1.2em;
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 2px solid #000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #aab7b7;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }


        /* Text Placement */
        .action-buttons a span {
            position: relative;
            z-index: 2;
            /* Ensures the text appears above the overlay */
        }

        /* Hover Effects */
        .action-buttons a:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 3px solid #003366;
            animation: glowing 1.5s ease-in-out infinite;
        }

        /* Glowing effect */
        @keyframes glowing {
            0% {
                border-color: #222;
                /* Initial border color */
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
            }

            50% {
                border-color: #222;
                box-shadow: 0 0 10px #222, 0 0 20px #222, 0 0 30px #222;
                /* More intense green glow */
            }

            100% {
                border-color: darkslategray;
                /* End border color */
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
                /* Soft green glow */
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: row;
                align-items: center;
            }

            .action-buttons a {
                max-width: 100%;
                height: 500px;
                background-image: url('uploads/card-background.jpg');
            }
        }

        /* Recommendations Section */
        .recommendations {
            text-align: center;
            margin: 20px 20px;
            color: #222;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .recommendation-card {
            background: #aab7b7;
            border-radius: 10px;
            color:#222 ;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .recommendation-card   h3 , h2 , p , a{
            color:#222 ;
            text-align: center;
        }

        .recommendations a:hover {
            color: #003366;
            font-weight: bold;
            text-decoration: none;
        }






            /* Footer styles */
        footer {
            color: white;
            text-align: center;
            padding: 1rem 1rem; 
            margin-top: 9rem; 
            font-size: 0.9rem; 
            
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem; 
            text-align: left; 
        }

        footer .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        footer .footer-section ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        footer .footer-section ul li {
            margin: 0.4rem 0;
            list-style-type:disc;
            color: white;
        }

        footer .footer-section ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            color:black ;
            font-weight: bolder;
        }

        footer .footer-bottom {
            font-size: 0.85rem;
            margin-top: 1rem; 
            color: white;
        }

        footer .footer-bottom a {
            color: white;
            text-decoration: none;
        }

        footer .footer-bottom a:hover {
            color: black;
        }

        /* Responsive design for the footer */
        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem; /* Reduced margin */
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }
        }

       
        
    </style>

</head>

<body>

    <header>
        <!-- Logo Section -->
        <a href="homelog.php" class="logo">
            <img src="https://cdn.discordapp.com/attachments/791220541376692234/1310228238240583690/UOBandSilverJubilee-Colleges-new-logo-1.png?ex=674474a2&is=67432322&hm=48b965ce1457aa8031fb40b08ab17ffb6fcdc7924a004aaebf258e485756497b&" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->
        <nav>
            <a href="homelog.php">Home</a>
            <a href="rooms.php">Rooms</a>
            <a href="reservations.php">My Reservations</a>
            <a href="support.php">Support</a>
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

        <!-- Welcome Section -->
        <section class="welcome-section">
            <?php
            if($_SESSION['role'] == 'student'){
            echo "<h1>Welcome, " . htmlspecialchars($username). "!</h1>";
            }else if ($_SESSION['role'] == 'teacher'){
                echo "<h1>Welcome Dr.". htmlspecialchars($username). "!</h1>";}
                ?>
            <p>Your personalized dashboard awaits.</p>
        </section>

        <!-- Action Buttons -->
        <section class="action-buttons">
            <a href="rooms.php"><span>View Available Rooms</span></a>
            <a href="reservations.php"><span>My Reservations</span></a>
            <a href="support.php"><span>Contact Support</span></a>
        </section>

        <!-- Recommendations -->
        <section class="recommendations">
            <h2>Recommended for You</h2>
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
            <p style = "color:white;">&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy">Privacy Policy | </a>  
                <a href="https://www.uob.edu.bh/terms-and-conditions">Terms of Service</a> 
            </p>
        </div>
</footer>
    <script>
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

    </script>


</body>

</html>
