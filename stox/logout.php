<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy the session completely

// Redirect to login page
header("Location: index.php?message=Successfully logged out");
exit;
?>
