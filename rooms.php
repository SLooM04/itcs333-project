<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

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
/* Basic Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    text-align: center;
}

header {
    background-color: #222;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 15px 30px;
    font-family: "Libre Baskerville", Garamond, sans-serif;
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
    border-radius: 12px;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s, box-shadow 0.3s;
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
    transition: transform 0.3s;
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
    padding: 8px 15px;
    border-radius: 5px;
    transition: background-color 0.3s;
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

/* Dropdown */
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

.dropdown:hover .dropdown-content {
    display: block;
}

/* Container */
.container, .rooms {
    display: flex;
    justify-content: center;
    margin-top: 50px;
}
/* ------------------------------------------------*/

/* Department Cards */
.department {
  width: 170px;
  height: 350px; /* Increased height to accommodate the building proportions */
  background-color: #f5f0e1; /* Beige color for the main building */
  border: 3px solid #333; /* Dark border for definition */
  position: relative; /* Needed for absolute positioning of inner elements */
  overflow: hidden; /*To ensure the elements stay within the boundaries*/
  margin: 0 40px;
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
    z-index: 2; /*Ensures the circle is on top of the roof*/

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
  background-color: #1893a3; /* Teal/Green for windows */
  border-radius: 50% 50% 0 0; /*For arched window shape*/
  margin: 10px auto;
  border: 1px solid #333;
}


.department .door-R {
  width: 20%;
  height: 80px;
  background-color: #45a8a7; /* Teal/Green for doors */
  margin: 10px auto;
  position: absolute;
  bottom: -10px;
  left: 60%;
  transform: translateX(-50%);
  border: 1px solid #333;
  text-align: left;
}
.department .door-L {
  width: 20%;
  height: 80px;
  background-color: #45a8a7; /* Teal/Green for doors */
  margin: 10px auto;
  position: absolute;
  bottom: -10px;
  left: 40%;
  transform: translateX(-50%);
  border: 1px solid #333;
  text-align: right;
  
}
.department .door-R-text {
    position: absolute; /* Position the text within the department div */
  bottom: 20px; /* Adjust as needed */
}


.department .door-L-text {
    position: absolute; /* Position the text within the department div */
  bottom: 20px; /* Adjust as needed */
  right: 0px;
}


.department .side {
    width: 25px;
    background-color: #f5f0e1;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    font-size: 13px;
    font-weight: bold;

}
.department .side-right{
    width: 25px;
    background-color: #f5f0e1;
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    font-size: 13px;
    font-weight: bold;

}

.department-text {
  position: absolute;
  bottom: 0px;
  left: 50%;
  transform: translateX(-50%);
  color: #704900;
  font-size: 25px;
  z-index: 10;
  font-weight: bold;
  /* Add pseudo-element for background */
  position: relative; /* Needed to position the pseudo-element */
}

.department-text::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%); /* Vertically center */
  width: 100%; /* Adjust as needed */
  height: 100%; /* Adjust as needed */
  background-color: rgba(0, 0, 0, 0.1); /* Adjust color and opacity */
  z-index: -1; /* Ensure it's behind the text */
  /* Add blurring if needed */
  filter: blur(7px); /* Adjust blur radius */
  border-radius: 8px; /*optional to add rounded corners*/
}




.department:hover {
    background-color: #b5a77b;
}
/* ------------------------------------------------*/

/* Room Gallery */
.room-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Dynamic columns */
    gap: 20px; /* Space between items */
    margin: 20px 20px; /* Add gap on the top/bottom and left/right sides */
    width: 100%; /* Ensure it takes the full width of its parent */
}



.room {
    border: 2px solid #ccc;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    overflow: hidden;
    transition: box-shadow 0.3s;
    
}
.room a{
    text-decoration: none;
}

.room:hover {
    box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
}

.room figure {
    margin: 0;
    
}

.room img {
    width: 100%;
    height: auto;
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
    color: #555;
}

/* Footer */
footer {
    background-color: #222;
    color: #f0f4f7;
    text-align: center;
    padding: 1rem;
    margin-top: 4rem;
    font-size: 0.9rem;
}

footer .footer-container {
    display: flex;
    justify-content: space-around;
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
    font-size: 1rem;
    transition: color 0.3s;
}

footer .footer-section ul li a:hover {
    color: #007bff;
}

footer .footer-bottom {
    font-size: 0.85rem;
    margin-top: 1rem;
    color: #d1d1d1;
}

footer .footer-bottom a {
    color: #d1d1d1;
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
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
            <span> <?php echo htmlspecialchars($username); ?></span>
            <div class="dropdown-content">
                <a href="profile.php">My Profile</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            </div>
        </div>
    </header>
    <!-- Department Selection -->
    <div class="container">
      <div class="department" onclick="showRooms('Information Systems')">
      <div class="roof">|||||||||||||||||||||||||||</div>
      <div class="top-circle"></div>
      <div class="side">S40</div>
      <div class="side-right">S40</div>
      <div class="window">======= <br>========</div>
      <div class="window">======= <br>========</div>
      <div class="door-L"><div class="door-L-text">-</div></div>
      <div class="door-R"><div class="door-R-text">-</div></div>
      <div class="department-text">Information Systems</div> </div>

      <div class="department" onclick="showRooms('Computer Science')">
      <div class="roof">|||||||||||||||||||||||||||</div>
      <div class="top-circle"></div>
      <div class="side">S40</div>
      <div class="side-right">S40</div>
      <div class="window">======= <br>========</div>
      <div class="window">======= <br>========</div>
      <div class="door-L"><div class="door-L-text">-</div></div>
      <div class="door-R"><div class="door-R-text">-</div></div>
      <div class="department-text">Computer Science</div> </div>

      <div class="department" onclick="showRooms('Network Engineering')">
      <div class="roof">|||||||||||||||||||||||||||</div>
      <div class="top-circle"></div>
      <div class="side">S40</div>
      <div class="side-right">S40</div>
      <div class="window">======= <br>========</div>
      <div class="window">======= <br>========</div>
      <div class="door-L"><div class="door-L-text">-</div></div>
      <div class="door-R"><div class="door-R-text">-</div></div>
      <div class="department-text">Network Engineering</div> </div>

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