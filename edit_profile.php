<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $bio = trim($_POST['bio']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $profilePicture = $user['profile_picture'];}