<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load DB
require_once __DIR__ . '/dbconnect.php';
?>
