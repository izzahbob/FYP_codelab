<?php
session_start();
session_destroy();

// Redirect back to login page
header("Location: login.php");
?>
