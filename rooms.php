<?php
session_start();
require 'db.php'; // Include the DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Home'])) {
    // Redirect to Home.php
    header("Location: Home.php");
    exit();
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
            margin-top: 10%;
            padding: 0;
            text-align: center;
            position: relative;
        }

        .container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .department {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            width: 200px;
            background-color: #004488;
            color: white;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .department:hover {
            background-color: #0055a5;
        }

        .rooms {
            display: none;
            margin-top: 30px;
            text-align: center;
        }

        .room-selection {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .room-selection button {
            margin: 10px;
            width: 120px;
            height: 60px;
            background-color: #003366;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .room-selection button:hover {
            background-color: #0055a5;
        }

        #roomSelection {
            display: grid;
            justify-content: center;
            margin: 20px 0;
            grid-template-columns: repeat(2, 1fr);
            gap: 99px;
        }

        nav {
            background-color: #003366;
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            margin: 0 15px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #0055a5;
        }

        nav .button {
            background-color: #003366;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            margin: 0 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        nav .button:hover {
            background-color: #0055a5;
        }

        /* Footer styles */
        footer {
            background-color: #222;
            color: #f0f4f7;
            text-align: center;
            padding: 1rem 1rem;
            margin-top: 4rem;
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
            font-size: 1rem;
        }

        footer .footer-bottom {
            font-size: 0.85rem;
            margin-top: 1rem;
            color: #d1d1d1;
        }

        footer .footer-bottom a {
            color: #d1d1d1;
            text-decoration: none;
        }

        footer .footer-bottom a:hover {
            color: #007bff;
        }

        /* Room Gallery Styles */
        .room-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px auto;
            max-width: 800px;
        }

        .room {
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
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
    </style>
</head>

<body>

    <!-- Department Selection -->
    <div class="container">
        <div class="department" onclick="showRooms('information')">Information Systems</div>
        <div class="department" onclick="showRooms('computer')">Computer Science</div>
        <div class="department" onclick="showRooms('network')">Network Engineering</div>
        
    </div>

    <!-- Room Selection (Dynamic Content) -->
    <div id="rooms" class="rooms">
        <div id="floors" class="floor"></div>
        <div id="roomSelection" class="room-selection"></div>
    </div>

    <!-- Navigation -->
    <nav class="menu">
        <a href="home.php" class="button">home</a>
        <a href="rooms.php" class="button">Rooms</a>
        <a href="profile.php" class="button">Profile</a>
        <a href="logout.php" class="button">Logout</a>
    </nav>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>About Us</h3>
                <ul>
                    <li><a href="#">Company</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Sales</a></li>
                    <li><a href="#">Location</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2024 Room Booking System | All rights reserved.
        </div>
    </footer>

    <script>
        function showRooms(department) {
            let roomsSection = document.getElementById('rooms');
            let roomSelection = document.getElementById('roomSelection');
            let floorsSection = document.getElementById('floors');

            // Clear existing content
            roomSelection.innerHTML = '';
            floorsSection.innerHTML = '';

            // Show rooms based on the department clicked
            if (department === 'network') {
                roomSelection.innerHTML = getRoomHTML('Network Engineering');
            } else if (department === 'information') {
                roomSelection.innerHTML = getRoomHTML('Information Systems');
            } else if (department === 'computer') {
                roomSelection.innerHTML = getRoomHTML('Computer Science');
            }

            // Show the rooms container
            roomsSection.style.display = 'block';
        }

        function getRoomHTML(department) {
            let roomsHTML = '';
for (let i = 1; i <= 6; i++) {
    roomsHTML += `
        <a href="room${i}.php" class="room">
        <figure>
            <!-- Replace the room image with your own picture -->
            <img src="s44-106.jpg" alt="Room ${i}">
            <figcaption>
                <h2>Room ${i}</h2>
                <p>${department} Room</p>
                <p>Available for booking</p>
            </figcaption>
        </figure>
    </a>`;
}

            return roomsHTML;
        }
    </script>

</body>

</html>
