<?php
session_start();
require 'db.php';  // Ensure the database connection is established

// Check if the user is logged in and is an admin
if ($_SESSION['role'] != 'admin' && !isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("You are not logged in or authorized to view this page.");
}

// Fetch admin details from the database based on the session user ID
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the admin exists in the database
if (!$user) {
    die("Admin not found.");
}

// Fetch all feedbacks with room names
$stmt = $pdo->prepare("
    SELECT c.*, r.room_name, 
           CASE 
               WHEN c.user_role = 'student' THEN s.username 
               WHEN c.user_role = 'teacher' THEN t.username 
           END AS username
    FROM comments c
    LEFT JOIN rooms r ON c.room_id = r.id
    LEFT JOIN students s ON c.user_id = s.student_id AND c.user_role = 'student'
    LEFT JOIN teachers t ON c.user_id = t.teacher_id AND c.user_role = 'teacher'
    ORDER BY c.created_at DESC
");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
// Display success messages if they exist
if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

/* Success Message Styling */
.success-message {
    margin: 20px 0; 
    padding: 15px;
    background-color: #d4edda; 
    border-left: 5px solid #28a745; 
    color: #155724; 
    border-radius: 8px; 
    font-size: 1rem; 
    font-weight: bold; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}

/* Room name link */
a {
    color: #1e90ff; 
    text-decoration: none; 
    font-weight: bold; 
}

a:hover {
    color: #87cefa; /* Lighter blue on hover */
    text-decoration: underline; /* Underline on hover */
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #1a73e8;
    color: white;
}

table tr:hover {
    background-color: #f1f1f1;
}

button {
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

button:hover {
    opacity: 0.9;
}

         /* General styles */
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
        html {

            padding: 0px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .sections {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin: 40px 0;
        }

        .section {
            position: relative;
            width: 250px;
            height: 250px;
            background: linear-gradient(1deg, #1a73e8, #004db3 );  
            color: white;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .section:hover {
            background: #2980b9;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .section i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .section:hover .options {
            opacity: 1;
            visibility: visible;
        }

        .options {
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 10px;
            width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            text-align: left;
        }

        .options a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #3498db;
            padding: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease-in-out;
        }

        .options a:hover {
            background: #f3f4f6;
            color: #2980b9;
            border-radius: 5px;
        }

        .options a i {
            margin-right: 8px;
        }

        .logout {
            margin-top: 30px;
            color: #e74c3c;
            font-weight: bold;
            background-color: transparent;
            border: 2px solid #e74c3c;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .logout:hover {
            background-color: #e74c3c;
            color: white;
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            transform: translateY(-3px);
        }

        @media screen and (max-width: 768px) {
            .sections {
                flex-direction: column;
                align-items: center;
            }

            .section {
                width: 100%;
                height: 200px;
            }

            .options {
                width: 90%;
                bottom: -70px;
            }
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
            height: 133px;
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
    </style>
</head>
<body>
    <header>
        <!-- Logo Section -->
        <a  class="logo">
            <img src="uploads/UOB-Colleges-new-logo.png" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->

        <nav class="nav-links">
            <a href="adminrooms.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'adminrooms.php' ? 'active' : ''; ?>">Rooms</a>
            <a href="admin-dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'active' : ''; ?>">Home</a>

        </nav>



        <!-- User Profile Section -->
        <div class="user-profile dropdown">
            <img src="<?= !empty($admin['profile_picture']) ? htmlspecialchars($admin['profile_picture']) : 'uploads/admin-default.png' ?>" alt="Profile Picture" class="profile-image">
            <span> <?php echo htmlspecialchars($user['username']); ?></span>
            <div class="dropdown-content">
                <a href="adminprofile.php">My Profile</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            </div>
        </div>
    </header>
    <main>
    


    


    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="sections">
            <!-- Bookings Section -->
            <div class="section">
                <i class="fas fa-book"></i>
                <p>Bookings</p>
                <div class="options">
                    <a href="admin-booking.php"><i class="fas fa-plus"></i> Add Booking</a>
                    <a href="amin-cancel-dashboard.php"><i class="fas fa-times"></i> Cancel Booking</a>
                    <a href="admin-block-booking.php"><i class="fas fa-ban"></i> Blocked Rooms</a>
                </div>
            </div>

            <!-- Room Section -->
            <div class="section">
                <i class="fas fa-door-open"></i>
                <p>Rooms</p>
                <div class="options">
                    <a href="add-room.php"><i class="fas fa-plus"></i> Add Room</a>
                    <a href="edit-room.php"><i class="fas fa-edit"></i> Edit Room</a>
                    <a href="delete-room.php"><i class="fas fa-trash"></i> Delete Room</a>
                </div>
            </div>

            <!-- Student Section -->
            <div class="section">
                <i class="fas fa-user-graduate"></i>
                <p>Students</p>
                <div class="options">
                    <a href="admin-addStudent.php"><i class="fas fa-plus"></i> Add Student</a>
                    <a href="admin-editStudent.php"><i class="fas fa-edit"></i> Edit Student</a>
                    <a href="admin-deleteStudent.php"><i class="fas fa-trash"></i> Delete Student</a>
                </div>
            </div>

            <!-- Teacher Section -->
            <div class="section">
                <i class="fas fa-chalkboard-teacher"></i>
                <p>Teachers</p>
                <div class="options">
                    <a href="admin-addTeacher.php"><i class="fas fa-plus"></i> Add Teacher</a>
                    <a href="admin-editTeacher.php"><i class="fas fa-edit"></i> Edit Teacher</a>
                    <a href="admin-deleteTeacher.php"><i class="fas fa-trash"></i> Delete Teacher</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Management -->
    <div class="container">
    <div class="container">
    <h1>Feedback Management</h1>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #1a73e8; color: white;">
                <th style="padding: 10px;">Room</th>
                <th style="padding: 10px;">User</th>
                <th style="padding: 10px;">Feedback</th>
                <th style="padding: 10px;">Response</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <!-- Room name as a clickable link -->
                    <td style="padding: 10px;">
                        <a href="room_details.php?id=<?php echo $feedback['room_id']; ?>" style="color: #1e90ff; text-decoration: none;">
                            <?php echo htmlspecialchars($feedback['room_name']); ?>
                        </a>
                    </td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($feedback['username']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($feedback['comment_text']); ?></td>
                    <td style="padding: 10px;"><?php echo $feedback['admin_response'] ? htmlspecialchars($feedback['admin_response']) : 'No response'; ?></td>
                    <td style="padding: 10px;">
                        <!-- Reply Form -->
                        <form action="respond_comment.php" method="POST" style="display:inline;">
                            <input type="hidden" name="comment_id" value="<?php echo $feedback['comment_id']; ?>">
                            <input type="text" name="response" placeholder="Reply..." required>
                            <button type="submit" style="background-color: #4caf50; color: white; padding: 5px 10px; border: none; border-radius: 5px;">Reply</button>
                        </form>
                        <!-- Delete Button -->
                        <form action="delete_comment.php" method="POST" style="display:inline;">
                            <input type="hidden" name="comment_id" value="<?php echo $feedback['comment_id']; ?>">
                            <button type="submit" style="background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 5px;" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
