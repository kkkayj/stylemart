<?php
session_start();

// Clear all session data
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to home page
header("Location: index.php"); // <-- change to index.php if needed
exit;
