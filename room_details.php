<?php
session_start();
require 'db.php'; // Include the DB connection file

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

// Fetch the room ID from the URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Invalid room ID");
}

// Fetch the booking date from the URL or request
$booking_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch already booked time slots for the selected date
$stmt = $pdo->prepare("
    SELECT start_time, end_time 
    FROM bookings 
    WHERE room_id = :room_id 
    AND DATE(start_time) = :booking_date
    AND status != 'Cancelled'
");
$stmt->execute(['room_id' => $room_id, 'booking_date' => $booking_date]);
$booked_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to check if a time slot is booked
function isTimeSlotAvailable($start_time, $end_time, $booked_slots)
{
    foreach ($booked_slots as $slot) {
        if (
            ($start_time >= $slot['start_time'] && $start_time < $slot['end_time']) ||
            ($end_time > $slot['start_time'] && $end_time <= $slot['end_time']) ||
            ($start_time <= $slot['start_time'] && $end_time >= $slot['end_time'])
        ) {
            return false;
        }
    }
    return true;
}

// Generate available time slots for a day (8 AM to 10 PM, 1-hour slots)
function generateAvailableSlots($booked_slots)
{
    $available_slots = [];
    $start_hour = 8;
    $end_hour = 22;

    for ($hour = $start_hour; $hour < $end_hour; $hour++) {
        $start_time = sprintf("%02d:00:00", $hour);
        $end_time = sprintf("%02d:00:00", $hour + 1);

        if (isTimeSlotAvailable($start_time, $end_time, $booked_slots)) {
            $available_slots[] = $start_time . ' - ' . $end_time;
        }
    }

    return $available_slots;
}

// Get available slots
$available_slots = generateAvailableSlots($booked_slots);
?>

<?php
// Fetch the room ID from the URL
$room_id = $_GET['id'];

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :room_id");
$stmt->execute([':room_id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch comments for the room
$stmt = $pdo->prepare("
    SELECT c.*, 
           CASE 
               WHEN c.user_role = 'student' THEN s.username 
               WHEN c.user_role = 'teacher' THEN t.username 
           END AS username
    FROM comments c
    LEFT JOIN students s ON c.user_id = s.student_id AND c.user_role = 'student'
    LEFT JOIN teachers t ON c.user_id = t.teacher_id AND c.user_role = 'teacher'
    WHERE c.room_id = :room_id 
    ORDER BY c.created_at DESC
");
$stmt->execute([':room_id' => $room_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
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


        /* comments section styling */
.comments-section {
    margin-top: 40px;
    padding: 20px;
    background-color: #f8f9fa; /*  background for the comments section */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Styling for comments */
.comment {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #ffffff; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}


.comment p {
    margin: 5px 0;
}

.comment strong {
    color: #333; 
    font-weight: bold;
}

.comment em {
    font-size: 12px;
    color: #888; 
}

.admin-response {
    margin-top: 15px;
    padding: 10px;
    border-left: 4px solid #007bff; /* Blue border for admin responses */
    background-color: #e9f5ff; /* Light blue background */
    border-radius: 5px;
    font-style: italic;
}

.comment .rating {
    margin: 10px 0;
    color: #ffcc00; /* Gold stars for ratings */
    font-size: 18px;
}

/* Styling for the comment form */
.comment-form {
    margin-top: 30px;
    padding: 20px;
    background-color: #f1f1f1; /* Light gray background for the form */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comment-form textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    resize: none;
}

.comment-form button {
    background-color: #007bff; /* Blue submit button */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.comment-form button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

        /*stars styles*/

.star-rating {
display: inline-block;
}

.star-rating input {
display: none; /* Hide the radio buttons themselves */
}

.star-rating label {
font-size: 30px;
color: gray;  /* Default color for empty stars */
cursor: pointer;
}

.star-rating label:hover {
color: gold; /* Hover effect to show gold color on star selection */
}
        /* Basic Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        body.dark-mode {
            background-color: #2e4156;
            color: white;
        }

        body.dark-mode header,
        body.dark-mode footer {
            background-color: #1a2d42;
            color: #d1d1d1;
        }

        body.dark-mode .dropdown-content {
            background-color: #2b2b3b;
        }

        body.dark-mode .dropdown-content a {
            color: #edf4fa;
        }

        body.dark-mode .recommendation-card {
            background-color: #2e344e;
        }

        body.dark-mode nav a {
            color: #e0e0e0;
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
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid #ffffff;
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

        /* Room Details Layout */
        .room-container {
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info {
            padding: 3%;
        }

        .room-images {
            width: 50%;
            margin-right: 110px;
        }

        .main-image {
            width: 35%;
            height: auto;
            border: 2px solid #1A2D42;
            border-radius: 15px;
            margin: 30px;
        }

        .thumbnail-images {
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* Space between thumbnail images */
        }

        .thumbnail-images img {
            width: 150px;
            height: auto;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .thumbnail-images img:hover {
            transform: scale(1.2);
        }

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

        .containerDee {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .features {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .feature-box {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            flex: 1 1 calc(25% - 20px);
            min-width: 200px;
        }

        .feature-box img {
            width: 50px;
            margin-bottom: 10px;
        }

        .feature-box h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .feature-box p {
            color: #555;
        }

        @media (max-width: 768px) {
            .feature-box {
                flex: 1 1 calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .feature-box {
                flex: 1 1 100%;
            }
        }

        /* Footer Styles */
        footer {
            color: white;
            background-color: #1a73e8;
            text-align: center;
            padding: 1rem;
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

        /* Media Queries */
        @media (min-width: 801px) and (max-width: 1000px) {
            .logo img {
                width: 3rem;
            }
        }

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
            }
        }

        @media (min-width: 600px) and (max-width: 1024px) {
            .room-container {
                flex-direction: row;
            }

            .room-images,
            .room-details {
                width: 45%;
            }

            .thumbnail-images img {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 599px) {
            header {
                flex-direction: column;
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
    <main>


        <div class="room-container">
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


        <div class="containerDee">

            <div class="title">
                <p style="color: #000000"><strong>Detailed information for </strong><?php echo htmlspecialchars($room['room_name']); ?></p>
            </div>

            <div class="features">
                <div class="feature-box">
                    <div style="font-size: 40px;"><img src="Seats.png" style="width:60px;"></div>
                    <h3 style="color: #000000">Room Volume</h3>
                    <p><strong style="color:#1a73e8">Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                </div>

                <div class="feature-box">
                    <div style="font-size: 30px;">🏢</div>
                    <h3 style="color: #000000">Location</h3>

                    <p><strong style="color:#1a73e8">Department:</strong> <?php echo htmlspecialchars($room['department']); ?></p>
                    <p><strong style="color:#1a73e8">Floor:</strong> <?php echo htmlspecialchars($room['floor']); ?></p>
                </div>

                <div class="feature-box">
                    <div style="font-size: 30px;">🖥️</div>
                    <h3 style="color: #000000">Room Equipment:</h3>
                    <p><strong style="color:#1a73e8">Room Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>
                </div>



                <div class="feature-box">
                    <div style="font-size: 30px;">🖥️</div>
                    <h3 style="color: #000000">Room Equipment:</h3>
                    <p><strong style="color:#1a73e8">Room Equipment:</strong> <?php echo htmlspecialchars($room['equipment']); ?></p>
                </div>
                <div class="feature-box">

                    <!-- Reserve Button -->
                    <form action="Room_Booking.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $room['id']; ?>" />
                        <?php if (isset($_SESSION['username'])): ?>
                            <button type="submit">Book Now</button>
                        <?php else: ?>
                            <button type="button" disabled>Book Now</button>
                            <p style="color: red; font-size: 1.1em;">You need to log in to book this room.</p>
                        <?php endif; ?>
                    </form>
                </div>

            </div>

<!-- Comments Section -->
<div class="comments-section">
    <h2>[ FeedBacks ]</h2>

   <!-- Display Existing Comments -->
<div class="comments-list">
    <?php if ($comments): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                <p><em><?php echo htmlspecialchars($comment['created_at']); ?></em></p>

                <!-- Display Rating as Stars -->
                <p><strong>Rating:</strong>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $comment['rating']) {
                            echo '★'; // Full star
                        } else {
                            echo '☆'; // Empty star
                        }
                    }
                    ?>
                </p>

                <?php if (!empty($comment['admin_response'])): ?>
                    <div class="admin-response">
                        <p><strong>Admin:</strong> <?php echo htmlspecialchars($comment['admin_response']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No feedbacks yet. Be the first!</p>
    <?php endif; ?>
</div>



   <!-- Comment Form -->
   <h3>Leave your feedback</h3>
<form action="add_comment.php" method="POST">
    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>"> <!-- Pass room_id -->

    <!-- Star Rating -->
    <label for="rating">Rating:</label>
    <div class="star-rating">
        <input type="radio" id="star1" name="rating" value="1" onclick="updateRating(1)">
        <label for="star1">★</label>

        <input type="radio" id="star2" name="rating" value="2" onclick="updateRating(2)">
        <label for="star2">★</label>

        <input type="radio" id="star3" name="rating" value="3" onclick="updateRating(3)">
        <label for="star3">★</label>

        <input type="radio" id="star4" name="rating" value="4" onclick="updateRating(4)">
        <label for="star4">★</label>

        <input type="radio" id="star5" name="rating" value="5" onclick="updateRating(5)">
        <label for="star5">★</label>
    </div>

    <!-- Display Selected Rating Number (1 to 5) -->
    <p>Selected Rating: <span id="rating-display">0</span>/5</p>

    <!-- Comment Text -->
    <textarea name="comment_text" placeholder="Write your feedback here.." required></textarea>

    <button type="submit">Submit Feedback</button>
</form>

<script>
    function updateRating(rating) {
        // Display the numeric rating
        document.getElementById("rating-display").textContent = rating;
        
        // Update the selected star rating
        var stars = document.querySelectorAll(".star-rating input");
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.nextElementSibling.style.color = "gold"; // Filled star
            } else {
                star.nextElementSibling.style.color = "gray"; // Empty star
            }
        });
    }
</script>




            <script>
                function changeMainImage(image) {
                    document.getElementById("main-image").src = "RoomPic/" + image;
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