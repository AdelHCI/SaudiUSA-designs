<?php
session_start();
$timeout = 300; // Number of seconds until it times out.
// Check if the timeout field exists.
if (isset($_SESSION['timeout'])) {
    // See if the number of seconds since the last
    // visit is larger than the timeout period.
    $duration = time() - (int)$_SESSION['timeout'];
    if ($duration > $timeout) {
        // Destroy the session and restart it.
        session_destroy();
        session_start();
    }
    // else {
    //     include 'refreshSession.php';
    // }
}

// Update the timeout field with the current time.
$_SESSION['timeout'] = time();
