<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Account Type</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        .account-type-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 2rem;
        }
        .account-card {
            width: 200px;
            text-align: center;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .account-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .account-card img {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
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
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </main>
</body>
</html>
