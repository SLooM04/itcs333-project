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
        /* Basic Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0 0 0 0;
            padding: auto;
            text-align: center;

        }

        header {
            background-color: #2e4156;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 15px 30px;
            font-family: "Libre Baskerville", Garamond, sans-serif;
            font-size: auto;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            border: 3px solid white;
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
            color: white;
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
            color: white;
        }

        .user-profile img {
            width: 50px;
            height: 50px;
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
            background-color: #2e4156;
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

    <main>


        <div class="room-container">
            <div class="room-images">
                <!-- Main Room Image -->
                <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" class="main-image" id="main-image">

                <!-- Thumbnail Images -->
                <div class="thumbnail-images">
                    <!-- First thumbnail is the main image -->
                    <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="Main Image" onclick="changeMainImage('<?php echo htmlspecialchars($room['image']); ?>')">

                    <?php
                    // Display additional thumbnail images if available
                    for ($i = 1; $i <= 4; $i++) {
                        $thumb = $room['thumbnail_' . $i] ?? 'default-thumbnail.jpg'; // Default if no thumbnail
                        if ($i > 1) { // Skip the first one since it's already the main image
                            echo '<img src="' . htmlspecialchars($thumb) . '" alt="Thumbnail ' . $i . '" onclick="changeMainImage(\'' . htmlspecialchars($thumb) . '\')">';
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
                    document.getElementById("main-image").src = image;
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
            <p>&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy">Privacy Policy</a> |
                <a href="https://www.uob.edu.bh/terms-and-conditions">Terms of Service</a>
            </p>
        </div>
    </footer>

</body>

</html>