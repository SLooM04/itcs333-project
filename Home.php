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

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        /* General styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333;
        }

        body.dark-mode {
            background-color: #2e4156;
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

        body.dark-mode header {
            background-color: #1a2d42;
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

        body.dark-mode footer {
            background-color: #1a2d42;
            color: #d1d1d1;
        }

        /* Header Styles */
        header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 10px 30px;
            background-color: #1a73e8;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1000;
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
            border-radius: 10%;
            border: 3px solid #f0f0f0;
            transition: transform 0.3s;
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

        /* Ensure main content is above the video */
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

        /* Action Buttons */



        /* Styling for h1 element with shadow effects */
        .overlay-text h1 {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            /* Text shadow */
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background for better visibility */
            padding: 10px 20px;
            /* Add padding around the text */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.5);
            /* Background shadow */
            color: white;
            /* Text color */
            text-align: center;
        }

        /* Footer styles */
        footer {
            color: white;
            background-color: #1a73e8;
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

        footer .footer-section ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            text-decoration: underline;
        }

        /* Main Section Styling */

        /* Container for video and overlay text */
        .video-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        /* Background video styling */
        .video-container .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            /* Ensure the video stays at the background */
        }

        /* Overlay text styling */
        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 2;
            /* Ensure the text stays above the video */
        }

        /* Optional: Styling for the text */
        .overlay-text h1 {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            /* Add a shadow to improve readability */
        }
        .col-lg-4 h2{
            color: #1a73e8;
        }

        /* Responsive design for the footer */
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



            footer .footer-section {
                align-items: center;
            }
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
            <img src="https://cdn.discordapp.com/attachments/791220541376692234/1310228238240583690/UOBandSilverJubilee-Colleges-new-logo-1.png?ex=674474a2&is=67432322&hm=48b965ce1457aa8031fb40b08ab17ffb6fcdc7924a004aaebf258e485756497b&" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->
        <nav class="nav-links">
            <a href="homelog.php" class="nav-item ">Home</a>
            <a href="combined_login.php" class="nav-item ">Rooms</a>
            <a href="combined_login.php" class="nav-item ">Login</a>
            <a href="account_type.php" class="nav-item ">Resigter</a>
        </nav>

        </div>
    </header>

    <!-- Main Content Section -->
    <main>






        <section class="video-container">
            <!-- Background Video -->
            <video class="background-video" autoplay muted loop>
                <source src="homevid.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Overlay Text -->
            <div class="overlay-text">
                <h1>Reserve your classroom now and take full advantage of the college facilities.</h1>
            </div>
        </section>
        <div class="container marketing">
    <hr class="featurette-divider">
    <div class="row">
        <div class="col-lg-4">
            <img class="rounded-circle" src="h1.png" alt="Classroom Image" width="140" height="140">
            <h2>Book a Classroom</h2>
            <p>Experience seamless learning in our modern classrooms equipped with the latest technology, perfect for lectures, workshops, and group study sessions.</p>
            <a class="btn btn-secondary" href="rooms.php" role="button">Book Now »</a>
        </div>

        <div class="col-lg-4">
            <img class="rounded-circle" src="h2.png" alt="Classroom Image" width="140" height="140">
            <h2>Flexible Room Setup</h2>
            <p>Our classrooms are designed to meet your needs, whether you're hosting a small seminar or a large conference. Choose from a variety of room setups to match your event.</p>
            <a class="btn btn-secondary" href="rooms.php" role="button">Explore Options »</a>
        </div>

        <div class="col-lg-4">
            <img class="rounded-circle" src="h3 - Copy.png" alt="Classroom Image" width="140" height="140">
            <h2>Advanced Labs</h2>
            <p>Each room is equipped with the latest technology, including projectors, interactive whiteboards, and high-speed internet, to support dynamic teaching methods and student engagement.</p>
            <a class="btn btn-secondary" href="rooms.php" role="button">View Details »</a>
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">Why Choose Our Classrooms? <span style="color: #1a73e8;" >Everything you need for a productive session.</span></h2>
            <p class="lead">Our classrooms are designed to create an optimal environment for learning and collaboration. With comfortable seating, modern amenities, and a range of available resources, you'll have everything you need to make the most out of your time.</p>
        </div>
        <div class="col-md-5">
            <img class="featurette-image img-fluid mx-auto" alt="Classroom Setup" style="width: 400px; height: 100%;"  src="n0.jpg" data-holder-rendered="true">
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Oh yes, it's that good. <span style="color: #1a73e8;" >See for yourself.</span></h2>
            <p class="lead">We offer a learning environment equipped with everything you need to succeed. Our classrooms are flexible, allowing you to customize every detail to meet your needs.</p>
        </div>
        <hr class="featurette-divider">
        <div class="col-md-5 order-md-1">
            <img class="featurette-image img-fluid mx-auto" data-src="holder.js/500x500/auto" alt="500x500" style="width: 400px; height: 100%;" src="008.jpg" data-holder-rendered="true">
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">And lastly, this one. <span style="color: #1a73e8;" >Checkmate.</span></h2>
            <p class="lead">Our classrooms provide the perfect environment for your next lecture, seminar, or workshop. Equipped with advanced facilities, they support both traditional and modern teaching techniques.</p>
        </div>
        <div class="col-md-5">
            <img class="featurette-image img-fluid mx-auto" data-src="holder.js/500x500/auto" alt="500x500" style="width: 400px; height: 100%" src="h.jpg" data-holder-rendered="true">
        </div>
    </div>
</div>



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

</body>

</html>