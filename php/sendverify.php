<?php
//include 'timeout.php';
if (!isset($_SESSION["hash"])) $_SESSION["hash"] = md5(rand(0, 1000));

$to      = $_POST["username"]; // Send email to our user
$subject = 'SaudiUSA design | Verify your account'; // Give the email a subject 


// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
$headers .= "From: no_reply@saudiusa.com\r\n" .
    "Reply-To: no_reply@saudiusa.com\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
$message = '<html><body>';
$message .= '<img src="https://saudiusa.com/files/logo.png" alt="Saudis In USA logo" width="200" height="200">';
$message .= '<h1>Use the following code to verify your account with us</h1>';
$message .= '<p style="font-size:18px;">Your verification code is ' . $_SESSION["hash"] . '.</p>';
$message .= '</body></html>';

mail($to, $subject, $message, $headers); // Send our email
