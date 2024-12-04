<?php
session_start();
require 'db.php'; // Include the DB connection file


// Function to fetch rooms from the database based on department
function fetchRooms($department = null)
{
    global $pdo;

    // If department is provided, fetch rooms by department
    if ($department) {
        $sql = "SELECT * FROM rooms WHERE department = :department";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['department' => $department]);
    } else {
        // Fetch all rooms if no department is specified
        $sql = "SELECT * FROM rooms";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch rooms if a department is selected
$rooms = [];
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $rooms = fetchRooms($department); // Fetch rooms by department
} else {
    $rooms = fetchRooms(); // Fetch all rooms
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>    
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* General styles */
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

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
            z-index: 1000000000;

        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode p,
        body.dark-mode a {
            color: black;
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
        body.dark-mode {
            background-color: #2e4156;
            color: white;
        }

        body.dark-mode .dropdown-content a:hover {
            background-color: #2e4156;
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

        body.dark-mode .dropdown-content a {
            color: #edf4fa;
        }

        body.dark-mode footer {
            background-color: #1a2d42;
            color: #d1d1d1;
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

        .action-buttons a {


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
            background-color: #1a73e8;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            margin: 40px 10px;
        }



        .action-buttons a:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 3px solid #003366;
            animation: glowing 1.5s ease-in-out infinite;
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

        



        /* Container */
        .container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            /* Enable snapping to cards */
            gap: 20px;
            /* Space between cards */
            padding: 20px;
            /* Padding around the container */


        }


        .rooms {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            padding: 10px 10% 10px 10%;
        }



        /* ------------------------------------------------*/
        /* Department Cards */
        .department {
            flex: 0 0 auto;
            /* Prevent shrinking or growing, maintain width */
            scroll-snap-align: center;
            /* Snap card to the center */
            width: 170px;
            height: 350px;
            background-color: #f5f0e1;
            border: 3px solid #333;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            /* Center each card horizontally */
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        }

        .department .top-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f5f0e1;
            position: absolute;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            border: 2px solid #333;
            z-index: 2;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to the circle */
        }

        .department .top-circle::before {
            content: "";
            position: absolute;
            width: 1px;
            height: 45px;
            background-color: #333;
            left: 50%;
            top: 0%;
            transform: translateX(-50%) rotate(45deg);
        }

        .department .top-circle::after {
            content: "";
            position: absolute;
            width: 1px;
            height: 45px;
            background-color: #333;
            left: 50%;
            top: 0%;
            transform: translateX(-50%) rotate(135deg);
        }

        .department .window {
            width: 60%;
            height: 60px;
            background-color: #1893a3;
            border-radius: 50% 50% 0 0;
            margin: 10px auto;
            border: 1px solid #333;
            box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.2);
            /* Subtle inner shadow */
        }

        .department .door-R,
        .department .door-L {
            width: 20%;
            height: 80px;
            background-color: #45a8a7;
            margin: 10px auto;
            position: absolute;
            bottom: -10px;
            transform: translateX(-50%);
            border: 1px solid #333;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to doors */
        }

        .department .door-R {
            left: 60%;
            text-align: left;
        }

        .department .door-L {
            left: 40%;
            text-align: right;
        }

        .department .door-R-text,
        .department .door-L-text {
            position: absolute;
            bottom: 20px;
        }

        .department .door-L-text {
            right: 0px;
        }

        .department .side,
        .department .side-right {
            width: 25px;
            background-color: #f5f0e1;
            height: 100%;
            position: absolute;
            top: 0;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to sides */
        }

        .department .side-right {
            right: 0;
        }

        .department-text {
            position: absolute;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #704900;
            font-size: 25px;
            z-index: 10;
            font-weight: bold;
            position: relative;
        }

        .department-text::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            /* Lighter background */
            z-index: -1;
            filter: blur(7px);
            border-radius: 8px;
        }

        .department:hover {
            transform: scale(1.05);
            /* Slightly enlarge the card */
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.4);
            /* Add a stronger shadow */
            border-color: #555;
            /* Highlight the border */
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        /* Door Sliding Animation */
        .department:hover .door-R {
            transform: translateX(100%);
            /* Slide the right door outward to the right */
            transition: transform 0.6s ease;
        }

        .department:hover .door-L {
            transform: translateX(-200%);
            /* Slide the left door outward to the left */
            transition: transform 0.6s ease;
        }

        /* Ensure Default State for Doors */
        .department .door-R,
        .department .door-L {
            transform-origin: left center;
            /* Rotate from the edge */
            transition: transform 0.4s ease;
        }

        .department .door-L {
            transform-origin: right center;
            /* Rotate from the edge */
        }

        /* ------------------------------------------------*/

        /* Room Gallery */
        .room-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* Create 3 equal columns */
            gap: 20px;
            /* Space between items */
            margin: 20px;
            /* Space around the gallery */

        }



        .room {
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
            transition: box-shadow 0.3s;
            margin: 0;
            max-width: 200px;
            /* Set a fixed width for smaller boxes */
            padding: 10px;
            /* Add some padding */

        }

        .room a {
            text-decoration: none;
        }

        .room:hover {
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .room figure {
            margin: 0;

        }

        .room img {
            width: 100%;
            /* Makes the image fill the box */
            height: 150px;
            /* Set a fixed height */
            object-fit: contain;
           
        }

        .room figcaption {
            padding: 1px;
            text-align: left;
        }

        .room h2 {
            font-size: 1.2em;
            margin-bottom: 8px;
            color: #000;
        }

        .room p {
            margin: 5px 0;
            color: #000;
            font-size: 0.9em;
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

    .toggle-buttons {
        text-align: center;
        margin-bottom: 20px;
    }

    .toggle-buttons button {
        padding: 10px 20px;
        margin: 5px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #618bb8;
        color: white;
    }

    .toggle-buttons button:hover {
        background-color: #396391;
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

            .room-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                /* Dynamic columns */
                gap: 40px;
                /* Space between items */
                margin: 20px 20px;
                /* Add gap on the top/bottom and left/right sides */
                width: 100%;
                /* Ensure it takes the full width of its parent */
            }
        

        .all {
         text-align: center;
         vertical-align: middle;
         line-height: 0.5; /* Adjust line spacing if needed */
         font-size: 30px; /* Adjust text size */
         color: #333; /* Text color */
         font-weight: bold; /* Make it bold */
         font-family: 'Arial', sans-serif; /* Font family */
         padding: 10px; /* Space inside the element */
         margin: 1px auto; /* Center the div horizontally (if block-level) */
         border: 1px solid #ccc; /* Optional border */
         background-color: #f9f9f9; /* Optional background */
         border-radius: 338px; /* Rounded corners */
         max-width: 600px; /* Limit width */
         display: flex; /* Enable flexbox */
         align-items: center; /* Align items vertically in the center */
         justify-content: center; /* Center content horizontally */
         gap: 10px; /* Space between .all and .down */
         height: 80px;
         padding-left: 90px;

        }

        .down {
         width: 80px; /* Adjust width */
         height: auto; /* Maintain aspect ratio */
         display: block; /* To center, if needed */
         margin: 0 auto; /* Center horizontally */
         border-radius: 8px; /* Rounded corners (optional) */
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
    <span>
        <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
    </span>
    <div class="dropdown-content">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">My Profile</a>
            <a href="settings.php">Settings</a>
            <a id="themeToggle">Dark Mode</a>
            <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
        <?php else: ?>
            <a href="combined_login.php">Login</a>
            <a href="account_type.php">Register</a>
            <a id="themeToggle">Dark Mode</a>

        <?php endif; ?>
    </div>
</div>
        </div>
    </header>
    <!-- Department Selection -->
    <div class="container">
        <div class="department" onclick="redirectToPage('IS')">
            
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Information Systems</div>
        </div>

        <div class="department" onclick="redirectToPage('CS')">
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Computer Science</div>
        </div>

        <div class="department" onclick="redirectToPage('NE')">
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Network Engineering</div>
        </div>

    </div>
      <div class="all">
     All Departments room
     <img src="uploads/down.png" alt="down here" class="down">


       </div>
    <!-- Room Selection (Dynamic Content) -->
    <div id="rooms" class="rooms">

     
        <!-- Map View -->
       <div id="mapclick" class="map" style="display: none;">
        <h4>Map View Content Here</h4>
       </div>

       <!-- All rooms View -->
       <div id="Allrooms" class="room-gallery" >
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <a href="room_details.php?id=<?php echo htmlspecialchars($room['id']); ?>">
                            <figure>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                                <?php else: ?>
                                    <img src="RoomPic/jpg" alt="Default Room Image">
                                <?php endif; ?>
                                <figcaption>
                                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                    <p>
                                        <strong><img src="Seats.png" alt="Capacity Icon" style="width: 28px; height: 28px; vertical-align: middle;"> Capacity:</strong>
                                        <?php echo htmlspecialchars($room['capacity']); ?>
                                    </p>
                                    <p>
                                        <strong><img src="de.png" alt="Department Icon" style="width: 24px; height: 28px; vertical-align: middle;"> Department:</strong>
                                        <?php echo htmlspecialchars($room['department']); ?>
                                    </p>
                                </figcaption>

                            </figure>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected department.</p>
            <?php endif; ?>
        </div>
    </div>
        
    
        <!-- department rooms View -->
        <div id="roomSelection" class="room-gallery" style="display: none;">
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <a href="room_details.php?id=<?php echo htmlspecialchars($room['id']); ?>">
                            <figure>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                                <?php else: ?>
                                    <img src="RoomPic/jpg" alt="Default Room Image">
                                <?php endif; ?>
                                <figcaption>
                                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                    <p>
                                        <strong><img src="Seats.png" alt="Capacity Icon" style="width: 28px; height: 28px; vertical-align: middle;"> Capacity:</strong>
                                        <?php echo htmlspecialchars($room['capacity']); ?>
                                    </p>
                                    <p>
                                        <strong><img src="de.png" alt="Department Icon" style="width: 24px; height: 28px; vertical-align: middle;"> Department:</strong>
                                        <?php echo htmlspecialchars($room['department']); ?>
                                    </p>
                                </figcaption>

                            </figure>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected department.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- selected department -->
    <script>
        function  showRooms(department) {
            // Fetch rooms data based on the selected department
            window.location.href = '?department =' + department ;
        }
    </script>

 <script>
    function redirectToPage(department) {
        // Redirect based on the department clicked
        if (department === 'IS') {
            window.location.href = "information_system.php";
        } else if (department === 'CS') {
            window.location.href = "computer_science.php";
        } else if (department === 'NE') {
            window.location.href = "network_engineering.php";
        }
    }
 </script>

   <script >
    function showView(viewId) {
        // Hide all views initially
        document.getElementById('Allrooms').style.display = 'none';
        document.getElementById('roomSelection').style.display = 'none';
        document.getElementById('mapclick').style.display = 'none';

        // Show the selected view
        document.getElementById(viewId).style.display = 'block';
    }
   </script>






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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>

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
       
    </script>






</body>

</html>