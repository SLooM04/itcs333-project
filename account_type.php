<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Room Reservation</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* Reset and general styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100%;
            overflow-x: hidden; /* Allow vertical scrolling */
            flex-direction: column;
            min-height: 100vh;
        }



        .container {
            text-align: center;
            z-index: 1;
            max-width: 960px;
            width: 100%;
            margin-top: 5rem;
        }

        h1 {
            font-size: 2.5rem;
            color: #444;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .account-type-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            margin-top: 3rem;
            flex-wrap: wrap;
            transition: all 0.5s ease;
        }

        /* Make the entire account card clickable */
        .account-card a {
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
        .account-card a:hover {
            transform: translateY(-12px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-color: #007bff;
            background-color: #f7f7f7;
        }

        .account-card img {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .account-card a:hover img {
            transform: scale(1.1);
        }

        .account-card h2 {
            font-size: 1.5rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .account-card a:hover h2 {
            color: #007bff;
        }

        /* Footer styles */
        footer {
            background-color: #222;
            color: #f0f4f7;
            text-align: center;
            padding: 1rem 1rem; /* Reduced padding */
            margin-top: 4rem; /* Added space between content and footer */
            font-size: 0.9rem; /* Reduced font size */
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
            margin-bottom: 1rem; /* Reduced margin for footer sections */
            text-align: left; /* Ensure text aligns properly */
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
            margin-top: 1rem; /* Reduced margin */
            color: #d1d1d1;
        }

        footer .footer-bottom a {
            color: #d1d1d1;
            text-decoration: none;
        }

        footer .footer-bottom a:hover {
            color: #007bff;
        }

        /* Responsive design for the footer */
        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem; /* Reduced margin */
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }
        }

        /* Remove bullets from lists */
        footer .footer-section ul {
            list-style-type: none !important;
            padding-left: 0;
        }



    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container">
    <div class="3d">  
        <h1>UoB Rooms Reservation</h1>
    </div>
        <p>Please select your account type to proceed</p>
        <div class="account-type-container">
            <!-- Student Account -->
            <div class="account-card">
                <a href="student_registration.php?type=student">
                    <img src="https://t3.ftcdn.net/jpg/01/14/22/80/360_F_114228015_OUhGD20zEmICTJ2Y57qLz3mm2RPPgnFv.jpg" alt="Student Icon">
                    <h2>Student</h2>
                </a>
            </div>

            <!-- Teacher Account -->
            <div class="account-card">
                <a href="teacher_registration.php?type=teacher">
                    <img src="https://t3.ftcdn.net/jpg/05/34/96/24/360_F_534962400_yI5SiJ0dNhVdDN6UIt9oyAM0z7jcyiAT.jpg" alt="Teacher Icon">
                    <h2>Teacher</h2>
                </a>
            </div>
        </div>
    </div>

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
