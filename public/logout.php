<?php
session_start();

// Unset all session values
$_SESSION = array();

// Destroy the session
session_destroy();

// Set a success message before redirecting
$_SESSION['message'] = 'You have been logged out successfully.';
$_SESSION['message_type'] = 'success';

// Redirect to login page
header('Location: login.php');
exit();
?>