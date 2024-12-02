<?php
session_start();
require 'db.php'; // Include the DB connection file
if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Check if room ID is provided in the URL
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Fetch room details from the database
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "Room not found.";
        exit();
    }
} else {
    echo "No room selected.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
        /* Basic Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            margin: 0 0 0 0;
            padding: auto;
            text-align: center;

        }

        body.dark-mode {
            background-color: #2e4156;
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
        border-radius: 10%;
        border: 3px solid #f0f0f0;
        transition: transform 0.3s;
    }


        /* Header Styles */
  header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 10px 30px;
            background-color: #1a73e8;
            color: white;
            height: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1000;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            gap: 40px;
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
        /* Room details layout */
        .room-container {
            border: 2px solid #ccc;
            /* Reduced border size (2px instead of default or larger sizes) */
            border-radius: 10px;
            /* Optional for rounded corners */
            display: flex;

            align-items: center;
            justify-content: center;
        }

        .info {
            background-color: #e4f0f2;
            border-radius: 15px;
            padding: 3%;



        }

        /* Left side with large image and thumbnails */
        .room-images {
            width: 50%;
            margin-right: 110px;

        }

        .main-image {
            width: 60%;
            height: auto;
            margin-bottom: 20px;
            border: 2px solid #1A2D42;
            border-radius: 15px;

        }

        .thumbnail-images {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .thumbnail-images img {
            width: 150px;
            height: auto;
            cursor: pointer;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .thumbnail-images img:hover {
            transform: scale(1.2);
        }

        /* Right side with room details */
        .room-details {
            width: 50%;
            text-align: left;

        }

        .room-details h2 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #000000;
        }

        .room-details p {
            font-size: 1.1em;
            margin: 5px 0;
            color: #000000;
        }

        .reserve-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #1A2D42;
            color: white;
            border-radius: 15px;
            cursor: pointer;

        }

        .reserve-button:hover {
            background-color: #AAB7B7;
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



        /* Media Query for Tablets */
        @media (min-width: 600px) and (max-width: 1024px) {
            .room-container {
                flex-direction: row;
                /* Side-by-side layout for tablets */
            }

            .room-images,
            .room-details {
                width: 45%;
                /* Take up less space on tablets */
            }

            .thumbnail-images img {
                width: 100px;
                height: 100px;
            }
        }

        /* Media Query for Mobile Screens */
        @media (max-width: 599px) {
            header {
                flex-direction: column;
                /* Stack header elements vertically */
            }

            nav {
                flex-direction: column;
                gap: 5px;
            }

            .room-images,
            .room-details {
                width: 100%;
            }

            .info {
                display: grid;
            }

            .thumbnail-images img {
                width: 60px;
                height: 60px;
            }

            .reserve-button {
                width: 100%;
                /* Full-width button for mobile */
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
            <a href="reservations.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : ''; ?>">My Reservations</a>
            <a href="support.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>">Support</a>
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
    <main>


        <div class="room-container">
            <div class="room-images">
                <!-- Main Room Image -->
                <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" class="main-image" id="main-image">

                <!-- Thumbnail Images -->
                <div class="thumbnail-images">
                    <!-- First thumbnail is the main image -->
                    <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="Main Image" onclick="changeMainImage('<?php echo htmlspecialchars($room['image']); ?>')">

                    <?php
                    // Display additional thumbnail images if available
                    for ($i = 1; $i <= 4; $i++) {
                        $thumb = $room['thumbnail_' . $i] ?? 'default-thumbnail.jpg'; // Default if no thumbnail
                        if ($i > 1) { // Skip the first one since it's already the main image
                            echo '<img src="RoomPic/' . htmlspecialchars($thumb) . '" alt="Thumbnail ' . $i . '" onclick="changeMainImage(\'' . htmlspecialchars($thumb) . '\')">';
                        }
                    }
                    ?>
                </div>
            </div>
            <figure class="info">
                <div class="room-details">
                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                    <p><strong>Room Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                    <p><strong>Available Timeslot:</strong> <?php echo htmlspecialchars($room['available_timeslot']); ?></p>
                    <p><strong>Room Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>

                    <!-- Reserve Button -->
                    <form action="Room_Booking.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $room['id']; ?>" />
                        <button type="submit">Book Now</button>
                    </form>

                </div>
            </figure>


            <script>
                function changeMainImage(image) {
                    document.getElementById("main-image").src =RoomPic/ image;
                }
            </script>

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