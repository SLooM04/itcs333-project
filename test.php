<?php
session_start();

echo "Hello Mr". $_SESSION['user_id'];
?>