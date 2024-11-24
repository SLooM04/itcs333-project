<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to the login page 
header("Location: combined_login.php");
exit();
?>