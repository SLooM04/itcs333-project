<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Get user details from session
$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

// Fetch user information based on role
if ($userRole == 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
}
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$username = $_SESSION['username'] ?? 'User';

// Fetch top 5 most booked rooms
$sqlstmt = $pdo->prepare("
    SELECT room_id, room_name, COUNT(*) as total_bookings 
    FROM bookings 
    WHERE status != 'Cancelled'
    GROUP BY room_id, room_name
    ORDER BY total_bookings DESC 
    LIMIT 5
");
$sqlstmt->execute();
$rooms = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total bookings for percentages
$totalBookings = array_sum(array_column($rooms, 'total_bookings'));

// Update booking statuses for expired bookings
$query = ($userRole == 'student') 
    ? "UPDATE bookings SET status = 'Confirmed' WHERE student_id = :id AND end_time < NOW()"
    : "UPDATE bookings SET status = 'Confirmed' WHERE teacher_id = :id AND end_time < NOW()";

$updater = $pdo->prepare($query);
$updater->execute([':id' => $userId]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        .simple-bar-chart {
            --line-count: 10;
            --line-color: currentcolor;
            --line-opacity: 0.25;
            --item-gap: 2%;
            --item-default-color: #060606;

            height: 10rem;
            display: grid;
            grid-auto-flow: column;
            gap: var(--item-gap);
            align-items: end;
            padding-inline: var(--item-gap);
            padding-block: 1.5rem;
            position: relative;
            isolation: isolate;
        }

        .simple-bar-chart::after {
            content: "";
            position: absolute;
            inset: 1.5rem 0;
            z-index: -1;
            background-image: repeating-linear-gradient(
                to top,
                transparent 0 calc(100% / var(--line-count) - 1px),
                var(--line-color) 0 calc(100% / var(--line-count))
            );
            box-shadow: 0 1px 0 var(--line-color);
            opacity: var(--line-opacity);
        }

        .simple-bar-chart > .item {
            height: calc(1% * var(--val));
            background-color: var(--clr, var(--item-default-color));
            position: relative;
            animation: item-height 1s ease forwards;
        }

        @keyframes item-height {
            from {
                height: 0;
            }
        }

        .simple-bar-chart > .item > * {
            position: absolute;
            text-align: center;
        }

        .simple-bar-chart > .item > .label {
            inset: 100% 0 auto 0;
        }

        .simple-bar-chart > .item > .value {
            inset: auto 0 100% 0;
        }

        

    </style>
</head>

<body>

    <h2>Top 5 Booked Chart</h2>
    <div class="simple-bar-chart">
        <?php foreach ($rooms as $index => $room): ?>
            <?php 
                $percentage = ($room['total_bookings'] / $totalBookings) * 100;
                $colors = ['#5EB344', '#FCB72A', '#F8821A', '#E0393E', '#963D97']; // Colors for bars
            ?>
            <div class="item" style="--clr: <?php echo $colors[$index % count($colors)]; ?>; --val: <?php echo $percentage; ?>">
                <div class="label"><?php echo htmlspecialchars($room['room_name']); ?></div>
                <div class="value"><?php echo round($percentage, 1); ?>%</div>
            </div>
        <?php endforeach; ?>
    </div>

    
</body>

</html>
