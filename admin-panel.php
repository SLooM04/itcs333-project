<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

// جلب جميع الغرف
$query = $conn->query("SELECT * FROM rooms");
$rooms = $query->fetchAll();
?>

<h1>لوحة الإدارة</h1>
<a href="add_room.php">إضافة غرفة جديدة</a>
<table>
    <tr>
        <th>اسم الغرفة</th>
        <th>السعة</th>
        <th>الأدوات</th>
        <th>الجدول الزمني</th>
        <th>الإجراءات</th>
    </tr>
    <?php foreach ($rooms as $room): ?>
    <tr>
        <td><?php echo $room['name']; ?></td>
        <td><?php echo $room['capacity']; ?></td>
        <td><?php echo $room['equipment']; ?></td>
        <td><?php echo $room['schedule']; ?></td>
        <td>
            <a href="edit_room.php?id=<?php echo $room['id']; ?>">تعديل</a>
            <a href="delete_room.php?id=<?php echo $room['id']; ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
