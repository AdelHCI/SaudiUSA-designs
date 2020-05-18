<?php
//include 'timeout.php';
if(!isset($_SESSION["hash"])) $_SESSION["hash"] = md5( rand(0,1000) );

$to      = $_POST["username"]; // Send email to our user
$subject = 'SaudiUSA design | Verify your account'; // Give the email a subject 
$message = 'Your verification code is '.$_SESSION["hash"].'.'; // Our message above including the link
                     
$headers = 'From:no_reply@saudiusa.com' . "\r\n"; // Set from headers
mail($to, $subject, $message, $headers); // Send our email

?>