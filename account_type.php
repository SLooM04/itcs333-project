<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Account Type</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 3rem;
            text-align: center;
        }

        h1 {
            font-size: 2.2rem;
            color: #444;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .account-type-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .account-card {
            width: 200px;
            text-align: center;
            padding: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .account-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .account-card img {
            width: 70px;
            height: 70px;
            margin-bottom: 1.2rem;
        }

        .account-card h2 {
            font-size: 1.3rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .account-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .account-card a:hover h2 {
            color: #6c757d;
        }

        p {
            margin-top: 2.5rem;
            font-size: 1rem;
            color: #555;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #ffffff;
            color: #555;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* For small screen responsiveness */
        @media screen and (max-width: 768px) {
            .account-type-container {
                flex-direction: column;
                gap: 1.5rem;
            }

            .account-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Choose Your Account Type</h1>
        <div class="account-type-container">
            <!-- Student Account -->
            <div class="account-card">
                <a href="student_registration.php?type=student">
                    <img src="student-icon.png" alt="Student Icon">
                    <h2>Student</h2>
                </a>
            </div>

            <!-- Teacher Account -->
            <div class="account-card">
                <a href="teacher_registration.php?type=teacher">
                    <img src="teacher-icon.png" alt="Teacher Icon">
                    <h2>Teacher</h2>
                </a>
            </div>
      </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> ITCS333 Project | All rights reserved.</p>
        <ul>
            <li><a href="#privacy-policy">Privacy Policy</a></li>
            <li><a href="#terms-of-service">Terms of Service</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
    </footer>
</body>
</html>
