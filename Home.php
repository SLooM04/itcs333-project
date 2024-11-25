<?php
session_start();
require 'db.php';
/*
// Get all rooms from the database
$stmt = $pdo->prepare("SELECT * FROM rooms");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/
// Check if the button is clicked
if (isset($_POST['reserve_now'])) {
    // Add a delay if you want
    sleep(2); // This pauses for 2 seconds

    // Redirect to the desired page
    header("Location: rooms.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms List</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        header {
            background-color: #222;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 15px 30px;
            font-family: "Libre Baskerville", Garamond, sans-serif;
            font-size: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

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
            background: linear-gradient(90deg, #d1d1d1, #222);
            /* Gradient background */
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
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.15);
        }

        /* Navigation Links */
        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #d1d1d1;
            color: #222;
        }

        /* Ensure main content is above the video */
        main {
            display: grid;
            min-height: 100vh;
            padding: 80px 20px 20px 20px;
            /* Padding to make space for the fixed menu */
            position: relative;
            z-index: 2;
        }

        /* Make sure the video covers the whole background */
        video.background-video {

            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            object-fit: cover;
            z-index: 10;

        }

        /* Footer styles */
        footer {
            background-color: #222;
            color: #f0f4f7;
            text-align: center;
            padding: 1rem 1rem;
            /* Reduced padding */
            margin-top: 4rem;
            /* Added space between content and footer */
            font-size: 0.9rem;
            /* Reduced font size */
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
        }

        footer .footer-section ul li a {
            color: #d1d1d1;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            color: #007bff;
        }

        footer .footer-bottom {
            font-size: 0.85rem;
            margin-top: 1rem;
            /* Reduced margin */
            color: #d1d1d1;
        }

        footer .footer-bottom a {
            color: #d1d1d1;
            text-decoration: none;
        }

        footer .footer-bottom a:hover {
            color: #007bff;
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
            <a href="combined_login.php">Rooms</a>
            <a href="combined_login.php">Login</a>
            <a href="account_type.php">Resigter</a>
        </nav>

        </div>
    </header>

    <!-- Main Content Section -->
    <main>
        <section name="vide">


            <!-- Background Video -->
            <video class="background-video" autoplay muted loop>
                <source src="homevid.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

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
            <p>&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy">Privacy Policy</a> |
                <a href="https://www.uob.edu.bh/terms-and-conditions">Terms of Service</a>
            </p>
        </div>
    </footer>

</body>

</html>