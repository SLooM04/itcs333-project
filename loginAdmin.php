<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // تحقق من بيانات الدخول
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_panel.php');
        exit();
    } else {
        $error_message = "اسم المستخدم أو كلمة المرور غير صحيحة!";
    }
}
?>

<form method="POST">
    <label>اسم المستخدم:</label>
    <input type="text" name="username" required>
    <label>كلمة المرور:</label>
    <input type="password" name="password" required>
    <button type="submit">تسجيل الدخول</button>
</form>
<?php if(isset($error_message)) echo $error_message; ?>
