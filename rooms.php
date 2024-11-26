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
    <style>
        /* Basic Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0 0 0 0;
            text-align: center;

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

        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: antiquewhite;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
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
            color: #003366;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f4f7f6;
        }

        /* Keep the dropdown visible when hovering over the parent or the dropdown-content */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        p.dep{
            text-align: center;
            margin: 20px 0;
            color: #222;
            font-weight: bold;

            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            font-size: 2.5em;
            margin-bottom: 10px;
            color: black;
        }


        .department-type-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            margin-top: 3rem;
            flex-wrap: wrap;
            transition: all 0.5s ease;
        }

        /* Make the entire account card clickable */
        .department  {
            display: block;
            width: 250px;
            text-align: center;
            padding: 2rem;
            border: 2px solid transparent;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, border-color 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none; /* Remove text underline */
        }

        /* Hover animation for the selection */
        .department:hover {
            transform: translateY(-12px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-color: #007bff;
            background-color: #f7f7f7;
        }

        .department img {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .department:hover img {
            transform: scale(1.1);
        }

        .department h2 {
            font-size: 1.5rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .department:hover h2 {
            color: #007bff;
        }


        .rooms {
            display: block;
            margin-top: 30px;
            text-align: center;
        }

        .room-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* Ensures 4 columns */
            gap: 20px;
            /* Space between grid items */
            margin: 20px auto;
            max-width: 1000px;
        }


        .room {
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .room a {
            text-decoration: none;
            /* Removes underline from room links */
            color: inherit;
            /* Keeps the current color (default text color) */
        }


        .room figure {
            margin: 0;

        }

        .room img {
            width: 100%;
            height: auto;
            display: block;
        }

        .room figcaption {
            padding: 15px;
            text-align: left;
        }

        .room h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .room p {
            margin: 5px 0;
            font-size: 1em;
            color: #555;

        }

        .room:hover {
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
            /* Adds a shadow effect */
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
            <a href="rooms.php">Rooms</a>
            <a href="reservations.php">My Reservations</a>
            <a href="support.php">Support</a>
        </nav>

        <!-- User Profile Section -->
        <div class="user-profile dropdown">
            <img src="https://cdn.pixabay.com/photo/2021/07/02/04/48/user-6380868_1280.png" alt="User Avatar">
            <span> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <div class="dropdown-content">
                <a href="profile.php">My Profile</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            </div>
        </div>
    </header>

        <p class = "dep">Departments</p>
        <div class="department-type-container">
            <!-- Information Systems -->
            <div class="department" onclick="showRooms('Information Systems')">
                    <img src="https://t3.ftcdn.net/jpg/05/34/96/24/360_F_534962400_yI5SiJ0dNhVdDN6UIt9oyAM0z7jcyiAT.jpg" alt="Taecher Icon">
                    <h2>Information Systems</h2>
                </a>
            </div>

            <!-- Computer Science -->
            <div class="department" onclick="showRooms('Computer Science')">
                    <img src="https://t3.ftcdn.net/jpg/05/34/96/24/360_F_534962400_yI5SiJ0dNhVdDN6UIt9oyAM0z7jcyiAT.jpg" alt="Teacher Icon">
                    <h2>Computer Science</h2>
                </a>
            </div>

            <!-- Network Engineering -->
            <div class="department" onclick="showRooms('Network Engineering')">
                    <img src="https://t3.ftcdn.net/jpg/05/34/96/24/360_F_534962400_yI5SiJ0dNhVdDN6UIt9oyAM0z7jcyiAT.jpg" alt="Teacher Icon">
                    <h2>Network Engineering</h2>
                </a>
            </div>
        </div>
    </div>


    <!-- Room Selection (Dynamic Content) -->
    <div id="rooms" class="rooms">
        <div id="roomSelection" class="room-gallery">
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <a href="room_details.php?id=<?php echo htmlspecialchars($room['id']); ?>">
                            <figure>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                                <?php else: ?>
                                    <img src=".jpg" alt="Default Room Image">
                                <?php endif; ?>
                                <figcaption>
                                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                                    <p><strong>Available Timeslot:</strong> <?php echo htmlspecialchars($room['available_timeslot']); ?></p>
                                    <p><strong>Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>
                                    <p><strong>Department:</strong> <?php echo htmlspecialchars($room['department']); ?></p>
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

    <script>
        function showRooms(department) {
            // Fetch rooms data based on the selected department
            window.location.href = '?department=' + department;
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
            <p>&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy">Privacy Policy</a> |
                <a href="https://www.uob.edu.bh/terms-and-conditions">Terms of Service</a>
            </p>
        </div>
    </footer>






















</body>

</html>